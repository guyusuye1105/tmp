<?php

namespace app\store\model\bargain;

use app\common\model\bargain\Active as ActiveModel;

/**
 * 拼团拼单模型
 * Class Active
 * @package app\store\model\bargain
 */
class Active extends ActiveModel
{
    /**
     * 获取拼单列表
     * @param null $active_id
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($active_id = null)
    {
        $active_id > 0 && $this->where('active_id', '=', $active_id);
        return $this->with(['goods.image.file', 'user'])
            ->order(['create_time' => 'desc'])
            ->paginate(15, false, [
                'query' => request()->request()
            ]);
    }

}
