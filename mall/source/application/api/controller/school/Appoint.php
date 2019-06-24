<?php

namespace app\api\controller\school;

use app\api\controller\Controller;
use app\api\model\Order as OrderModel;
use app\api\model\school\Appoint as AppointModel;
use think\Db;
/*
use app\api\model\sharing\Goods as GoodsModel;
use app\common\service\qrcode\Goods as GoodsPoster;
use app\api\model\sharing\Active as ActiveModel;
*/

class Appoint extends Controller
{
    public function _initialize()
    {
        parent::_initialize();
        $this->user = $this->getUser();   // 用户信息
    }
    //
    public function lists($state,$appoint_id)
    {
        $model = new AppointModel;
        $list = $model->getList($this->user['user_id'],$state,$appoint_id);
        return $this->renderSuccess(compact('list'));
    }


    /**
     * 预约
     * @param $appoint_id
     * @param $subjecttime_id
     * @param $day
     * @param $store_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function appoint($appoint_id,$subjecttime_id,$day,$store_id,$teacher_id)
    {
        $model = new AppointModel;

        /*预约前判断*/
        // 判断是否超过预约时间
        $subject_id = $model->getDataById($appoint_id)['subject_id'];
        $subject_endtime = Db('school_subject')->where('subject_id='.$subject_id)->find()['subject_endtime'];
        if($subject_endtime > time()){
            return $this->renderError('超过预约时间，预约失败');
        }

        //$user_id = $this->user['user_id'];
        $param = array(
            'subjecttime_id'=>$subjecttime_id,
            'state'=>'ing',
            'day'=>$day,
            'store_id'=>$store_id,
            'teacher_id'=>$teacher_id,
        );
        $res = $model->updateDataById($param,$appoint_id);
        if($res){
            return $this->renderSuccess('预约成功');
        }else{
            return $this->renderError($model->getError() ?: '预约失败');
        }
    }
    // 取消预约
    public function cancelAppoint($appoint_id)
    {
        $user_id = $this->user['user_id'];
        $model = new AppointModel;
        $param = array(
            'state'=>'nouse',
        );
        $res = $model->updateDataById($param,$appoint_id);
        if($res){
            return $this->renderSuccess('取消成功');
        }else{
            return $this->renderError($model->getError() ?: '取消失败');
        }
    }



}
