<?php

namespace app\common\model\flashsale;

use app\common\model\BaseModel;
use app\common\model\Region as RegionModel;

/**
 * 订单收货地址模型
 * Class OrderAddress
 * @package app\common\model
 */
class OrderAddress extends BaseModel
{
    protected $name = 'flashsale_order_address';
    protected $updateTime = false;

    /**
     * 追加字段
     * @var array
     */
    protected $append = ['region'];

    /**
     * 地区名称
     * @param $value
     * @param $data
     * @return array
     */
    public function getRegionAttr($value, $data)
    {
        return [
            'province' => RegionModel::getNameById($data['province_id']),
            'city' => RegionModel::getNameById($data['city_id']),
            'region' => $data['region_id'] == 0 ? '' : RegionModel::getNameById($data['region_id']),
        ];
    }

    /**
     * 获取完整地址
     * @return string
     */
    public function getFullAddress()
    {
        return $this['region']['province'] . $this['region']['province'] . $this['region']['region'] . $this['detail'];
    }

}
