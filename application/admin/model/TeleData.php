<?php
/**
 * Created by PhpStorm.
 * User: Daly Dai
 * Date: 2019/4/13
 * Time: 23:17
 */

namespace app\admin\model;


class TeleData extends Base
{
    /**
     * 提交电话资料到数据库
     */
    public function add($data){
        $res = $this->where('tel_number', $data['tel_number'])->value('id');//查询该号码是否已存在pid=0的记录

        if ($data['pid']=='0' && $res){
            $info = info(lang('信息有误，请重试！'), 0);
        }else{
            $lastId = $this->where(['building_id'=>$data['pid'], 'tel_number'=>$data['tel_number']])->value('id');
            $data['pid'] = $lastId ? $lastId:0;
            $id = $this->allowField(true)->save( $data );

            if( false === $id) {
                $info = info(lang('添加失败，请重试！'), 0);
            } else {
                $info = info(lang('添加成功!'), 1, '', $id);
            }
        }
        return $info;
    }   

    /**
     *  获取记录结果的分级结构
     * */
    public function getTree($condition){
        $result = $this->where(array_filter($condition))->select(); //data数组为空则查询全部

        /**
         *  SELECT tele_data.id, tele_data.tel_number, b.arch_name AS building_name, c.arch_name AS location_name
        FROM tele_data
        LEFT JOIN architecture_details c ON tele_data.location_id = c.id
        LEFT JOIN architecture_details b ON tele_data.building_id = b.id;
         */

        if (array_key_exists('tel_number',$condition) && !$condition['tel_number'] && $condition['building']){//查询某栋建筑的资料
            return $result;
        }
        return $this->sort($result);

    }


    /**
     * 对记录进行递归得到排好序的结果*/
    protected function sort($data, $pid=0, $level=0){
        static $sortArray = array();
        foreach($data as $k=>$v){
            if ($v['pid']==$pid){
                $v['level']=$level;
                $sortArray[] = $v;
                $this->sort($data, $v['id'],$level+1);
            }
        }
        return $sortArray;
    }


}