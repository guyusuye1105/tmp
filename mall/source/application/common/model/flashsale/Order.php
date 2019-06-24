<?php

namespace app\common\model\flashsale;

use think\Hook;
use app\common\model\BaseModel;

/**
 * 秒杀订单模型
 * Class Order
 * @package app\common\model
 */
class Order extends BaseModel
{
    protected $name = 'flashsale_order';

    /**
     * 追加字段
     * @var array
     */
    protected $append = [
        'state_text',   // 售后单状态文字描述
    ];

    /**
     * 秒杀订单状态文字描述
     * @param $value
     * @param $data
     * @return string
     */
    public function getStateTextAttr($value, $data)
    {
        if (!isset($data['active_status'])) {
            $data['active_status'] = '';
        }
        // 订单状态：已完成
        if ($data['order_status'] == 30) {
            return '已完成';
        }
        // 订单状态：已取消
        if ($data['order_status'] == 20) {
            // 拼单未成功
            if ($data['order_type'] == 20 && $data['active_status'] == 30) {
                return $data['is_refund'] ? '秒杀未成功，已退款' : '秒杀未成功，待退款';
            }
            return '已取消';
        }
        // 付款状态
        if ($data['pay_status'] == 10) {
            return '待付款';
        }
        // 订单类型：单独购买
        if ($data['order_type'] == 10) {
            if ($data['delivery_status'] == 10) {
                return '已付款，待发货';
            }
            if ($data['receipt_status'] == 10) {
                return '已发货，待收货';
            }
        }
        // 订单类型：秒杀
        if ($data['order_type'] == 20) {
            // 拼单未成功
            if ($data['active_status'] == 30) {
                return $data['is_refund'] ? '秒杀未成功，已退款' : '秒杀未成功，待退款';
            }
            // 拼单中
            if ($data['active_status'] == 10) {
                return '已付款，待成团';
            }
            // 拼单成功
            if ($data['active_status'] == 20) {
                if ($data['delivery_status'] == 10) {
                    return '秒杀成功，待发货';
                }
                if ($data['receipt_status'] == 10) {
                    return '已发货，待收货';
                }
            }
        }
        return $value;
    }

    /**
     * 订单模型初始化
     */
    public static function init()
    {
        parent::init();
        // 监听订单处理事件
        $static = new static;
        Hook::listen('flashsale_order', $static);
    }

    /**
     * 关联拼单表
     * @return \think\model\relation\BelongsTo
     */
    public function active()
    {
        return $this->belongsTo('Active');
    }

    /**
     * 订单商品列表
     * @return \think\model\relation\HasMany
     */
    public function goods()
    {
        return $this->hasMany('OrderGoods', 'order_id');
    }

    /**
     * 关联订单收货地址表
     * @return \think\model\relation\HasOne
     */
    public function address()
    {
        return $this->hasOne('OrderAddress', 'order_id');
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
     * 关联物流公司表
     * @return \think\model\relation\BelongsTo
     */
    public function express()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->belongsTo("app\\{$module}\\model\\Express");
    }

    /**
     * 获取器：拼单状态
     * @param $value
     * @return array|bool
     */
    public function getActiveStatusAttr($value)
    {
        if (is_null($value)) {
            return false;
        }
        $state = [
            0 => '未拼单',
            10 => '拼单中',
            20 => '拼单成功',
            30 => '拼单失败',
        ];
        return ['text' => $state[$value], 'value' => $value];
    }

    /**
     * 改价金额（差价）
     * @param $value
     * @return array
     */
    public function getUpdatePriceAttr($value)
    {
        return [
            'symbol' => $value < 0 ? '-' : '+',
            'value' => sprintf('%.2f', abs($value))
        ];
    }

    /**
     * 订单类型
     * @param $value
     * @return array
     */
    public function getOrderTypeAttr($value)
    {
        $status = [10 => '单独购买', 20 => '秒杀'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 付款状态
     * @param $value
     * @return array
     */
    public function getPayStatusAttr($value)
    {
        $status = [10 => '待付款', 20 => '已付款'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 发货状态
     * @param $value
     * @return array
     */
    public function getDeliveryStatusAttr($value)
    {
        $status = [10 => '待发货', 20 => '已发货'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 收货状态
     * @param $value
     * @return array
     */
    public function getReceiptStatusAttr($value)
    {
        $status = [10 => '待收货', 20 => '已收货'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 收货状态
     * @param $value
     * @return array
     */
    public function getOrderStatusAttr($value)
    {
        $status = [10 => '进行中', 20 => '已取消', 21 => '待取消', 30 => '已完成', 40 => '秒杀失败'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 生成订单号
     * @return string
     */
    protected function orderNo()
    {
        return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }

    /**
     * 订单详情
     * @param $where
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($where)
    {
        is_array($where) ? $filter = $where : $filter['order_id'] = (int)$where;
        return self::get($filter, ['active', 'goods.image', 'address', 'express']);
    }

    /**
     * 主订单详情 (不关联其他表，给分销订单使用)
     * @param $order_id
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public static function masterDetail($order_id)
    {
        return (new static)->with(['goods' => ['image', 'refund'], 'address', 'user'])
            ->alias('order')
            ->field('order.*, active.status as active_status')
            ->join('flashsale_active active', 'order.active_id = active.active_id', 'LEFT')
            ->where('order_id', '=', $order_id)->find();
    }

}
