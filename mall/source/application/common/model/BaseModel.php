<?php

namespace app\common\model;

use think\Model;
use think\Request;
use think\Session;

/**
 * 模型基类
 * Class BaseModel
 * @package app\common\model
 */
class BaseModel extends Model
{
    public static $wxapp_id;
    public static $base_url;

    /**
     * 模型基类初始化
     */
    public static function init()
    {
        parent::init();
        // 获取当前域名
        self::$base_url = base_url();
        // 后期静态绑定wxapp_id
        self::bindWxappId();
      ;
    }

    /**
     * 获取当前调用的模块名称
     * 例如：admin, api, store, task
     * @return string|bool
     */
    protected static function getCalledModule()
    {
        if (preg_match('/app\\\(\w+)/', get_called_class(), $class)) {
            return $class[1];
        }
        return false;
    }

    /**
     * 后期静态绑定类名称
     * 用于定义全局查询范围的wxapp_id条件
     * 子类调用方式:
     *   非静态方法:  self::$wxapp_id
     *   静态方法中:  $self = new static();   $self::$wxapp_id
     */
    private static function bindWxappId()
    {

        if ($module = self::getCalledModule()) {
            $callfunc = 'set' . ucfirst($module) . 'WxappId';

            method_exists(new self, $callfunc) && self::$callfunc();
        }

    }

    /**
     * 设置wxapp_id (store模块)
     */
    protected static function setStoreWxappId()
    {
        //$session = Session::get('yoshop_store');

        $session=Session::get();
        $module=$session['moudle'];
        $store = $session[$module];

        self::$wxapp_id = $store['wxapp']['wxapp_id'];

    }

    /**
     * 设置wxapp_id (api模块)
     */
    protected static function setApiWxappId()
    {
        $request = Request::instance();
        self::$wxapp_id = $request->param('wxapp_id');
    }

    /**
     * 获取当前域名
     * @return string
     */
    protected static function baseUrl()
    {
        $request = Request::instance();
        $host = $request->scheme() . '://' . $request->host();
        $dirname = dirname($request->baseUrl());
        return empty($dirname) ? $host : $host . $dirname . '/';
    }

    /**
     * 定义全局的查询范围
     * @param \think\db\Query $query
     */
    protected function base($query)
    {
        if (self::$wxapp_id > 0) {
            $query->where($query->getTable() . '.wxapp_id', self::$wxapp_id);
        }
    }

    /**
     * [delDataById 根据id删除数据]
     * @author lichenjie
     * @param string $id
     * @param bool $delSon  是否删除子孙数据
     * @return bool
     * @throws \think\exception\PDOException
     */
    public function delDataById($id = '', $delSon = false)
    {
        $this->startTrans();
        try {
            $this->where($this->getPk() ,'in', $id)->delete();
            if ($delSon && is_numeric($id)) {
                // 删除子孙
                $childIds = $this->getAllChild($id);
                if($childIds){
                    $this->where($this->getPk(), 'in', $childIds)->delete();
                }
            }
            $this->commit();
            return true;
        } catch(\Exception $e) {
            $this->error = '删除失败';
            $this->rollback();
            return false;
        }
    }

    /**
     * @author lichenjie
     */
    public function getDataById($id = '')
    {
        $data = $this->get($id);
        if (!$data) {
            $this->error = '暂无此数据';
            return false;
        }
        return $data;
    }

    /**
     * 新建
     * @author lichenjie
     */
    public function createData($param)
    {
        // 验证
        /*$validate = validate($this->name);
        if (!$validate->check($param)) {
            $this->error = $validate->getError();
            return false;
        }*/
        $param['wxapp_id'] = self::$wxapp_id;
        try {
            $this->data($param)->allowField(true)->save();
            return true;
        } catch(\Exception $e) {
            $this->error = '添加失败';
            return false;
        }
    }

    /**
     * 新建
     * @author lichenjie
     */
    public function createDataGetId($param)
    {

        // 验证
        /*$validate = validate($this->name);
        if (!$validate->check($param)) {
            $this->error = $validate->getError();
            return false;
        }*/
        $param['wxapp_id'] = self::$wxapp_id;
        try {
            $id = $this->insertGetId($param);
            return $id;
        } catch(\Exception $e) {
            $this->error = '添加失败';
            return false;
        }
    }

    /**
     * [updateDataById 编辑]
     * @author lichenjie
     */
    public function updateDataById($param, $id)
    {
        /* $checkData = $this->get($id);
         if (!$checkData) {
             $this->error = '暂无此数据';
             return false;
         }*/
        // 验证
        /* $validate = validate($this->name);
         if (!$validate->check($param)) {
             $this->error = $validate->getError();
             return false;
         }*/
        $param['wxapp_id'] = self::$wxapp_id;
        try {
            $this->allowField(true)->save($param, [$this->getPk() => $id]);
            return true;
        } catch(\Exception $e) {
            $this->error = '编辑失败';
            return false;
        }
    }

    /**
     * 写数据库日志
     * @author lichenjie
     * @param string $level 日志等级(error,warn,info,debug)
     * @param string $ch    日志描述
     * @param string $content   内容
     * @param string $method    所属方法（__METHOD__）
     * @return int|string
     * @example $this->log('debug','日志描述','内容',__METHOD__ )
     */
    function log($level='info',$ch='',$content='',$method='0'){
       $data = array(
            'wxapp_id'=>isset(self::$wxapp_id) ? self::$wxapp_id : 0,
            'create_time'=>date("Y-m-d H:i:s"),
            'level'=>$level,
            'ch'=>$ch,
            'content'=>$content,
            'method'=>$method,
        );
        Db('log')->insert($data);
    }

}
