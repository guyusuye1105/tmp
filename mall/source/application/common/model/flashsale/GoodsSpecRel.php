<?php

namespace app\common\model\flashsale;

use app\common\model\BaseModel;

/**
 * 秒杀商品规格关系模型
 * Class GoodsSpecRel
 * @package app\common\model\flashsale
 */
class GoodsSpecRel extends BaseModel
{
    protected $name = 'flashsale_goods_spec_rel';
    protected $updateTime = false;

    /**
     * 关联规格组
     * @return \think\model\relation\BelongsTo
     */
    public function spec()
    {
        return $this->belongsTo('Spec');
    }

}
