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

class StoreUser extends Model
{
    const USER_STATUS = 0;

    const USER_TYPE_ADMIN = 1;
    const USER_TYPE_STORE_MANAGER = 2;


    public function isCanManageStore($uid, $storeID) {
        $uid = (int)$uid;
        $storeID = (int)$storeID;
        $ret = DB::table('re_store_user')->where(['uid' => $uid, 'store_id'=> $storeID, 'status' => self::USER_STATUS])
            ->find();
        if (in_array($ret['type'], array(self::USER_TYPE_ADMIN, self::USER_TYPE_STORE_MANAGER))) {
            return  true;
        }
        return  false;
    }

    public function addUserAdmin($storeID, $uid){
        $storeID = (int)$storeID;
        $uid     = (int)$uid;
        $id = $this->insertGetId([
                'store_id' => $storeID,
                'uid'      => $uid,
                'status'   => self::USER_STATUS,
                'type'     => self::USER_TYPE_ADMIN,
                'time'     => time(),
            ]);
        return  $id;
    }
}
