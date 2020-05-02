<?php

namespace app\admin\model;
use think\Db;

class OpticalFibersMeltDetails extends Base
{
    // 查询所有光纤的始末端的上架信息
    public function getMeltDetails()
    {
        $meltDetails = Db::query('SELECT
                                    m1.fiber_id AS fiber_id,
                                    m1.port_id AS start_port_id,
                                    m2.port_id AS end_port_id 
                                FROM
                                    optical_fibers_melt_details AS m1,
                                    optical_fibers_melt_details AS m2 
                                WHERE
                                    m1.fiber_id = m2.fiber_id 
                                    AND m1.endpoint_type = ? 
                                    AND m2.endpoint_type = ?', [0, 1]);
        return $meltDetails;
    }
}