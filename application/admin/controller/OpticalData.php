<?php

namespace app\admin\controller;

use app\admin\model\OpticalJumpingFiberDetails as OpticalJumpingFiberDetails;
use think\Db;

class OpticalData extends Base
{
    public function index()
    {
        $opticalJumpingFiberDetails = new OpticalJumpingFiberDetails();
        
        $formData = array();
        if (request()->isPost()){
            $formData = input('post.');
        }

        $result = $opticalJumpingFiberDetails->search($formData);

        // dump($result); die;
        
        $this->assign('result', $result);
        return view();
    }

    public function addOpticalCable()
    {
        return view();
    }
    /*
    * 添加新跳纤 
    */
    public function addJumper()
    {
        return view();
    }

    /* 
    * 编辑纤芯与ODF端口的对应信息
     */
    public function alterFiberPort()
    {
        return view();
    }
}
