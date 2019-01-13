<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2019/1/6
 * Time: 19:36
 */

namespace app\admin\controller;


class Leave extends Base
{
    public function index(){
        return view();
    }
    /**
     * 新增
     */
    public function add(){
        return $this->fetch();
    }

    /**
     * 提交
     */
    public function saveData(){
        $data = input('post.');
        $result = model('Leave')->saveData($data);
        if ($result){
            return $this->success('提交成功');
        }

    }
}