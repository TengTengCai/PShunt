<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en" class="no-js">

    <head>

        <meta charset="utf-8">
        <title>登录(Login)</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- CSS -->
        <link rel="stylesheet" href="<?php echo HOME_CSS_URL;?>reset.css">
        <link rel="stylesheet" href="<?php echo HOME_CSS_URL;?>supersized.css">
        <link rel="stylesheet" href="<?php echo HOME_CSS_URL;?>style.css">

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="assets/js/html5.js"></script>
        <![endif]-->

    </head>
    <body>
        <div class="page-container">
            <h1>登录(Login)</h1>
            <form id="login_from" action="/PShunt/index.php/Home/Login/doLogin" method="post">
                <input type="text" id="username" name="username" placeholder="请输入您的学号！">
                <input type="password" id="password" name="password" autocomplete="off" placeholder="请输入您的密码！">
                <input type="text" id="captcha" name="captcha" autocomplete="off" placeholder="请输入验证码！">
                <img id="captchaImg" style="margin-top:20px;" src="/PShunt/index.php/Home/Login/verifyImg"  >
                <div id="error"></div>
            </form>
            <button id="bt_login" class="submit_button">登录</button>
        </div>
        <!-- Javascript -->
        <script src="<?php echo HOME_JS_URL;?>jquery-1.8.2.min.js" ></script>
        <!--<script src="<?php echo HOME_JS_URL;?>jquery-3.1.1.js"></script>-->
        <script src="<?php echo HOME_JS_URL;?>supersized.3.2.7.min.js" ></script>
        <script src="<?php echo HOME_JS_URL;?>supersized-init.js" ></script>
        <script src="<?php echo HOME_JS_URL;?>jquery.md5.js"></script>
        <script>
            function go(){
                var username = $('#username').val(),
                password = $('#password').val(),
                captcha = $('#captcha').val();
                if(username.length===0){
                    $('#error').html("<p>学号不能为空！</p>");
                }else if(password.length===0){
                    $('#error').html("<p>密码不能为空！</p>");
                }else if(captcha.length===0){
                    $('#error').html("<p>验证码不能为空！</p>");
                }else if(username.length>0&&password.length>0&&captcha.length>0){
                    $.post("/PShunt/index.php/Home/Login/doLogin",{
                        username:username,
                        password:$.md5(password),
                        captcha:captcha
                    },function(data){
                        data = eval('('+data+')');
                        if(data.code === 2){
                            $('#error').html("验证码错误");
                            $('#captcha').val("");
                        }else if(data.code === 3){
                            $('#error').html("帐号不存在");
                            $('#username').val("");
                        }else if(data.code === 4){
                            $('#error').html("密码错误");
                            $('#password').val("");
                        }else if(data.code === 5){
                            location.href = "/PShunt/index.php/Home/Login/setPassword";
                            //跳转设置密码
                        }else if(data.code === 1){
                            location.href = "/PShunt/index.php/Home/User/choice";
                        }else if(data.code === 6){
                            location.href = "/PShunt/index.php/Home/User/choiced";
//                            alert("321");
                        }
                        RefreshImg();
                    });
                }
            }
            function RefreshImg(){
                $('#captchaImg').attr("src","/PShunt/index.php/Home/Login/verifyImg?"+Math.random());
//                alert(' asdasda das ');
            }
            $().ready(function(){
                $('#bt_login').bind("click", go);
                $('#captchaImg').bind("click", RefreshImg);
            });
        </script>
    </body>
</html>