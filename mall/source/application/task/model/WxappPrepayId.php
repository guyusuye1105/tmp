<?php

namespace app\task\model;

use app\common\model\WxappPrepayId as WxappPrepayIdModel;

/**
 * 小程序prepay_id模型
 * Class WxappPrepayId
 * @package app\task\model
 */
class WxappPrepayId extends WxappPrepayIdModel
{
    /**
     * 更新prepay_id已付款状态
     * @return false|int
     */
    public function updatePayStatus()
    {
        return $this->save([
            'can_use_times' => 3,
            'pay_status' => 1
        ]);
    }

}