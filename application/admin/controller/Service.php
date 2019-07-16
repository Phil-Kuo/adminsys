<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2019/1/6
 * Time: 15:25
 */

namespace app\admin\controller;


class Service extends Base
{
    public function index(){
        $data = model('Service')->getList();
//        dump($data);
        $this->assign('data',$data);
        return $this->fetch();
    }

    public function add(){
        return view('Service/edit');
    }

    /**
     * 提交
     */
    public function saveData(){
        $data = input('post.');
        $result = model('Service')->saveData($data);
        if ($result){
            return $this->success('提交成功');
        }else{

        }

    }

}