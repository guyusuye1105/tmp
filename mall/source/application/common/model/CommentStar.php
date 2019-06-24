<?php

namespace app\common\model;
use think\Db;

/**
 * 商品模型
 * Class Goods
 * @package app\common\model
 */
class CommentStar extends BaseModel
{
    protected $name = 'comment_star';

    // 晒单点赞
    public function star($comment_id,$user_id)
    {
        $map = [];
        $map['comment_id'] = ['=',$comment_id];
        $map['user_id'] = ['=',$user_id];
        $map['wxapp_id'] = ['=',self::$wxapp_id];
        // 判断用户是否已经点赞过
        $res = $this->where($map)->count();
        if($res){
            return array('code'=>0,'msg'=>'你已经点赞过该商品了');
        }
        // 插入点赞数据
        $insertParam = array(
            'comment_id'=>$comment_id,
            'user_id'=>$user_id,
            'wxapp_id'=>self::$wxapp_id
        );
        $res = $this->insert($insertParam);
        if(!$res){
            return array('code'=>0,'msg'=>'点赞失败');
        }
        // 商品星数加一
        $CommentModel = new Comment;
        $CommentModel->where('comment_id='.$comment_id)->setInc('star');
        return array('code'=>1);
    }


}
