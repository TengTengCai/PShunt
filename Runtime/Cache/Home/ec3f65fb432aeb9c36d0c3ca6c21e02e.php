<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en" class="no-js">
    <head>
        <meta charset="utf-8">
        <title>修改密码</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <!-- CSS -->
        <link rel="stylesheet" href="<?php echo HOME_CSS_URL;?>reset.css">
        <link rel="stylesheet" href="<?php echo HOME_CSS_URL;?>supersized.css">
        <link rel="stylesheet" href="<?php echo HOME_CSS_URL;?>style.css">
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="page-container">
            <h1>请修改您的密码</h1>
            <p>为了您的帐号安全，请重新设置帐号密码<br/>（新密码长度不的小于8位数）</p>
            <p>学号：<?php echo $SNumber;?><br/>姓名：<?php echo $name;?></p>
            <form action="/PShunt/index.php/Home/Login" method="post">
                <input type="password" id="old_password" name="old_password" class="username" placeholder="旧密码">
                <input type="password" id="new_password" name="new_password" class="username" placeholder="新密码">
                <input type="password" id="new_password_again" name="new_password_again" class="password" placeholder="重新输入新密码">
                <div id="error"></div>
            </form>
            <button id="bt_ok" >确认</button>
        </div>
        <!-- Javascript -->
        <script src="<?php echo HOME_JS_URL;?>jquery-1.8.2.min.js" ></script>
        <!--<script src="<?php echo HOME_JS_URL;?>jquery-3.1.1.js"></script>-->
        <script src="<?php echo HOME_JS_URL;?>supersized.3.2.7.min.js" ></script>
        <script src="<?php echo HOME_JS_URL;?>supersized-init.js" ></script>
        <script src="<?php echo HOME_JS_URL;?>jquery.md5.js" type="text/javascript"></script>
        <script>
            function  check(){
                var old_password = $('#old_password').val();
                var new_password = $("#new_password").val();
                var new_password_again = $('#new_password_again').val();
                alert($.md5(old_password));
                
                if(old_password.length == 0 || new_password.length == 0 || new_password_again == 0){
                    $('#error').html("<p>输入不能为空</p>");
                    return;
                }else if(new_password != new_password_again){
                    $('#error').html("<p>两次输入的密码不一致</p>");
                    return;
                }else if(new_password.length < 8 || new_password_again < 8 ){
                    $('#error').html("<p>新密码长度不得小于8位</p>");
                    return;
                }else if(old_password == new_password){
                    $('#error').html("<p>旧密码不得与新密码重复</p>");
                }else{
                    $.post("/PShunt/index.php/Home/Login/doChange",{
                        oldPassword:$.md5(old_password),
                        newPassword:$.md5(new_password)
                    },function(data){
                        data = eval('('+data+')');
                        if(data.code == 1){
                            location.href = "/PShunt/index.php/Home/User/choice";
                        }else if(data.code == 2){
                            $('#error').html("<p>会话异常</p>");
                            alert("会话异常,回到登录页面");
                            location.href = "/PShunt/index.php/Home/Login/login";
                        }else if(data.code == 3){
                            $('#error').html("<p>旧密码错误</p>");
                        }
                    });
                }
            }
            $().ready(function(){
                $('#bt_ok').bind("click",check);
            });
        </script>
    </body>
</html>