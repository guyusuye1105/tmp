<?php

namespace app\common\model;

use think\Cache;

/**
 * 系统设置模型
 * Class Setting
 * @package app\common\model
 */
class Setting extends BaseModel
{
    protected $name = 'setting';
    protected $createTime = false;

    /**
     * 获取器: 转义数组格式
     * @param $value
     * @return mixed
     */
    public function getValuesAttr($value)
    {
        return json_decode($value, true);
    }

    /**
     * 修改器: 转义成json格式
     * @param $value
     * @return string
     */
    public function setValuesAttr($value)
    {
        return json_encode($value);
    }

    /**
     * 获取指定项设置
     * @param $key
     * @param $wxapp_id
     * @return array
     */
    public static function getItem($key, $wxapp_id = null)
    {
        $data = self::getAll($wxapp_id);
        return isset($data[$key]) ? $data[$key]['values'] : [];
    }

    /**
     * 获取设置项信息
     * @param $key
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($key)
    {
        return self::get(compact('key'));
    }

    /**
     * 全局缓存: 系统设置
     * @param null $wxapp_id
     * @return array|mixed
     */
    public static function getAll($wxapp_id = null)
    {
        $static = new static;
        is_null($wxapp_id) && $wxapp_id = $static::$wxapp_id;
        if (!$data = Cache::get('setting_' . $wxapp_id)) {
            $setting = $static::all(compact('wxapp_id'));
            $data = empty($setting) ? [] : array_column(collection($setting)->toArray(), null, 'key');
            Cache::tag('cache')->set('setting_' . $wxapp_id, $data);
        }
        return array_merge_multiple($static->defaultData(), $data);
    }

    /**
     * 默认配置
     * @param null $store_name
     * @return array
     */

    public function defaultData($store_name = null)
    {
        return [
            'store' => [
                'key' => 'store',
                'describe' => '商城设置',
                'values' => [
                    'name' => $store_name ?: '时乘小程序商城',
                    'kuaidi100' => [
                        'customer' => '',
                        'key' => '',
                    ]
                ],
            ],
            'trade' => [
                'key' => 'trade',
                'describe' => '交易设置',
                'values' => [
                    'order' => [
                        'close_days' => '3',
                        'receive_days' => '10',
                        'refund_days' => '7'
                    ],
                    'freight_rule' => '10',
                ]
            ],
            'storage' => [
                'key' => 'storage',
                'describe' => '上传设置',
                'values' => [
                    'default' => 'local',
                    'engine' => [
                        'qiniu' => [
                            'bucket' => '',
                            'access_key' => '',
                            'secret_key' => '',
                            'domain' => 'http://'
                        ],
                        'aliyun' => [
                            'bucket' => '',
                            'access_key_id' => '',
                            'access_key_secret' => '',
                            'domain' => 'http://'
                        ],
                        'qcloud' => [
                            'bucket' => '',
                            'region' => '',
                            'secret_id' => '',
                            'secret_key' => '',
                            'domain' => 'http://'
                        ],
                    ]
                ],
            ],
            'sms' => [
                'key' => 'sms',
                'describe' => '短信通知',
                'values' => [
                    'default' => 'aliyun',
                    'engine' => [
                        'aliyun' => [
                            'AccessKeyId' => '',
                            'AccessKeySecret' => '',
                            'sign' => '时乘科技',
                            'order_pay' => [
                                'is_enable' => '0',
                                'template_code' => '',
                                'accept_phone' => '',
                            ],
                        ],
                    ],
                ],
            ],
            'user_extra_field' => [
                'key' => 'user_extra_field',
                'describe' => '用户额外字段',
                'values' => [
                ],
            ],
            'tplMsg' => [
                'key' => 'tplMsg',
                'describe' => '模板消息',
                'values' => [
                    'payment' => [
                        'is_enable' => '0',
                        'template_id' => '',
                    ],
                    'delivery' => [
                        'is_enable' => '0',
                        'template_id' => '',
                    ],
                    'refund' => [
                        'is_enable' => '0',
                        'template_id' => '',
                    ],
                ],
            ],
            'poster' => [
                'key' => 'poster',//template_msg
                'describe' => '分销海报',
                'values' => [
                    'backdrop' => [
                        'src' => self::$base_url . 'assets/store/img/goods_bg.png',
                    ],
                    'nickName' => [
                        'fontSize' => 14,
                        'color' => '#000000',
                        'left' => 150,
                        'top' => 99
                    ],
                    'avatar' => [
                        'width' => 70,
                        'style' => 'circle',
                        'left' => 150,
                        'top' => 18
                    ],
                    'qrcode' => [
                        'width' => 100,
                        'style' => 'circle',
                        'left' => 136,
                        'top' => 128
                    ]
                ],
            ],
            'sharepic' => [
                'key' => 'sharepic',
                'describe' => '分享图片设置',
                'values' => [
                    'backdrop' => [
                        'src' => self::$base_url . 'assets/store/img/goods_bg.png',
                    ],
                    'nickName' => [
                        'fontSize' => 14,
                        'color' => '#000000',
                        'left' => 150,
                        'top' => 99
                    ],
                    'avatar' => [
                        'width' => 70,
                        'style' => 'circle',
                        'left' => 150,
                        'top' => 18
                    ],
                    'qrcode' => [
                        'width' => 100,
                        'style' => 'circle',
                        'left' => 136,
                        'top' => 128
                    ]
                ],
            ],
        ];
    }


    /**
     * 新增默认配置
     * @param $wxapp_id
     * @param $app_name
     * @return array|false
     * @throws \Exception
     */
    public function insertDefault($wxapp_id, $app_name)
    {
        // 添加商城默认设置记录
        $setting = [
            'store' => [
                'key' => 'store',
                'describe' => '商城设置',
                'values' => [
                    'name' => $app_name ?: '时乘科技',
                    'kuaidi100' => [
                        'customer' => '',
                        'key' => '',
                    ]
                ],
                'wxapp_id' => $wxapp_id
            ],
            'trade' => [
                'key' => 'trade',
                'describe' => '交易设置',
                'values' => [
                    'order' => [
                        'close_days' => '3',
                        'receive_days' => '10',
                        'refund_days' => '7'
                    ],
                    'freight_rule' => '10',
                ],
                'wxapp_id' => $wxapp_id
            ],
            'storage' => [
                'wxapp_id' => $wxapp_id,
                'key' => 'storage',
                'describe' => '上传设置',
                'values' => [
                    'default' => 'local',
                    'engine' => [
                        'qiniu' => [
                            'bucket' => '',
                            'access_key' => '',
                            'secret_key' => '',
                            'domain' => 'http://'
                        ],
                        'aliyun' => [
                            'bucket' => '',
                            'access_key_id' => '',
                            'access_key_secret' => '',
                            'domain' => 'http://'
                        ],
                        'qcloud' => [
                            'bucket' => '',
                            'region' => '',
                            'secret_id' => '',
                            'secret_key' => '',
                            'domain' => 'http://'
                        ],
                    ]
                ],
            ],
            'sms' => [
                'wxapp_id' => $wxapp_id,
                'key' => 'sms',
                'describe' => '短信通知',
                'values' => [
                    'default' => 'aliyun',
                    'engine' => [
                        'aliyun' => [
                            'AccessKeyId' => '',
                            'AccessKeySecret' => '',
                            'sign' => '时乘科技',
                            'order_pay' => [
                                'is_enable' => '0',
                                'template_code' => '',
                                'accept_phone' => '',
                            ],
                        ],
                    ],
                ],
            ],
            'tplMsg' => [
                'wxapp_id' => $wxapp_id,
                'key' => 'tplMsg',
                'describe' => '模板消息',
                'values' => [
                    'payment' => [
                        'is_enable' => '0',
                        'template_id' => '',
                    ],
                    'delivery' => [
                        'is_enable' => '0',
                        'template_id' => '',
                    ],
                    'refund' => [
                        'is_enable' => '0',
                        'template_id' => '',
                    ],
                ],
            ],
            'poster' => [
                'key' => 'template_msg',
                'wxapp_id' => $wxapp_id,
                'describe' => '分销海报',
                'values' => [
                    'backdrop' => [
                        'src' => self::$base_url . 'assets/store/img/goods_bg.png',
                    ],
                    'nickName' => [
                        'fontSize' => 14,
                        'color' => '#000000',
                        'left' => 150,
                        'top' => 99
                    ],
                    'avatar' => [
                        'width' => 70,
                        'style' => 'circle',
                        'left' => 150,
                        'top' => 18
                    ],
                    'qrcode' => [
                        'width' => 100,
                        'style' => 'circle',
                        'left' => 136,
                        'top' => 128
                    ]
                ],
            ],
        ];
        return $this->saveAll($setting);
    }

}
