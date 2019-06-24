<?php

namespace app\common\model\school;

use app\common\model\BaseModel;
use think\Request;

class Subject extends BaseModel
{
    protected $name = 'school_subject';
    //protected $append = ['goods_sales'];


    public function getList($subject_id = '',$teacher_id='',$keywords='',$status='',$state='',$is_delete=0,$pagesize = 1000,$type='',$classify_id='')
    {
        $map['wxapp_id'] = ['=',self::$wxapp_id];
        $map['is_delete'] = ['=',$is_delete];
        if($type!==''){
            if(is_array($type)){
                $map['type'] = ['in',$type];
            }else{
                $map['type'] = ['=',$type];
            }
        }
        if($classify_id!=''){
            $map['classify_id'] = ['=',$classify_id];
        }
        if($teacher_id!=''){
            $map['teacher_id'] = ['=',$teacher_id];
        }
        if($state!==''){
            $map['state'] = ['=',$state];
        }
        if($status!==''){
            $map['status'] = ['=',$status];
        }
        if($keywords!==''){
            $map['subject_name'] = ['like','%'.$keywords.'%'];
        }
        /*
        if($type == 'super'){
            $with = ['teacher','classroom','subjecttime','student'];
        }else{
            $with = ['teacher','classroom','subjecttime'];
        }
        */
        //dump($map);die;
        // 查询单个
        if($subject_id !=''){
            $map['subject_id'] = ['=',$subject_id];
            $res = $this
                ->where($map)->find();
            //查询多个
        }else{
            $res = $this->where($map)
                ->paginate($pagesize, false, [
                    'query' => Request::instance()->request()
                ]);
        }

        // 对$res遍历
        if($subject_id !=''){
            //$res['slide'] = explode(';',$res['slide']);
//            $res['baoming_endtime_date'] = ($res['baoming_endtime'] == 0) ? '' : date('Y-m-d',$res['baoming_endtime']);
           // $res['open_endtime_date'] = ($res['open_endtime'] == 0) ? '' : date('Y-m-d',$res['open_endtime']);
           // $res['open_begintime_date'] = ($res['open_begintime'] == 0) ? '' : date('Y-m-d',$res['open_begintime']);
            $res['subject_endtime_date'] = ($res['subject_endtime'] == 0) ? '' : date('Y-m-d',$res['subject_endtime']);

        }else{
            foreach($res as $key=>$val){
//                $res[$key]['slide'] = explode(';',$res[$key]['slide']);
//                $res[$key]['baoming_endtime_date'] = ($res[$key]['baoming_endtime'] == 0) ? '' : date('Y-m-d',$res[$key]['baoming_endtime']);
//                $res[$key]['open_endtime_date'] = ($res[$key]['open_endtime'] == 0) ? '' : date('Y-m-d',$res[$key]['open_endtime']);
//                $res[$key]['open_begintime_date'] = ($res[$key]['open_begintime'] == 0) ? '' : date('Y-m-d',$res[$key]['open_begintime']);
                $res[$key]['subject_endtime_date'] = ($res[$key]['subject_endtime'] == 0) ? '' : date('Y-m-d',$res[$key]['subject_endtime']);
            }
        }

        // p($res);
        return $res;
    }


}
