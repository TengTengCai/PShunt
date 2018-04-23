<?php
namespace Admin\Controller;
use Think\Controller;
use Model\SCourseModel;
use Model\CourseModel;
use Model\StudentModel;
/**
 * Description of ChoiceController
 *
 * @author Tianjun
 */
class ChoiceController extends Controller{
    var $SCourseM;
    var $CourseM;
    var $number;
    var $StudentM;
    function __construct() {
        parent::__construct();
        $this->SCourseM = new SCourseModel();
        $this->CourseM = new CourseModel();
        $this->StudentM = new StudentModel();
//        if(isset($_SESSION['username'])&&$_SESSION['username'] != ''){
//            
//        }  else {
//            $this ->redirect('Login/login');
//        }
    }
    function Choice(){
        $SNumber = isset($_GET['number'])?$_GET['number']:"";
        $this->number = $SNumber;
        $data = $this->StudentM->field("si_name,si_sex,si_grade,si_profession,si_class,si_ischange")->where("si_number = %d",$SNumber)->select();
        $message = "学号:".$SNumber."&nbsp;&nbsp;&nbsp;&nbsp;姓名："
                .$data[0]['si_name']."&nbsp;&nbsp;&nbsp;&nbsp;性别："
                .$data[0]['si_sex']."&nbsp;&nbsp;&nbsp;&nbsp;班级："
                .$data[0]['si_grade']."级".$data[0]['si_profession'].$data[0]['si_class']."班";
        $this->assign("name", $SNumber);
        $this->assign("table", $this->getCourse());
        $this->assign("message",$message);
        $this->display();
    }
    function getCourse(){
        $SNumber = $this->number;
        if($SNumber){
            $SCourse = $this->SCourseM;
            $data = $SCourse->field("courseID AS c_id")->where("SNumber = %d",$SNumber)->select();
            if (!$data){
                return;
            }
            $data['_logic'] = 'OR';
            $list = $this->CourseM->field("c_id,c_name,c_teacher")->where($data)->select();
            for($i = 0 ; $i<count($list);$i++){
                $count = $SCourse->field("count(`SNumber`) as count")->where("courseID = %d",$list[$i]['c_id'])->select();
                $temp = $temp."<tr><th scope=\"row\">".($i+1)."</th><td>"
                    .$list[$i]['c_name']."</td><td>"
                    .$count[0]['count']."</td><td>".$list[$i]['c_teacher']."</td>";
                    
            }
            return $temp;
        } else {
            echo '错误';
        }
    }
    function clearChoice(){
        $number= isset($_GET['number'])?$_GET['number']:"";
        if ($number){
            $this->SCourseM->where("SNumber = %d",$number)->delete();
            echo '成功';
        } else {
            echo 'error';
        }
//        $this->SCourseM->where("SNumber = %d",$this->number)->delete();
    }
}
