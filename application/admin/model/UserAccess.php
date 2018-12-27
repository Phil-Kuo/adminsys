<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2018/12/26
 * Time: 21:40
 */

namespace app\admin\model;

/**
 * 获取用户所拥有的权限
 */
class UserAccess extends Base
{
    /**
     * 获取用户的所有角色
     * param $uid 用户ID
     */
    public function getRoleIds( $uid )
    {
        $role_ids = model('UserRole')->where(['uid'=>$uid])->value('role_id');
        return $role_ids;
    }

    /**
     * 获取角色所拥有的权限规则
     * param $role_id 角色ID
     */
    public function getRuleVals( $role_id )
    {
        $rule_ids = model('RoleAccess')->where(['role_id'=>$role_id])->column('rule_id');
        return model('AuthRule')->where('id', 'in', $rule_ids)->column('rule_val');
    }

    /**
     * 获取用户所拥有的权限ID
     */
    public function getRuleIds($uid){
        $role_ids  = $this->getRoleIds($uid);
        $rule_ids = model('RoleAccess')->where(['role_id'=>$role_ids])->column('rule_id');
        return $rule_ids;
    }

    /**
     * 获取用户所拥有的权限规则
     */
    public function getAccess( $uid )
    {
        $role_ids  = $this->getRoleIds($uid);
        $rule_vals = $this->getRuleVals($role_ids);
        return $rule_vals;
    }

    public function saveData( $role_id, $data )
    {
        if(empty($data)) {
            return info(lang('Save success'), 1);
        }
        Db::startTrans(); // 启动事务
        try{
            $this->where(['role_id'=>$role_id])->delete();
            $insertData = [];
            foreach($data as $val) {
                $insertData[] = ['role_id'=>$role_id, 'rule_id'=>$val];
            }
            $this->insertAll( $insertData );
            Db::commit();
        }catch (\Exception $e) {
            Db::rollback();
        }
        return info(lang('Save success'), 1);
    }
}