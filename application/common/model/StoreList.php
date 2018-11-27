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

class StoreList extends Model
{

    public function getStore($storeID) {
        $storeID = (int)$storeID;
        $data = $this->where(['id' => $storeID])->select();
        return  isset($data[0]) ? $data[0] : false;
    }
}
