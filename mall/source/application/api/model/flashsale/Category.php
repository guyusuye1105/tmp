<?php

namespace app\api\model\flashsale;

use app\common\model\flashsale\Category as CategoryModel;

/**
 * 秒杀商品分类模型
 * Class Category
 * @package app\common\model\flashsale
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
