<?php

namespace app\api\model\flashsale;

use app\common\model\flashsale\OrderAddress as OrderAddressModel;

/**
 * 秒杀订单收货地址模型
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
