<?php

namespace app\api\model\flashsale;

use app\common\model\flashsale\Goods as GoodsModel;

/**
 * 秒杀商品模型
 * Class Goods
 * @package app\api\model\flashsale
 */
class Goods extends GoodsModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'sales_initial',
        'sales_actual',
        'is_delete',
        //'wxapp_id',
        'create_time',
        'update_time'
    ];

    /**
     * 商品详情：HTML实体转换回普通字符
     * @param $value
     * @return string
     */
    public function getContentAttr($value)
    {
        return htmlspecialchars_decode($value);
    }

    /**
     * 根据商品id集获取商品列表
     * @param $goodsIds
     * @param null $status
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getListByIds($goodsIds, $status = null)
    {
        // 筛选条件
        $filter = ['goods_id' => ['in', $goodsIds]];
        $status > 0 && $filter['goods_status'] = $status;
        if (!empty($goodsIds)) {
            $this->orderRaw('field(goods_id, ' . implode(',', $goodsIds) . ')');
        }
        // 获取商品列表数据
        return $this->with(['category', 'image.file', 'sku', 'spec_rel.spec', 'delivery.rule'])
            ->where($filter)
            ->select();
    }

    /**
     * 获取商品列表
     * @param null $status
     * @param int $category_id
     * @param string $search
     * @param string $sortType
     * @param bool $sortPrice
     * @param int $listRows
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList(
        $status = null,
        $category_id = 0,
        $search = '',
        $sortType = 'all',
        $sortPrice = false,
        $listRows = 15,
        $blocks = ''
    )
    {
        // 筛选条件
        $filter = [];
            // 进行中
        if($blocks == 'ing'){
            $filter['show_time'] = ['<',time()];
            $filter['begin_time'] = ['<',time()];
            // 预告
        }elseif($blocks == 'before'){
            $filter['show_time'] = ['<',time()];
            $filter['begin_time'] = ['>',time()];
        }

        $category_id > 0 && $filter['category_id'] = ['IN', Category::getSubCategoryId($category_id)];
        $status > 0 && $filter['goods_status'] = $status;
        !empty($search) && $filter['goods_name'] = ['like', '%' . trim($search) . '%'];
        // 排序规则
        $sort = [];
        if ($sortType === 'all') {
            $sort = ['goods_sort', 'goods_id' => 'desc'];
        } elseif ($sortType === 'sales') {
            $sort = ['goods_sales' => 'desc'];
        } elseif ($sortType === 'price') {
            $sort = $sortPrice ? ['goods_max_price' => 'desc'] : ['goods_min_price'];
        }
        // 商品表名称
        $tableName = $this->getTable();
        // 多规格商品 最高价与最低价
        $GoodsSku = new GoodsSku;
        $minPriceSql = $GoodsSku->field(['MIN(goods_price)'])
            ->where('goods_id', 'EXP', "= `$tableName`.`goods_id`")->buildSql();
        $maxPriceSql = $GoodsSku->field(['MAX(goods_price)'])
            ->where('goods_id', 'EXP', "= `$tableName`.`goods_id`")->buildSql();
        // 到达结束时间就下架
       /* $where = 'where goods_status=10 and wxapp_id='.self::$wxapp_id.' and stop_time<'.time();
        $sql = 'update ims_mall_flashsale_goods set goods_status=20 '.$where;
        db()->query($sql);*/

        // 执行查询
        $list = $this
            ->field(['*', '(sales_initial + sales_actual) as goods_sales',
                "$minPriceSql AS goods_min_price",
                "$maxPriceSql AS goods_max_price"
            ])
            ->with(['category', 'image.file', 'sku'])
            ->where('is_delete', '=', 0)
            ->where($filter)
            ->order($sort)
            ->paginate($listRows, false, [
                'query' => \request()->request()
            ]);
        return $list;
    }

}
