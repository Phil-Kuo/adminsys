<?php

namespace app\admin\controller;

use app\admin\model\OpticalCablesProfiles as CablesProfiles;
use app\admin\model\OpticalCableEndpoint as Endpoint;
use think\Db;

class OpticalFibers extends Base
{
    /* 
    ** 编辑纤芯上架信息表
    */
    public function edit()
    {
        return view();
    }
}
