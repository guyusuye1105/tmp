<?php

namespace app\api\model\flashsale;

use app\common\model\flashsale\ActiveUsers as ActiveUsersModel;

/**
 * 秒杀拼单成员模型
 * Class ActiveUsers
 * @package app\api\model\flashsale
 */
class ActiveUsers extends ActiveUsersModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'wxapp_id',
        'create_time',
    ];

}
