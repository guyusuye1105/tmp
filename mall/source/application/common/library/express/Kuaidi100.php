<?php

namespace app\common\library\express;

use think\Cache;

/**
 * 快递100API模块
 * Class Kuaidi100
 * @package app\common\library\express
 */
class Kuaidi100
{
    /* @var array $config 微信支付配置 */
    private $config;

    /* @var string $error 错误信息 */
    private $error;

    /**
     * 构造方法
     * WxPay constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * 执行查询
     * @param $express_code
     * @param $express_no
     * @return bool
     */
    public function query($express_code, $express_no)
    {
        // 缓存索引
        $cacheIndex = 'express_' . $express_code . '_' . $express_no;
        if ($data = Cache::get($cacheIndex)) {
            return $data;
        }
        // 参数设置
        $postData = [
            'customer' => $this->config['customer'],
            'param' => json_encode([
                'resultv2' => '1',
                'com' => $express_code,
                'num' => $express_no
            ])
        ];
        $postData['sign'] = strtoupper(md5($postData['param'] . $this->config['key'] . $postData['customer']));
        // 请求快递100 api
        $url = 'http://poll.kuaidi100.com/poll/query.do';
        $result = curlPost($url, http_build_query($postData));
        $express = json_decode($result, true);
        // 记录错误信息
        if (isset($express['returnCode']) || !isset($express['data'])) {
            $this->error = isset($express['message']) ? $express['message'] : '查询失败';
            return false;
        }
        // 记录缓存, 时效5分钟
        Cache::set($cacheIndex, $express['data'], 300);
        return $express['data'];
    }

    public function query_new($express_code, $express_no)
    {
        //参数设置
        $post_data = array();
        $post_data["customer"] = $this->config['customer'];
        $key= $this->config['key'];
        //var_dump($express_code, $express_no);die;
        $post_data["param"] = '{"com":"yundakuaiyun","num":"3985740402972"}';

        $url='http://poll.kuaidi100.com/poll/query.do';
        $post_data["sign"] = md5($post_data["param"].$key.$post_data["customer"]);
        $post_data["sign"] = strtoupper($post_data["sign"]);
        $o="";
        foreach ($post_data as $k=>$v)
        {
            $o.= "$k=".urlencode($v)."&";		//默认UTF-8编码格式
        }
       // dump($o);die;
        $post_data=substr($o,0,-1);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $result = curl_exec($ch);
        $data = str_replace("\"",'"',$result );
        $data = json_decode($data,true);
        dump($result);
        dump($data);
        die;
    }

    /**
     * 返回错误信息
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

}