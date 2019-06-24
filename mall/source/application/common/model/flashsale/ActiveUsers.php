<?php

namespace app\common\model\flashsale;

use app\common\model\BaseModel;

/**
 * 秒杀拼单成员模型
 * Class ActiveUsers
 * @package app\common\model\flashsale
 */
class ActiveUsers extends BaseModel
{
    protected $name = 'flashsale_active_users';
    protected $updateTime = false;

    /**
     * 关联用户表
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->belongsTo("app\\{$module}\\model\\User");
    }

    /**
     * 关联秒杀订单表
     * @return \think\model\relation\BelongsTo
     */
    public function flashsaleOrder()
    {
        return $this->belongsTo('Order', 'order_id');
    }

    /**
     * 新增秒杀拼单成员记录
     * @param $data
     * @return false|int
     */
    public static function add($data)
    {
        return (new static)->save($data);
    }

}
