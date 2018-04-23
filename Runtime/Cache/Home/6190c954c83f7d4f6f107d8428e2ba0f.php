<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>选课系统</title>

<link href="<?php echo HOME_CSS_URL;?>bootstrap.min.css" rel="stylesheet">
<link href="<?php echo HOME_CSS_URL;?>datepicker3.css" rel="stylesheet">
<link href="<?php echo HOME_CSS_URL;?>styles.css" rel="stylesheet">
<link href="<?php echo HOME_CSS_URL;?>zTreeStyle.css" rel="stylesheet" type="text/css"/>

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
				<a class="navbar-brand" href="#">选课系统</a>
				<ul class="user-menu">
					<li class="dropdown pull-right">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span><?php echo $name;?>  <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="#"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div><!-- /.container-fluid -->
	</nav>
		
	<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
		<ul class="nav menu">
			<li class="active"><a href="/PShunt/index.php/Home/User/choiced"><span class="glyphicon glyphicon-dashboard"></span> 专业方向选择</a></li>
		</ul>
	</div><!--/.sidebar-->
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
            <div class="row">
                    <ol class="breadcrumb">
                            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
                            <li class="active">专业方向模块选择</li>
                            <li><?php echo $profession; ?></li>
                    </ol>
            </div><!--/.row-->

            <div class="row">
                <div><p>你当前选择的课程</p></div>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>课程名称</th>
                            <th>教师</th>
                            <th>当前人数</th>
                            <th>课程介绍</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $message;?>
                    </tbody>
                </table>
            </div><!--/.row-->
	</div>	<!--/.main-->

	<script src="<?php echo HOME_JS_URL;?>jquery-3.1.1.js"></script>
	<script src="<?php echo HOME_JS_URL;?>bootstrap.min.js"></script>
	<script src="<?php echo HOME_JS_URL;?>bootstrap-table.js"></script>
        <script src="<?php echo HOME_JS_URL;?>jquery.ztree.all.min.js"></script>
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
</body>

</html>