<?php

namespace app\admin\model;
use think\Db;

class OpticalJumpingFiberDetails extends Base
{

    /* 
    * 根据传入条件搜索某机房的跳纤情况，传回前端。
    * param $condition string 机房名称 
            $all bool TRUE: 返回跳纤表内所有数据；FALSE(默认): 不返回所有数据
    * 
    */
    public function showJumpInfo($condition = "", $all = FALSE)
    {
        
        if ($all) {
            // 整个跳纤数据表内的数据
            $jumpFibers = Db::query('SELECT
            j.id,
            j.a_port_id AS in_port_id,
            j.b_port_id AS out_port_id,
            p1.business AS business,
            p1.equip_id AS in_equip_id,
            e1.`name` AS in_equip_name,
            e1.location AS in_equip_location,
            p1.box_no AS in_box_no,
            p1.plate_no AS in_plate_no,
            p1.port_no AS in_port_no,
            p1.port_type AS in_port_type,
            p1.port_status AS in_port_status,
            p2.equip_id AS out_equip_id,
            e2.`name` AS out_equip_name,
            e2.location AS out_equip_location,
            p2.box_no AS out_box_no,
            p2.plate_no AS out_plate_no,
            p2.port_no AS out_port_no,
            p2.port_type AS out_port_type,
            p2.port_status AS out_port_status 
        FROM
            optical_jumping_fiber_details AS j
            JOIN optical_equip_ports AS p1 ON p1.id = j.a_port_id
            JOIN optical_equip_ports AS p2 ON p2.id = j.b_port_id
            JOIN optical_equip_profiles AS e1 ON p1.equip_id = e1.id
            JOIN optical_equip_profiles AS e2 ON p2.equip_id = e2.id');
            return $jumpFibers;
        }elseif (empty($condition)) {
            //默认查询该机房的跳纤表 
            $condition = "郊尾镇"."%"; 
            
        }
        $jumpFibers = Db::query('SELECT
        j.id,
        j.a_port_id AS in_port_id,
        j.b_port_id AS out_port_id,
        p1.business AS business,
        p1.equip_id AS in_equip_id,
        e1.`name` AS in_equip_name,
        e1.location AS in_equip_location,
        p1.box_no AS in_box_no,
        p1.plate_no AS in_plate_no,
        p1.port_no AS in_port_no,
        p1.port_type AS in_port_type,
        p1.port_status AS in_port_status,
        p2.equip_id AS out_equip_id,
        e2.`name` AS out_equip_name,
        e2.location AS out_equip_location,
        p2.box_no AS out_box_no,
        p2.plate_no AS out_plate_no,
        p2.port_no AS out_port_no,
        p2.port_type AS out_port_type,
        p2.port_status AS out_port_status 
    FROM
        optical_jumping_fiber_details AS j
        JOIN optical_equip_ports AS p1 ON p1.id = j.a_port_id
        JOIN optical_equip_ports AS p2 ON p2.id = j.b_port_id
        JOIN optical_equip_profiles AS e1 ON p1.equip_id = e1.id
        JOIN optical_equip_profiles AS e2 ON p2.equip_id = e2.id 
    WHERE
        e1.location LIKE ?',[$condition]);
        return $jumpFibers;
    }

    /* 
    ** 查询某一ID的跳纤信息
    */
    public function showSpecificJumper($jumperID)
    {
        $jumpFiber = Db::query('SELECT
        j.id,
        j.a_port_id AS in_port_id,
        j.b_port_id AS out_port_id,
        p1.business AS business,
        p1.equip_id AS in_equip_id,
        e1.`name` AS in_equip_name,
        e1.location AS in_equip_location,
        p1.box_no AS in_box_no,
        p1.plate_no AS in_plate_no,
        p1.port_no AS in_port_no,
        p1.port_type AS in_port_type,
        p1.port_status AS in_port_status,
        p2.equip_id AS out_equip_id,
        e2.`name` AS out_equip_name,
        e2.location AS out_equip_location,
        p2.box_no AS out_box_no,
        p2.plate_no AS out_plate_no,
        p2.port_no AS out_port_no,
        p2.port_type AS out_port_type,
        p2.port_status AS out_port_status 
    FROM
        ( SELECT * FROM optical_jumping_fiber_details WHERE id = ? ) AS j
        JOIN optical_equip_ports AS p1 ON p1.id = j.a_port_id
        JOIN optical_equip_ports AS p2 ON p2.id = j.b_port_id
        JOIN optical_equip_profiles AS e1 ON p1.equip_id = e1.id
        JOIN optical_equip_profiles AS e2 ON p2.equip_id = e2.id',[$jumperID]);
        return $jumpFiber;
    }




}