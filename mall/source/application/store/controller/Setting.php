<?php

namespace app\store\controller;

use app\api\model\UserAddress;
use app\common\library\sms\Driver as SmsDriver;
use app\store\model\Setting as SettingModel;

/**
 * 系统设置
 * Class Setting
 * @package app\store\controller
 */
class Setting extends Controller
{
    /**
     * 商城设置
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function poster()
    {
        return $this->updateEvent2('poster');
    }

    public function sharepic()
    {
        return $this->updateEvent2('sharepic');
    }
    /**
     * 商城设置
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function store()
    {
        return $this->updateEvent('store');
    }

    /**
     * 交易设置
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function trade()
    {
        return $this->updateEvent('trade');
    }


    /*
     * 用户额外字段设置
     */
    public function user_extra_field(){

        if ($this->request->isAjax()) {
            $data = $this->postData('user_extra_field');
            return $this->updateEvent('user_extra_field');
        }
        $field=(new UserAddress)->extra_filed;
        return $this->fetch('user_extra_field', [
            'fieldList' => $field]
        );


    }


    /**
     * 短信通知
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function sms()
    {
        return $this->updateEvent('sms');
    }

    /**
     * 模板消息
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function tplMsg()
    {
        return $this->updateEvent('tplMsg');
    }

    /**
     * 发送短信通知测试
     * @param $AccessKeyId
     * @param $AccessKeySecret
     * @param $sign
     * @param $msg_type
     * @param $template_code
     * @param $accept_phone
     * @return array
     * @throws \think\Exception
     */
    public function smsTest($AccessKeyId, $AccessKeySecret, $sign, $msg_type, $template_code, $accept_phone)
    {
        $SmsDriver = new SmsDriver([
            'default' => 'aliyun',
            'engine' => [
                'aliyun' => [
                    'AccessKeyId' => $AccessKeyId,
                    'AccessKeySecret' => $AccessKeySecret,
                    'sign' => $sign,
                    $msg_type => compact('template_code', 'accept_phone'),
                ],
            ],
        ]);
        $templateParams = [];
        if ($msg_type === 'order_pay') {
            $templateParams = ['order_no' => '2018071200000000'];
        }
        if ($SmsDriver->sendSms($msg_type, $templateParams, true)) {
            return $this->renderSuccess('发送成功');
        }
        return $this->renderError('发送失败 ' . $SmsDriver->getError());
    }

    /**
     * 上传设置
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function storage()
    {
        return $this->updateEvent('storage');
    }

    /**
     * 更新商城设置事件
     * @param $key
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    private function updateEvent($key)
    {
        if (!$this->request->isAjax()) {
            $values = SettingModel::getItem($key);
            return $this->fetch($key, compact('values'));
        }
        $model = new SettingModel;
        if ($model->edit($key, $this->postData($key))) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError('更新失败');
    }

    private function updateEvent2($key)
    {
        if (!$this->request->isAjax()) {
            $values = SettingModel::getItem($key);
           // dump($values);die;
            return $this->fetch($key, [
                'data' => json_encode($values, JSON_UNESCAPED_UNICODE)
            ]);

        }
        $model = new SettingModel;
        if ($model->edit($key, $this->postData($key))) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError('更新失败');

    }

}
