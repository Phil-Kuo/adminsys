<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2018/12/26
 * Time: 21:32
 */

namespace app\admin\model;


class Role extends Base
{
    /**
     * 根据uid返回角色
     * */
    public function getRoleInfo( $uid )
    {

    }
    public function getKvData()
    {
        return $this->where('status',1)->column('name','id');
    }
    public function getList( $request )
    {
        $request = $this->fmtRequest( $request );
        return $this->order('create_time desc')->where( $request['map'] )->limit($request['offset'], $request['limit'])->select();
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
        $result = Role::destroy($id);
        if ($result > 0) {
            return info(lang('Delete succeed'), 1);
        }
    }
}