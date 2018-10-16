<?php
// +----------------------------------------------------------------------
// | [RhaPHP System] Copyright (c) 2017 http://www.rhaphp.com/
// +----------------------------------------------------------------------
// | [RhaPHP] 并不是自由软件,你可免费使用,未经许可不能去掉RhaPHP相关版权
// +----------------------------------------------------------------------
// | Author: Geeson <qimengkeji@vip.qq.com>
// +----------------------------------------------------------------------


namespace app\store\controller;


use app\common\model\StoreSku;
use think\Db;
use think\Exception;
use think\facade\Request;
use think\facade\Session;
use think\facade\Url;
use think\Validate;
use app\admin\controller\Base;

class Goods extends Base
{

    public function initialize()
    {

       return parent::initialize(); // TODO: Change the autogenerated stub

    }

    public function list()
    {
        //这里需要做分页
        //
        $model = new StoreSku();
        $list = $model->getSkuByUid($this->admin_id);
        $this->assign('menu_title', '商品管理');
        $this->assign('list', $list);
        return view();
    }

    protected function checkSkuParams($data){
            if (utf8_strlen($data['name']) > 10) {
                return '名字太长';
            }

            if (utf8_strlen($data['unit']) > 2) {
                return '单位太长';
            }

            if (utf8_strlen($data['content']) >= 100) {
                return '描述太长';
            }
        
            if (strlen($data['img']) > 200) {
                return '图片地址太长';
            }
            
            return true;
    
    }

    public function addSku(){
        if (Request::isAjax() && Request::isPost()) {
            $data = input('post.');
            
            $checkMsg = $this->checkSkuParams($data);
            if ($checkMsg !== TRUE) {
                return ajaxMsg(0, $checkMsg);
            } 

            $mode = new StoreSku();
            $id = $mode->addSku(
                [
                'name' => $data['name'], 
                'unit' => $data['unit'], 
                'unit_price' => $data['unit_price'], 
                'content' => $data['content'],
                'img'=> $data['img'],
                'stock' => (int)$data['stock'],
                'type' => (int)$data['type'], 
                ], $this->admin_id);
            if ($id) {
                ajaxMsg(1, '新增成功');
            }
            ajaxMsg(0, '新增失败');
        }
        $this->assign('menu_title', '新增商品');
        return  view('addSku');
    }

    public function editSku(){
        $model = new \app\common\model\StoreSku();
        if (Request::isAjax() && Request::isPost()) {
            $data = input('post.');
            
            $checkMsg = $this->checkSkuParams($data);
            if ($checkMsg !== TRUE) {
                return ajaxMsg(0, $checkMsg);
            } 
            $res = $model->editSku($data, $this->admin_id);
            if ($res) {
                return ajaxMsg(0, '更新成功');
            }
            return ajaxMsg(0, '更新失败');
        }
        $id = (int)input('id');
        $aStore = Db::name('store_sku')->where(['id'=>$id, 'uid'=>$this->admin_id])->select();
        if (count($aStore) <= 0) {
            $this->assign('menu_title', '新增商品');
            return view('addSku');
            
        }
        
        $this->assign('data', $aStore[0]);
        $this->assign('menu_title', '修改商品');
        return  view('editSku');
    }


}
