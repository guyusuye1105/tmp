<?php

namespace app\common\model\school;

use app\common\model\BaseModel;
use think\Request;
use think\Db;

/**
 * 上课时间模型
 * @author lichenjie
 */
class Timeform extends BaseModel
{
    protected $name = 'school_timeform';

    /**
     * 上课时间列表（传入七天时间的数组）
     */
    /**
     * 上课时间列表（传入七天时间的数组）
     * @param $day  传入七天时间的数组
     * @param int $subject_id   课程id(不传则查询所有)
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getTimeForm($day,$subject_id=0,$store_id=0)
    {
        $timeForm = array();
        foreach($day as $key=>$val){
            $map = [];
            $map['day'] = ['=',$val];
            $map['store_id'] = ['=',$store_id];
            $res = $this
                ->where($map)
                ->select()
                ->toArray();
           // return $res;
            //dump($res);die;
            //p($res);
            // 如果没有传入$subject_id
            if($subject_id == 0){
                foreach($res as $key2 => $val2){
                    $map2 = [];
                    $teacher_id = $val2['teacher_id'];
                    $map2['wxapp_id'] = ['=',self::$wxapp_id];
                    $map2['subject_id'] = ['=',$val2['subject_id']];
                    $key3 = $key.'-'.$val2['subjecttime_id'];
                    $val2 = Db('school_subject')
                        ->where($map2)
                        ->find();
                    $val2['teacher_id'] = $teacher_id;
                    $val2['teacher_name'] = Db('school_teacher')->where('teacher_id',$teacher_id)->value('teacher_name');
                    // $val2 = $subjectModel->where('subject_id='.$val2['subject_id'])->select()->toArray();
                    if(!isset($timeForm[$key3])){
                        $timeForm[$key3] = array();
                    }
                    array_push($timeForm[$key3],$val2);

                    //$timeForm[$key3] = $val2;
                }
            }else{
                foreach($res as $key2 => $val2){
                    if($val2['subject_id'] == $subject_id){
                        $map2 = [];
                        $teacher_id = $val2['teacher_id'];
                        $map2['wxapp_id'] = ['=',self::$wxapp_id];
                        $map2['subject_id'] = ['=',$val2['subject_id']];
                        $key3 = $key.'-'.$val2['subjecttime_id'];
                        $val2 = Db('school_subject')
                            ->where($map2)
                            ->find();
                        $val2['teacher_id'] = $teacher_id;
                        $val2['teacher_name'] = Db('school_teacher')->where('teacher_id',$teacher_id)->value('teacher_name');
                        // $val2 = $subjectModel->where('subject_id='.$val2['subject_id'])->select()->toArray();
                        if(!isset($timeForm[$key3])){
                            $timeForm[$key3] = array();
                        }
                        array_push($timeForm[$key3],$val2);
                    }

                }
            }

        }
        // p($timeForm);
        return $timeForm;
    }

    // 根据上课时间和日期获得该时段的所有课程
    public function getSubject($day,$time,$store_id){
        $map['day'] = ['=',$day];
        $map['subjecttime_id'] = ['=',$time];
        $map['store_id'] = ['=',$store_id];
        $res = $this
            ->where($map)
            ->select()
            ->toArray();
        foreach($res as $key=>$val){
            $map2 = [];
            $map2['wxapp_id'] = ['=',self::$wxapp_id];
            $map2['subject_id'] = ['=',$val['subject_id']];
            $val2 = Db('school_subject')
                ->where($map2)
                ->find();
            if(!isset($res[$key]['subject'])){
                $res[$key]['subject'] = array();
            }
            $res[$key]['subject'] = $val2;
            $res[$key]['teacher_name'] = Db('school_teacher')->where('teacher_id',$val['teacher_id'])->value('teacher_name');
        }
        return $res;
    }

    // 根据门店id和课程id获取对应时间段
    public function getTime($store_id,$subject_id,$day){
        $map['a.store_id'] = ['=',$store_id];
        $map['a.subject_id'] = ['=',$subject_id];
        $map['a.day'] = ['=',$day];
        $res = $this
            ->where($map)
            ->alias('a')
            ->join('school_teacher teacher','a.teacher_id = teacher.teacher_id')
            ->join('school_subject_time time','a.subjecttime_id = time.subjecttime_id')
            ->field('a.*,teacher.teacher_name,time.time1,time.time2,time.time3,time.time4')
            ->order('time.timestamp1')
            ->select();
        return $res;
    }

}