<?php

namespace app\admin\controller;

use app\admin\model\OpticalEquipPorts as EquipPorts;
use app\admin\model\OpticalEquipProfiles as Equips;

class OpticalEquipPorts extends Base
{
    /* 
    ** 设备端口管理
    ** 输入机房，查询机房的设备
    */

    public function index()
    {
        $equipID = 1; //默认条件
        $ports = new EquipPorts();
        $records = $ports->where('equip_id', '=', $equipID)->select();
        $this->assign('ports', $records);
        return view();
    }


       
    /* 
    ** 编辑
    ** @ $id 传入端口ID
    */
    public function edit( $id )
    {
        if (empty($id)|| intval($id) < 0){
            return info(lang("Data ID excepetion"),0);
        }
        // 查询端口的信息
        $ports = new EquipPorts();
        $record = $ports->where('id', '=', $id)->select();
          
        if (!$record){
            $this->error('不存在该条记录，请重试！',url('index'));
        }

        // dump($record);die;
        $this->assign('port', $record);

        // 对提交的数据进行处理
         
        if(request()->isPost()){
            $submitData = input('post.');

            $updateNum = $ports->isUpdate(true)->allowField(True)->save($submitData);
            if ($updateNum){// 有待改正
                $this->success('更新成功',url('index'));
            }else{
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


}


