<?php

namespace app\api\controller\school;

use app\api\controller\Controller;
use app\api\model\school\Store  as StoreModel;

/**
 * 门店
 * @author lichenjie
 */
class Store extends Controller
{
    /**
     * @api {post} api/store/getList 获取门店
     * @apiName storegetListn
     * @apiGroup 门店
     * @apiDescription storegetListn，获取门店
     * @apiParam    {int}   store_id   主键，门店id,传入空代表全部门店
     * @apiParam    {int}    page   页码，要么和页数一起传入，要么都不传（非必需）
     * @apiParam    {int}    pagesize    每页数量（非必需）
     ** @apiUse  storegetListn
     */
    public function getList()
    {
        $model = new StoreModel;
        $post = $this->request->post();
        isset($post['store_id']) ? $post['store_id'] = $post['store_id'] : $post['store_id'] = '';
        //isset($post['page']) ? $post['page'] = $post['page'] : $post['page'] = 1;
        isset($post['pagesize']) ? $post['pagesize'] = $post['pagesize'] : $post['pagesize'] = 1000;
        $list = $model->getList($post['store_id'],$post['pagesize']);
        return $this->renderSuccess(compact('list'));
    }

}