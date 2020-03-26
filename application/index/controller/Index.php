<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use app\admin\model\TeleData as TelModel;
use app\admin\model\ArchitectureDetails;
use think\Db;

class Index extends Controller
{
    public function index()
    {
        $tel = new TelModel(); 
        $arch = new ArchitectureDetails();
        // 获取所有建筑
        $buildings = $arch->where('pid','0')->select();
        $condition = array();
        foreach($buildings as $k => $v){
            array_push($condition, $v['id']);
        }
        // dump($condition );die;
        $result = array();
        foreach($condition as $m=>$n){
            $telData = TelModel::with('buildings,locations')->where('building_id', $n)->select();
            foreach($telData as $k=>$v){
                $v->building = $v->buildings->arch_name;
                $v->location = $v->locations->arch_name;                
            }
            if($telData){// 如果查询结果不为空
                // array_push($result, $telData);
                array_push($result, $telData);
            }
            
        }
        // dump($result);die;

        $this->assign('result', $result);
        return view();
    }
}
