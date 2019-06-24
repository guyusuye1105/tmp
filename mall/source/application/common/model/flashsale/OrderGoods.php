<?php

namespace app\common\model\flashsale;

use app\common\model\BaseModel;

/**
 * 秒杀订单商品模型
 * Class OrderGoods
 * @package app\common\model\flashsale
 */
class OrderGoods extends BaseModel
{
    protected $name = 'flashsale_order_goods';
    protected $updateTime = false;

    /**
     * 关联秒杀商品表
     * @return \think\model\relation\BelongsTo
     */
    public function goods()
    {
        return $this->belongsTo('Goods');
    }

    /**
     * 订单秒杀商品图
     * @return \think\model\relation\BelongsTo
     */
    public function image()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->belongsTo("app\\{$module}\\model\\UploadFile", 'image_id', 'file_id');
    }

    /**
     * 关联秒杀商品sku表
     * @return \think\model\relation\BelongsTo
     */
    public function sku()
    {
        return $this->belongsTo('GoodsSku', 'spec_sku_id', 'spec_sku_id');
    }

    /**
     * 关联秒杀订单主表
     * @return \think\model\relation\BelongsTo
     */
    public function orderM()
    {
        return $this->belongsTo('Order');
    }

    /**
     * 关联秒杀售后单记录表
     * @return \think\model\relation\HasOne
     */
    public function refund()
    {
        return $this->hasOne('OrderRefund', 'order_goods_id');
    }

    /**
     * 秒杀订单商品详情
     * @param $where
     * @return OrderGoods|null
     * @throws \think\exception\DbException
     */
    public static function detail($where)
    {
        return static::get($where, ['image', 'refund']);
    }

}
