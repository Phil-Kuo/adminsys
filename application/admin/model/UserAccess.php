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
     * 获取用户所拥有的权限
     * return 数组
     */
    public function getRuleVals($uid){
        $role_id  = model('UserRole')->getRoleId($uid);
        $rule_ids = model('RoleAccess')->getRuleId($role_id);
        $rules = model('AuthRule')->where('id', 'in', $rule_ids)->column('rule_val');
        return $rules;
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