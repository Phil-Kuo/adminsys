<?php

namespace app\admin\model;
use think\Db;

class OpticalJumpingFiberDetails extends Base
{

    /* 
    * 根据传入条件搜索数据库，传回前端。
    * 若传入条件为空，默认传回odf_id = 2的结果。

    */
    public function search($data)
    {
        if (empty($data)) {
            $result = Db::view(['optical_jumping_fiber_details'=>'d'],['id'])
                        ->view(['optical_equip_ports'=>'p'], ['equip_id'=>'in_odf', 'box_no'=>'in_box', 'plate_no'=>'in_plate', 'port_no'=>'in_port'], 'p.id = d.a_port_id')
                        ->view(['optical_equip_ports'=>'l'], ['equip_id'=>'out_odf', 'box_no'=>'out_box', 'plate_no'=>'out_plate', 'port_no'=>'out_port'], 'l.id = d.b_port_id')
                        ->where('in_odf', '2')
                        ->select();
            return $result;
        }
    }
}