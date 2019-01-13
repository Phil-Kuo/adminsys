<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2018/12/30
 * Time: 12:49
 */

namespace app\admin\model;


class RoleAccess extends Base
{
    /**
     * 获取角色所拥有的权限id
     * @param $role_id 角色ID
     * @return 数组
     */
    public function getRuleId($id){
        return $this->where(['role_id'=>$id])->column('rule_id');
    }

    /**
     * 删除
     */
    public function deleteById($id)
    {
        $result = RoleAccess::destroy(['role_id'=>$id]); // 返回成功删除的记录条数；
        if ($result > 0) {
            return info(lang('Delete succeed'), 1);
        }
    }

    public function getAccess(){
        return $this->hasOne('AuthRule','id','rule_id');
    }

}