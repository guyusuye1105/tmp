<?php

namespace app\common\model\school;

use app\common\model\BaseModel;
use think\Db;
/**
 * 拼团商品评价模型
 * Class Comment
 * @package app\common\model\bargain
 */
class Comment extends BaseModel
{
    protected $name = 'school_comment';

    /**
     * 所属订单
     * @return \think\model\relation\BelongsTo
     */
    public function orderM()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->belongsTo("app\\{$module}\\model\\Order");
    }

    /**
     * 订单商品
     * @return \think\model\relation\BelongsTo
     */
    public function OrderGoods()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->belongsTo("app\\{$module}\\model\\OrderGoods");
    }

    /**
     * 关联用户表
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->belongsTo("app\\{$module}\\model\\User");
    }

    /**
     * 关联评价图片表
     * @return \think\model\relation\HasMany
     */
    public function image()
    {
        return $this->hasMany('CommentImage', 'comment_id')->order(['id' => 'asc']);
    }

    /**
     * 评价详情
     * @param $comment_id
     * @return Comment|null
     * @throws \think\exception\DbException
     */
    public static function detail($comment_id)
    {
        return self::get($comment_id, ['user', 'orderM', 'OrderGoods', 'image.file']);
    }

    /**
     * 更新记录
     * @param $data
     * @return bool
     */
    public function edit($data)
    {
        // 开启事务
        Db::startTrans();
        try {
            // 删除评价图片
            $this->image()->delete();
            // 添加评论图片
            isset($data['images']) && $this->addCommentImages($data['images']);
            // 是否为图片评价
            $data['is_picture'] = !$this->image()->select()->isEmpty();
            // 更新评论记录
            $this->allowField(true)->save($data);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            Db::rollback();
            return false;
        }
    }

    /**
     * 添加评论图片
     * @param $images
     * @return int
     */
    private function addCommentImages($images)
    {
        $data = array_map(function ($image_id) {
            return [
                'image_id' => $image_id,
                'wxapp_id' => self::$wxapp_id
            ];
        }, $images);
        return $this->image()->saveAll($data);
    }

    /**
     * 获取评价列表
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList()
    {
        return $this->with(['user', 'orderM', 'OrderGoods'])
            ->where('is_delete', '=', 0)
            ->order(['sort' => 'asc', 'create_time' => 'desc'])
            ->paginate(15, false, [
                'query' => request()->request()
            ]);

    }

}