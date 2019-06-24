<?php

namespace app\api\model\bargain;

use app\common\model\bargain\CommentImage as CommentImageModel;

/**
 * 拼团商品图片模型
 * Class GoodsImage
 * @package app\api\model\bargain
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
