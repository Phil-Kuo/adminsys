<?php
include "conn/conn.php";
include "public/func.php";

$isUser = loginCheck($conn, $_POST['username'], $_POST['password']);

if($isUser == true){
    echo "<script>alert('欢迎光临');location='pub_main.php';</script>";
}
else{
    echo "<script>alert('用户名或密码错误');history.go(-1);</script>";
}
?>