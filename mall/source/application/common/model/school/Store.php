<?php

namespace app\common\model\school;

use app\common\model\BaseModel;
use think\Request;
/**
 * 门店模型 common
 * @author lichenjie
 */
class Store extends BaseModel
{
    protected $name = 'school_store';


    public function getList($store_id='',$pagesize=1000)
    {
        $map['wxapp_id'] = ['=',self::$wxapp_id];
        $map['is_delete'] = ['=',0];
        if($store_id !== ''){
            $map['store_id'] = ['=',$store_id];
        }
        $res = $this->where($map)
            ->paginate($pagesize, false, ['query' => Request::instance()->request()]);
        foreach($res as $key=>$val){
            $res[$key]['slide'] = explode(';',$val['slide']);
        }
        return $res;
    }
}