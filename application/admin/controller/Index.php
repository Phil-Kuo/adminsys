<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2018/12/19
 * Time: 23:49
 */

namespace app\admin\controller;

use think\Session;

class Index extends Base
{
    function _initialize(){
        parent::_initialize();
    }
    /**
     * 后台首页
     * */
    public function index(){
        if( !Session::has('userinfo', 'admin') ) {
            $this->redirect('admin/Login/index');
        }
        return view();
    }

}