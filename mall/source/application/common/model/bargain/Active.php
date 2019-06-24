<?php

namespace app\common\model\bargain;

use think\Hook;
use app\common\model\BaseModel;

/**
 * 拼团拼单模型
 * Class Active
 * @package app\common\model\bargain
 */
class Active extends BaseModel
{
    protected $name = 'bargain_active';
    protected $append = ['surplus_people'];

    /**
     * 拼团拼单模型初始化
     */
    public static function init()
    {
        parent::init();
        // 监听订单处理事件
        $static = new static;
        Hook::listen('bargain_active', $static);
    }

    /**
     * 获取器：拼单状态
     * @param $value
     * @return array
     */
    public function getStatusAttr($value)
    {
        $state = [
            0 => '未拼单',
            10 => '拼单中',
            20 => '拼单成功',
            30 => '拼单失败',
        ];
        return ['text' => $state[$value], 'value' => $value];
    }

    /**
     * 获取器：结束时间
     * @param $value
     * @return array
     */
    public function getEndTimeAttr($value)
    {
        return ['text' => date('Y-m-d H:i:s', $value), 'value' => $value];
    }

    /**
     * 获取器：剩余拼团人数
     * @param $value
     * @return array
     */
    public function getSurplusPeopleAttr($value, $data)
    {
        return $data['people'] - $data['actual_people'];
    }

    /**
     * 关联拼团商品表
     * @return \think\model\relation\BelongsTo
     */
    public function goods()
    {
        return $this->belongsTo('Goods');
    }

    /**
     * 关联用户表（团长）
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->belongsTo("app\\{$module}\\model\\User", 'creator_id');
    }

    /**
     * 关联拼单成员表
     * @return \think\model\relation\HasMany
     */
    public function users()
    {
        return $this->hasMany('ActiveUsers', 'active_id')
            ->order(['is_creator' => 'desc', 'create_time' => 'asc']);
    }

    /**
     * 拼单详情
     * @param $active_id
     * @param array $with
     * @return static|null
     * @throws \think\exception\DbException
     */
    public static function detail($active_id, $with = [])
    {
        return static::get($active_id, array_merge(['goods', 'users' => ['user', 'bargainOrder']], $with));
    }
    // 根据$active_id获取订单信息
    public function getActive($active_id)
    {
        $res = $this->alias('a')
            ->join('bargain_order',"bargain_order.order_id =a.order_id")
            ->where('a.active_id='.$active_id)
            ->select();
    }

    /**
     * 验证当前砍价者是否允许砍价
     * @return bool
     */
    public function checkAllowJoin($active,$user_id)
    {
        /*if (!in_array($active['status']['value'], [0, 10])) {
            $this->error = '当前拼单已结束';
            return false;
        }*/
        // 判断是否已经砍价过
        $map = array(
            'wxapp_id'=>self::$wxapp_id,
            'active_id'=>$active['active_id'],
            'user_id'=>$user_id
        );
        $res = db('bargain_active_users')->where($map)->select();
        if(count($res)>0){
            return array('code'=>2,'msg'=>'当前用户已经帮砍过价了');
        }
        // 判断砍价是否结束
        if (time() > $active['end_time']) {
            return array('code'=>3,'当前砍价已结束');
        }
       /*
        if ($active['actual_people'] >= $active['people']) {
            $this->error = '当前拼单人数已满';
            return false;
        }
       */
        return array('code'=>1);
    }

}
