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
}
