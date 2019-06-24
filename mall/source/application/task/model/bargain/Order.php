<?php

namespace app\task\model\bargain;

use app\common\service\Message;
use app\common\library\wechat\WxPay;
use app\task\model\User as UserModel;
use app\task\model\Wxapp as WxappModel;
use app\common\model\bargain\Order as OrderModel;
use app\task\model\WxappPrepayId as WxappPrepayIdModel;

/**
 * 拼团订单模型
 * Class Order
 * @package app\common\model\bargain
 */
class Order extends OrderModel
{
    /**
     * 待支付订单详情
     * @param $order_no
     * @return null|static
     * @throws \think\exception\DbException
     */
    public function payDetail($order_no)
    {
        return self::get(['order_no' => $order_no, 'pay_status' => 10], ['goods', 'user']);
    }

    /**
     * 订单支付成功业务处理
     * @param $transaction_id
     * @throws \Exception
     * @throws \think\Exception
     */
    public function paySuccess($transaction_id)
    {
        // 更新付款状态
        $this->updatePayStatus($transaction_id);
        // 发送消息通知
        $Message = new Message;
        $Message->payment($this, 20);
    }

    /**
     * 更新付款状态
     * @param $transaction_id
     * @return false|int
     * @throws \Exception
     */
    private function updatePayStatus($transaction_id)
    {
        $this->startTrans();
        try {
            // 更新商品库存、销量
            (new Goods)->updateStockSales($this['goods']);
            // 更新拼单记录
            $this->savebargainActive($this['goods'][0]);
            // 更新订单状态
            $this->save([
                'pay_status' => 20,
                'pay_time' => time(),
                'transaction_id' => $transaction_id,
            ]);
            // 累积用户总消费金额
            $user = UserModel::detail($this['user_id']);
            $user->cumulateMoney($this['pay_price']);
            // 更新prepay_id记录
            $prepayId = WxappPrepayIdModel::detail($this['order_id'], 20);
            $prepayId->updatePayStatus();
            // 事务提交
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }

    /**
     * 更新拼单记录
     * @param $goods
     * @return bool
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    private function savebargainActive($goods)
    {
        // 新增/更新拼单记录
        if ($this['order_type']['value'] != 20) {
            return false;
        }
        // 参与他人的拼单, 更新拼单记录
        if ($this['active_id'] > 0) {
            $ActiveModel = Active::detail($this['active_id']);
            return $ActiveModel->onUpdate($this['user_id'], $this['order_id']);
        }
        // 自己发起的拼单, 新增拼单记录
        $ActiveModel = new Active;
        $ActiveModel->onCreate($this['user_id'], $this['order_id'], $goods);
        // 记录拼单id
        $this['active_id'] = $ActiveModel['active_id'];
        return true;
    }

    /**
     * 获取订单列表
     * @param array $filter
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getList($filter = [])
    {
        return $this->with(['goods' => ['refund']])->where($filter)->select();
    }

    /**
     * 获取拼团失败的订单
     * @param int $limit
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getFailedOrderList($limit = 100)
    {
        return $this->alias('order')
            ->join('bargain_active active', 'order.active_id = active.active_id', 'INNER')
            ->where('order_type', '=', 20)
            ->where('pay_status', '=', 20)
            ->where('order_status', '=', 10)
            ->where('active.status', '=', 30)
            ->where('is_refund', '=', 0)
            ->limit($limit)
            ->select();
    }

    /**
     * 更新拼团失败的订单并退款
     * @param $orderList
     * @return bool
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function updateFailedStatus($orderList)
    {
        // 获取小程序配置信息
        $wxConfig = WxappModel::getWxappCache();
        // 实例化微信支付API类
        $WxPay = new WxPay($wxConfig);
        // 批量更新订单状态
        foreach ($orderList as $order) {
            /* @var static $order */
            try {
                // 执行微信原路退款
                $WxPay->refund($order['transaction_id'], $order['pay_price'], $order['pay_price']);
                // 更新订单状态
                $order->save([
                    'is_refund' => 1,
                    'order_status' => '20'
                ]);
            } catch (\Exception $e) {
                $this->error = '订单ID：' . $order['order_id'] . ' 退款失败，错误信息：' . $e->getMessage();
                return false;
            }
        }
        return true;
    }

}
