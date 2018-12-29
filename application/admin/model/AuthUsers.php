<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2018/12/23
 * Time: 23:11
 */
namespace app\admin\model;

use function PHPSTORM_META\type;
use traits\model\SoftDelete;

class AuthUsers extends Base
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    /**
     *  用户登录
     */
    public function login(array $data)
    {
        $code = 1;
        $msg = '';
        $userValidate = validate('Users'); // 验证器
        if(!$userValidate->scene('login')->check($data)) {
            return info(lang($userValidate->getError()), 4001);
        }
        if( $code != 1 ) {
            return info($msg, $code);
        }
        $map = [
            'username' => $data['username']
        ];
        $userRow = $this->where($map)->find();
//        dump($userRow);
        if( empty($userRow) ) {
            return info(lang('You entered the account or password is incorrect, please again'), 5001);
        }
        $md_password = md5( $data['pwd'] ); // 密码加密
        if( $userRow['pwd'] != $md_password ) {
            return info(lang('You entered the account or password is incorrect, please again'), 5001);
        }
        return info(lang('Login succeed'), $code, '', $userRow);
    }

    /**
     * 格式化数据
     * */
    private function _fmtData( $data )
    {
        if(empty($data) && is_array($data)) {
            return $data;
        }
        foreach ($data as $key => $value) {
            $data[$key]['status'] = $value['status'] == 1 ? lang('Enable') : lang('Disable');
        }
        return $data;
    }

    /**
     * 用户列表
     * */
    public function getList()
    {
        $data =$this->order('create_time desc')->select(); // 返回结果为二维数组
        $data = $this->_fmtData( $data );
        return $data;
    }

    /**
     * 保存
     */
    public function saveData( $data )
    {
        if( isset( $data['id']) && !empty($data['id'])) {
            $result = $this->edit( $data );
        } else {
            $result = $this->add( $data );
        }
        return $result;
    }

    /**
     * 新增
     */
    public function add(array $data = [])
    {
        $userValidate = validate('Users'); // 验证器还未细看
        if(!$userValidate->scene('add')->check($data)) {
            return info(lang($userValidate->getError()), 4001);
        }
        $user = AuthUsers::get(['username' => $data['username']]);
        if (!empty($user)) {
            return info(lang('Username already exists'), 0);
        }
        if($data['pwd2'] != $data['pwd']){
            return info(lang('The password is not the same twice'), 0);
        }
        unset($data['pwd2']);
        $data['pwd'] = md5($data['pwd']);
        $data['create_time'] = time();
        $this->allowField(true)->save($data);  // 过滤非字段数据
        if($this->id > 0){
            return info(lang('Add succeed'), 1, '', $this->id);
        }else{
            return info(lang('Add failed') ,0);
        }
    }

    /**
     * 编辑*/
    public function edit(array $data=[]){
        $userValidate = validate('Users');
        if(!$userValidate->scene('edit')->check($data)) {
            return info(lang($userValidate->getError()), 4001);
        }
        $username = $this->where(['username'=>$data['username']])->where('id','neq',$data['id'])->value('username'); // 查询是否与原始数据库内用户名重名
        if (!empty($username)){
            return info(lang('Username already exists'), 0);
        }
        if($data['pwd2'] != $data['pwd']){
            return info(lang('The two passwords No match!'),0);
        }
        $data['update_time'] = time();
        $data['pwd'] = md5($data['pwd']); // 对密码加密

        $res = $this->allowField(true)->save($data,['id'=>$data['id']]);
        if($res == 1){
            return info(lang('Edit succeed'), 1);
        }else{
            return info(lang('Edit failed'), 0);
        }
    }

    /**
     * 删除
     */
    public function deleteById($id)
    {
        $result = AuthUsers::destroy($id); // 返回成功删除的记录条数；
        if ($result > 0) {
            return info(lang('Delete succeed'), 1);
        }
    }
}