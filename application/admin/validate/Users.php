<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2018/12/24
 * Time: 20:13
 */
namespace app\admin\validate;

use think\Validate;

class Users extends Validate
{
    protected $rule =   [
        'username'              => 'require',
        'pwd'              => 'length:6,16',
        'role_id' => 'require',
    ];

    protected $message  =   [
        'username.require'      => 'Username require',
        'pwd.length'       => 'Please enter a correct password',
    ];

    protected $scene = [
        'add' => ['username','pwd', 'role_id'],
        'login' =>  ['username','pwd'],
        'edit' => ['username', 'pwd', 'role_id']
    ];
}