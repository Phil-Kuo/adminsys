<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2018/12/24
 * Time: 19:19
 */

namespace app\admin\controller;

use app\common;
use think\Controller;
use think\Loader;
use think\Session;
use think\Request;
use think\Url;

class Base
{

    //执行该动作必须验证权限，否则抛出异常
    public function mustCheckRule( $rule_val = '' )
    {

    }
}