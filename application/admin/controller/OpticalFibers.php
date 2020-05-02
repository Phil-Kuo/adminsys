<?php

namespace app\admin\controller;

use app\admin\model\OpticalCablesProfiles as cablesProfiles;
use app\admin\model\OpticalFibers as Fibers;

class OpticalFibers extends Base
{
    /* 
    ** 光缆内纤芯连接管理
    ** $cableID   光缆ID
    */
    public function displayInnerFibers($cableID)
    {
        if (intval($cableID) < 0 || $cableID==""){
            return info(lang("不存在该记录，请重试！"),0);
        }
        // dump($cableID);die;

        $cable = new cablesProfiles();
        $record = $cable->where('id', '=', $cableID)->select();
        // dump($cable);die;
        // 查询光缆的基本信息和纤芯的基本信息、上架信息
        $fibers = new Fibers();
        $fiber = $fibers->showFibers($cableID);
        // dump($fiber);die; $fiber 是一个二维数组

        $this->assign('cable', $record);
        $this->assign('fiber', $fiber);
        return view();
    }
    /* 
    ** 编辑纤芯上架信息表
    */
    public function edit($fiberID)
    {
        if (intval($fiberID) < 0 || $fiberID==""){
            return info(lang("不存在该记录，请重试！"),0);
        }
        // dump($fiberID);die;

        $fibers = new Fibers();
        $fiber = $fibers->showFiberInfo($fiberID);

        // 将对应光纤ID相关数据传回前台
        $this->assign('fiber', $fiber);

        // 更新数据库内容
        
        return view();
    }
}
