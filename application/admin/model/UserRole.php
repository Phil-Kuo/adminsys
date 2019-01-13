<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2018/12/29
 * Time: 23:34
 */

namespace app\admin\model;


class UserRole extends Base
{
    /**
     * 获取用户拥有的角色，目前只考虑了一一对应，不清楚一对多是否可行
     * param $uid 用户ID
     * return 数组
     */
    public function getRoleId( $uid )
    {
        $role_id = $this->where(['uid'=>$uid])->value('role_id');
        return $role_id;
    }
}