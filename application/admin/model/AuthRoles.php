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
     */
    public function getRolesName()
    {
        return $this->where('status',1)->column('name','id');
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

    public function getList()
    {
        $data = $this->order('create_time desc')->select();
        return $this->_fmtData($data);
    }
    public function saveData( $data )
    {
        if( isset( $data['id']) && !empty($data['id'])) {
            $info = $this->edit( $data );
        } else {
            $info = $this->add( $data );
        }
        return $info;
    }
    public function edit( $data )
    {
        $flag = true; // 是否发生更新
        $result = $this->allowField(true)->save($data,['uid'=>$data['id']]);
        if( false === $result) {
            $flag = false;
        }
        return $flag;
    }

    public function add( $data )
    {
        $id = $this->insertGetId( $data );
        if( false === $id) {
            $info = info(lang('Add failed'), 0);
        } else {
            $info = info(lang('Add succeed'), 1, '', $id);
        }

        return $info;
    }

    public function deleteById($id)
    {
        $result = AuthRoles::destroy($id);
        if ($result > 0) {
            return info(lang('Delete succeed'), 1);
        }
    }
}