<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>学生信息</title>

<link href="<?php echo ADMIN_CSS_URL;?>bootstrap.min.css" rel="stylesheet">
<link href="<?php echo ADMIN_CSS_URL;?>bootstrap-table.css" rel="stylesheet">
<link href="<?php echo ADMIN_CSS_URL;?>styles.css" rel="stylesheet">


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
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> User <span class="caret"></span></a>
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
			<li class="active"><a href="/PShunt/index.php/Admin/Student/student"><span class="glyphicon glyphicon-dashboard"></span>学生信息</a></li>
			<li><a href="/PShunt/index.php/Admin/Project/project"><span class="glyphicon glyphicon-th"></span>课程信息</a></li>
			<li><a href="/PShunt/index.php/Admin/Result/result"><span class="glyphicon glyphicon-stats"></span>选课情况</a></li>
			<li><a href="tables.html"><span class="glyphicon glyphicon-list-alt"></span> Tables</a></li>
			<li><a href="forms.html"><span class="glyphicon glyphicon-pencil"></span> Forms</a></li>
                        <li role="presentation" class="divider"></li>
		</ul>
	</div><!--/.sidebar-->
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
				<li class="active">学生信息</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">学生信息</h1>
			</div>
		</div><!--/.row-->
                <div id="toolbar1" align="left">
                    <input id="input_number" type="text" class="form-control" placeholder="学号">
                    <input id="input_name" type="text" class="form-control" placeholder="姓名">
                    <select id="input_profession" class="form-control"><?php echo $profession;?> </select>
                    <button class="btn btn-info" id="bt_select">查询</button>
                    <button class="btn btn-info" id="bt_reset">重置</button>
                    <button class="btn btn-info" id="bt_output">导出</button>
                </div>
                <div class="toolbar"></div>
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
                    showExport: true,                   //是否显示导出
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
                    }],
                    onClickRow:function(row,tr){
//                        alert(row.number);
                        window.open("/PShunt/index.php/Admin/Choice/choice?number="+row.number,'_blank'
                        ,'width=555,height=555,menubar=no,toolbar=no, status=no,scrollbars=yes');
                    }
                });
                
            }
            window.onload = initTable(); 
            $('#bt_select').click(
                function (){
                    // $('#cusTable').bootstrapTable('destroy');
                    var number = $('#input_number').val();
                    var name = $('#input_name').val();
                    var temp = $('#input_profession').val();
                    var garde = temp.slice(0,4);
                    var profession = temp.slice(4,temp.length-2);
                    var classs = temp.slice(temp.length-2,temp.length-1);
//                    alert(number+" "+name+" "+garde+" "+profession+" "+classs);
                    $('#stuTable').bootstrapTable(
                         'refresh',{
                        url: "/PShunt/index.php/Admin/Student/conditionQuery?number="+number+"&name="
                        +name+"&grade="+garde+"&profession="+profession+"&classs="+classs //重设数据来源
                        });
                }
            );
            $('#bt_reset').click(
                function (){
                    $('#stuTable').bootstrapTable(
                         'refresh',{
                        url: "/PShunt/index.php/Admin/Student/getAllStudentInfo" //重设数据来源
                        });
                    $('#input_number').val("");
                    $('#input_name').val("");
                    $('#input_profession').val("");
                }
            );
            $('#bt_output').click(
                    function(){
                        window.open('/PShunt/index.php/Admin/Student/getExcel'); 
                    }
            );
        </script>
        
</body>

</html>