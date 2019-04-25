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
     *  获取查询条件对应的数据。
     * @condition   数组(array)   查询条件（建筑楼、电话号码）
     * */
    public function getTelData($condition){
        $result = $this->where(array_filter($condition)) //data数组为空则查询全部
                        ->order('id')
                        ->select();

        // 尚未解决buildingID与building对应问题。
        if (array_key_exists('tel_number',$condition) && !$condition['tel_number'] && $condition['building']){
            //当查询为某栋建筑名称（无电话号码）直接返回
            return $result;
        }elseif (!$condition || array_key_exists('building',$condition)){
            //当查询条件为空或building为空时递归排序后返回
            return $this->sort($result);
        }
        // 当查询条件为电话号码时，
        // 将数组对象转换为二维数组
        foreach ($result as $k=>$v){
            $array[$k] = $v->toArray();
        }
        return $this->transToTree($array);
        // 当查询条件二者均有时尚未考虑
    }


    /**
     * 利用非递归方法将二维扁平数组转化为树形结构数组
     * @array   二维数组（2-D array）
     * return   嵌套数组，表现为树形结构
     * */
    protected function transToTree($array) {
        $tree = array();
        $map = array();
        foreach ($array as $k=>$item){
            $map[$item['id']] = $k; //初始化映射数组
        }

        $pointer = &$array;
        foreach ($pointer as $item) {
            if ($item['pid']!==0){
                // 后续还需加一个判断，检查索引是否存在。
                $pointer[$map[$item['pid']]]['children'][] = &$pointer[$map[$item['id']]];// &很重要的！！（foreach是复制传值）
            }else{
                $tree[] = &$pointer[$map[$item['id']]];
            }
        }
        return $tree;
    }


    /**
     * 利用递归方法得到排序好的记录
     * @data    待排序数组对象
     * @pid     parentID值
     * @level   对象所处的层次
     * return   排好序的数组对象，且新增'level'字段
     */

    protected function sort($data, $pid=0, $level=0){
        static $sortArray = array();

        foreach($data as $k=>$v){
            if ($v['pid']==$pid){
                $v['level']=$level;
                $sortArray[] = $v;
                $this->sort($data,$v['id'],$level+1);
            }
        }
        return $sortArray;
    }




}