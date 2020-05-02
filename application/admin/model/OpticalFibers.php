<?php

namespace app\admin\model;
use think\Db;

class OpticalFibers extends Base
{
    public function showFibers($cableID = "")
    {
        // 查询特定光缆的基本信息和纤芯的基本信息、上架信息
        if (!empty($cableID)) {
            $fiber = Db::query('SELECT
                                    ff.*,
                                    q.*,
                                    e1.location AS start_equip_location,
                                    e1.`name` AS start_equip_name,
                                    e1.type AS start_equip_type,
                                    e1.boxes_quantity AS start_equip_boxes_quantity,
                                    e2.location AS end_equip_location,
                                    e2.`name` AS end_equip_name,
                                    e2.type AS end_equip_type,
                                    e2.boxes_quantity AS end_equip_boxes_quantity
                                FROM
                                    ( SELECT * FROM optical_fibers WHERE cable_id = ? ) AS ff
                                    JOIN (
                                    SELECT
                                        f.fiber_id,
                                        f.start_joint_type,
                                        f.end_joint_type,
                                        p1.id AS start_port_id,
                                        p1.equip_id AS start_equip_id,
                                        p1.box_no AS start_box_no,
                                        p1.plate_no AS start_plate_no,
                                        p1.port_no AS start_port_no,
                                        p1.port_type AS start_port_type,
                                        p1.port_status AS start_port_status,
                                        p2.id AS end_port_id,
                                        p2.equip_id AS end_equip_id,
                                        p2.box_no AS end_box_no,
                                        p2.plate_no AS end_plate_no,
                                        p2.port_no AS end_port_no,
                                        p2.port_type AS end_port_type,
                                        p2.port_status AS end_port_status 
                                    FROM
                                        (
                                        SELECT
                                            m1.fiber_id AS fiber_id,
                                            m1.port_id AS start_port_id,
                                            m2.port_id AS end_port_id,
                                            m1.joint_type AS start_joint_type,
                                            m2.joint_type AS end_joint_type
                                        FROM
                                            optical_fibers_melt_details AS m1,
                                            optical_fibers_melt_details AS m2 
                                        WHERE
                                            m1.fiber_id = m2.fiber_id 
                                            AND m1.endpoint_type = ? 
                                            AND m2.endpoint_type = ? 
                                        ) AS f
                                        JOIN optical_equip_ports AS p1 ON f.start_port_id = p1.id
                                        JOIN optical_equip_ports AS p2 ON f.end_port_id = p2.id 
                                    ) AS q ON ff.id = q.fiber_id
                                    JOIN optical_equip_profiles AS e1 ON e1.id = q.start_equip_id
                                    JOIN optical_equip_profiles AS e2 ON e2.id = q.end_equip_id', [$cableID, 0, 1]);
        // dump($fiber);die;
        } 
        // 查询所有光缆的基本信息
        else {
            $fiber = Db::query('SELECT
                                    ff.*,
                                    q.*,
                                    e1.location AS start_equip_location,
                                    e1.`name` AS start_equip_name,
                                    e1.type AS start_equip_type,
                                    e1.boxes_quantity AS start_equip_boxes_quantity,
                                    e2.location AS end_equip_location,
                                    e2.`name` AS end_equip_name,
                                    e2.type AS end_equip_type,
                                    e2.boxes_quantity AS end_equip_boxes_quantity
                                FROM
                                    ( SELECT * FROM optical_fibers) AS ff
                                    JOIN (
                                    SELECT
                                        f.fiber_id,
                                        f.start_joint_type,
                                        f.end_joint_type,
                                        p1.id AS start_port_id,
                                        p1.equip_id AS start_equip_id,
                                        p1.box_no AS start_box_no,
                                        p1.plate_no AS start_plate_no,
                                        p1.port_no AS start_port_no,
                                        p1.port_type AS start_port_type,
                                        p1.port_status AS start_port_status,
                                        p2.id AS end_port_id,
                                        p2.equip_id AS end_equip_id,
                                        p2.box_no AS end_box_no,
                                        p2.plate_no AS end_plate_no,
                                        p2.port_no AS end_port_no,
                                        p2.port_type AS end_port_type,
                                        p2.port_status AS end_port_status 
                                    FROM
                                        (
                                        SELECT
                                            m1.fiber_id AS fiber_id,
                                            m1.port_id AS start_port_id,
                                            m2.port_id AS end_port_id,
                                            m1.joint_type AS start_joint_type,
                                            m2.joint_type AS end_joint_type
                                        FROM
                                            optical_fibers_melt_details AS m1,
                                            optical_fibers_melt_details AS m2 
                                        WHERE
                                            m1.fiber_id = m2.fiber_id 
                                            AND m1.endpoint_type = ? 
                                            AND m2.endpoint_type = ? 
                                        ) AS f
                                        JOIN optical_equip_ports AS p1 ON f.start_port_id = p1.id
                                        JOIN optical_equip_ports AS p2 ON f.end_port_id = p2.id 
                                    ) AS q ON ff.id = q.fiber_id
                                    JOIN optical_equip_profiles AS e1 ON e1.id = q.start_equip_id
                                    JOIN optical_equip_profiles AS e2 ON e2.id = q.end_equip_id', [0, 1]);
            // dump($fiber);die;
        }       
         
        return $fiber;
    }

    public function showFiberInfo($id)
    {
         // 查询特定纤芯的基本信息、上架信息
         $fiber = Db::query('SELECT
         * 
     FROM
         (
         SELECT
             * 
         FROM
             optical_fibers 
         WHERE
         id =?) AS ff
         JOIN (
         SELECT
             f.fiber_id,
             f.start_joint_type,
             f.end_joint_type,
             p1.id AS start_port_id,
             p1.equip_id AS start_equip_id,
             p1.box_no AS start_box_no,
             p1.plate_no AS start_plate_no,
             p1.port_no AS start_port_no,
             p1.port_type AS start_port_type,
             p1.port_status AS start_port_status,
             p2.id AS end_port_id,
             p2.equip_id AS end_equip_id,
             p2.box_no AS end_box_no,
             p2.plate_no AS end_plate_no,
             p2.port_no AS end_port_no,
             p2.port_type AS end_port_type,
             p2.port_status AS end_port_status 
         FROM
             (
             SELECT
                 m1.fiber_id AS fiber_id,
                 m1.port_id AS start_port_id,
                 m1.joint_type AS start_joint_type,
                 m2.port_id AS end_port_id,
                 m2.joint_type AS end_joint_type 
             FROM
                 optical_fibers_melt_details AS m1,
                 optical_fibers_melt_details AS m2 
             WHERE
                 m1.fiber_id = m2.fiber_id 
                 AND m1.endpoint_type = ? 
             AND m2.endpoint_type = ?) AS f
             JOIN optical_equip_ports AS p1 ON f.start_port_id = p1.id
         JOIN optical_equip_ports AS p2 ON f.end_port_id = p2.id 
         ) AS q ON ff.id = q.fiber_id',[$id, 0, 1]);
        // dump($fiber);die;
        return $fiber;
    }
    
}