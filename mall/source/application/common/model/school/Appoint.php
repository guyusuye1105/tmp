<?php

namespace app\common\model\school;

use app\common\model\BaseModel;
use app\api\model\Goods as GoodsModel;
use think\Request;
use think\Db;


class Appoint extends BaseModel
{
    protected $name = 'school_appoint';

    public function getList($user_id='',$state='',$appoint_id='',$keywords='')
    {
        $map = [];
        if($appoint_id !=''){
            $map['appoint_id'] = $appoint_id;
        }
        if($user_id != ''){
            $map['user_id'] = $user_id;
        }
        if($state != ''){
            $map['state'] = $state;
        }
        if($keywords != ''){
            $map['user_id'] = $keywords;
        }
        $res = $this
            ->where($map)
            ->with(['user','order','store','goods.image.file','subjecttime','subject'])
            ->paginate(15, false, [
                'query' => Request::instance()->request()
            ]);
        foreach($res as $key=>$val){
            // 时间戳变成时间
            $res[$key]['subject']['subject_endtime_format'] = date('Y-h-d',$res[$key]['subject']['subject_endtime']);
        }
        return $res;
    }

    // 关联用户表
    public function user()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->belongsTo("app\\{$module}\\model\\User");
    }
    // 关联订单
    public function order()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->belongsTo("app\\{$module}\\model\\Order");
    }
    // 关联门店
    public function store()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->belongsTo("app\\{$module}\\model\\school\\Store");
    }
    // 关联商品
    public function goods()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->belongsTo("app\\{$module}\\model\\Goods");
    }
    // 关联时间
    public function subjecttime()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->belongsTo("app\\{$module}\\model\\school\\Subjecttime");
    }
    // 关联课程
    public function subject()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->belongsTo("app\\{$module}\\model\\school\\Subject");
    }


}