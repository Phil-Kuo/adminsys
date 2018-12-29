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
//        $access = model('AuthRule')-
        return view();
    }

    /**
     * 保存数据
     * */
    public function saveData(){
        //        $this->mustCheckRule( 'admin/auth_roles/edit' );

        $data = input('post.');
//        dump($data);
        $isUpdate = model('AuthRoles')->saveData( $data );
        if ($isUpdate){
            return $this->success('Edit Success!','index','',2);
        }else{
            return $this->error('Edit Failed!','index','',2);
        }
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