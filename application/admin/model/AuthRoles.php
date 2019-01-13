<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2018/12/26
 * Time: 21:32
 */

namespace app\admin\model;


class AuthRoles extends Base
{
    /**
     * 获取状态为启用的所有角色
     * return array
     */
    public function getRolesName()
    {
        return $this->where('status',1)->column('name','id');
    }

    /**
     * 格式化数据
     * 对查询结果进行格式化
     * @param array     $data
     * @return array    格式化后的数据
     * */
    private function fmtData( $data )
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
     * 取出所有角色记录
     * return array     包含模型对象的二维数组（或数据集对象）
     * */
    public function getList()
    {
        $data = $this->order('id')->select();
        return $this->fmtData($data);
    }

    /**
     * 取出对应role_id的角色记录
     */
    public function getRoles(array $role_id=[]){
        $roleData = $this->where('id','in',$role_id)->select();
        return $roleData;
    }

    /**
     * 提交操作
     * @param array     $data
     * @return bool     true|false
     * */
    public function saveData( $data )
    {
        if( isset( $data['role_id']) && !empty($data['role_id'])) {
            $info = $this->edit( $data );
        } else {
            $info = $this->add( $data );
        }
        return $info;
    }

    /**
     * 编辑
     * @param array     $data
     * @return bool    true|false
     * */
    public function edit( $data )
    {
        $flag = true; // 是否发生更新
        // 更新角色表
        $result = $this->allowField(true)->save($data,['id'=>$data['role_id']]);
        // 首先判断是否存在rule_id
        if (key_exists('rule_id',$data)){
            foreach ($data['rule_id'] as $key=>$rec){
                $record[$key] = ['role_id'=>$data['role_id'],'rule_id'=>$rec];
            }
        }else{
            $record = [];
        }
        // 删除角色的原有权限
        $resultDel = model('RoleAccess')::Where(['role_id'=>$data['role_id']])->delete();
//        $result_del = model('RoleAccess')->deleteById($data['role_id']);
        // 新增角色权限
        $resultAdd = model('RoleAccess')->saveAll($record);

        if( false === $result or false === $resultDel or false === $resultAdd) {
            $flag = false;
        }
        return $flag;
    }

    /**
     * 新增
     * @param array     $data
     * @return array    mixed
     */
    public function add( $data )
    {
        $id = $this->allowField(true)->save( $data );
        if( false === $id) {
            $info = info(lang('Add failed'), 0);
        } else {
            $info = info(lang('Add succeed'), 1, '', $id);
        }

        return $info;
    }

    /**
     * 删除
     * @param $id
     * @return mixed
     */
    public function deleteById($id)
    {
        $result = AuthRoles::destroy($id);
        if ($result > 0) {
            return info(lang('Delete succeed'), 1);
        }
    }

    /**
     * 建立角色与权限的一对多关联
     */
    public function getRuleIds(){
        return $this->hasMany('RoleAccess','role_id');
    }


}