<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2019/1/7
 * Time: 19:20
 */

namespace app\admin\model;


class Leave extends Base
{
    /**
     * 提交
     */
    public function saveData($data){
        //        dump($data);
        // 验证输入还未实现
        $data['create_time'] = time();
        // 检查输入时间区间是否存在
        $timeInterv = explode('-', $data['period']);
        $data['start'] = date('Y-m-d H:i',strtotime($timeInterv[0]));
        $data['end'] = date('Y-m-d H:i',strtotime($timeInterv[1]));
        $this->allowField(true)->save($data);
        if($this->id > 0){
            return info(lang('Add succeed'), 1, '', $this->id);
        }else{
            return info(lang('Add failed') ,0);
        }
    }
}