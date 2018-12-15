<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2018/12/15
 * Time: 22:18
 */

//函数功能：记录后台管理信息
//记录管理员的操作
//登录、登出、添加、删除等
//参数$action为操作动作
function w_log($act){
    $filename = "./admin/log.txt";
    $f_open = fopen($filename,"a+");
    $str = $_SESSION['username'].",".date("Y-m-d H:i:s").",".$_SERVER['REMOTE_ADDR'].",".$act."\n";
    fwrite($f_open,$str);
    fclose($f_open);
}

/** 登录验证：验证用户名与密码
 *
*/
function loginCheck($conn, $user, $pwd){
    $flag = false;
    if(!$user || !$pwd){
        return $flag;
    }

    $sqlstr = "select id, username, pwd from users where username ='$user'";
    $result = mysqli_query($conn,$sqlstr);
    $record = mysqli_fetch_assoc($result);

    $flag = false;
    if ($record['username']==$user && $record['pwd']==$pwd) {
        $flag = true;
        session_start();    # 开启session
        $_SESSION["id"] = $record['id'];
        $_SESSION["username"] = $_POST['username'];
        w_log($_POST['action']); # 写入日志
    }
    return $flag;
}