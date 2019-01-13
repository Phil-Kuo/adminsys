<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2019/1/7
 * Time: 20:41
 */

namespace app\admin\model;


class Service extends Base
{

    /**
     * 获取数据库记录
     * return array     包含模型对象的二维数组（或数据集对象）
     * */
    public function getList(){
        $data = model('Service')->order('start','desc')->select();
//        dump($data);
        $data = $this->fmtData($data);
        return $data;
    }

    /**
     * 对查询结果进行格式化
     * @param array     $data
     * @return array    格式化后的数据
     */
    private function fmtData( $data )
    {
        if(empty($data) && is_array($data)) {
            return $data;
        }
        foreach ($data as $key => $value) {
            $parIds = json_decode($value['participant']);
//            dump($parIds);
            $result = model('Staffs')->all($parIds);
            $participant = [];
            foreach ($result as $k2 => $rec){
                $participant[$k2] = $rec['name'];
            }
            $data[$key]['participant'] = implode('，',$participant);
//            $data[$key]['start'] = date('Y-M-D',$data[$key]['start']);
        }
        return $data;
    }

    /**
     * 提交
     */
    public function saveData($data){
        //        dump($data);
        // 验证输入还未实现
        $data['create_time'] = time();
        // 应检查输入时间区间是否存在
        $timeInterv = explode('-', $data['interval']);
        $timeInterv[0] = strtotime($timeInterv[0]);
        $timeInterv[1] = strtotime($timeInterv[1]);

        $data['start'] = date('Y-m-d H:i',$timeInterv[0]);
        $data['end'] = date('Y-m-d H:i',$timeInterv[1]);
        // 将数组形式的参与者ID转存为json编码的字符串
        $data['participant'] = json_encode($data['participant']);
//         dump($data);
        $this->allowField(true)->save($data);
        if($this->id > 0){
            return info(lang('Add succeed'), 1, '', $this->id);
        }else{
            return info(lang('Add failed') ,0);
        }
    }
}