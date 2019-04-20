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
        $res = $this->where('tel_number', $data['tel_number'])->value('id');
        if ($data['pid']=='0' && $res){
            $info = info(lang('信息有误，请重试！'), 0);
        }else{
            $lastId = $this->where(['building'=>$data['pid'], 'tel_number'=>$data['tel_number']])->value('id');
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

    public function getTree($data){
        $res = $this->where(array_filter($data))->select();
//        dump($res);die;
        return $this->sort($res);

    }

    public function sort($res, $pid=0, $level=0){
        static $arr = array();
        foreach($res as $k=>$v){
            if ($v['pid']==$pid){
                $v['level']=$level;
                $arr[] = $v;
                $this->sort($res, $v['id'],$level+1);
            }
        }

        return $arr;
    }
}