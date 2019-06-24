<?php

namespace app\api\model\flashsale;

use app\common\model\flashsale\OrderRefundAddress as OrderRefundAddressModel;

/**
 * 售后单退货地址模型
 * Class OrderRefundAddress
 * @package app\api\model\flashsale
 */
class OrderRefundAddress extends OrderRefundAddressModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'wxapp_id',
        'create_time'
    ];

}