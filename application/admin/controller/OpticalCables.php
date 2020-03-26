<?php

namespace app\admin\controller;

use app\admin\model\OpticalCablesProfiles as CablesProfiles;
use app\admin\model\OpticalCableEndpoint as Endpoint;
use app\admin\model\OpticalFibers as Fibers;
use think\Db;

class OpticalCables extends Base
{
    /* 
    ** 光缆信息概览
    */
    public function index()
    {
        if (request()->isPost()){
            $formData = input('post.');
        }

        // 查询光缆的基本信息
        $cables = Db::query('SELECT c.*, d.start_location, d.end_location 
                             FROM optical_cables_profiles AS c join 
                                (SELECT e1.cable_id AS id, e1.location AS start_location, e2.location AS end_location 
                                FROM optical_cable_endpoint e1, optical_cable_endpoint e2 
                                WHERE e1.cable_id = e2.cable_id AND e1.endpoint_type= ? AND e2.endpoint_type=?) AS d ON c.id = d.id',[0, 1]);                    
        // dump($result);die;
        $this->assign('cables', $cables);

        return view();
    }

    /* 
    ** 添加新光缆
    */
    public function add()
    {
        $cable = new CablesProfiles();

        if (request()->isPost()){
            $data = input("post.");
        //    dump($data);die;
            if (!is_array($data)||empty($data)){
                $this->error('提交失败，请重试！');
            }

            // optical_cables_profiles表
            $r1 = $cable->allowField(True)->save($data);
            // dump($r1);die;
                        
            // optical_cable_endpoint表 
            $endpoint = new Endpoint();
            $list = [
                ['cable_id'=>$cable->id, 'endpoint_type'=>0, 'location'=>$data['start_location']],
                ['cable_id'=>$cable->id, 'endpoint_type'=>1, 'location'=>$data['end_location']]            
            ];
            $r2 = $endpoint->allowField(True)->saveAll($list);
            
            if ($r1 || $r2 ){// 有待改正
                $this->success('更新成功',url('index'));
            }else{
                $this->error('更新失败，请重试');
            }
        }

        return view();
    }

    /* 
    ** 编辑光缆信息
    ** @ $id 传入光缆ID
    */
    public function edit( $id )
    {
        if (empty($id)|| intval($id) < 0){
            return info(lang("Data ID excepetion"),0);
        }

        // 查询光缆的基本信息
        $cable = Db::query('SELECT c.*, d.start_location, d.end_location 
                            FROM optical_cables_profiles AS c join 
                                (SELECT e1.cable_id AS id, e1.location AS start_location, e2.location AS end_location 
                                FROM optical_cable_endpoint e1, optical_cable_endpoint e2 
                                WHERE e1.cable_id = e2.cable_id AND e1.endpoint_type= ? AND e2.endpoint_type=?) AS d ON c.id = d.id 
                            WHERE c.id=?',[0, 1, $id]);
          
        if (!$cable){
            $this->error('不存在该条记录，请重试！',url('index'));
        }

        // dump($cable);die;
        $this->assign('cable', $cable);

        // 对提交的数据进行处理
        $cable = new CablesProfiles();        
        if(request()->isPost()){
            $submitData = input('post.');
            // dump($submitData['end_location']);die;

            // 更新optical_cables_profiles表
            $r1 = $cable->isUpdate(true)->allowField(True)->save($submitData);

            // 更新optical_cable_endpoint表            
            $r2 = Db::table('optical_cable_endpoint')->where(['cable_id'=>$submitData['id'], 'endpoint_type'=>0])->update(['location'=>$submitData['start_location']]);
            $r3 = Db::table('optical_cable_endpoint')->where(['cable_id'=>$submitData['id'], 'endpoint_type'=>1])->update(['location'=>$submitData['end_location']]);
            
            if ($r1 || $r2 || $r3){// 有待改正
                $this->success('更新成功',url('index'));
            }else{
                $this->error('更新失败，请重试');
            }
        }
        return view();
    }

    /* 
    ** 删除光缆
    */
    public function delete($id)
    {
        if (intval($id) < 0 || $id==""){
            return info(lang("不存在该记录，请重试！"),0);
        }
        $cable = new CablesProfiles(); 
        $affectNum = $cable->where('id',$id)->delete();
        if ($affectNum > 0) {
            $this->success('删除成功！');
        }else{
            $this->error('删除失败！');
        }
    }

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

        // 查询光缆的基本信息和纤芯的基本信息、上架信息
        $fiber = Db::query('SELECT *
                            FROM (SELECT *
                                    FROM optical_fibers
                                    WHERE cable_id=?) AS ff JOIN (SELECT f.fiber_id, f.start_joint_type, f.end_joint_type, p1.id AS start_port_id, p1.equip_id AS start_equip_id, p1.box_no AS start_box_no, p1.plate_no AS start_plate_no, p1.port_no AS start_port_no, p1.port_type AS start_port_type, p1.port_status AS start_port_status, p2.id AS end_port_id, p2.equip_id AS end_equip_id, p2.box_no AS end_box_no, p2.plate_no AS end_plate_no, p2.port_no AS end_port_no, p2.port_type AS end_port_type, p2.port_status AS end_port_status
                                                                  FROM (SELECT m1.fiber_id AS fiber_id, m1.port_id AS start_port_id, m1.joint_type AS start_joint_type, m2.port_id AS end_port_id,  m2.joint_type AS end_joint_type 
                                                                        FROM optical_fibers_melt_details AS m1, optical_fibers_melt_details AS m2 
                                                                        WHERE m1.fiber_id = m2.fiber_id AND m1.endpoint_type = ? AND m2.endpoint_type = ?) AS f JOIN optical_equip_ports AS p1 ON f.start_port_id = p1.id JOIN optical_equip_ports AS p2 ON f.end_port_id = p2.id) AS q ON ff.id = q.fiber_id',[$cableID, 0, 1]);
        // dump($fiber);die; $fiber 是一个二维数组
        $this->assign('fiber', $fiber);
        return view();
    }

}


