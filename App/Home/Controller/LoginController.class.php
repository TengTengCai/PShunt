<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Home\Controller;
use Think\Controller;
use Model\StudentModel;
use Model\SCourseModel;
use Think\Verify;

/**
 * 登录模块
 *
 * @author TJ
 */
class LoginController extends Controller{
    var $SCourse;
    function __construct() {
        parent::__construct();
        $this->SCourse = new SCourseModel();
    }
    /**
     * 初始化
     */
    function login(){
        $this->display();
    }
    /**
     * 验证码的生成
     */
    function verifyImg() {
        $cfg = array(
            'fontSize'  =>  20,              // 验证码字体大小(px)
            'imageH'    =>  50,               // 验证码图片高度
            'imageW'    =>  150,               // 验证码图片宽度 
            'length'    =>  4,               // 验证码位数
            'fontttf'   =>  '1.ttf',              // 验证码字体，不设置随机获取
        );
        $very = new Verify($cfg);
        $very -> entry();
    }
    /**
     * 登录行为
     */
    function doLogin(){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $captcha = $_POST['captcha'];
        $student = new StudentModel();
        $vry = new Verify();
        if($vry ->check($captcha)){
            $list = $student->field("*")->where("si_number=%d",$username)->select();
//            dump($list);
            if($list){
                if($password === $list[0]['si_password']){
                    $list[0]['si_password'] = "";
                    session('SNumber',$list[0]['si_number']);
                    $isChange = $list[0]['si_ischange'];
                    if($isChange==0){
                        $data['code'] = 5;
                        echo json_encode($data);
                    }  else {
                        if($this->SCourse->field('*')->where("SNumber = %d",$username)->select()){
                            $data['code'] = 6;
                            echo json_encode($data);
                        }  else {
                            $data['code'] = 1;
                            echo json_encode($data);
                        }
                    }
                }  else {
                    $data['code'] = 4;
                    echo json_encode($data);
                }
            }  else {
                $data['code'] = 3;
                echo json_encode($data);
            }
        } else {
            $data['code'] = 2;
            echo json_encode($data);
        }
    }
    /**
     * 判断是否重新设置密码
     */
    function setPassword(){
        if(isset($_SESSION['SNumber'])&&$_SESSION['SNumber'] != ''){
            $SNumber = $_SESSION['SNumber'];
            $student = new StudentModel();
            $list = $student->field("si_name,si_ischange")->where("si_number = %d",$SNumber)->select();
            if($list[0]['si_ischange'] == 0){
                $this->assign("SNumber", $SNumber);
                $this->assign("name",$list[0]['si_name']);
                $this -> display();
            }  else {
                $this -> redirect('Login/login');
                session(null);
            }
        }  else {
            $this ->redirect('Login/login');
        }
    }
    /**
     * 重设密码的行为
     */
    function doChange(){
        $oldPsaaword = $_POST['oldPassword'];
        $newPassword = $_POST['newPassword'];
        if(isset($_SESSION['SNumber'])&&$_SESSION['SNumber'] != ''){
            $SNumber = $_SESSION['SNumber'];
            $student = new StudentModel();
            $list = $student ->field("*") ->where("si_number = %d AND si_password = '%s' ",$SNumber,$oldPsaaword)->select();
            if($list){
                $data['si_password'] = $newPassword;
                $data['si_ischange'] = 1;
                $student->data($data)->where("si_number = %d",$SNumber)->save();
                $msg['code'] = 1;
                echo json_encode($msg);
            }  else {
                $msg['code'] = 3;
                echo json_encode($msg);
            }
        }  else {
            $msg['code'] = 2;
            echo json_encode($msg);
        }
        
    }
}
