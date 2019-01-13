<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2018/12/26
 * Time: 20:17
 */

namespace app\admin\model;


class AuthRule extends Base
{
    /**
     * 获取所有列表规则
     * */
    public function getList(){
        return $this->order('level')->select();
    }

    /**
     * 取出角色的所有权限
     */
    public function getAccessByRoleID($role_id){
        // 取出role_id角色的所有权限ID
        $map = ['role_id'=>$role_id];
        $rule_id = model('RoleAccess')->where($map)->column('rule_id');

        // 根据rule_id取出所有权限字段
        $record = [];
        if (!empty($rule_id)){
            $record = $this->all($rule_id);
        }
//        dump($record);
        return $record;
    }

    /**
     * 通过ID获取权限
     * @param array 权限ID值
     * @return array 包含模型对象的二维数组（或数据集对象）
     */
    public function getAccessById(array $rule_id=[]){
        $record = [];
        if (!empty($rule_id)){
            $record = $this->all($rule_id);
        }
        return $record;
    }
    /**
     * 保存
     */
    public function saveData($data){
        if(isset($data['rule_val'])) {
            $data['rule_val'] = strtolower($data['rule_val']); // 转化为小写
        }
        $data['pid'] = $this->initParentId( $data['rule_val'] );
        if(isset($data['id']) && !empty($data['id'])) {
            $this->allowField(true)->save($data, ['id' => $data['id']]);
        } else {
            $this->insert($data);
        }
    }

    //是否需要检查节点，如果不存在权限节点数据，则不需要检查
    public function isCheck( $rule_val ){
        $rule_val = strtolower($rule_val);
        $map = ['rule_val'=>$rule_val];
        if($this->where($map)->count()){
            return true;
        }
        return false;
    }

    /**
     * 删除
     */
    public function deleteById($id)
    {
        $result = AuthRule::destroy($id); // 返回成功删除的记录条数；
        if ($result > 0) {
            return info(lang('Delete succeed'), 1);
        }
    }

    /**
     * 初始化父节点
     * */
    public function initParentId( $rule_val ){
        $parentId = 0;
        if( count(explode('/', $rule_val)) <= 2 ) {
            return $parentId;
        }
        $parent_rule_val =  substr($rule_val, 0, strrpos($rule_val, '/'));
        $map = ['rule_val'=>$parent_rule_val];
        $parentId = $this->where($map)->value('id');
        if(empty($parentId)) {
            $parentData = [];
            $parentData['title'] = $this->_fmtTitle( $parent_rule_val );
            $parentData['pid'] = 0;
            $parentData['rule_val'] = $parent_rule_val;
            $parentData['update_time'] = time();
            $parentId = $this->insertGetId($parentData); // 插入数据成功返回添加数据的自增主键
        }
        return $parentId;
    }

    /**
     * 格式化规则名称
     */
    private function _fmtTitle( $parent_rule_val ){
        $ex_tmp = explode('/', $parent_rule_val);
        $title = '';
        if(!empty($ex_tmp)) {
            foreach($ex_tmp as $val) {
                $tmp[] = ucwords($val);
            }
            $title = implode('/', $tmp);
            unset($tmp);
        }
        unset($ex_tmp);
        return $title;
    }

    /**
     * 获取分级权限数据
     */
    public function getLevelData(){
        $data = $this->order('pid asc')->select();
        if( empty($data) ) {
            return $data;
        }

        $ret = [];
        foreach($data as $val) {
            if( $val->pid == 0 ) {
                $ret[$val->id] = ['id'=>$val->id,'title'=>$val->title,'pid'=>$val->pid, 'rule_val'=>$val->rule_val];
            } elseif (isset($ret[$val->pid])) {
                // 看不懂
                $ret[$val->pid]['children'][] = ['id'=>$val->id,'title'=>$val->title,'pid'=>$val->pid, 'rule_val'=>$val->rule_val];
            }
        }
        return $ret;
    }
}