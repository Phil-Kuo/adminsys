<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2018/12/23
 * Time: 23:11
 */
namespace app\admin\model;

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
        if( $userRow['pwd'] != $md_password or $userRow['status']!=1 ) {
            return info(lang('You entered the account or password is incorrect, please again'), 5001);
        }
        return info(lang('Login succeed'), $code, '', $userRow);
    }

    /**
     * 对查询结果进行格式化
     * @param array     $data
     * @return array    格式化后的数据
     */
    private function fmtData( $data )
    {
        if(empty($data) && is_array($data)) {
            return $data;
        }
        foreach ($data as $key => $value) {
            $data[$key]['status'] = $value['status'] == 1 ? lang('Enable') : lang('Disable'); // 解释用户状态
        }
        return $data;
    }

    /**
     * 获取所有用户列表
     * return array     包含模型对象的二维数组（或数据集对象）
     * */
    public function getList()
    {
        $data =$this->order('id')->select(); // 返回结果为二维数组
        $data = $this->fmtData( $data );
        return $data;
    }

    /**
     * 提交操作
     * @param array     $data
     * @return bool     true|false
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
     * @param array     $data
     * @return array    mixed
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
     * 编辑
     * @param array     $data
     * @return bool    true|false
     * */
    public function edit(array $data=[]){
        $userValidate = validate('Users');
        if(!$userValidate->scene('edit')->check($data)) {
            return info(lang($userValidate->getError()), 4001);
        }
        $flag = false; // 是否发生更新,但是这里存在一个问题：如何实现新增UserRole记录
        $userChange = $this->allowField(true)->save($data,['id'=>$data['id']]);
        $roleChange = model('UserRole')->allowField(true)->save($data,['uid'=>$data['id']]);
        if($userChange || $roleChange){ // 一旦发生修改
            $flag = true;
        }
        return $flag;
    }

    /**
     * 删除
     * @param $id
     * @return mixed
     */
    public function deleteById($id)
    {
        $result = AuthUsers::destroy($id); // 成功删除的记录条数；
        if ($result > 0) {
            return info(lang('Delete succeed'), 1);
        }
    }

}