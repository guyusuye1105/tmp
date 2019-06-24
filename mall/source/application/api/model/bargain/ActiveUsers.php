<?php

namespace app\api\model\bargain;

use app\common\model\bargain\ActiveUsers as ActiveUsersModel;

/**
 * 拼团拼单成员模型
 * Class ActiveUsers
 * @package app\api\model\bargain
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
