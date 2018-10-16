<?php
// +----------------------------------------------------------------------
// | [RhaPHP System] Copyright (c) 2017 http://www.rhaphp.com/
// +----------------------------------------------------------------------
// | [RhaPHP] 并不是自由软件,你可免费使用,未经许可不能去掉RhaPHP相关版权
// +----------------------------------------------------------------------
// | Author: Geeson <qimengkeji@vip.qq.com>
// +----------------------------------------------------------------------


namespace app\store\controller;

use app\common\model\Addons;
use think\Db;
use think\facade\Session;

class Base extends \app\admin\controller\Base
{

    public $mid;
    public $mpInfo;
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->getAddonForMenu();
    }

    public function getAddonForMenu(){
        $model=new Addons();
        $adList=$model->where(['menu_show'=>1,'status'=>1])->select();
        $this->assign('menu_app',$adList);
        $this->assign('menu_app_title','应用扩展');
    }
}
