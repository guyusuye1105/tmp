<?php

namespace app\api\model\bargain;

use app\common\model\bargain\GoodsSku as GoodsSkuModel;

/**
 * 拼团商品规格模型
 * Class GoodsSku
 * @package app\api\model\bargain
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
