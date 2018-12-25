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
class Users extends Base
{
    /**
     * 用户列表
     */
    public function index(){
        return view();
    }

    /**
     * 异步获取列表数据
     * @return mixed
     */
    public function getList()
    {
//        if(!request()->isAjax()) {
//            $this->error(lang('Request type error'), 4001);
//        }

        $request = request()->param();
        $data = model('Users')->getList( $request );
        return $data;
    }

    /**
     * 添加用户
     * */
    public function add(){
//        $roleData = model('role')->getKvData();
//        $this->assign('roleData', $roleData);
        return $this->fetch('edit');
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
//        $roleData = model('Role')->getKvData();
//        $this->assign('roleData', $roleData);
        $data = model('Users')->get(['id' =>$id]);
        $this->assign('data',$data);
        return $this->fetch();
    }

    /**
     * 保存
     * */
    public function saveData(){
//        $this->mustCheckRule( 'admin/users/edit' );
//        if(!request()->isAjax()) {
//            return info(lang('Request type error'));
//        }

        $data = input('post.');
//        var_dump($data);die;
        return model('Users')->saveData( $data );
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
            return Loader::model('Users')->deleteById($id);
        }
    }
}