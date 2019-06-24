<?php

namespace app\task\behavior\school;
use think\Db;

/**
 * [预约中心]课程核销
 * Class Hexiao
 * @package app\task\behavior
 */
class Hexiao
{
    public function run($request)
    {
        // 核销课程
        if(!empty($request->param()['order_id'])){
            $order_id = $request->param()['order_id'];
            Db('school_appoint')->where('order_id',$order_id)->update(['state' => 'comment']);
        }
    }


}
