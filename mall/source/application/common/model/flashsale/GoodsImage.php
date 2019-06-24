<?php

namespace app\common\model\flashsale;

use app\common\model\BaseModel;

/**
 * 秒杀商品图片模型
 * Class GoodsImage
 * @package app\common\model\flashsale
 */
class GoodsImage extends BaseModel
{
    protected $name = 'flashsale_goods_image';
    protected $updateTime = false;

    /**
     * 关联文件库
     * @return \think\model\relation\BelongsTo
     */
    public function file()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->belongsTo("app\\{$module}\\model\\UploadFile", 'image_id', 'file_id')
            ->bind(['file_path', 'file_name', 'file_url']);
    }

}
