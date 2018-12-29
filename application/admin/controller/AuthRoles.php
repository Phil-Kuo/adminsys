<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2018/12/26
 * Time: 21:22
 */

namespace app\admin\controller;


use think\Loader;

class AuthRoles extends Base
{
    private $role;
    function _initialize()
    {
        parent::_initialize();
        $this->role = Loader::model('AuthRoles');
    }


    /**
     * 角色列表
     */
    public function index(){
        $data = model('AuthRoles')->getList();
        $this->assign('data',$data);
        return view();
    }

    /**
     * 添加角色
     * */
    public function add(){
        return view('edit');
    }

    /**
     * 编辑
     */
    public function edit($id = 0){
        $roleData = model('AuthRoles')->get(['id'=>$id]);
        $this->assign('data',$roleData);
        return view();
    }

    /**
     * 保存数据
     * */
    public function saveData(){
        if(!request()->isAjax()) {
            return info(lang('Request type error'));
        }
        $data = input('post.');
        model('AuthRule')->saveData($data);
        $this->success(lang('Save success'));
    }
    /**
     * 删除
     */
    public function delete($id = 0){
        if(empty($id)){
            return info(lang('Data ID exception'), 0);
        }
        return model('AuthRoles')->deleteById($id);
    }
}