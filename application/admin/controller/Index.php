<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2018/12/19
 * Time: 23:49
 */

namespace app\admin\controller;

class Index extends Base
{
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