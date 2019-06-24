<?php

namespace app\api\model\bargain;

use app\common\model\bargain\Category as CategoryModel;

/**
 * 拼团商品分类模型
 * Class Category
 * @package app\common\model\bargain
 */
class Category extends CategoryModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'wxapp_id',
        'update_time'
    ];

}
