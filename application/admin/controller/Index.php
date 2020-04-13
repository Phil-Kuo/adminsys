<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2018/12/19
 * Time: 23:49
 */

namespace app\admin\controller;
use app\admin\model\OpticalFibers as Fibers;
use app\admin\model\OpticalJumpingFiberDetails as jumpFiberDetails;


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
        $fibers = new Fibers();
        $jumpFiberDetails = new jumpFiberDetails();
        $fibers = $fibers->showFibers();
        $jumpFibers = $jumpFiberDetails->showJumpInfo();
        // dump($fibers);
        // dump($jumpFibers); die;
        return view();
    }

    /* 
    ** 特定光纤业务路径表
    */
    public function fiberRoute()
    {
        
    }

}