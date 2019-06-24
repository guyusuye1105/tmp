<?php

namespace app\api\controller\flashsale;

use app\api\controller\Controller;
use app\api\model\flashsale\Setting as SettingModel;

/**
 * 秒杀设置控制器
 * Class Setting
 * @package app\api\controller\flashsale
 */
class Setting extends Controller
{
    /**
     * 获取所有设置
     * @return array
     */
    public function getAll()
    {
        $basic = SettingModel::getItem('basic');
        return $this->renderSuccess(['setting' => compact('basic')]);
    }

}
