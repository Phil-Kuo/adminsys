<?php
/**
 * Created by PhpStorm.
 * User: Daly Dai
 * Date: 2019/4/13
 * Time: 18:21
 */

namespace app\admin\controller;


class TeleData extends Base
{
    public function add(){
        if (request()->isPost()){
            $data = input("post.");
            if (!is_array($data)||empty($data)){
                $this->error('提交失败，请重试！');
            }
            $res = model('tele_data')->add($data);
//            dump($res);die;
            if ($res['code']){
                $this->success($res['msg']);
            }else{
//                dump($res);
                $this->error($res['msg']);
            }

        }
        $res = model('tele_data')->distinct(true)->column('building');
//        dump($res);die;
        $this->assign('telData',$res);
        return view();
    }

    /**
     * 配线资料汇总资料
     */
    public function summary(){
        $formData = array();
        if (request()->isPost()){
            $formData = input('post.');
        }
        $res = model('tele_data')->getTree($formData);
//        $page = $res->render();
//        dump($res);die;
//        $res->paginate();
        $this->assign('telData', $res);
//        $this->assign('page',$page);
        return view();
    }

    public function edit($id){
        if (intval($id) < 0){
            return info(lang("Data ID excepetion"),0);
        }
        $res = model('tele_data')->where('id', $id)->select();
        $this->assign('res', $res);
//        dump($res);die;
        $buildingData = model('tele_data')->distinct(true)->column('building');
        $locationData = model('tele_data')->distinct(true)->column('location');
        $typeData = model('tele_data')->distinct(true)->column('type');

        $this->assign('buildings',$buildingData);
        $this->assign('locations',$locationData);
        $this->assign('types',$typeData);
        return view();
    }
    public function del(){

    }
}