<?php

namespace app\api\model;

use app\common\model\Region;
use app\common\model\UserAddress as UserAddressModel;

/**
 * 用户收货地址模型
 * Class UserAddress
 * @package app\common\model
 */
class UserAddress extends UserAddressModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'wxapp_id',
        'create_time',
        'update_time'
    ];

    //为教育edu模板添加的扩展字段
    public $extra_filed=['kid_name'=>'孩子姓名','kid_age'=>'孩子年龄','wechat_id'=>'微信号'];

    /**
     * @param $user_id
     * @return false|static[]
     * @throws \think\exception\DbException
     */
    public function getList($user_id)
    {
        return self::all(compact('user_id'));
    }

    /**
     * 新增收货地址
     * @param User $user
     * @param $data
     * @return bool
     * @throws \think\exception\PDOException
     */
    public function add($user, $data)
    {
        // 整理地区信息
        if(!empty($data['region'])){
            $region = explode(',', $data['region']);
            $province_id = Region::getIdByName($region[0], 1);
            $city_id = Region::getIdByName($region[1], 2, $province_id);
            $region_id = Region::getIdByName($region[2], 3, $city_id);
        }else{
            $region_id=0;
            $province_id=0;
            $region='';
            $city_id=0;
        }



        $extra_data=self::handler_extra_field($data);


        // 添加收货地址
        $this->startTrans();
        try {
            $this->allowField(true)->save([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'province_id' => $province_id ?? 0,
                'city_id' => $city_id ?? 0,
                'region_id' => $region_id ?? 0,
                'detail' => $data['detail'] ?? '',
                'district' => ($region_id === 0 && !empty($region[2])) ? $region[2] : '',
                'user_id' => $user['user_id'],
                'wxapp_id' => self::$wxapp_id,
                'extra' => $extra_data,
            ]);

            // 设为默认收货地址
            !$user['address_id'] && $user->save(['address_id' => $this['address_id']]);
            $this->commit();
            return true;
        } catch (\Exception $e) {

            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }

    /**
     * 编辑收货地址
     * @param $data
     * @return false|int
     */
    public function edit($data)
    {

        // 添加收货地址
        // 整理地区信息
        if(!empty($data['region'])){
            $region = explode(',', $data['region']);
            $province_id = Region::getIdByName($region[0], 1);
            $city_id = Region::getIdByName($region[1], 2, $province_id);
            $region_id = Region::getIdByName($region[2], 3, $city_id);
        }else{
            $region_id=0;
            $province_id=0;
            $region='';
            $city_id=0;
        }

        $extra_data=self::handler_extra_field($data);
        return $this->allowField(true)->save([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'province_id' => $province_id,
            'city_id' => $city_id,
            'region_id' => $region_id,
            'detail' => $data['detail'] ?? '',
            'extra' => $extra_data,
            'district' => ($region_id === 0 && !empty($region[2])) ? $region[2] : '',
        ]);
    }


    private function handler_extra_field($data){
        $extra=$this->extra_filed;
        $extra=array_keys($extra);
        if(empty($extra)){
            return true;
        }
        $extra_data=[];
        foreach ($data as $k=> $v){
            if(in_array($k,$extra)){
                $extra_data[$k]=$v;
            }
        }
        if(!empty($extra_data)){
            return json_encode($extra_data);
        }else{
            return "";
        }
    }

    /**
     * 设为默认收货地址
     * @param null|static $user
     * @return int
     */
    public function setDefault($user)
    {
        // 设为默认地址
        return $user->save(['address_id' => $this['address_id']]);
    }

    /**
     * 删除收货地址
     * @param null|static $user
     * @return int
     */
    public function remove($user)
    {
        // 查询当前是否为默认地址
        $user['address_id'] == $this['address_id'] && $user->save(['address_id' => 0]);
        return $this->delete();
    }

    /**
     * 收货地址详情
     * @param $user_id
     * @param $address_id
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($user_id, $address_id)
    {
        return self::get(compact('user_id', 'address_id'));
    }

}
