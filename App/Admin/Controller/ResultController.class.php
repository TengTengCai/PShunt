<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Admin\Controller;
use Think\Controller;
use Model\TreeModel;
use Model\SCourseModel;
use Model\StudentModel;
/**
 * 选择的结果，选课后的信息查看与统计
 *
 * @author TJ
 */
class ResultController extends Controller{
    var $treeList = null;
    var $treeM;
    var $SCourseM;
    var $StudentM;
    var $temp;
    var $j = 0;
    /**
     * 构造方法初始化Model，避免使用时再次创建
     */
    public function __construct() {
        parent::__construct();
        if(isset($_SESSION['username'])&&$_SESSION['username'] != ''){
            $this->treeM = new TreeModel();
            $this->SCourseM = new SCourseModel();
            $this->StudentM = new StudentModel();
        }  else {
            $this ->redirect('Login/login');
        }
    }
    /**
     * 初始化result.html页面
     */
    function result(){
        $this->display();
    }
    /**
     * 获取专业课程信息
     */
    function getCourseInfo(){
        $profession = $_POST['profession'];
        $j = 0;
        $list = $this->treeM->field("branch_id")->where("name = '%s'",$profession)->select();
        $this->queryTree($list[0]['branch_id']);
        for($i = 0;$i<count($this->treeList);$i++){
            if($this->treeList[$i]['course_id']!=null){
                $temp = $temp."<option cid = ".$this->treeList[$i]['course_id'].">".$this->treeList[$i]['name']."</option>";
            }
        }
        echo $temp;
    }
    /**
     * 递归查询，深度遍历
     * @param int $id
     * @return null
     */
    function queryTree($id){
        $tree = $this->treeM;
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
    /**
     * 查询选择了相关课程的学生
     */
    function getInfo(){
        $cId = $_GET['cId'];
        $list = $this->SCourseM->field("SNumber as si_number")->where("courseID = %d",$cId)->select();
        if ($list){
            $list['_logic'] = 'OR';
            $list2 = $this->StudentM->field("si_number,si_name,si_sex,si_grade,si_profession,si_class")->where($list)->select();
            for($i = 0;$i<count($list2);$i++){
                $this->temp[$i]['number'] = $list2[$i]['si_number'];
                $this->temp[$i]['name'] = $list2[$i]['si_name'];
                $this->temp[$i]['sex'] = $list2[$i]['si_sex'];
                $this->temp[$i]['grade'] = $list2[$i]['si_grade'].$list2[$i]['si_profession'].$list2[$i]['si_class']."班";
            }
            F('data',$this->temp);
            echo json_encode($this->temp);
        }  else {
            echo '';
        }
    }
    /**
     * 获取查询的数据表格
     */
    function getExcel(){
        $Data = F('data');
//        dump($Data);
//        exit();
        if(!$Data){
            $this->error('请查询后再导出数据');
        }
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
        $objWriter->save('php://output');
    }
}
