<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2018/12/23
 * Time: 23:11
 */
namespace app\admin\model;

use think\Model;

class Users extends Model
{


    /**
     * 编辑*/
    public function edit(array $data=[]){
//        $username = $this->where(['username'=>$data['username']])->where('id','<>',$data['id'])->value('username'); // 查询是否与原始数据库内用户名重名
        if (!empty($username)){
            return info(lang('Username already exists'), 0);
        }
        if($data['password2'] != $data['password']){
            return info(lang('The two passwords No match!'),0);
        }
        $data['update_time'] = time();
//        $data['password'] = md5($data['password']); \\ 对密码加密

        $res = $this->allowField(true)->save($data,['id'=>$data['id']]);
        if($res == 1){
            return info(lang('Edit succeed'), 1);
        }else{
            return info(lang('Edit failed'), 0);
        }
    }
}