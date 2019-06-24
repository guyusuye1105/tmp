<?php

namespace app\api\model\flashsale;

use app\common\model\flashsale\Setting as SettingModel;

/**
 * 秒杀设置模型
 * Class Setting
 * @package app\api\model\flashsale
 */
class Setting extends SettingModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'update_time',
    ];

}