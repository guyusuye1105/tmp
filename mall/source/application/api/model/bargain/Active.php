<?php

namespace app\api\model\bargain;

use app\common\exception\BaseException;
use app\common\model\bargain\Active as ActiveModel;
use app\common\model\bargain\Order as OrderModel;
/**
 * 拼团拼单模型
 * Class Active
 * @package app\api\model\bargain
 */
class Active extends ActiveModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        //'wxapp_id',
        'create_time',
        'update_time'
    ];

    /**
     * 新增拼单记录
     * @param $data
     * @return false|int
     */
    public function add($data)
    {
        return $this->save($data);
    }

    /**
     * 根据商品id获取进行中的拼单列表
     * @param $goods_id
     * @param int $limit
     * @return false|\PDOStatement|string|\think\Collection
     */
    public static function getActivityListByGoods($goods_id, $limit = 15)
    {
        return (new static)->with(['user'])
            ->where('goods_id', '=', $goods_id)
            ->where('status', '=', 10)
            ->limit($limit)
            ->select();
    }
    /**
     * 新增拼单记录
     * @param $creator_id
     * @param $order_id
     * @param OrderGoods $goods
     * @return false|int
     */
    public function onCreate($creator_id, $order_id, $goods)
    {
        // 新增拼单记录
        $param = array(
            'goods_id' => $goods['goods_id'],
            //'people' => 0,
            'actual_people' => 0,
            'creator_id' => $creator_id,
            'end_time' => time() + ($goods['group_time'] * 60 * 60),
            'status' => 10,
            'wxapp_id' => $goods['wxapp_id'],
            'orders_id' =>$order_id,
            'bargain_x'=>$goods['bargain_x'],
            'bargain_y'=>$goods['bargain_y'],
        );
        $res = $this->createDataGetId($param);
        if($res){
            // 修改订单的active_id
            $orderModel = new Order;
            $orderModel->updateDataById(array('active_id'=>$res),$order_id);
        }else{
            return false;
        }

        /*$this->save([
            'goods_id' => $goods['goods_id'],
            //'people' => 0,
            'actual_people' => 0,
            'creator_id' => $creator_id,
            'end_time' => time() + ($goods['group_time'] * 60 * 60),
            'status' => 10,
            'wxapp_id' => $goods['wxapp_id'],
            'orders_id' =>$order_id,
        ]);*/
        /*
        // 新增拼单成员记录
        ActiveUsers::add([
            'active_id' => $this['active_id'],
            'order_id' => $order_id,
            'user_id' => $creator_id,
            'is_creator' => 1,
            'wxapp_id' => $goods['wxapp_id']
        ]);
        */
        return true;
    }

    /**
     * 更新拼单记录
     * @param $user_id
     * @param $order_id
     * @return bool
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function onUpdate($creator_id, $user_id,$order)
    {
        $active = $this->getDataById($order['active_id'])->toArray();
        // 验证当前拼单是否允许加入新成员
        $isNew = $this->checkAllowJoin($active, $user_id);
        if ($isNew['code'] != 1) {
            //return array('code'=>2,'msg'=>$isNew['msg']);
        }
        $kan_money = rand($active['bargain_x']*100,$active['bargain_y']*100)/100;

        // 减少订单价格
        $param2['pay_price'] = $order['pay_price'] - $kan_money;
            // 如果已经砍到了最低价
        if($order['min_price']>$param2['pay_price']){
            $kan_money = $param2['pay_price'] - $order['min_price'];
            $param2['pay_price'] = $order['min_price'];
            if($kan_money == 0){
                return array('code'=>3,'msg'=>'已经砍到了最低价，无法再砍价');
            }
        }
            //减少订单价格
        $orderModel = new OrderModel;
        $orderModel->updateDataById($param2,$order['order_id']);
        // 新增拼单成员记录
        ActiveUsers::add([
            'active_id' => $order['active_id'],
            'order_id' => $order['order_id'],
            'user_id' => $user_id,
            'is_creator' => 0,
            'wxapp_id' => self::$wxapp_id,
            'kan_money'=>$kan_money,
        ]);

        // 累计已拼人数
        $actual_people = $active['actual_people'] + 1;
        // 更新拼单记录：当前已拼人数、拼单状态
        $status = $actual_people >= $active['people'] ? 20 : 10;
        $this->save([
            'actual_people' => $actual_people,
            'status' => $status
        ]);

        // 拼单成功, 发送模板消息
        if ($status == 20) {
            $model = static::detail($order['active_id']);
            // (new Message)->bargainActive($model, '拼团成功');
        }
        return array('code'=>1);
    }
    public function onUpdate_edit($user_id,$order_id,$order)
    {
        // 验证当前拼单是否允许加入新成员
        if (!$this->checkAllowJoin_edit($order)) {
            return false;
        }
        // 新增拼单成员记录
        ActiveUsers::add([
            'active_id' => $order['active_id'],
            'order_id' => $order_id,
            'user_id' => $user_id,
            'is_creator' => 0,
            'wxapp_id' => $order['wxapp_id']
        ]);
        // 累计已拼人数
        $actual_people = $order['actual_people'] + 1;
        // 更新拼单记录：当前已拼人数、拼单状态
        $status = $actual_people >= $order['people'] ? 20 : 10;
        $this->save([
            'actual_people' => $actual_people,
            'status' => $status
        ]);
        // 拼单成功, 发送模板消息
        if ($status == 20) {
            $model = static::detail($order['active_id']);
            (new Message)->bargainActive($model, '拼团成功');
        }
        return true;
    }



}
