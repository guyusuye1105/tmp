<?php

namespace app\api\controller\school;

use app\api\controller\Controller;
use app\api\model\Order as OrderModel;
use app\api\model\OrderGoods as OrderGoodsModel;
use app\api\model\school\Comment as CommentModel;
use think\Db;

/**
 * 拼团订单评价管理
 * Class Comment
 * @package app\api\controller\bargain
 */
class Comment extends Controller
{
    /**
     * 待评价订单商品列表
     * @param $order_id
     * @return array
     * @throws \Exception
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function order($order_id,$teacher_id)
    {
        // 用户信息
        $user = $this->getUser();
        // 订单信息
        $order = OrderModel::getUserOrderDetail($order_id, $user['user_id']);
        // 验证订单是否已完成
        $model = new CommentModel;
        if (!$model->checkOrderAllowComment($order)) {
            return $this->renderError($model->getError());
        }
        // 待评价商品列表
        /* @var \think\Collection $goodsList */
        $goodsList = OrderGoodsModel::getNotCommentGoodsList($order_id);
        if ($goodsList->isEmpty()) {
            return $this->renderError('该订单没有可评价的商品');
        }
        // 得到老师id
        $teacher = Db('school_appoint')->where('order_id',$order_id)->find();

        //$teacher_name = Db('school_teacher')->where('teacher_id',$teacher_id)->value('teacher_name');

        foreach($goodsList as $key=>$val){
            $goodsList[$key]['teacher'] = $teacher;
        }
        // 提交商品评价
        if ($this->request->isPost()) {
            $formData = $this->request->post('formData', '', null);
            if ($model->addForOrder($order, $goodsList, $formData,$teacher_id)) {
                // 更新预约内容
                Db('school_appoint')
                    ->where('order_id',$order_id)
                    ->update(['state'=>'end']);
                return $this->renderSuccess([], '评价发表成功');
            }
            return $this->renderError($model->getError() ?: '评价发表失败');
        }
        return $this->renderSuccess(compact('goodsList'));
    }

    /**
     * 商品评价列表
     * @param $goods_id
     * @param int $scoreType
     * @return array
     * @throws \think\exception\DbException
     */
    public function lists($goods_id,$order_id)
    {
        $model = new CommentModel;
        $list = $model->getGoodsCommentList($goods_id,$order_id);
       // $total = $model->getTotal($goods_id);
        return $this->renderSuccess(compact('list'));
    }

}