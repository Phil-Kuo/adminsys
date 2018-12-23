<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2018/12/17
 * Time: 23:06
 */

namespace app\admin\controller;

use think\Controller;
use think\Db;
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
        $remember = isset($_POST['remember']) ? $_POST['remember'] : 0;
        if (!empty($condition['username']) && !empty($condition['pwd']))
        {
//            $condition['pwd'] = md5($_POST['pwd']); //密码加密
            $result = Db::table('users')->where($condition)->find(); // 查找密码是否存在
            dump($result);
            if (is_array($result)){
                session_start();
                session('id', $result['id']); //将管理员ID存入缓存
                Session::set('userinfo',$result['username'],'admin');
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