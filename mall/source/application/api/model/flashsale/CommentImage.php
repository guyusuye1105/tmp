<?php

namespace app\api\model\flashsale;

use app\common\model\flashsale\CommentImage as CommentImageModel;

/**
 * 秒杀商品图片模型
 * Class GoodsImage
 * @package app\api\model\flashsale
 */
class CommentImage extends CommentImageModel
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
