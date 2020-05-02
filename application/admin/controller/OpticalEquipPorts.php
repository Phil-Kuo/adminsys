<?php

namespace app\admin\controller;

use app\admin\model\OpticalEquipPorts as EquipPorts;
use app\admin\model\OpticalEquipProfiles as Equips;
use app\admin\model\OpticalJumpingFiberDetails as jumpFibers;
use app\admin\model\OpticalFibersMeltDetails as meltDetails;
use app\admin\model\OpticalEquipProfiles as equipProfiles;
use Graph\ALGraph as ALGraph; //图遍历API

class OpticalEquipPorts extends Base
{
    /* 
    ** 设备端口管理
    ** 输入机房，查询机房的设备
    */

    public function index()
    {
        $equipID = 1; //默认条件
        $equips = new Equips();
        if (request()->isPost()) {
            $condition = input('post.');
            $equipID = $equips->getEquipID($condition);
            // dump($equipID);die;
        }
        $ports = new EquipPorts();
        $records = $ports->where('equip_id', '=', $equipID)->select();
        $this->assign('ports', $records);
        return view();
    }



    /* 
    ** 编辑
    ** @ $id 传入端口ID
    */
    public function edit($id)
    {
        if (empty($id) || intval($id) < 0) {
            return info(lang("Data ID excepetion"), 0);
        }
        // 查询端口的信息
        $ports = new EquipPorts();
        $record = $ports->where('id', '=', $id)->select();

        if (!$record) {
            $this->error('不存在该条记录，请重试！', url('index'));
        }

        // dump($record);die;
        $this->assign('port', $record);

        // 对提交的数据进行处理

        if (request()->isPost()) {
            $submitData = input('post.');

            $updateNum = $ports->isUpdate(true)->allowField(True)->save($submitData);
            if ($updateNum) { // 有待改正
                $this->success('更新成功', url('index'));
            } else {
                $this->error('更新失败，请重试');
            }
        }
        return view();
    }

    /* 
    ** 添加新端口
    */
    public function add()
    {
        return view();
    }


    /* 
    ** 传入一个端口位置，
    ** 获取该端口后续的路由
    */
    public function route()
    {       

        // 获取所有设备端口ID以及端口ID之间的连接关系
        $ports = new EquipPorts();
        $portRecords = collection($ports->select())->toArray();
        // dump($portRecords);die;
        $vetex = array();
        foreach ($portRecords as $k => $v) {
            array_push($vetex, $v['id']);
        }
        // dump($vetex);die;

        $jumpers = new jumpFibers();
        $jumperRecords = collection($jumpers->select())->toArray();
        // dump($jumperRecords);die;
        $edge = array();
        foreach ($jumperRecords as $key => $value) {
            array_push($edge, [$value['a_port_id'], $value['b_port_id']]);
        }
        // dump($edge);die;

        $fiberPorts = new meltDetails();
        $meltRecords = $fiberPorts->getMeltDetails();
        // dump($meltRecords);die;
        foreach ($meltRecords as $key => $value) {
            array_push($edge, [$value['start_port_id'], $value['end_port_id']]);
        }
        // dump($edge);die;

        // 实例化邻接表图
        $graph = new ALGraph($vetex, $edge);
        if (request()->isPost()) {
            $data = input("post.");
            dump($data);die;
            // 条件：机房、设备、端口位置
        }
        $location = '郊尾镇';
        $equipName = '机柜1';
        $boxNo = 0;
        $plateNo = 0;
        $portNo = 1;

        $equipProfiles = new equipProfiles();
        $equipID = $equipProfiles->getEquipID(['location'=>$location, 'name'=>$equipName]);
        $portID = $ports->getPortID(['equip_id' => $equipID, 'box_no' => $boxNo, 'plate_no' => $plateNo, 'port_no' => $portNo]);

        $routePortIDs = $graph->bfs($portID); // 传入起始端口ID
        // dump($routePortIDs);die;
        $details = $ports->getPortDetailsByIDs($routePortIDs);
        // dump($details);die;
        $this->assign('ports', $details);
        return view();
    }
}
