<?php

namespace app\store\controller;

use app\store\model\Category;
use app\store\model\Delivery;
use app\store\model\Goods as GoodsModel;
use app\store\model\school\Subject as SubjectModel;

/**
 * 商品管理控制器
 * Class Goods
 * @package app\store\controller
 */
class Goods extends Controller
{
    /**
     * 商品列表(出售中)
     * @param null $goods_status
     * @param null $category_id
     * @param string $goods_name
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index($goods_status = null, $category_id = null, $goods_name = '')
    {
        // 商品分类
        $catgory = Category::getCacheTree();
        // 商品列表
        $model = new GoodsModel;
        $list = $model->getList($goods_status, $category_id, $goods_name);
        return $this->fetch('index', compact('list', 'catgory'));
    }

    /**
     * 添加商品
     * @return array|mixed
     * @throws \think\exception\PDOException
     */
    public function add()
    {
        if (!$this->request->isAjax()) {
            // 商品分类
            $catgory = Category::getCacheTree();
            // 配送模板
            $delivery = Delivery::getAll();
            // 预约内容(例如课程)
            $subjectModel = new SubjectModel;
            $subject = $subjectModel->getList('','','','','',0,'1000');

            return $this->fetch('add', compact('catgory', 'delivery','subject'));
        }
        
        //package字段复制过去 且不转义
        $model = new GoodsModel;
        $goods=$this->postData('goods');
        $goods['package']=$_POST['goods']['package'] ?? '';

        if ($model->add($goods)) {
            return $this->renderSuccess('添加成功', url('goods/index'));
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 一键复制
     * @param $goods_id
     * @return array|mixed
     * @throws \think\exception\PDOException
     */
    public function copy($goods_id)
    {
        // 商品详情
        $model = GoodsModel::detail($goods_id);
        if (!$this->request->isAjax()) {
            // 商品分类
            $catgory = Category::getCacheTree();
            // 配送模板
            $delivery = Delivery::getAll();
            // 商品sku数据
            $specData = 'null';
            if ($model['spec_type'] == 20) {
                $specData = json_encode($model->getManySpecData($model['spec_rel'], $model['sku']), JSON_UNESCAPED_SLASHES);
            }
            return $this->fetch('edit', compact('model', 'catgory', 'delivery', 'specData'));
        }

        //package字段复制过去 且不转义
        $model = new GoodsModel;
        $goods=$this->postData('goods');
        $goods['package']=$_POST['goods']['package'] ?? '';

        if ($model->add($goods)) {

            return $this->renderSuccess('添加成功', url('goods/index'));
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 商品编辑
     * @param $goods_id
     * @return array|mixed
     * @throws \think\exception\PDOException
     */
    public function edit($goods_id)
    {
        // 商品详情
        $model = GoodsModel::detail($goods_id);

        if (!$this->request->isAjax()) {
            // 商品分类
            $catgory = Category::getCacheTree();
            // 配送模板
            $delivery = Delivery::getAll();
            // 预约内容(例如课程)
            $subjectModel = new SubjectModel;
            $subject = $subjectModel->getList('','','','','',0,'1000');
            // 商品sku数据
            $specData = 'null';
            if ($model['spec_type'] == 20) {
                $specData = json_encode($model->getManySpecData($model['spec_rel'], $model['sku']), JSON_UNESCAPED_SLASHES);
            }
            return $this->fetch('edit', compact('model', 'catgory', 'delivery', 'specData','subject'));
        }

        //package字段复制过去 且不转义
        $goods=$this->postData('goods');
        $goods['package']=$_POST['goods']['package'] ?? '';

        // 更新记录
        if ($model->edit($goods)) {
            return $this->renderSuccess('更新成功', url('goods/index'));
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 修改商品状态
     * @param $goods_id
     * @param boolean $state
     * @return array
     */
    public function state($goods_id, $state)
    {
        // 商品详情
        $model = GoodsModel::detail($goods_id);
        if (!$model->setStatus($state)) {
            return $this->renderError('操作失败');
        }
        return $this->renderSuccess('操作成功');
    }

    /**
     * 删除商品
     * @param $goods_id
     * @return array
     */
    public function delete($goods_id)
    {
        // 商品详情
        $model = GoodsModel::detail($goods_id);
        if (!$model->setDelete()) {
            return $this->renderError('删除失败');
        }
        return $this->renderSuccess('删除成功');
    }


    public function test(){


       /* $a['金昇音乐体验中心']=['小歌手声乐表演（4周岁+）','钢琴（4周岁+）','钢琴（4周岁+）','尤克里里（4周岁+）','吉他（7周岁+）'];
        $a['武杰国际跆拳道']=['幼儿班(3-6周岁)','少儿班(7-12周岁)'];
        $a['3Q儿童商学院']=['亲子课（1-2周岁）','IQ智力提升（2-5周岁）','童商专项训练（5-7周岁）'];
        $a['满分体育绍兴校区']=['小篮球综合体能（中班-1年级）','少儿篮球（2年级-5年级）','少儿羽毛球（1年-6年级）'];
        $a['乐知之数学思维']=['小学3-6年级数学培优','初一数学 ','初一科学'];
        $a['绘斑斓艺术学院']=['世界艺术绘画（4-6周岁）','硬笔书法（6-9周岁） ','墨趣国画（小学1-3年级）','素描基础（小学3年级起）','卡通动漫（小学3年级起）'];
        $a['能力风暴机器人']=['机器人设计搭建（4 -5周岁）','机器人设计搭建编程（5 -9周岁） ','机器人设计搭建编程（9 -16周岁）'];
        $a['易贝乐少儿英语']=['自然拼读体验（3周岁-6周岁）'];
        $a['优胜个性学（越城校区）']=['小学语、数、英（任选一或两）','初中语、数、英、科学（任选一或两）'];
        $a['红火舞蹈']=['少儿街舞启蒙（4 -6周岁）','少儿街舞基础（7 -13周岁）'];

        $j['data']=$a;
        $j['min_select']=4;
        $j['max_select']=4;

        echo json_encode($j,JSON_UNESCAPED_UNICODE);
        die;*/

       $a='{"page":{"type":"page","name":"\u9875\u9762\u8bbe\u7f6e","params":{"name":"\u9875\u9762\u540d\u79f0","title":"\u9875\u9762\u6807\u9898","share_title":"\u5206\u4eab\u6807\u9898"},"style":{"titleTextColor":"black","titleBackgroundColor":"#ffffff","backgroundUrl":"https:\/\/mp.minstech.cn\/addons\/mall\/web\/assets\/store\/img\/diy\/phone-top-black.png"}},"items":[{"name":"\u5546\u54c1\u7ec4","type":"goods","params":{"source":"auto","auto":{"category":0,"goodsSort":"all","showNum":6}},"style":{"background":"#F6F6F6","display":"list","column":"2","show":{"goodsName":"1","goodsPrice":"1","linePrice":"1"}},"defaultData":[{"goods_name":"\u6b64\u5904\u663e\u793a\u5546\u54c1\u540d\u79f0","image":"https:\/\/mp.minstech.cn\/addons\/mall\/web\/assets\/store\/img\/diy\/goods\/01.png","goods_price":"99.00","line_price":"139.00"},{"goods_name":"\u6b64\u5904\u663e\u793a\u5546\u54c1\u540d\u79f0","image":"https:\/\/mp.minstech.cn\/addons\/mall\/web\/assets\/store\/img\/diy\/goods\/01.png","goods_price":"99.00","line_price":"139.00"},{"goods_name":"\u6b64\u5904\u663e\u793a\u5546\u54c1\u540d\u79f0","image":"https:\/\/mp.minstech.cn\/addons\/mall\/web\/assets\/store\/img\/diy\/goods\/01.png","goods_price":"99.00","line_price":"139.00"},{"goods_name":"\u6b64\u5904\u663e\u793a\u5546\u54c1\u540d\u79f0","image":"https:\/\/mp.minstech.cn\/addons\/mall\/web\/assets\/store\/img\/diy\/goods\/01.png","goods_price":"99.00","line_price":"139.00"}],"data":[{"goods_name":"\u6b64\u5904\u663e\u793a\u5546\u54c1\u540d\u79f0","image":"https:\/\/mp.minstech.cn\/addons\/mall\/web\/assets\/store\/img\/diy\/goods\/01.png","goods_price":"99.00","line_price":"139.00","is_default":true}]}]}';
        echo var_export(json_decode($a,true));die;














    }

}
