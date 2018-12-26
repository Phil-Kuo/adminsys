<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2018/12/26
 * Time: 21:40
 */

namespace app\admin\model;

/**
 * 角色权限
 */
class AuthAccess extends Base
{
    /**
     * 获取对应用户的权限规则
     */
    public function getRuleVals( $uid )
    {
        $role_id = model('Users')->where(['id'=>$uid])->value('role_id');
        $rule_ids = model('AuthAccess')->where(['role_id'=>$role_id])->column('rule_id');
        return model('AuthRule')->where('id', 'in', $rule_ids)->column('rule_val');
    }

    /**
     * 获取对应用户的规则id
     */
    public function getIds( $uid )
    {
        $role_id = model('Users')->where(['id'=>$uid])->value('role_id');
        return model('AuthAccess')->where(['role_id'=>$role_id])->column('rule_id');
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