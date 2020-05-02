<?php

namespace app\admin\model;
use think\Db;

class OpticalEquipProfiles extends Base
{

   public function getEquipID($condition)
   {
       $record = $this->get($condition);
       if ($record) {
           return $record->id;
       }       
   }
}