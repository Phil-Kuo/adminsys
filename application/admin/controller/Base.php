<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2018/12/24
 * Time: 19:19
 */

namespace app\admin\controller;

use think\Controller;
use think\Session;
use think\Request;

class Base extends Controller
{
    protected $uid = 0;
    protected $role_id = 0;

    /**
     * 初始化
     */
    function _initialize()
    {
        parent::_initialize();

        // 判断是否已经登录
        if (!Session::has('userinfo','admin')){
            $this->error('Please login first',url('admin/Login/index'));
        }

        $userRow = Session::get('userinfo','admin');
//        dump($userRow);

        // 验证权限
        $request = Request::instance();
        $rule_val = $request->module().'/'.$request->controller().'/'.$request->action();
//        dump($rule_val);
        // 设置登录用户的用户ID和角色ID
        $this->uid = $userRow['id'];
        $this->role_id = model('UserRole')->getRoleId($this->uid);
        if ($userRow['administrator']!=1 && !$this->checkRule($this->uid,$rule_val)){
            $this->error(lang('Without permissions page.'));
        }
    }

    /**
     * 检查用户是否拥有权限
     * */
    public function checkRule($uid, $rule_val){
        $rule_val = strtolower($rule_val);
        $authAccess = model('UserAccess');
        if (in_array($rule_val, $authAccess->getRuleVals($uid))){

            return true;
        }
        return false;
    }

    //执行该动作必须验证权限，否则抛出异常
    public function mustCheckRule( $rule_val = '' )
    {
        $userRow = Session::get('userinfo', 'admin');
        if( $userRow['administrator'] == 1 ) {
            return true;
        }
        if( empty($rule_val) ) {
            $request = Request::instance();
            $rule_val = $request->module().'/'.$request->controller().'/'.$request->action();
        }

        if(!model('AuthRule')->isCheck($rule_val)) { // 不存在节点数据
            $this->error(lang('This action must be rule'));
        }
    }


}