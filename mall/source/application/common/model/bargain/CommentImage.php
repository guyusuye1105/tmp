<?php

namespace app\common\model\bargain;

use app\common\model\BaseModel;

/**
 * 拼团商品评价图片模型
 * Class CommentImage
 * @package app\common\model\bargain
 */
class CommentImage extends BaseModel
{
    protected $name = 'bargain_comment_image';
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
