<?php

namespace app\admin\controller;

use app\admin\model\OpticalEquipPorts as EquipPorts;
use app\admin\model\OpticalEquipProfiles as Equips;

class OpticalEquips extends Base
{
    /* 
    ** 设备管理
    ** 输入机房，查询机房的设备
    */

    public function index()
    {
        $equips = new Equips();
        $records = $equips->select();
        $this->assign('equips', $records);
        return view();
    }

    /*
    **
    */
    public function add()
    {
        
        if (request()->isPost()){
            $data = input("post.");
            // dump($data);die;
            if (!is_array($data)||empty($data)){
                $this->error('提交失败，请重试！');
            }

            $equips = new Equips();
            // optical_cables_profiles表
            $recordNum = $equips->allowField(True)->save($data);          
            // dump($r1);die;                       
                                  
            if ($recordNum){// 有待改正
                $this->success('更新成功',url('index'));
            }else{
                $this->error('更新失败，请重试');
            }
        }
        return view();
       
    }

        
    /* 
    ** 编辑
    ** @ $id 传入设备ID
    */
    public function edit( $id )
    {
        if (empty($id)|| intval($id) < 0){
            return info(lang("Data ID excepetion"),0);
        }
        // 查询设备的信息
        $equips = new Equips();
        $record = $equips->where('id', '=', $id)->select();
          
        if (!$record){
            $this->error('不存在该条记录，请重试！',url('index'));
        }

        // dump($record);die;
        $this->assign('equip', $record);

        // 对提交的数据进行处理
         
        if(request()->isPost()){
            $submitData = input('post.');

            
            if (true){// 有待改正
                $this->success('更新成功',url('index'));
            }else{
                $this->error('更新失败，请重试');
            }
        }
        return view();
    }


}


