<?php

namespace app\api\controller\flashsale;

use app\api\controller\Controller;
use app\api\model\flashsale\Category as CategoryModel;

/**
 * 秒杀首页控制器
 * Class Active
 * @package app\api\controller\flashsale
 */
class Index extends Controller
{
    /**
     * 秒杀首页
     * @return array
     */
    public function index()
    {
        // 秒杀分类列表
        $categoryList = CategoryModel::getCacheAll();
        return $this->renderSuccess(compact('categoryList'));
    }

}
