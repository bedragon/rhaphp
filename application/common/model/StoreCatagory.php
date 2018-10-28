<?php
// +----------------------------------------------------------------------
// | [RhaPHP System] Copyright (c) 2017 http://www.rhaphp.com/
// +----------------------------------------------------------------------
// | [RhaPHP] 并不是自由软件,你可免费使用,未经许可不能去掉RhaPHP相关版权
// +----------------------------------------------------------------------
// | Author: Geeson <qimengkeji@vip.qq.com>
// +----------------------------------------------------------------------

namespace app\common\model;
use think\Model;

class StoreCatagory extends Model
{
    public function getCatagoryByStoreID($uid, $storeID) {
        //判断uid是否对storeID有权限
        $storeID = (int)$storeID;
        $list = $this->where(['store_id' => $storeID])->select();
        return  $list;
    }

    public function getCatagory($id, $uid) {
        //判断uid是否对storeID有权限。
        $id = (int)$id;
        //$storeID = (int)$storeID;
        $list = $this->where(['id' => $id])
                     ->select();
        return isset($list[0]) ? $list[0] : false;
    }
    
    public function addCatagory($data, $storeID, $uid) {
        //判断uid是否对storeID有权限。
        $storeID = (int)$storeID;
        $id = $this->insertGetId(
            [
            'name' => $data['name'],
            'sort' => (int)$data['sort'],
            'type' => (int)$data['type'],
            'store_id' => $storeID,
            ]);
        return $id;
    }

    public function editCatagory($data, $id, $storeID, $uid) {
        //判断uid是否对storeID有权限。
        $id = (int)$id;
        $storeID = (int)$storeID;
        $aUpdate = [
            'name' => $data['name'],
            'sort' => (int)$data['sort'],
            'type' => (int)$data['type'],
        ];
        $ret = $this->allowField(true)->save($aUpdate, ['id' => $id, 'store_id' => $storeID]);
        return  $ret;
    }

}
