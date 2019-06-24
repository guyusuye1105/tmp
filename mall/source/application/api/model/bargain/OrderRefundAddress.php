<?php

namespace app\api\model\bargain;

use app\common\model\bargain\OrderRefundAddress as OrderRefundAddressModel;

/**
 * 售后单退货地址模型
 * Class OrderRefundAddress
 * @package app\api\model\bargain
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