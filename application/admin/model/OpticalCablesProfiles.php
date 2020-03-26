<?php

namespace app\admin\model;
use think\Db;

class OpticalCablesProfiles extends Base
{
    /**
     * 新增光缆记录
     * @data 数组
     */
    public function add( $data ){
        //查询该记录是否已存在


        if ($data){
            $info = info(lang('信息有误，请重试！'), 0);
        }else{
            $id = $this->allowField(true)->save( $data );
            if( false === $id) {
                $info = info(lang('添加失败，请重试！'), 0);
            } else {
                $info = info(lang('添加成功!'), 1, '', $id);
            }
        }
        return $info;
    }
    
}