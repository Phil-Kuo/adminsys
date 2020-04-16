<?php

namespace app\index\controller;

use think\Controller;
use app\admin\model\OpticalEquipPorts as EquipPorts;
use app\admin\model\OpticalEquipProfiles as Equips;


class TvOptical extends Controller
{
    /* 
    ** 设备端口信息概览
    ** 输入机房、设备查询该设备的所有端口信息
    */
    public function equipPorts()
    {
        $formData = array();
        $condition = array();
        if (request()->isPost()){
            $formData = input('post.');
            // 有待改进
            $condition = ['location'=>$formData['room'], 'name'=>$formData['equip']];
            $equips = new Equips();
            $equipID = $equips->getEquipID($condition);
                       
        }

        // 处理提交搜索条件

        $equipID = isset($equipID)?$equipID:2; //默认搜索条件
        // 获取特定设备所有端口的连接纤芯、对端端口、跳纤连接端口
        $equip = new EquipPorts();
        $attachedFibers = $equip->getAttachedFibers($equipID);
        // dump($attachedFibers);die;
        $oppositePorts = $equip->getOppositePorts($equipID);
        // dump($oppositePorts);die;
        $jumperPorts = $equip->getJumperPorts($equipID);
        // dump($jumperPorts);die;
        foreach ($attachedFibers as $key => $value) {            
            $composedResult[] = array_merge($value, $oppositePorts[$key], $jumperPorts[$key]);
        }
        // dump($composedResult);die;
        $this->assign('result', $composedResult);
        if (!$condition) {
            $condition = ['location'=>'郊尾镇', 'name'=>'机柜1'];
        }
        $this->assign('condition', $condition); 
        return view();
    }

}


