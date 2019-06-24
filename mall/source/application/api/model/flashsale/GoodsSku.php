<?php

namespace app\api\model\flashsale;

use app\common\model\flashsale\GoodsSku as GoodsSkuModel;

/**
 * 秒杀商品规格模型
 * Class GoodsSku
 * @package app\api\model\flashsale
 */
class GoodsSku extends GoodsSkuModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'wxapp_id',
        'create_time',
        'update_time'
    ];

}
