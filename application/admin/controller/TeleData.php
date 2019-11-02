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
use think\Db;

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
        //    dump($data);die;
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

        // 获取已有配线资料传送到前端，实现二表联结查询
        $telData = TelModel::with('buildings,locations')->select();
        foreach ($telData as $k=>$v){
            $v->building = $v->buildings->arch_name;
            $v->location = $v->locations->arch_name;
        }
        $this->assign('telData', $telData);

        return view();
    }

    /**
     * 编辑单条记录,尚未实现配线架类型的自动选中
     */
    public function edit($id){
        $arch = new ArchitectureDetails();
        $tel = new TelModel();

        if (empty($id)|| intval($id) < 0){
            return info(lang("Data ID excepetion"),0);
        }
        // 获取传入id值的数据详细记录
        $telData = $tel->where('id', $id)->select();
        if (!$telData){
            $this->error('不存在该条记录，请重试！',url('summary'));
        }
        $buildingId = $telData[0]['building_id'];

        // 获取已有配线资料传送到前端，实现二表联结查询
        $totalData = TelModel::with('buildings,locations')->select();
        foreach ($totalData as $k=>$v){
            $v->building = $v->buildings->arch_name;
            $v->location = $v->locations->arch_name;
        }
        $this->assign(['totalData'=> $totalData,'telData'=>$telData]);

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

    /**
     * 上传Excel文件
     */

    public function upload() {
        //设置附件上传文件大小200Kib

        //设置附件上传类型

        //设置附件上传目录在/Home/temp下

        //保持上传文件名不变

        //存在同名文件是否是覆盖

        // 获取表单上传文件
        $file = request()->file('excel_file');
        if (empty($file)) {  //如果上传失败,提示错误信息
            $this->error($_FILES['excel_file']['error']);
        } else {  //上传成功
            //获取上传文件信息
            $info = $file->rule('uniqid')->move(ROOT_PATH.'public'.DS.'upload_excel');
            //获取上传保存文件名
            $fileName = $info->getFilename();
//            dump($fileName);die;
            //重定向,把$fileName文件名传给importExcel()方法
            $this->redirect('importExcel', ['fileName'=>$fileName]);
        }
    }

    public function importExcel($fileName){
        $filePath = ROOT_PATH.'public'.DS.'upload_excel'.DS.$fileName;
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        $spreadsheet = $reader->load($filePath);
        $currentSheet = $spreadsheet->getActiveSheet();
        $highetsIndex = $currentSheet->getHighestRowAndColumn();

        $arch = new ArchitectureDetails();
        for ($currentRow = 2; $currentRow <= $highetsIndex['row'];$currentRow++){
            $tel = new TelModel();
            $record = array();
            $record['tel_number'] = $currentSheet->getCell('A'.$currentRow)->getValue();

            $record['building'] = $currentSheet->getCell('B'.$currentRow)->getValue();
            $record['building_id'] = $arch->where('arch_name',$record['building'])->value('id');

            $record['location'] = $currentSheet->getCell('C'.$currentRow)->getValue();
            $record['location_id'] = $arch->where(['arch_name'=> $record['location'],'pid'=>$record['building_id']])->value('id');

            $record['type'] = $currentSheet->getCell('D'.$currentRow)->getValue();
            $record['entrance'] = $currentSheet->getCell('E'.$currentRow)->getValue();
            $record['jump_to'] = $currentSheet->getCell('F'.$currentRow)->getValue();
            $record['parent'] = $currentSheet->getCell('G'.$currentRow)->getValue();
            $record['remark'] = $currentSheet->getCell('H'.$currentRow)->getValue();
            if ($record['parent']!='0'){
                $parent= explode('-',$record['parent']);
//                dump($parent);
                // 根据号码和上一跳建筑、机房弱电间查找其父id
                $pid = Db::view(['tele_data'=>'t'])
                    ->view(['architecture_details'=>'b'],['arch_name'=>'building'],'t.building_id=b.id')
                    ->view(['architecture_details'=>'l'],['arch_name'=>'location'],'t.location_id=l.id')
                    ->where(['tel_number'=>$record['tel_number'],'building'=>$parent[0],'location'=>$parent[1]])
                    ->value('t.id');
                $record['pid'] =$pid;
            }else{
                $record['pid'] = 0;
            }
            $tel->allowField(true)->save($record);
        }
    }

}