<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2018/12/26
 * Time: 20:00
 */

namespace app\admin\controller;


class AuthRule extends Base
{
    /**
     * 规则列表
     */
    public function index(){
        return view();
    }

    /**
     * 添加规则
     * */
    public function add(){
        return view();
    }

    /**
    * 设置权限
    */
    public function setAuth(){
        $levelData = model('AuthRule')->getLevelData();
        $this->assign('data', $levelData);
        $ids = model('AuthAccess')->getIds($this->uid); // getIds
        $this->assign('rule_ids', $ids);
        return view();
    }

    /**
     * 编辑规则
     */
    public function edit($id=''){
        $data = model('AuthRule')->get(['id'=>$id]);
        $this->assign('data',$data);
        return view();
    }

    /**
     * 保存数据
     * */
    public function saveData(){
        $this->mustCheckRule('admin/authrule/edit'); // 检查权限
        if(!request()->isAjax()) {
            return info(lang('Request type error'));
        }
        $data = input('post.');
        model('AuthRule')->saveData($data);
        $this->success(lang('Save success'));

    }

    /**
     * 删除
     */
    public function delete($id = 0){
        if(empty($id)){
            return info(lang('Data ID exception'), 0);
        }
        return model('AuthRule')->deleteById($id);
    }

    /**
     * 保存权限
     * */
    public function saveAuthAccess()
    {
        if(!request()->isAjax()) {
            return info(lang('Request type error'));
        }
        $post_data = input('post.');
        $data = isset($post_data['authrule'])?$post_data['authrule']:[];
        $res = model('AuthAccess')->saveData($this->role_id, $data);
        if ($res['code'] == 1) {
            return $this->success();
        }
    }
}