<?php

namespace app\api\controller\school;

use app\api\controller\Controller;
use app\api\model\school\Timeform as TimeformModel;

class Timeform extends Controller
{
    // 根据门店id和课程id获取对应时间段
    function getTime($store_id,$subject_id,$day){
        $model = new TimeformModel;
        $list = $model->getTime($store_id,$subject_id,$day);
        return $this->renderSuccess(compact('list'));
    }
}
