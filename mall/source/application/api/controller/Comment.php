<?php

namespace app\api\controller;

use app\api\model\Comment as CommentModel;
use app\common\model\CommentStar as CommentStarModel;

/**
 * 商品评价控制器
 * Class Comment
 * @package app\api\controller
 */
class Comment extends Controller
{
    /**
     * 商品评价列表
     * @param $goods_id
     * @param int $scoreType
     * @return array
     * @throws \think\exception\DbException
     */
    public function lists($goods_id, $scoreType = -1)
    {
        $model = new CommentModel;
        $list = $model->getGoodsCommentList($goods_id, $scoreType);
        $total = $model->getTotal($goods_id);
        return $this->renderSuccess(compact('list', 'total'));
    }

    /**
     * 晒单列表
     * @param $goods_id
     * @param int $scoreType
     * @return array
     * @throws \think\exception\DbException
     */
    public function sunList($scoreType = -1,$comment_id = -1)
    {
        $model = new CommentModel;
        $list = $model->getSunGoodsCommentList($scoreType,$comment_id);
       // dump($list->toArray());
        return $this->renderSuccess(compact('list'));
    }

    // 晒单点赞
    public function star($comment_id,$user_id)
    {
        $model = new CommentStarModel;
        $res = $model->star($comment_id,$user_id);

        if($res['code'] == 1){
            return $this->renderSuccess('点赞成功');
        }else{
            return $this->renderError($res['msg']);
        }

    }

}