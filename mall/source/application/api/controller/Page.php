<?php

namespace app\api\controller;

use app\api\model\WxappPage;
use app\api\model\Category as CategoryModel;

/**
 * 页面控制器
 * Class Index
 * @package app\api\controller
 */
class Page extends Controller
{
    /**
     * 首页diy数据
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function home()
    {
        // 页面元素
        $data = WxappPage::getPageData($this->getUser(false));
        // 给分类加上图片
        foreach($data['items'] as $key=>$val){
            if(($val['name'] == '商品组')&&!empty($val['params']['auto']['category'])){
                $category_id = $val['params']['auto']['category'];
                $categoryModel = new CategoryModel;
                $data['items'][$key]['team'] = CategoryModel::get($category_id, ['image']);
            }
        }
        return $this->renderSuccess($data);
    }

    /**
     * 自定义页数据
     * @param $page_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function custom($page_id)
    {
        // 页面元素
        $data = WxappPage::getPageData($this->getUser(false), $page_id);
        return $this->renderSuccess($data);
    }

}
