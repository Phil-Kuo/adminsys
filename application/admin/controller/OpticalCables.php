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
}


