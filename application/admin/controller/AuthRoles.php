<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2018/12/26
 * Time: 21:22
 */

namespace app\admin\controller;

class AuthRoles extends Base
{
    private $role;
    function _initialize()
    {
        parent::_initialize();
        $this->role = model('AuthRoles');
    }


    /**
     * 角色列表
     */
    public function index(){
        // 取出所有的角色信息
        $roleData = model('AuthRoles')->getList();
        $this->assign('roleData',$roleData);
        $roleIds = [];
        foreach ($roleData as $key=>$role){
            $roleIds[$key] = $role['id'];
        }
//        dump($roleIds);

        // 取出角色对应的权限信息
        $roleList = model('AuthRoles')->all($roleIds,'getRuleIds'); //关联查询的预查询载入功能
        $ruleIds = [];
        foreach ($roleList as $key=>$rule){
            $ruleIds[$key] = $rule->getRuleIds;
        }
        foreach ($ruleIds as $k1=>$role){
            foreach ($role as $k2=>$rule) {
                $roleRule[$k1][$k2] = $rule['rule_id'];// 对应角色的权限id
            }
//            dump($roleRule[$k1]);
            $ruleData[$k1] = model('AuthRule')->getAccessById($roleRule[$k1]);//取出相应权限字段
        }
//        dump($roleRule);
//        dump($ruleData);
        $this->assign('ruleData',$ruleData);
        return view();
    }

    /**
     * 添加角色
     * */
    public function add(){
        // 取出权限表的所有权限
        $ruleData = model('AuthRule')->getList();
        $this->assign('ruleData',$ruleData);
        $res = [];
        $this->assign('accessChecked', $res);
        return view('edit');
    }

    /**
     * 编辑
     */
    public function edit($id){
        $roleData = model('AuthRoles')->get(['id'=>$id]);
        $this->assign('data',$roleData);

        // 取出权限表的所有权限
        $ruleData = model('AuthRule')->getList();
        $this->assign('ruleData',$ruleData);
        // 取出对应角色的已有权限
        $checked = model('AuthRule')->getAccessByRoleID($id);

        $res = [];
        if (!empty($checked)){
            foreach ($checked as $key=>$rec){ // 将查询结果对象转化为纯数组
                $res[$key] = $rec['id'];
            }
        }

        $this->assign('accessChecked', $res);
//        dump($res);
        return view();
    }

    /**
     * 保存数据
     * */
    public function saveData(){
        //        $this->mustCheckRule( 'admin/auth_roles/edit' );

        $data = input('post.');
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