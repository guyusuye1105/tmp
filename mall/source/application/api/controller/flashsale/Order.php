<?php

namespace app\api\controller\flashsale;

use app\api\controller\Controller;
use app\common\library\wechat\WxPay;
use app\api\model\Wxapp as WxappModel;
use app\api\model\flashsale\Order as OrderModel;
use app\api\model\flashsale\Active as ActiveModel;
use app\api\model\WxappPrepayId as WxappPrepayIdModel;
use app\api\model\flashsale\Goods as GoodsModel;

/**
 * 秒杀订单控制器
 * Class Order
 * @package app\api\controller
 */
class Order extends Controller
{
    /* @var \app\api\model\User $user */
    private $user;

    /**
     * 构造方法
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function _initialize()
    {
        parent::_initialize();
        $this->user = $this->getUser();   // 用户信息
    }

    /**
     * 生成秒杀订单
     * @param $order_type
     * @param $goods_id
     * @param $goods_num
     * @param $goods_sku_id
     * @param null $active_id
     * @param null $coupon_id
     * @param string $remark
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function checkout(
        $order_type,
        $goods_id,
        $goods_num,
        $goods_sku_id,
        $active_id = null,
        $coupon_id = null,
        $remark = ''
    )
    {
        // 商品结算信息
        $model = new OrderModel;
        $order = $model->getBuyNow($this->user, $order_type, $goods_id, $goods_num, $goods_sku_id);
        if (!$this->request->isPost()) {
            return $this->renderSuccess($order);
        }
        if ($model->hasError()) {
            return $this->renderError($model->getError());
        }
        // 创建订单
        if ($model->createOrder($this->user['user_id'], $order, $active_id, $coupon_id, $remark)) {
            // 发起微信支付
            return $this->renderSuccess([
                'payment' => $this->unifiedorder($model, $this->user),
                'order_id' => $model['order_id']
            ]);
        }
        return $this->renderError($model->getError() ?: '订单创建失败');
    }

    // 帮秒杀
    public function helpflashsale($active_id,$creator_id)
    {
        $model = new OrderModel;
       /* $activeModel = new ActiveModel;
        $active = $activeModel->getActive($active_id);*/
        $param['creator_id'] = $creator_id;
        //$param['user_id'] = 10038;///////////////////
        $param['user_id'] = $this->user['user_id'];
        $param['active_id'] = $active_id;
        $param['order'] = $model->where('active_id='.$active_id)->find()->toArray();
        $res2 = $model->saveflashsaleActive($param);
        if($res2){
            return $this->renderSuccess( '帮秒杀成功');
        }else{
            return $this->renderError( $model->getError() ?: '帮秒杀失败');
        }
    }
    /**
     * 构建微信支付
     * @param $order
     * @param $user
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    private function unifiedorder($order, $user)
    {
        // 统一下单API
        $wxConfig = WxappModel::getWxappCache();
        $WxPay = new WxPay($wxConfig);
        $payment = $WxPay->unifiedorder($order['order_no'], $user['open_id'], $order['pay_price'], 'flashsale');
        // 记录prepay_id
        $model = new WxappPrepayIdModel;
        $model->add($payment['prepay_id'], $order['order_id'], $user['user_id'], 20);
        return $payment;
    }

    /**
     * 我的秒杀订单列表
     * @param $dataType
     * @return array
     * @throws \think\exception\DbException
     */
    public function lists($dataType)
    {
        $model = new OrderModel;
        $list = $model->getList($this->user['user_id'], $dataType);
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 秒杀订单详情信息
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     */
    public function detail($order_id)
    {
        // 订单详情
        $order = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        // 该订单是否允许申请售后
        $order['isAllowRefund'] = $order->isAllowRefund();
        return $this->renderSuccess(compact('order'));
    }

    /**
     * 获取物流信息
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     */
    public function express($order_id)
    {
        // 订单信息
        $order = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        if (!$order['express_no']) {
            return $this->renderError('没有物流信息');
        }
        // 获取物流信息
        /* @var \app\store\model\Express $model */
        $model = $order['express'];
        $express = $model->dynamic($model['express_name'], $model['express_code'], $order['express_no']);
        if ($express === false) {
            return $this->renderError($model->getError());
        }
        return $this->renderSuccess(compact('express'));
    }

    /**
     * 取消订单
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     */
    public function cancel($order_id)
    {
        $model = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        if ($model->cancel()) {
            return $this->renderSuccess($model->getError() ?: '订单取消成功');
        }
        return $this->renderError($model->getError() ?: '订单取消失败');
    }

    /**
     * 确认收货
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function receipt($order_id)
    {
        $model = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        if ($model->receipt()) {
            return $this->renderSuccess();
        }
        return $this->renderError($model->getError());
    }

    /**
     * 立即支付
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function pay($order_id)
    {
        // 订单详情
        $order = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        // 判断商品状态、库存
        if (!$order->checkGoodsStatusFromOrder($order['goods'])) {
            return $this->renderError($order->getError());
        }
        // 统一下单API
        $wxConfig = WxappModel::getWxappCache();
        $WxPay = new WxPay($wxConfig);
        $payment = $WxPay->unifiedorder($order['order_no'], $this->user['open_id'], $order['pay_price'], 'flashsale');
        // 记录prepay_id
        $model = new WxappPrepayIdModel;
        $model->add($payment['prepay_id'], $order['order_id'], $this->user['user_id'], 20);
        return $this->renderSuccess($payment);
    }

}
