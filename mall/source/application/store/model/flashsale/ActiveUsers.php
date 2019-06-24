<?php

namespace app\store\model\flashsale;

use app\common\model\flashsale\ActiveUsers as ActiveUsersModel;

/**
 * 秒杀拼单成员模型
 * Class ActiveUsers
 * @package app\store\model\flashsale
 */
class ActiveUsers extends ActiveUsersModel
{
    /**
     * 获取拼单成员列表
     * @param $active_id
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($active_id)
    {
        return $this->with(['flashsaleOrder.address', 'user'])
            ->where('active_id', '=', $active_id)
            ->order(['create_time' => 'asc'])
            ->paginate(15, false, [
                'query' => request()->request()
            ]);
    }

}
