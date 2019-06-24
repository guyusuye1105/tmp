<?php

namespace app\api\model\bargain;

use app\common\model\bargain\OrderAddress as OrderAddressModel;

/**
 * 拼团订单收货地址模型
 * Class OrderAddress
 * @package app\api\model
 */
class OrderAddress extends OrderAddressModel
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
