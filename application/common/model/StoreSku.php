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

class StoreSku extends Model
{

    public function getSkuByUid($uid) {
        $uid = (int)$uid;
        $list = $this->where(['uid' => $uid])
                     ->select();
        return $list;
    }
    
    public function addSku($data, $uid) {
        $id = $this->insertGetId(
            [
            'name' => $data['name'],
            'unit' => $data['unit'],
            'unit_price' => $data['unit_price'],
            'content' => $data['content'],
            'img'=> $data['img'],
            'stock' => (int)$data['stock'],
            'type' => (int)$data['type'],
            'uid' => (int)$uid,
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

}
