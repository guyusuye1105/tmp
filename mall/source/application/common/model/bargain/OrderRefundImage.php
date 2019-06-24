<?php

namespace app\common\model\bargain;

use app\common\model\BaseModel;

/**
 * 售后单图片模型
 * Class OrderRefundImage
 * @package app\common\model\bargain
 */
class OrderRefundImage extends BaseModel
{
    protected $name = 'bargain_order_refund_image';
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
