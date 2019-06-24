<?php

namespace app\api\model\school;

use app\common\model\school\Store as StoreModel;

/**
 * 门店模型api
 * @author lichenjie
 */
class Store extends StoreModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'is_delete',
        'wxapp_id',
    ];




}
