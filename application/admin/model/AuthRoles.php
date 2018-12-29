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
    public function getKvData()
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
        $result = $this->where(['id'=>$data['id']])->update( $data );
        if( false === $result) {
            $info = info(lang('Edit failed'), 0);
        } else {
            $info = info(lang('Edit succeed'), 1);
        }
        return $info;
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