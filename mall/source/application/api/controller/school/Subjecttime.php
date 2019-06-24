<?php

namespace app\api\controller\school;

use app\api\controller\Controller;
use app\api\model\school\Subjecttime as SubjecttimeModel;
/*
use app\api\model\sharing\Goods as GoodsModel;
use app\common\service\qrcode\Goods as GoodsPoster;
use app\api\model\sharing\Active as ActiveModel;
*/

class Subjecttime extends Controller
{
    public function lists()
    {
        $model = new SubjecttimeModel;
        $list = $model->getList('',config('paginate.list_rows'),'1');
        return $this->renderSuccess(compact('list'));
    }


}
