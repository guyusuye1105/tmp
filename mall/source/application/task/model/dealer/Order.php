<?php

namespace app\task\model\dealer;

use app\common\model\dealer\Order as OrderModel;

/**
 * 分销商订单模型
 * Class Apply
 * @package app\task\model\dealer
 */
class Order extends OrderModel
{
    /**
     * 获取未结算的分销订单
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUnSettledList()
    {
        return $this->where('is_settled', '=', 0)->select();
    }

}