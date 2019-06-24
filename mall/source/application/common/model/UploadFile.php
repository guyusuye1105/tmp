<?php

namespace app\common\model;


/**
 * 文件库模型
 * Class UploadFile
 * @package app\common\model
 */
class UploadFile extends BaseModel
{
    protected $name = 'upload_file';
    protected $updateTime = false;
    protected $deleteTime = false;
    protected $append = ['file_path'];

    /**
     * 获取图片完整路径
     * @param $value
     * @param $data
     * @return string
     */
    public function getFilePathAttr($value, $data)
    {
        if ($data['storage'] === 'local') {
            return self::$base_url . 'uploads/' . $data['file_name'];
        }
        return $data['file_url'] . '/' . $data['file_name'];
    }

    /**
     * 根据文件名查询文件id
     * @param $fileName
     * @return mixed
     */
    public static function getFildIdByName($fileName)
    {
        return (new static)->where(['file_name' => $fileName])->value('file_id');
    }

    /**
     * 查询文件id
     * @param $fileId
     * @return mixed
     */
    public static function getFileName($fileId)
    {
        return (new static)->where(['file_id' => $fileId])->value('file_name');
    }

    /**
     * 添加新记录
     * @param $data
     * @return false|int
     */
    public function add($data)
    {
        $data['wxapp_id'] = self::$wxapp_id;
        return $this->save($data);
    }

    /**
     * 根据文件id获得文件详细地址
     * @author lichenjie
     * @param $ids
     * @param string $file_type
     * @return string
     */
    function getUrlById($ids,$file_type = 'image'){
        $arr = array();
        foreach($ids as $key=>$val){
            // 判断是id 还是url地址
            if(is_numeric( $val)) {
                $a = db($this->name)->where(['file_type' => $file_type, 'is_delete' => 0, 'file_id' => $val])
                    ->find();
                $url = $this->getFilePathAttr('', $a);
            }else{
                $url = $val;
            }
            $arr[$key] = $url;
        }
        $result = implode(';',$arr);
        return $result;
    }

}
