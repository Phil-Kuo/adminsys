<?php
    $conn = mysqli_connect("127.0.0.1","root","bingo","adminsys") or die("数据库连接失败".mysqli_error());
    mysqli_query($conn,"set names utf8");
?>
