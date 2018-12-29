<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2018/12/23
 * Time: 21:12
 */

namespace app\admin\controller;

//use think\Loader;
use think\Request;

/**
 * 用户管理
 */
class AuthUsers extends Base
{
    function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 用户列表
     */
    public function index(){
        $data = model('AuthUsers')->getList();
        $this->assign('data',$data);
        return view();
    }

    /**
     * 添加用户
     * */
    public function add(){
        $roleData = model('AuthRoles')->getRolesName();
        $this->assign('roleData', $roleData);
        return $this->fetch('edit');
    }

    /**
     * 编辑
     */
    public function edit($id = 0){
        if (intval($id) < 0){
            return info(lang("Data ID excepetion"),0);
        }
/*        //检查权限
        if (intval($id == 1)){
            //报错
            return info(lang('Edit without authorization'), 0);
        }*/
        $roleData = model('AuthRoles')->getRolesName();
        $this->assign('roleData', $roleData);
        $data = model('AuthUsers')->get(['id' =>$id]);
        $this->assign('data',$data);
        return $this->fetch();
    }

    /**
     * 保存
     * */
    public function saveData(){
//        $this->mustCheckRule( 'admin/auth_users/edit' );

        $data = input('post.');
//        dump($data);
        $isUpdate = model('AuthUsers')->saveData( $data );
        if ($isUpdate){
            return $this->success('Edit Success!','index','',2);
        }else{
            return $this->error('Edit Failed!','index','',2);
        }
    }

    /**
     * 删除
     * */
    public function delete($id = 0){
        if(empty($id)){
            return info(lang('Data ID exception'), 0);
        }
//        if (intval($id == 1)) {
//            return info(lang('Delete without authorization'), 0);
//        }
            return model('AuthUsers')->deleteById($id);
    }
}