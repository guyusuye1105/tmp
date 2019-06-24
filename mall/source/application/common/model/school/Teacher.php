<?php

namespace app\common\model\school;

use app\common\model\BaseModel;
use think\Request;

/**
 * 教师模型
 * @author lichenjie
 */
class Teacher extends BaseModel
{
    protected $name = 'school_teacher';

    /**
     * 获取教师列表
     */
    public function getList($teacher_id = '',$is_delete=0,$pagesize = 1000,$msubject_state='')
    {
        $map['wxapp_id'] = ['=',self::$wxapp_id];
       // $map['is_delete'] = ['=',$is_delete];
        if($msubject_state!==''){
            $map['msubject_state'] = ['=',$msubject_state];
        }

        // 查询单个
        if($teacher_id !=''){
            $map['teacher_id'] = ['=',$teacher_id];
            $res = $this
                ->with('store')
                ->where($map)->find();
            //查询多个
        }else{
            $res = $this
                ->with('store')
                ->where($map)->paginate($pagesize, false, [
                    'query' => Request::instance()->request()
                ]);
        }
        return $res;
    }
    public function classify(){
        $module = self::getCalledModule() ?: 'common';
        return $this->belongsTo("app\\{$module}\\model\\teacher\\Classify");
    }
    public function store(){
        $module = self::getCalledModule() ?: 'common';
        return $this->belongsTo("app\\{$module}\\model\\school\\Store");
    }




}
