<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2018/12/24
 * Time: 19:19
 */

namespace app\admin\controller;

use think\Controller;
use think\Loader;
use think\Session;
use think\Request;
use think\Url;

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

        // 验证权限
        $request = Request::instance();
        $rule_val = $request->module().'/'.$request->controller().'/'.$request->action();
        $this->uid = $userRow['id'];
//        $this->role_id = $userRow['role_id'];
        if ($userRow['administrator']!=1 && !$this->checkRule($this->uid,$rule_val)){
            $this->error(lang('Without permissions page.'));
        }
    }

    /**
     * 检查权限规则
     * */
    public function checkRule($uid, $rule_val){
        $authRule = Loader::model('AuthRule');
        if (!$authRule->isCheck($rule_val)){// 判定是否需要检查节点
            return true;
        }
        $authAccess = Loader::model('UserAccess');
        if (in_array($rule_val, $authAccess->getAccess($uid))){
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