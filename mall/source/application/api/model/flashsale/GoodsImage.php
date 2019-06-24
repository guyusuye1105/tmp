<?php

namespace app\api\model\flashsale;

use app\common\model\flashsale\GoodsImage as GoodsImageModel;

/**
 * 秒杀商品图片模型
 * Class GoodsImage
 * @package app\api\model\flashsale
 */
class GoodsImage extends GoodsImageModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'wxapp_id',
        'create_time',
    ];

}
