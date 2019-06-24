<?php

namespace app\store\controller;

use think\Session;
use app\store\model\store\User;
use app\store\model\Wxapp as WxappModel;
use app\store\model\store\User as StoreUser;


/**
 * 商户认证
 * Class Passport
 * @package app\store\controller
 */
class Passport extends Controller
{
    /**
     * 商户后台登录
     * @return array|mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function login()
    {
        if ($this->request->isAjax()) {
            $model = new StoreUser;
            if ($model->login($this->postData('User'))) {
                return $this->renderSuccess('登录成功', url('index/index'));
            }
            return $this->renderError($model->getError() ?: '登录失败');
        }
        $this->view->engine->layout(false);
        return $this->fetch('login');
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        $base_url=base_url();
        $base_url=str_replace('addons/mall/web/','',$base_url);
        $we7_url=$base_url."web/index.php?c=account&a=display";
        header("Location: {$we7_url}");
        die;
    }


    /**
     * 微擎自动登录/注册
     * @throws \Exception
     * @throws \think\exception\DbException
     */
    public function we7login()
    {
        

        // 获取当前小程序信息
        $wxapp = WxappModel::detail();

        // 判断不存在小程序信息 则自动注册
        if (empty($wxapp)) {
            $model = new WxappModel;
            $model->add($this->store['we7_data']);
        }

        // 当前用户信息
        $where['we7_uid']=$this->store['we7_user']['uid'];
        $where['user_name']=$this->store['we7_user']['user_name'];
        $user = User::detail($where);

        if(empty($user)){
            // 判断用户不存在 自动祖册
            $StoreUser = new \app\common\model\StoreUser();
            $store_user_id=$StoreUser->insertDefault($this->store['wxapp']['wxapp_id'],$this->store['we7_user']);

            //直接将we7的信息放到本来的user里面 避免再去查询一次
            $this->store['user']['user_name']=$this->store['we7_user']['user_name'];
            $this->store['user']['real_name']=$this->store['we7_user']['real_name'];
            $this->store['user']['store_user_id']=$store_user_id;
            $session=Session::get();
            $module=$session['moudle'];
            Session::set($module,$this->store);
        }else{
            //刷新sesssion
            //页面验证之后 获取到了用户信息 刷新session
            $session=Session::get();
            $module=$session['moudle'];
            $userdata=$user->getData();

            unset($userdata['password']);
            $this->store['user']=array_merge($userdata,$this->store['we7_user']);

            Session::set($module,$this->store);
        }

        $this->redirect('index/index');
    }
}
