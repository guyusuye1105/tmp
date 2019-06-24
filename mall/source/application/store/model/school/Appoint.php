<?php

namespace app\store\model\school;

use app\common\model\school\Appoint as AppointModel;
use think\Request;

/**
 * 上课时间模型
 * @author  lichenjie
 */
class Appoint extends AppointModel
{

    // 后台获取预约数据用
    public function getListStore($user_id='',$state='',$appoint_id='',$keywords='')
    {
        $map = [];
        if($appoint_id !=''){
            $map['a.appoint_id'] = $appoint_id;
        }
        if($user_id != ''){
            $map['a.user_id'] = $user_id;
        }
        if($state != ''){
            $map['a.state'] = $state;
        }
        if($keywords != ''){
            $map['user.nickName|store.store_name|subject.subject_name'] = ['like','%'.$keywords.'%'];
        }
        $res = $this
            ->where($map)
            ->alias('a')
            ->join('user','a.user_id=user.user_id')
            ->join('school_store store','a.store_id=store.store_id')
            ->join('school_subject subject','a.subject_id=subject.subject_id')
            ->join('goods','a.goods_id=goods.goods_id')
            ->join('school_subject_time time','a.subjecttime_id=time.subjecttime_id')
            ->field('a.*,user.nickName,store.store_name,subject.subject_name,goods.goods_name,time.time1,time.time2,time.time3,time.time4')
            ->paginate(15, false, [
                'query' => Request::instance()->request()
            ]);
        return $res;
    }


}