<?php

namespace app\common\model\school;

use app\common\model\BaseModel;
use think\Request;

/**
 * 上课时间模型
 * @author lichenjie
 */
class Subjecttime extends BaseModel
{
    protected $name = 'school_subject_time';

    /**
     * 上课时间列表
     */
    public function getList($subjecttime_id = '',$pagesize = 1000,$status='')
    {
        $map['wxapp_id'] = ['=',self::$wxapp_id];
        if($status!==''){
            $map['status'] = ['=',$status];
        }
        // 查询单个
        if($subjecttime_id !=''){
            $map['subjecttime_id'] = ['=',$subjecttime_id];
            $res = $this->where($map)->find();
            //查询多个
        }else{
            $res = $this->where($map)
                ->order('timestamp1')
                ->paginate($pagesize, false, [
                    'query' => Request::instance()->request()
                ]);
        }
        return $res;
    }

}