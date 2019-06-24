<?php

namespace app\api\controller\flashsale;

use app\api\controller\Controller;
use app\api\model\flashsale\Active as ActiveModel;
use app\api\model\flashsale\Goods as GoodsModel;
use app\api\model\flashsale\Order as OrderModel;

/**
 * 秒杀拼单控制器
 * Class Active
 * @package app\api\controller\flashsale
 */
class Active extends Controller
{
    /**
     * 拼单详情
     * @param $active_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function detail($active_id)
    {
        // 拼单详情
        $detail = ActiveModel::detail($active_id);
        if (!$detail) {
            return $this->renderError('很抱歉，拼单不存在');
        }
        // 秒杀商品详情
        $goods = GoodsModel::detail($detail['goods_id']);
        // 多规格商品sku信息
        $specData = $goods['spec_type'] == 20 ? $goods->getManySpecData($goods['spec_rel'], $goods['sku']) : null;
        // 更多秒杀商品
        $model = new GoodsModel;
        $goodsList = $model->getList(10, 0, '', 'all', false, 5);
        return $this->renderSuccess(compact('detail', 'goods', 'goodsList', 'specData'));
    }



}
