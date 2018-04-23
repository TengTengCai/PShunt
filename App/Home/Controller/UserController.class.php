<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Home\Controller;
use Think\Controller;
use Model\StudentModel;
use Model\TreeModel;
use Model\CourseModel;
use Model\SCourseModel;
/**
 * 用户界面
 *
 * @author TJ
 */
class UserController extends Controller{
    var $treeList = null;
    var $j = 0;
    var $SNumber;
    var $profession;
    var $courseM;
    var $treeM;
    var $SCourseM;
    var $isChoice;
    /**
     * 构造方法实例化各项Model，方便使用
     */
    function __construct() {
        parent::__construct();
        $this->courseM = new CourseModel();
        $this->treeM = new TreeModel();
        $this->SCourseM = new SCourseModel();
        if(isset($_SESSION['SNumber'])&&$_SESSION['SNumber'] != ''){
            $this->SNumber = $_SESSION['SNumber'];
            $student = new StudentModel();
            $list = $student->field("si_name,si_grade,si_profession,si_class")
                    ->where("si_number = %d",  $this->SNumber)
                    ->select();
            $this->profession = $list[0]['si_profession'];
            if($this->SCourseM->field("*")->where("SNumber = %d",  $this->SNumber)->select()){
                $this->isChoice = TRUE;
            }  else {
                $this->isChoice = FALSE;
            }
        }  else {
            $this ->redirect('Login/login');
        }
    }
    /**
     * 选择界面
     * @return null
     */
    function choice(){
        if($this->isChoice == TRUE){
            $this ->redirect('User/choiced');
            return;
        }
        $SNumber = $_SESSION['SNumber'];
        $student = new StudentModel();
        $list = $student->field("si_name,si_grade,si_profession,si_class")->where("si_number = %d",$SNumber)->select();
        $this->assign("name",$list[0]['si_name']);
        $this->assign("profession",$list[0]['si_grade'].''.$list[0]['si_profession'].''.$list[0]['si_class'].'班');
        $this->display();
    }
    /**
     * 选择完成界面
     * @return null
     */
    function choiced(){
        if($this->isChoice == FALSE){
            $this->redirect('User/choice');
            return;
        }
        $SNumber = $_SESSION['SNumber'];
        $student = new StudentModel();
        $list = $student->field("si_name,si_grade,si_profession,si_class")->where("si_number = %d",$SNumber)->select();
        $list2 = $this->SCourseM->field("courseID AS c_id")->where("SNumber = %d",$SNumber)->select();
        $list2['_logic'] = 'OR';
        $list3 = $this->courseM->field("*")->where($list2)->select();
        for ($i = 0;$i<count($list3);$i++){
            $data = $this->SCourseM->field("count('SNumber') AS count ")->where("courseID = %d",$list3[$i]['c_id'])->select();
            $temp = $temp."<tr><th scope=\"row\">".($i+1)."</th><td>"
                    .$list3[$i]['c_name']."</td><td>"
                    .$list3[$i]['c_teacher']."</td><td>".$data[0]['count']."</td><td>"
                    .$list3[$i]['c_introduce']."</td></tr>";
        }
        $this->assign("message",  $temp);
        $this->assign("name",$list[0]['si_name']);
        $this->assign("profession",$list[0]['si_grade'].''.$list[0]['si_profession'].''.$list[0]['si_class'].'班');
        $this->display();
    }
    /**
     * 重新选择方法
     */
    function reSetChoice(){
        if($this->SCourseM->where("SNumber = %d",  $this->SNumber)->delete()){
            echo "重置成功";
        }  else {
            echo '重置失败';
        }
    }
    /**
     * 递归查询
     * @param int $id
     * @return null
     */
    function queryTree($id){
        $tree=$this->treeM;
        $list = $tree->field("branch_id AS id,parent_id AS pid,name,course_id,tlevel AS level")
                ->where("parent_id = %d",$id)
                ->select();
        if($list){
            for ($i = 0;$i<count($list);$i++){
                $this->queryTree($list[$i]['id']);
                $this->treeList[$this->j] = $list[$i];
                $this->j++;
//                dump($list[$i]);
            }
        }  else {
            return;
        }
    }
    function getChoice(){
        $tree = $this->treeM;
        $list = $tree->field("branch_id AS id,parent_id AS pid,name,course_id,tlevel AS level")
                ->where("name = '%s'", $this->profession)
                ->select();
//        dump($list);
        $list[0]['pid'] = 0;
        
        $this->treeList[$this->j++] = $list[0];
        $this->queryTree($list[0]['id']);
        echo json_encode($this->treeList);
    }
    /**
     * 获取课程信息
     */
    function getCourse(){
        $courseID = $_POST['c_id'];
        $course = $this->courseM;
        $list = $course->field("c_teacher,c_introduce")->where("c_id = %d",$courseID)->select();
        if ($list){
            $list2 = $this->SCourseM->field("count('SNumber') AS count")->where("courseID = %d",$courseID)->select();
            $data['code'] = 1;
            $data['courseName'] = $list[0]['c_name'];
            $data['teacherName'] = $list[0]['c_teacher'];
            $data['count'] = $list2[0]['count'];
            $data['introduce'] = $list[0]['c_introduce'];
            echo json_encode($data);
        }  else {
            $data = null;
            $data['code'] = 0;
            echo json_encode($data);
        }
    }
    /**
     * 确定选择，设置选择
     * @return int
     */
    function setChoice(){
        $cIds = $_POST['cIds'];
        $array = explode(',', $cIds);
        $list = $this->SCourseM->field('count(ID) as count')
                    ->where("SNumber = %d",$this->SNumber)
                    ->select();
        for ($i = 0;$i<count($array);$i++){
            if($list[0]['count']>=4){
                return 0;
            }  else {
                $data['SNumber'] = $this->SNumber;
                $data['courseID'] = $array[$i];
                $this->SCourseM->data($data)->add();
                echo 1;
            }
        }
//        echo $array[0];
    }
}
