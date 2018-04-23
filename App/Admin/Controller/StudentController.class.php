<?php

namespace Admin\Controller;
use Think\Controller;
use \Model\StudentModel;
/*
 * StudentController进行学生的信息查询，可以按专业按班级来查询，同时可以导出查询的excel表格
 */
class StudentController extends Controller{
    /*
     * 构造方法，判断是否存在session会话
     * 存在就进行正常操作，不存在就跳转到登录界面
     */
    function __construct() {
        parent::__construct();
        if(isset($_SESSION['username'])&&$_SESSION['username'] != ''){
        }  else {
            $this ->redirect('Login/login');
        }
    }
    /*
     * 初始化学生信息界面
     */
    function student(){
        if(isset($_SESSION['username'])&&$_SESSION['username'] != ''){
            $students = new StudentModel();
            $sql = 'SELECT DISTINCT `si_grade`,`si_profession`,`si_class` FROM `ps_student` WHERE 1  ';
            $list = $students->query($sql);
            //dump($list);
            for ($i = 0;$i<count($list);$i++){
                $arr[$i] = $list[$i]['si_grade'] ."". $list[$i]['si_profession'] ."". $list[$i]['si_class']."班";
            }
            //dump($arr);
            for($i = 0;$i<count($arr);$i++){
                $str = $str.'<option>'.$arr[$i].'</option> ';
            }
            //动态生成专业选择下拉框
            $this->assign('profession',$str);
            $this ->display();
        }  else {
            $this ->redirect('Login/login');
        }
    }
    /*
     * 获取数据库中所有的学生信息
     */
    function getAllStudentInfo(){
        $students = new StudentModel();
        $sql = 'SELECT `si_number`, `si_name`, `si_sex`, `si_grade`, `si_profession`, `si_class` FROM `ps_student` WHERE 1 ';
        $list = $students->query($sql);
        //将字段进行拼接，减少表列
        for ($i = 0;$i<count($list);$i++){
            $arr[$i]['number'] = $list[$i]['si_number'];
            $arr[$i]['name'] = $list[$i]['si_name'];
            $arr[$i]['sex'] = $list[$i]['si_sex'];
            $arr[$i]['grade'] = $list[$i]['si_grade'] ."". $list[$i]['si_profession'] ."". $list[$i]['si_class']."班";
        }
        //转换为json数据输出显示
        $json = json_encode($arr);
        echo $json;
    }
    /*
     * 条件查询，部分字段可以为空
     * number,name,grade,profession,classs
     * 学号，姓名，年级，专业，班级
     */
    function conditionQuery(){
        //判断是否有number，有就写入map中
        $number = $_GET['number'];
        if($number){
            $map['si_number'] = $number;
        }
        //判断是否有name，有就写入map中
        $name = $_GET['name'];
        if($name){
            $map['si_name'] = $name;
        }
        //判断是否有grade，有就写入map中
        $grade = $_GET['grade'];
        if($grade){
            $map['si_grade'] = $grade;
        }
        //判断是否有prefession，有就写入map中
        $profession = $_GET['profession'];
        if($profession){
            $map['si_profession'] = $profession;
        }
        //判断是否有classs，有就写入map中
        $classs = $_GET['classs'];
        if($classs){
            $map['si_class'] = $classs;
        }
        //将map作为条件进行查询
        $students = M('Student');
        $list = $students->field('si_number,si_name,si_sex,si_grade,si_profession,si_class')
                ->where($map)->select();
        if($list == null){
            //$this->error("");
            $arr[0]['number'] = 'null';
            $arr[0]['name'] = 'null';
            $arr[0]['sex'] = 'null';
            $arr[0]['grade'] = 'null';
        } else {
            for ($i = 0;$i<count($list);$i++){
                $arr[$i]['number'] = $list[$i]['si_number'];
                $arr[$i]['name'] = $list[$i]['si_name'];
                $arr[$i]['sex'] = $list[$i]['si_sex'];
                $arr[$i]['grade'] = $list[$i]['si_grade'] ."". $list[$i]['si_profession'] ."". $list[$i]['si_class']."班";
            }   
        }
        //将查询的数据存于缓存中，便于导出时获取
        F('data',$arr);
        //将数组转换成json数据输出
        $json = json_encode($arr);
        echo $json;
    }
    /*
     * 获取excel表格文件，用户进行下载
     */
    function getExcel(){
        //读取缓存
        $Data = F('data');
        //判断是否有数据
        if(!$Data){
            $this->error('请查询后再导出数据');
        }
        //引入必要的文件
        import("Org.Util.PHPExcel");
        import("Org.Util.PHPExcel.Writer");
        $fileName = date("YmdHis");//文件名
        $phpExcel = new \PHPExcel();
        $objWriter = new \PHPExcel_Writer_Excel5($phpExcel);
        $phpExcel->setActiveSheetIndex(0);
        $phpExcel->getActiveSheet()->setTitle('sheet1');
        $phpExcel->getActiveSheet()->setCellValue('A1', '学号');
        $phpExcel->getActiveSheet()->setCellValue('B1', '姓名');
        $phpExcel->getActiveSheet()->setCellValue('C1', '性别');
        $phpExcel->getActiveSheet()->setCellValue('D1', '年级班级');
        $k=2;
        for ($i=0;$i<count($Data);$i++){
            $phpExcel->getActiveSheet()->setCellValue('A'.$k, $Data[$i]['number']);
            $phpExcel->getActiveSheet()->setCellValue('B'.$k, $Data[$i]['name']);
            $phpExcel->getActiveSheet()->setCellValue('C'.$k, $Data[$i]['sex']);
            $phpExcel->getActiveSheet()->setCellValue('D'.$k, $Data[$i]['grade']);
            $k++;
        }
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");;
        header('Content-Disposition:attachment;filename='.$fileName.'.xls');
        header("Content-Transfer-Encoding:binary");
       // $objWriter->save('//APP//Admin//Public//temp'.$fileName.'.xls');
//        $objWriter->save('php://APP//Admin//Public//temp//output');
//        $objWriter->save($fileName.'.xls');
        $objWriter->save('php://output');//输出文件
    }
}


