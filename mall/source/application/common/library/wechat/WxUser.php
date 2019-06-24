<?php

namespace app\common\library\wechat;

/**
 * 微信小程序用户管理类
 * Class WxUser
 * @package app\common\library\wechat
 */
class WxUser extends WxBase
{
    const OK= 0;
    const IllegalAesKey = -41001;
    const IllegalIv = -41002;
    const IllegalBuffer = -41003;
    const DecodeBase64Error = -41004;

    /**
     * 获取session_key
     * @param $code
     * @return array|mixed
     */
    public function sessionKey($code)
    {
        /**
         * code 换取 session_key
         * ​这是一个 HTTPS 接口，开发者服务器使用登录凭证 code 获取 session_key 和 openid。
         * 其中 session_key 是对用户数据进行加密签名的密钥。为了自身应用安全，session_key 不应该在网络上传输。
         */
        $url = 'https://api.weixin.qq.com/sns/jscode2session';
        $result = json_decode(curl($url, [
            'appid' => $this->appId,
            'secret' => $this->appSecret,
            'grant_type' => 'authorization_code',
            'js_code' => $code
        ]), true);
        return isset($result['errcode']) ? [] : $result;
    }


    /*
    * 解密手机号
    */
    public  function getphone($encryptedData, $iv, $session_key){

        $errCode = self::decryptData($encryptedData, $iv, $session_key,$data);
        if ($errCode == self::OK) {
            $phoneinfo = json_decode($data,'true');
            return $phoneinfo;
        }else{
            self::setError($errCode,'获取手机号错误');
            return false;
        }
    }


    public  function decryptData( $encryptedData, $iv, $session_key,&$data )
    {


        if (strlen($session_key) != 24) {
            return self::IllegalAesKey;
        }
        $aesKey=base64_decode($session_key);


        if (strlen($iv) != 24) {
            return self::IllegalIv;
        }
        $aesIV=base64_decode($iv);

        $aesCipher=base64_decode($encryptedData);


        $result=openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);

        $dataObj=json_decode( $result );

        if( $dataObj  == NULL )
        {
            return self::IllegalBuffer;
        }

        $appid=$this->appId;

        if( $dataObj->watermark->appid != $appid )
        {
            return self::IllegalBuffer;
        }
        $data = $result;
        return self::OK;
    }

}