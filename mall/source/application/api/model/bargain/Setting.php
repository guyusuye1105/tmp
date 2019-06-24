<?php

namespace app\api\model\bargain;

use app\common\model\bargain\Setting as SettingModel;

/**
 * 拼团设置模型
 * Class Setting
 * @package app\api\model\bargain
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