<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Admin\Controller;
use Think\Controller;
use Model\TreeModel;
use Model\CourseModel;

/**
 * Description of ProjectController
 * 进行课程的管理
 * @author TJ
 * 
 */
class ProjectController extends Controller {
    /*
     * 构造方法进行判断session是否存在
     * 存在就进行正常操作，不存在就跳转到登录界面
     */
    public function __construct() {
        parent::__construct();
        if(isset($_SESSION['username'])&&$_SESSION['username'] != ''){
        }  else {
            $this ->redirect('Login/login');
        }
    }
    /*
     * 初始化课程管理界面
     */
    function project(){
        $tree = new TreeModel();
//        $list = $tree->field('max(`branch_id`)')->select();
        $sql = "select max(`branch_id`) as maxid from `ps_tree";
        $list = $tree->query($sql);
//        dump($list);
        $this->assign('maxId', $list[0]['maxid']+1);
        $this->display();
    }
    /**
     * 为树形结构进行添加课程信息的方法
     * @param int $tId 树型结构中的结点id
     * @param int $course_id 课程的id
     * @param string $courseName 课程的名称
     * @param string $teacherName 教师姓名
     * @param string $introduce 课程介绍
     */
    function addCourse(){
        $tId = $_POST['tId'];
        $course_id = $_POST['course_id'];
        $courseName = $_POST['courseName'];
        $teacherName = $_POST['teacherName'];
        $introduce = $_POST['introduce'];
        $course = new CourseModel();
        $tree = new TreeModel();
//        echo $course_id;
        //判断之前是否存在有课程的id，有就进行修改操作，没有就进行写入操作
        if($course_id){
            echo 'course_id不为空';
            $data3['c_teacher'] = $teacherName;
            $data3['c_introduce'] = $introduce;
            $course->data($data3)->where("course_id = '%s'",$course_id)->select();
        }  else {
            //教师，课程是否同时重名
            $list = $course->field('c_id')->where("c_teacher = '%s' AND c_name = '%s'",$teacherName,$courseName)->select();
//            dump($list);
            if($list){
                //修改树形结构相关结点的courseId
                $data1['course_id'] = $list[0]['c_id'];
                $tree->data($data1)->where("name = '%s'",$courseName)->save();
                echo '信息添加成功';
            }  else {
                //添加新的课程进course表单，同时在tree中添加节点
                $data2['c_name'] = $courseName;
                $data2['c_teacher'] = $teacherName;
                $data2['c_introduce'] = $_POST['introduce'];
                $course->add($data2);
                $list = $course->field('c_id')->where("c_teacher = '%s' AND c_name = '%s'",$teacherName,$courseName)->select();
//                dump($list);
                $data4['course_id'] = $list[0]['c_id'];
//                dump($data4);
                $tree->data($data4)->where("branch_id = %d",$tId)->save();
                echo '新的课程信息添加成功';
            }
        }
    }
    /*
     * 修改course中的数据
     */
    function setCourse(){
        $c_id = $_POST['course_id'];
        $data['c_teacher'] = $_POST['teacherName'];
        $data['c_introduce'] = $_POST['introduce'];
        $course = new CourseModel();
        if($course->data($data)->where("c_id = %d",$c_id)->save()){
            echo '修改成功';
        }else{
            echo '修改失败';
        }
    }
    /**
     * 获取Tree表单，然后输出json数据
     */
    function getTree(){
        $tree = new TreeModel();
        $list = $tree->field('branch_id as id,parent_id as pId,name,course_id')->select();
        //dump($list);
        for($i = 0;$i<count($list);$i++){
            if(!$tree->field('branch_id')->where('parent_id='.$list[$i]['id'])->select()){
                if(!$list[$i]['course_id']){
                    $list[$i]['name'] = "<font color='red'>".$list[$i]['name']."</font>";
                }
            }
        }
        $json = json_encode($list);
        echo $json;
    }
    /**
     * 设置课程的名称和结点名称
     */
    function setName(){
        $id = $_GET['id'];
        $courseId = $_GET['course_id'];
        $newName = $_GET['newName'];
        $data['name'] = $newName;
        $tree = new TreeModel();
        $tree->data($data)->where('branch_id = '.$id)->save();
        if($courseId){
            $course = new CourseModel();
            $data1['c_name'] = $newName;
            $course->data($data1)->where("c_id = %d",$courseId)->save();
        }
        echo '修改名字成功';
    }
    /**
     * 添加树形结构的节点
     */
    function addBranch(){
        $data['parent_id'] = $_GET['pId'];
        $data['name'] = $_GET['name'];
        $data['tlevel'] = $_GET['level']+1;
        $tree = new TreeModel();
        $list = $tree->field('max(branch_id)')->select();
        $data['branch_id'] = $list[0][0];
        if($tree->add($data)){
            echo '添加成功';
        }else{
            echo '添加失败';
        }
    }
    /**
     * 删除树形结构的节点和course表中的课程数据
     */
    function removeBranch(){
        $id = $_GET['id'];
        $courseId = $_GET['course_id'];
        $tree = new TreeModel();
        if($tree->where('branch_id ='.$id)->delete()){
            $course = new CourseModel();
            $course->where("c_id = %d",$courseId)->delete();
            echo '删除成功';
        }else{
            echo '删除失败';
        }
    }
    /**
     * 查询课程的信息
     */
    function selectClass(){
        $data['c_id'] = $_GET['c_id'];
        $course = new CourseModel();
        $list = $course->field('c_name,c_teacher,c_introduce')->where($data)->select();
//        dump($list);
        if($list){
            $map['teacher'] = $list[0]['c_teacher'];
            $map['introduce'] = $list[0]['c_introduce'];
            echo json_encode($map);
//            echo '老师：'.$list[0]['c_teacher'].'，';
//            echo '课程介绍：'.$list[0]['c_introduce'];
        }
    }
} 