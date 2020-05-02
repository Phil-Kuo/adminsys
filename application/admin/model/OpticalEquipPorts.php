<?php

namespace app\admin\model;

use think\Db;

class OpticalEquipPorts extends Base
{
    public function getPortID($condition)
    {
        $record = $this->get($condition);
        if ($record) {
            return $record->id;
        }
    }

    /* 
    ** 返回特定设备端口的所有相关信息和连接的纤芯信息
    */
    public function getAttachedFibers($equipID)
    {
        $record = Db::query('SELECT
        re.id AS port_id,
        re.equip_id,
        re.box_no,
        re.plate_no,
        re.port_no,
        re.business,
        re.port_type,
        re.port_status,
        m.endpoint_type,
        m.joint_type,
        f.id AS fiber_id,
        f.cable_id,
        f.tube_no,
        f.fiber_no 
    FROM
        (
        SELECT
            * 
        FROM
            optical_equip_ports 
        WHERE
        equip_id = ?) AS re
        JOIN optical_fibers_melt_details AS m ON re.id = m.port_id
        JOIN optical_fibers AS f ON f.id = m.fiber_id', [$equipID]);
        return $record;
    }



    /* 
    ** 返回特定设备的所有端口的对端端口信息
    */
    public function getOppositePorts($equipID)
    {
        $record = Db::query('SELECT
        p.id AS op_port_id,
        p.equip_id AS op_equip_id, 
        p.box_no AS op_box_no,
        p.plate_no AS op_plate_no,
        p.port_no AS op_port_no,
        p.port_type AS op_port_type,
        p.port_status AS op_port_status,
        e.location,
        e.`name`
    FROM
        optical_equip_ports AS p JOIN optical_equip_profiles AS e ON e.id = p.equip_id 
    WHERE
        p.id IN (
        SELECT
            port_id 
        FROM
            (
            SELECT
                f.id AS fiber_id,
                1- m.endpoint_type AS endpoint_type 
            FROM
                ( SELECT * FROM optical_equip_ports WHERE equip_id = ? ) AS re
                JOIN optical_fibers_melt_details AS m ON re.id = m.port_id
                JOIN optical_fibers AS f ON f.id = m.fiber_id #选出所有端口的连接纤芯			
            ) AS a JOIN optical_fibers_melt_details AS m ON a.fiber_id = m.fiber_id AND a.endpoint_type = m.endpoint_type)', [$equipID]); #后反过来选择该端口通过光缆连接的另一端口
        return $record;
    }

    /* 
    ** 获取跳纤所连接的对应端口
    */
    public function getJumperPorts($equipID)
    {
        $record = Db::query('SELECT
        op.id,
        opposite_port1,
        p1.equip_id AS equip_id1,
        p1.box_no AS box_no1,
        p1.plate_no AS plate_no1,
        p1.port_no AS port_no1,
        p1.port_type AS port_type1,
        p1.port_status AS port_status1,
        e1.location AS location1,
        e1.`name` AS name1,
        opposite_port2,
        p2.equip_id AS equip_id2,
        p2.box_no AS box_no2,
        p2.plate_no AS plate_no2,
        p2.port_no AS port_no2,
        p2.port_type AS port_type2,
        p2.port_status AS port_status2,
        e2.location AS location2,
        e2.`name` AS name2
    FROM
        (
        SELECT
            re.id,
            j.a_port_id AS opposite_port1,
            j.b_port_id AS opposite_port2 
        FROM
            ( SELECT * FROM optical_equip_ports WHERE equip_id = ? ) AS re
        LEFT JOIN optical_jumping_fiber_details AS j ON ( re.id = j.a_port_id OR re.id = j.b_port_id )) AS op
        LEFT JOIN optical_equip_ports AS p1 ON p1.id = op.opposite_port1
        LEFT JOIN optical_equip_ports AS p2 ON p2.id = op.opposite_port2
        LEFT JOIN optical_equip_profiles AS e1 ON e1.id = p1.equip_id
        LEFT JOIN optical_equip_profiles AS e2 ON e2.id = p2.equip_id', [$equipID]);
        return $record;
    }

    public function getPortDetailsByIDs($portIDs)
    {
        $record = Db::query('SELECT
                                e.location,
                                e.`name` AS equip_name,
                                e.boxes_quantity,
                                e.type,
                                p.id AS port_id,
                                p.box_no,
                                p.plate_no,
                                p.port_no,
                                p.business,
                                p.port_type,
                                p.port_status
                            FROM
                                `optical_equip_profiles` as e JOIN (
                                SELECT *
                                FROM optical_equip_ports as ep
                                WHERE	ep.id IN  ('.implode(',', $portIDs).') )as p
                                ON e.id = p.equip_id');
        return $record;
    }
}
