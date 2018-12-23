<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2018/12/23
 * Time: 21:12
 */

namespace app\admin\controller;

use app\common;

use think\Controller;
/**
 * 用户管理
*/
class User extends common
{
    /**
     * 用户列表
     */
    public function index(){
        return;
    }

    /**
     * 添加用户
     * */
    public function add(){

    }

    /**
     * 编辑
     */
    public function edit($id = 0){
        if (intval($id) < 0){
            // 报错
            return info(lang("Data ID excepetion"),0);
        }
        if (intval($id == 1)){
            //报错
            return info(lang('Edit without authorization'), 0);
        }
        $roleData = model('Role')->getKvData();
        $this->assign('roleData', $roleData);
        $data = model('User')->get(['id' =>$id]);
        $this->assign('data',$data);
        return $this->fetch();
    }

    /**
     * 保存
     * */
    public function saveData(){

    }

    /**
     * 删除
     * */
    public function delete($id = 0){
        if(empty($id)){
            return info(lang('Data ID exception'), 0);
        }
        if (intval($id == 1)) {
//            return info(lang('Delete without authorization'), 0);
//        }
        return Loader::model('User')->deleteById($id);
    }
}