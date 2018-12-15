<?php
include "conn/conn.php";

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title><{$title}></title>
    <link href="/public/css/bootstrap.min.css" rel="stylesheet">
    <script src="/public/js/jquery.min.js"></script>
    <script src="/public/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">

    <div class="row">
        <h2 class="text-center"><{$title}></h2>
        <hr>
        <ol class="breadcrumb">
            <li><a href="">主页</a></li>
            <li class="active">登录管理平台</li>
        </ol>
    </div>
    <div class="row">
        <br />
        <div class="col-md-4"></div>
        <div class="col-md-5">
            <form class="form-horizontal" action="./index_ok.php" method="POST">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-3 control-label">用户名</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="inputEmail3" name="username" placeholder="请输入用户名...">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-3 control-label">密码</label>
                    <div class="col-sm-6">
                        <input type="password" class="form-control" id="inputPassword3" name="password" placeholder="请输入密码...">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-6">
                        <button type="submit" class="btn btn-default">登录</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
