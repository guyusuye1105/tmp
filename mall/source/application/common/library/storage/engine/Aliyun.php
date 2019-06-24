<?php

namespace app\common\library\storage\engine;

use OSS\OssClient;
use OSS\Core\OssException;

/**
 * 阿里云存储引擎 (OSS)
 * Class Qiniu
 * @package app\common\library\storage\engine
 */
class Aliyun extends Server
{
    private $config;

    /**
     * 构造方法
     * Qiniu constructor.
     * @param $config
     * @throws \think\Exception
     */
    public function __construct($config)
    {
        parent::__construct();
        $this->config = $config;
    }

    /**
     * 执行上传
     * @return bool|mixed
     */
    public function upload()
    {
        try {
            $ossClient = new OssClient(
                $this->config['access_key_id'],
                $this->config['access_key_secret'],
                $this->config['domain']
            );

            $result = $ossClient->uploadFile(
                $this->config['bucket'],
                $this->getOssFileName(),
                $this->file->getRealPath()
            );
        } catch (OssException $e) {
            $this->error = $e->getMessage();
            return false;
        }
        return true;
    }

    /**
     * 返回文件路径
     * @return mixed
     */
    public function getFileName()
    {
        return $this->fileName;
    }



    //返回oss的路劲
    public function getOssFileName(){
        if(!empty($this->config['oss_path'])){

            return $this->config['oss_path']."/".$this->fileName;
        }else{
            return $this->fileName;
        }

    }

}
