<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Lumino - Dashboard</title>

<link href="<?php echo ADMIN_CSS_URL;?>bootstrap.min.css" rel="stylesheet"/>
<link href="<?php echo ADMIN_CSS_URL;?>bootstrap-table.css" rel="stylesheet"/>
<link href="<?php echo ADMIN_CSS_URL;?>datepicker3.css" rel="stylesheet"/>
<link href="<?php echo ADMIN_CSS_URL;?>styles.css" rel="stylesheet"/>
<link href="<?php echo ADMIN_CSS_URL;?>zTreeStyle.css" rel="stylesheet" type="text/css"/>

<!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->

</head>

<body>
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#"><span>Class</span>Admin</a>
				<ul class="user-menu">
					<li class="dropdown pull-right">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> Admin <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="#"><span class="glyphicon glyphicon-user"></span> Profile</a></li>
							<li><a href="#"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
							<li><a href="#"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div><!-- /.container-fluid -->
	</nav>
		
	<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
		<ul class="nav menu">
			<li><a href="/PShunt/index.php/Admin/Student/student"><span class="glyphicon glyphicon-dashboard"></span>学生信息</a></li>
			<li><a href="/PShunt/index.php/Admin/Project/project"><span class="glyphicon glyphicon-th"></span>课程信息</a></li>
			<li class="active"><a href="/PShunt/index.php/Admin/Result/Result/result"><span class="glyphicon glyphicon-stats"></span>选课情况</a></li>
			<li><a href=""><span class="glyphicon glyphicon-list-alt"></span> Tables</a></li>
			<li><a href=""><span class="glyphicon glyphicon-pencil"></span> Forms</a></li>
			<li role="presentation" class="divider"></li>
		</ul>
	</div><!--/.sidebar-->
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
            <div class="row">
                    <ol class="breadcrumb">
                            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
                            <li class="active">选课情况</li>
                    </ol>
            </div><!--/.row-->
            
            <div class="row">
                <div class="select_button">
                    <label>专业</label>
                    <select id="select_profession1" class="form-control"> 
                        <option>计算机科学与技术</option> 
                        <option>物联网工程</option> 
                        <option>数学与应用数学</option> 
                    </select>
                </div>
                <div class="select_button">
                    <label>课程名称</label>
                    <select id="select_course" class="form-control"></select>
                </div>
                <button id="bt_select" class="btn btn-info">查询</button>
                <button id="bt_output" class="btn btn-info">导出</button>
            </div><!--/.row-->
            <table data-toggle="table"  id="stuTable" >
                    <thead>
                        <tr>
                            <th data-field="number"  data-sortable="true">学号</th>
                            <th data-field="name" data-sortable="true">姓名</th>
                            <th data-field="sex" data-sortable="true">性别</th>
                            <th data-field="grade" data-sortable="true">年级专业班级</th>
                        </tr>
                    </thead>
                </table>
	</div>	<!--/.main-->

	<script src="<?php echo ADMIN_JS_URL;?>jquery-3.1.1.js"></script>
        <script src="<?php echo ADMIN_JS_URL;?>jquery.ztree.all.min.js"></script>
        <script src="<?php echo ADMIN_JS_URL;?>bootstrap.min.js"></script>
        <script src="<?php echo ADMIN_JS_URL;?>bootstrap-table.js"></script>
	<script>
		$('#calendar').datepicker({
		});
		!function ($) {
		    $(document).on("click","ul.nav li.parent > a > span.icon", function(){          
		        $(this).find('em:first').toggleClass("glyphicon-minus");      
		    }); 
		    $(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
		}(window.jQuery);

		$(window).on('resize', function () {
		  if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
		})
		$(window).on('resize', function () {
		  if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
		})
	</script>
        <script>
            function initTable() { 
                $('#stuTable').bootstrapTable({
                    method: 'get',
                    url:'/PShunt/index.php/Admin/Student/getAllStudentInfo',
                    exportDataType: "basic",            //basic', 'all', 'selected'.
                    striped: true,  //表格显示条纹
                    toolbar: '#toolbar',
                    pagination: true,
                    dataType: 'json',
                    showRefresh:true,
                    search:true,
                    silent: true,
                    sortable: true,                     //是否启用排序
                    sortOrder: "asc",                   //排序方式
                    uniqueId: "number",                 //每一行的唯一标识，一般为主键列
                    pageSize: 20,                       //每页的记录行数（*）
                    pageList: [20, 50, 100, 200],       //可供选择的每页的行数（*）
                    columns: [{
                        field:'number',
                        title:'学号',
                        sortable: true
                    },{
                        field:'name',
                        title:'姓名'
                    },{
                        field:'sex',
                        title:'性别'
                    },{
                        field:'grade',
                        title:'年级专业班级'
                    }]
                });
                
            }
            window.onload = initTable(); 
            function getCourseName(){
//                alert("data");
                var profession = $('#select_profession1').val();
                $.post("/PShunt/index.php/Admin/Result/getCourseInfo",{
                    profession:profession
                },function(data){
//                    alert(data);
                    $('#select_course').html(data);
                });
            }
            function getInfo(){
                var couser = $('#select_course option:selected').attr("cid");
//                $.post("/PShunt/index.php/Admin/Result/getInfo",{
//                    cId:couser
//                },function(data){
//                    alert(data);
//                    eval('(' + data + ')');
//                    $('#stuTable').bootstrapTable('removeAll');
//                    $('#stuTable').bootstrapTable('load',data);
//                });
                $('#stuTable').bootstrapTable('removeAll');
                $('#stuTable').bootstrapTable(
                    'refresh',{
                    url: "/PShunt/index.php/Admin/Result/getInfo?cId="+couser, //重设数据来源
                    }
                );
            }
            function outPut(){
                window.open('/PShunt/index.php/Admin/Result/getExcel'); 
            }
            $().ready(function(){
                $('#select_profession1').bind("click",getCourseName);
                $('#bt_select').bind("click",getInfo);
                $('#bt_output').bind("click",outPut);
            });
        </script>
</body>

</html>