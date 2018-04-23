<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Admin\Controller;
use Think\Controller;
use Model\MangerModel;

/**
 * Description of LoginController
 *
 * @author Administrator
 */
class LoginController extends Controller{
    //put your code here
    //控制器显示login.html页面
    function login(){
        $this ->display();
    }
    /*
     * 进行登录的方法
     * 获取用户名密码，通过数据库进行验证，然后创建session
     */
    function doLogin(){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $admin = new MangerModel();
        $where['m_name'] = $username;
        $where['m_password'] = $password;
        $arr = $admin->field('m_id') ->where($where)->find();
        if($arr>0){
            $_SESSION['username'] = $username;
            $this->success('用户登录成功', U('Student/student'));
        }else{
            $this->error('用户名或密码错误');
        }
    }
}
