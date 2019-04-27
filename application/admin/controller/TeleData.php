<?php
/**
 * Created by PhpStorm.
 * User: Daly Dai
 * Date: 2019/4/13
 * Time: 18:21
 */

namespace app\admin\controller;

use app\admin\model\TeleData as TelModel;
use app\admin\model\ArchitectureDetails;

class TeleData extends Base
{

    /**
     * 配线资料汇总，尚未解决buildingID与building对应问题。
     */
    public function summary(){
        $tel = new TelModel();
        $formData = array();

        if (request()->isPost()){
            $formData = input('post.');
        }

        $telData = $tel->getTelData($formData);
        $this->assign('telData', $telData);

        return view();
    }

    /**
     * 异步获取某条电话号码所对应的所有记录用于路径树图
     * 以json嵌套数组返回
     * */
    public function searchTel(){
        $tel_number = isset($_GET['tel_number'])?$_GET['tel_number']:"";

        $condition = array('tel_number'=>$tel_number);

        $tel = new TelModel();
        $telData = $tel->getTelData($condition);

        echo json_encode($telData);
    }


    /**
     * 添加电话号码资料， 尚未考虑在同一建筑内拥有分号的情况！
     * */
    public function add(){
        $tel = new TelModel();

        if (request()->isPost()){
            $data = input("post.");
//            dump($data);die;
            if (!is_array($data)||empty($data)){
                $this->error('提交失败，请重试！');
            }
            $res = $tel->add($data);
//            dump($res);die;
            if ($res['code']){
                $this->success($res['msg'],url('summary'));
            }else{
                $this->error($res['msg']);
            }
        }

        //  获取建筑楼和机房的记录传送到前端页面
        $arch = new ArchitectureDetails();
        $buildingData = $arch->where(['level'=>1])->select();
        $locationData = $arch->where(['level'=>2])->select();

        $this->assign('buildingData',$buildingData);
        $this->assign('locations',$locationData);

        return view();
    }

    /**
     * 编辑单条记录,尚未实现配线架类型的自动选中
     */
    public function edit($id){
        $arch = new ArchitectureDetails();
        $tel = new TelModel();

        if (intval($id) < 0 || $id==""){
            return info(lang("Data ID excepetion"),0);
        }

        // 获取传入id值的数据详细记录
        $telData = $tel->where('id', $id)->select();
        if (!$telData){
            $this->error('不存在该条记录，请重试！',url('summary'));
        }
        $buildingId = $telData[0]['building_id'];
        $lastId = $telData[0]['pid'];

        // 查询该id的对应的父记录对应的建筑楼并传到前端页面
        $lastBldId = $tel->where('id', $lastId)->value('building_id');
        $this->assign(['pid'=>$lastBldId,'telData'=>$telData]);

        // 获取建筑楼和配线架位置的信息并传送到前端以实现二级联动选择框
        $buildingData = $arch->where(['level'=>1])->select();
        $locationData = $arch->where(['pid'=>$buildingId,'level'=>2])->select();
        $this->assign(['buildings'=>$buildingData,'locations'=>$locationData]);

        // 对提交的数据进行处理
        if(request()->isPost()){
            $submitData = input('post.');
//             dump($submitData);die;
            $affectd = $tel->isUpdate(true)->save($submitData);
            if ($affectd){
                $this->success('更新成功',url('summary'));
            }else{
                $this->error('更新失败，请重试');
            }
        }
        return view();
    }

    /**
     * 异步获取以实现建筑和配线架所处位置的二级联动选择
     */
    public function getArchitecture()
    {
        $arch = new ArchitectureDetails();
        $arch_id = isset($_GET['id'])?$_GET['id']:"";

        if (!$arch_id){
//            exit(json_encode(array("flag" => false, "msg" => "查询类型错误")));报错
        }else{
            $location = $arch->where(['pid'=>$arch_id])->select();
            return json_encode($location);
        }
    }

    /**
     * 根据传入的ID值删除记录，尚未实现联动删除！
     * */
    public function del($id){
        if (intval($id) < 0 || $id==""){
            return info(lang("Data ID excepetion"),0);
        }
        $tel = new TelModel();
        $affect = $tel->where('id',$id)->delete();
        if ($affect > 0) {
            $this->success('删除成功！');
        }else{
            $this->error('删除失败！');
        }
    }
}