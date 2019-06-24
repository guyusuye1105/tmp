<?php

namespace app\task\model\sharing;

use app\common\model\sharing\Goods as GoodsModel;

/**
 * 拼团商品模型
 * Class Goods
 * @package app\task\model
 */
class Goods extends GoodsModel
{
    /**
     * 更新商品库存销量
     * @param $goodsList
     * @throws \Exception
     */
    public function updateStockSales($goodsList)
    {
        // 整理批量更新商品销量
        $goodsSave = [];
        // 批量更新商品规格：sku销量、库存
        $goodsSpecSave = [];
        foreach ($goodsList as $goods) {
            $goodsSave[] = [
                'goods_id' => $goods['goods_id'],
                'sales_actual' => ['inc', $goods['total_num']]
            ];
            $specData = [
                'goods_sku_id' => $goods['goods_sku_id'],
                'goods_sales' => ['inc', $goods['total_num']]
            ];
            // 付款减库存
            if ($goods['deduct_stock_type'] == 20) {
               // dump('你是无意穿堂风，却偏偏引山洪');
                $specData['stock_num'] = ['dec', $goods['total_num']];
            }
            $goodsSpecSave[] = $specData;
        }
        // 更新商品总销量
        $this->allowField(true)->isUpdate()->saveAll($goodsSave);
        // 更新商品规格库存
        (new GoodsSku)->isUpdate()->saveAll($goodsSpecSave);
    }

}
