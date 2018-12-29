<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2018/12/23
 * Time: 21:12
 */

namespace app\admin\controller;

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
//        $roleData = model('AuthRole')->getKvData();
//        $this->assign('roleData', $roleData);
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
        $roleData = model('AuthRoles')->getKvData();
        $this->assign('roleData', $roleData);
        $data = model('AuthUsers')->get(['id' =>$id]);
        $this->assign('data',$data);
        return $this->fetch();
    }

    /**
     * 保存
     * */
    public function saveData(){
        $this->mustCheckRule( 'admin/users/edit' );
        if(!request()->isAjax()) {
            return info(lang('Request type error'));
        }

        $data = input('post.');
//        var_dump($data);die;
        return model('AuthUsers')->saveData( $data );
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
            return Loader::model('AuthUsers')->deleteById($id);
        }
    }
}