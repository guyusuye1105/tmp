<?php

namespace app\store\controller;

use think\db;
//use app\admin\model\Wxapp as WxappModel;
//use app\admin\model\store\User as StoreUser;

/**
 * 定时任务
 */
class Crontab
{

    /**
     * 对于需要预约的商品 插入数据到预约表中
     * @author lcihenjie
     */
    public function order_goods()
    {
        $order_goods_id = $_REQUEST['order_goods_id'];
        $goods = Db('order_goods')->where('order_goods_id='.$order_goods_id)->select();
        $param = array(
            'goods_id'=>$goods[0]['goods_id'],
            'order_id'=>$goods[0]['order_id'],
            'user_id'=>$goods[0]['user_id'],
            'wxapp_id'=>$goods[0]['wxapp_id'],
            'subject_id'=>$goods[0]['subject_id'],
            'state'=>'nouse',
        );
        db('school_appoint')->insert($param);
    }

    //测试接口：http://10.131.4.200:9001/addons/mt2_school/web/index.php?s=/admin/crontab/workByDay
    //以上为真实的接口，下面没用到，测试而已

    /**
     * 小程序列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $model = new WxappModel;
        return $this->fetch('index', [
            'list' => $list = $model->getList(),
            'names' => $model->getStoreName($list)
        ]);
    }

    /**
     * [时乘教育]定时任务测试1
     *  admin/crontab/test1
     */
    public function test1()
    {
        $a = array(
            'teacher_name'=>'定时任务测试啊啊啊'
        );
        Db('teacher')->insert($a);
    }

    /**
     * [时乘教育]课程是否开班
     *  admin/crontab/subjectOpen
     */
    public function subjectOpen()
    {
        $map['is_delete'] = ['=',0];
        $map['status'] = ['=',0];
        $map['status'] = ['=',0];
        $subject = Db('subject')
            ->where($map)
            ->select();
        p($subject->toArray());
    }



}