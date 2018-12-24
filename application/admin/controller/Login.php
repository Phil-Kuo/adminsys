<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2018/12/17
 * Time: 23:06
 */

namespace app\admin\controller;

use think\Controller;
use think\Session;

class Login extends Controller
{
    public function index(){
        if( Session::has('userinfo', 'admin') ) {
            $this->redirect( url('admin/index/index') );
        }
        return view();
    }

    /**
     * 管理员登录
     */
    public function login(){

        $condition['username'] = $_POST['username'];
        $condition['pwd'] = $_POST['pwd'];
        $remember = isset($_POST['remember']) ? $_POST['remember'] : 0; //尚未实现记住功能
        if (!empty($condition['username']) && !empty($condition['pwd']))
        {
            $result = model('Users')->login($condition); // 查找密码是否存在
//            dump($result);
            if ($result['code']==1){
                unset($result['data']['password']);
                Session::set('userinfo',$result['data'],'admin');
                return $this->redirect('index/index');
            }
        }else{
            return $this->error('登录失败，请重试！','index'); // 请填写账号密码
        }
    }

    /**
     * 退出登录
     * */
    public function logout(){
        Session::clear('admin');
        $this->success("退出成功",'admin/login/index',"",2);
    }

    /**
     * 后台登录日志
     */
    public function log(){

    }
}