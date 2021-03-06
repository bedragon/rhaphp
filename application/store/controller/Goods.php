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

            if (check_price($data['unit_price']) == false) {
                return '单价错误，请重新输入';
            }

            if (check_price($data['discount_price']) == false) {
                return '会员价格错误，请重新输入';
            }
            
            return true;
    
    }

    public function addSku(){
        //var_dump(Request::isAjax());
        //var_dump(Request::isPost());
        //return ;
        if (Request::isAjax() && Request::isPost()) {
            $data = input('post.');
            
            $checkMsg = $this->checkSkuParams($data);
            if ($checkMsg !== TRUE) {
                return ajaxMsg(0, $checkMsg);
            } 
            
            $userModel = new \app\common\model\StoreUser();
            if (!$userModel->isCanManageStore($this->admin_id, $data['store_id'])) {
                return   ajaxMsg(0, '没有权限');
            }

            $mode = new StoreSku();
            $id = $mode->addSku(
                [
                'name' => $data['name'], 
                'unit' => $data['unit'], 
                'unit_price' => bcmul($data['unit_price'], 100, 0), 
                'content' => $data['content'],
                'img'=> $data['img'],
                'stock' => (int)$data['stock'],
                'type' => (int)$data['type'], 
                'catagory_id' => (int)$data['catagory_id'],
                'discount_type' => (int)$data['discount_type'],
                'discount_price' => bcmul($data['discount_price'], 100, 0),
                'discount_count' => (int)$data['discount_count'],
                'vip_price' => bcmul($data['vip_price'], 100, 0),
                'vip_price_type'=> (int)$data['vip_price_type'],
                ], $this->admin_id, (int)$data['store_id']);
            if ($id) {
               return ajaxMsg(1, '新增成功');
            }
            return  ajaxMsg(0, '新增失败');
        }
        $storeID = input('store_id');
        $oCatagory = new \app\common\model\StoreCatagory();
        $aCatagoryList = $oCatagory->getCatagoryByStoreID($storeID);
        $this->assign('caagory_list', $aCatagoryList);
        $this->assign('store_id', $storeID);
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

    public function showSku(){
        $storeID = (int)input('store_id');
        $page = (int)input('page');
        $page = $page>=0 ? $page : 0;
        $userModel = new \app\common\model\StoreUser();
        if (!$userModel->isCanManageStore($this->admin_id, $storeID)) {
            return   ajaxMsg(0, '没有权限');
        }
        $model = new \app\common\model\StoreSku();
        $list = $model->getSkuListByStoreID($storeID, $this->admin_id, $page);
        $count = $model->getSkuCountByStoreID($storeID, $this->admin_id);

        foreach ($list as &$item) {
            $item['unit_price'] = bcdiv($item['unit_price'], 100, 2);
            $item['discount_price'] = bcdiv($item['discount_price'], 100, 2);
            $item['vip_price'] = bcdiv($item['vip_price'], 100, 2);
        }
        $this->assign('page', $page);
        $this->assign('count', $count);
        $this->assign('list', $list);
        $this->assign('store_id', $storeID);
        return  view('list');
    }


}
