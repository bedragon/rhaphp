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
use think\Db;

class StoreSku extends Model
{

    public function getSkuByUid($uid) {
        $uid = (int)$uid;
        $list = $this->where(['uid' => $uid])
                     ->select();
        return $list;
    }
    
    public function addSku($data, $uid, $storeID) {
        //检查store_id是否符合uid
        $storeID = (int)$storeID;
        $id = $this->insertGetId(
            [
            'name' => $data['name'],
            'unit' => $data['unit'],
            'unit_price' => $data['unit_price'],
            'content' => $data['content'],
            'img'=> $data['img'],
            'stock' => (int)$data['stock'],
            'type' => (int)$data['type'],
            'store_id' => (int)$storeID,
            'catagory_id' => (int)$data['catagory_id'],
            'discount_type' => (int)$data['discount_type'],
            'discount_price' => $data['discount_price'],
            'discount_count' => (int)$data['discount_count'],
            'vip_price' => $data['vip_price'],
            'vip_price_type'=> (int)$data['vip_price_type'],
            'time' => time(),
            ]);
        return $id;
    }

    public function editSku($data, $uid) {
        $aUpdate = [
            'name' => $data['name'],
            'unit' => $data['unit'],
            'unit_price' => $data['unit_price'],
            'content' => $data['content'],
            'stock' => (int)$data['stock'],
            'type' => (int)$data['type'],
        ];
        if ($data['img'] != '') {
            $aUpdate['img'] = $data['img'];
        }
        $ret = $this->allowField(true)->save($aUpdate, ['id' => (int)$data['id'], 'uid' => $uid]);
        return  $ret;
    }

    public function getSkuListByStoreID($storeID, $uid, $page, $size = 20) {
        //检查store_id是否符合uid
        $storeID = (int)$storeID;
        $page = (int)$page;
        $page = $page >= 0 ? $page : 0;
        $limit = $page * $size.', ' . $size;
        $list = DB::table('re_store_sku')->where(['store_id' => $storeID])->order('id DESC')->limit($limit)->select();
        return $list;
    }

    public function getSkuCountByStoreID($storeID, $uid) {
        //检查store_id是否符合uid
        $storeID = (int)$storeID;
        $count = DB::table('re_store_sku')->where(['store_id' => $storeID])->count();
        return  $count;
    }

}
