<?php

namespace app\common\model;

/**
 * 微信小程序分类页模板
 * Class WxappCategory
 * @package app\common\model
 */
class WxappCategory extends BaseModel
{
    protected $name = 'wxapp_category';

    /**
     * 分类模板详情
     * @return static|null
     * @throws \think\exception\DbException
     */
    public static function detail()
    {
        return self::get([]);
    }

    public function insertDefault($wxapp_id)
    {
        return $this->save([
            'wxapp_id' => $wxapp_id,
            'category_style' => 10,
            'create_time' => time(),
        ]);
    }

}