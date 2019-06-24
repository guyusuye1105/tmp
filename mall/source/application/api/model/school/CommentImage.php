<?php

namespace app\api\model\school;

use app\common\model\school\CommentImage as CommentImageModel;

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
