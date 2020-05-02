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


    /* 
    ** 获取光缆信息
    */
    public function getAllCables()
    {
        // 查询光缆的基本信息
        $cables = Db::query('SELECT
                                c.*,
                                d.start_location,
                                d.end_location 
                            FROM
                                optical_cables_profiles AS c
                                JOIN (
                                SELECT
                                    e1.cable_id AS id,
                                    e1.location AS start_location,
                                    e2.location AS end_location 
                                FROM
                                    optical_cable_endpoint e1,
                                    optical_cable_endpoint e2 
                                WHERE
                                    e1.cable_id = e2.cable_id 
                                AND e1.endpoint_type = ? 
                                AND e2.endpoint_type =?) AS d ON c.id = d.id',[0, 1]); 
        return $cables;   
    }
}