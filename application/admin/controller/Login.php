<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2018/12/17
 * Time: 23:06
 */

namespace app\admin\controller;

use think\Controller;

class Login extends Controller
{
    public function index()
    {
        return view('./login');
    }

}