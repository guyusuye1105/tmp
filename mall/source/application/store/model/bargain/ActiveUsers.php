<?php

namespace app\store\model\bargain;

use app\common\model\bargain\ActiveUsers as ActiveUsersModel;

/**
 * 拼团拼单成员模型
 * Class ActiveUsers
 * @package app\store\model\bargain
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
        return $this->with(['bargainOrder.address', 'user'])
            ->where('active_id', '=', $active_id)
            ->order(['create_time' => 'asc'])
            ->paginate(15, false, [
                'query' => request()->request()
            ]);
    }

}
