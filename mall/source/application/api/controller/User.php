<?php

namespace app\api\controller;

use app\api\model\User as UserModel;

/**
 * 用户管理
 * Class User
 * @package app\api
 */
class User extends Controller
{
    /**
     * 用户自动登录
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function login()
    {
        $model = new UserModel;
        return $this->renderSuccess([
            'user_id' => $model->login($this->request->post()),
            'token' => $model->getToken()
        ]);
    }

    /**
     * 用户获取手机
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function getphone()
    {

        $model = new UserModel;
        $token=$this->request->param('token');
        return $this->renderSuccess([
            'phone' => $model->getphone($this->request->post(),$token),
        ]);
    }

}
