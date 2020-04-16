<?php

namespace app\admin\controller;

use app\admin\model\OpticalJumpingFiberDetails as jumpFiberDetails;
use app\admin\model\OpticalEquipProfiles as equipProfiles;
use app\admin\model\OpticalEquipPorts as equipPorts;

use think\Db;

class OpticalJumpFibers extends Base
{
    /* 
    ** 跳纤信息概览
    */
    public function index()
    {

        // 判断是否有搜索条件
        $condition = array();
        if (request()->isPost()) {
            $condition = input('post.');
            $condition = $condition['room'] . "%";
        }
        // dump($condition);die;
        $jumpFiberDetails = new jumpFiberDetails();
        $jumpFibers = $jumpFiberDetails->showJumpInfo($condition);

        $this->assign('jumpFibers', $jumpFibers);

        $location = empty($jumpFibers) ? substr($condition, 0, -1) : $jumpFibers[0]['in_equip_location'];
        $this->assign('location', $location);

        return view();
    }

    /* 
    ** 增加新跳纤
    */
    public function add()
    {
        if (request()->isPost()) {
            $data = input("post.");
            //    dump($data);die;
            
            if (!is_array($data) || empty($data)) {
                $this->error('提交失败，请重试！');
            }
            // 查询入、出端设备ID
            $inCondition = ['location'=>$data['location'], 'name'=>$data['in_equip_name']];
            $outCondition = ['location'=>$data['location'], 'name'=>$data['out_equip_name']];
            // dump($inCondition);die;
            $equipProfiles = new equipProfiles();
            $inEquipID = $equipProfiles->getEquipID($inCondition);
            $outEquipID = $equipProfiles->getEquipID($outCondition);
            
            if (!$inEquipID || !$outEquipID) {
                $this->error('所选设备不存在，请重试！');
            }
            // 查询入、出端端口是否存在
            $inPortCondition = ['equip_id'=>$inEquipID, 'box_no'=>$data['in_box_no'], 'plate_no'=>$data['in_plate_no'], 'port_no'=>$data['in_port_no']];
            $outPortCondition = ['equip_id'=>$outEquipID, 'box_no'=>$data['out_box_no'], 'plate_no'=>$data['out_plate_no'], 'port_no'=>$data['out_port_no']];            
            $ports = new equipPorts();
            $inPortID = $ports->getPortID($inPortCondition);
            $outPortID = $ports->getPortID($outPortCondition);
            if (!$inPortID || !$outPortID) {
                $this->error('所选端口不存在，请重试！');
            }

            // 更新到跳纤表
            $newRecord = ['a_port_id'=>$inPortID, 'b_port_id'=>$outPortID];
            $jumperDetails = new jumpFiberDetails();
            $result = $jumperDetails->allowField(True)->save($newRecord);

            if (!$result) {
                $this->error('更新失败，请重试！');
            }
            else{
                $this->success('更新成功！');
            }
                        
        }
        return view();
    }

    /* 
    ** 编辑跳纤页面
    */
    public function edit($jumperID)
    {

        if (empty($jumperID) || intval($jumperID) < 0) {
            return info(lang("不存在该记录，请重试！"), 0);
        }
        // 将对应跳纤ID相关数据传回前台
        $jumpFiberDetails = new jumpFiberDetails();
        $jumpFiber = $jumpFiberDetails->showSpecificJumper($jumperID);
        $this->assign('jumpFiber', $jumpFiber);

        return view();
    }

    /* 
    ** 编辑跳纤的提交功能实现
    */
    public function saveData()
    {
         // 更新跳纤表内容
         if (request()->isPost()) {
            $data = input("post.");
            //    dump($data);die;
            
            if (!is_array($data) || empty($data)) {
                $this->error('提交失败，请重试！');
            }
            // 查询入、出端设备ID
            $inCondition = ['location'=>$data['location'], 'name'=>$data['in_equip_name']];
            $outCondition = ['location'=>$data['location'], 'name'=>$data['out_equip_name']];
            // dump($inCondition);die;
            $equipProfiles = new equipProfiles();
            $inEquipID = $equipProfiles->getEquipID($inCondition);
            $outEquipID = $equipProfiles->getEquipID($outCondition);
            
            if (!$inEquipID || !$outEquipID) {
                $this->error('所选设备不存在，请重试！');
            }
            // 查询入、出端端口是否存在
            $inPortCondition = ['equip_id'=>$inEquipID, 'box_no'=>$data['in_box_no'], 'plate_no'=>$data['in_plate_no'], 'port_no'=>$data['in_port_no']];
            $outPortCondition = ['equip_id'=>$outEquipID, 'box_no'=>$data['out_box_no'], 'plate_no'=>$data['out_plate_no'], 'port_no'=>$data['out_port_no']];            
            $ports = new equipPorts();
            $inPortID = $ports->getPortID($inPortCondition);
            $outPortID = $ports->getPortID($outPortCondition);
            if (!$inPortID || !$outPortID) {
                $this->error('所选端口不存在，请重试！');
            }

            // 更新到跳纤表
            $newRecord = ['id'=>(int)$data['id'], 'a_port_id'=>$inPortID, 'b_port_id'=>$outPortID];
            $jumperDetails = new jumpFiberDetails();
            $result = $jumperDetails->isUpdate(True)->allowField(True)->save($newRecord);

            // 其他更新未实现
            if (!$result) {
                $this->error('更新失败，请重试！');
            }
            else{
                $this->success('更新成功！', url('admin/optical_jump_fibers'));
            }
                        
        }
    }
}
