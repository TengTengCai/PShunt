<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>选课系统 - 课程信息</title>

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
			<li class="active"><a href="/PShunt/index.php/Admin/Project/project"><span class="glyphicon glyphicon-th"></span>课程信息</a></li>
			<li><a href="/PShunt/index.php/Admin/Result/result"><span class="glyphicon glyphicon-stats"></span>选课情况</a></li>
			<li><a href="tables.html"><span class="glyphicon glyphicon-list-alt"></span> Tables</a></li>
			<li><a href="forms.html"><span class="glyphicon glyphicon-pencil"></span> Forms</a></li>
			<li role="presentation" class="divider"></li>
		</ul>
        </div>
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">		
            <div class="row">
                    <ol class="breadcrumb">
                            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
                            <li class="active">课程信息</li>
                    </ol>
            </div><!--/.row-->
            <div class="ztree_wrap">
                <ul id="tree" class="ztree"></ul>
            </div>
            <div class="ztree_right">
                <button id="bt_add" class="btn btn-info" >添加节点</button>
                <button id="bt_remove" class="btn btn-info">删除节点</button>
                <div>
                    <label>为选中叶子节点添加课程信息</label>
                    <input type="text" class="form-control" id="input_course_name" placeholder="课程名称"></input>
                    <textarea type="text" class="form-control" id="input_course_introduce" placeholder="课程介绍"></textarea>
                    <button id="bt_addCourse" class="btn btn-info">添加/修改</button>
                </div>
            </div>  
	</div>	<!--/.main-->
	<script src="<?php echo ADMIN_JS_URL;?>jquery-3.1.1.js"></script>
        <script src="<?php echo ADMIN_JS_URL;?>jquery.ztree.all.min.js"></script>
        <script src="<?php echo ADMIN_JS_URL;?>bootstrap.min.js"></script>
        <script src="<?php echo ADMIN_JS_URL;?>bootstrap-table.js"></script>
        <!--<script src="//rawgit.com/hhurz/tableExport.jquery.plugin/master/tableExport.js"></script>-->
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
            //zTree设置的参数
            var setting = {
                treeId:"tree",
                async:{
                    enable:true,
                    dataType:"text",
                    url:"/PShunt/index.php/Admin/Project/getTree"
                },
                view: {
                    selectedMulti: false,
                    nameIsHTML: true
                },
                edit: {
                    enable: true,
                    editNameSelectAll: true,
                    showRemoveBtn: false,
                    showRenameBtn: false
                },
                data:{
                    simpleData:{
                        enable:true,
                        idKey:"id",
                        pIdKey:"pid",
                        rootPid:0
                    }
                },
                //回调函数
                callback: {
                    beforeRename: zTreeBeforeRename,
                    onDblClick: zTreeOnDblClick,
                    beforeClick: zTreeBeforeClick,
                    onAsyncSuccess: zTreeOnAsyncSuccess
                }
            }
            //异步获取数据成功时调用
            function zTreeOnAsyncSuccess(event, treeId, treeNode, msg) {
                var zTree = $.fn.zTree.getZTreeObj("tree");
                zTree.expandAll(true);
//                alert(msg);
            };
            //点击事件点击前调用
            function zTreeBeforeClick(treeId, treeNode, clickFlag){
                $('#input_teacher_name').val("");
                $('#input_course_introduce').val("");
                if(treeNode.course_id){
                    $.get("/PShunt/index.php/Admin/Project/selectClass?c_id="+treeNode.course_id,function(data,status){
                        var json = eval('(' + data + ')');
                        $('#'+treeNode.tId+'_span').popover({
                             trigger:'click',
                             content : "教师姓名："+json.teacher+"  课程介绍："+json.introduce,
                             animation: true,
                             delay: { "show": 100, "hide": 100 },
                         });
                         setTimeout(function () { 
                             $('#'+treeNode.tId+'_span').popover('hide');
                         },5000);
                    });
                }
            }
            //双击调用的函数
            function zTreeOnDblClick(event, treeId, treeNode) {
//                alert(treeNode ? treeNode.tId + ", " + treeNode.name : "isRoot");
                var zTree = $.fn.zTree.getZTreeObj("tree");
//                alert(treeNode.level);
                zTree.editName(treeNode);
                if(treeNode.course_id){
                    $.get("/PShunt/index.php/Admin/Project/selectClass?c_id="+treeNode.course_id,function(data,status){
                        if(data){
                            var json = eval('(' + data + ')');
                            $('#input_course_name').val(json.name);
                            $('#input_course_introduce').val(json.introduce);
//                            var array = data.split("，");
//                            var temp = array[0];
//                            $('#input_teacher_name').val(temp.substring(3));
////                            alert(array.length);
//                            if(array.length>2){
//                                temp = " ";
//                                for(var i = 1;i<array.length;i++){
//                                    temp = temp+array[i]+"，";
//                                }
//                                temp = temp.substring(6,temp.length-1);
//                                $('#input_course_introduce').val(temp);
//                            }
                        }
                    });
                }
            };
            //重新命名调用函数
            function zTreeBeforeRename(treeId, treeNode, newName, isCancel) {
                var treeObj = $.fn.zTree.getZTreeObj(treeId),
                nodes = treeObj.getSelectedNodes(),
                treeNode = nodes[0],
                oldName = treeNode.name;
//                alert(treeNode.id+oldName);
                if(!newName){
                    alert("新名字不能为空");
                    treeObj.cancelEditName();
                    return false;
                }
                if(!isCancel && oldName !== newName ){
                    $.get("/PShunt/index.php/Admin/Project/setName?id="+treeNode.id+"&newName="+newName+"&course_id="+treeNode.course_id,function(data,status){
                        alert(data+status);
                    });
//                        alert(treeNode.id);
                    return true;
                } 
//                zTree.reAsyncChildNodes(null, "refresh");
            }
            //添加节点的方法
            var newCount = "<?php echo $maxId;?>";
            function add() {
                var zTree = $.fn.zTree.getZTreeObj("tree"),
                nodes = zTree.getSelectedNodes(),
                treeNode = nodes[0];
                if (nodes.length == 0) {
                    alert("请先选择一个节点");
                    return;
                }
                if(treeNode.level > 4){
                    alert("该节点无法添加");
                    return;
                }
                if(treeNode.course_id){
                    alert("该节点以添加课程，无法成为父节点");
                    return;
                }
                $.get("/PShunt/index.php/Admin/Project/addBranch?pId="+treeNode.id+"&name=new node"+newCount+"&level="+treeNode.level,function(data,status){
                    alert(data);
                });
                zTree.addNodes(treeNode, {id: newCount, pid:treeNode.id, name:"new node"});
                newCount++;
                setTimeout(function () { 
                    zTree.reAsyncChildNodes(null, "refresh");
                    zTree.expandAll(true);
                },1000);
                
            };
            //移除节点的方法
            function remove() {
                var zTree = $.fn.zTree.getZTreeObj("tree"),
                nodes = zTree.getSelectedNodes(),
                treeNode = nodes[0];
                if (nodes.length == 0) {
                    alert("请先选择一个节点");
                    return;
                }
                if(treeNode.isParent){
                    alert("该父节点无法删除");
                    return;
                }
                alert(treeNode.id+"-->"+treeNode.course_id);
                 $.get("/PShunt/index.php/Admin/Project/removeBranch?id="+treeNode.id+"&course_id="+treeNode.course_id,function(data,status){
                    alert(data);
                });
                setTimeout(function () { 
                    zTree.reAsyncChildNodes(null, "refresh");
                    zTree.expandAll(true);
                },1000);
            };
            //添加课程的方法
            function addCourse(){
                var zTree = $.fn.zTree.getZTreeObj("tree"),
                nodes = zTree.getSelectedNodes(),
                treeNode =  nodes[0];
                var courseName = $('#input_course_name').val(),
                courseIntroduce = $('#input_course_introduce').val();
//        str = treeNode.name,
//                alert(122);
                if (nodes.length == 0) {
                    alert("请先选择一个子节点");
                    return;
                }
                if(courseName.length === 0 || courseIntroduce.length === 0){
                    alert("输入不能为空");
                    return;
                }
                if(treeNode.isParent || treeNode.level < 4){
                    alert("该节点无法添加课程信息");
                    return;
                }
                if(treeNode.course_id){
//                    alert("该节点已经绑定课程无法添加子节点，请删除后重新添加结点。");
                    $.post("/PShunt/index.php/Admin/Project/setCourse",{
                        course_id:treeNode.course_id,
                        courseName:courseName,
                        introduce:courseIntroduce
                    },function(data,status){
                        alert(data);
                        
                    });
                }else{
                    $.post("/PShunt/index.php/Admin/Project/addCourse1",{
                        tId:treeNode.id,
                        courseName:courseName,
                        introduce:courseIntroduce
                    },function(data,status){
                        alert(data);
                    });
                }
                setTimeout(function () { 
                        zTree.reAsyncChildNodes(null, "refresh");
                        zTree.expandAll(true);
                    },1000);
                $('#input_course_name').val("");
                $('#input_course_introduce').val("");
            }
            $(document).ready(function(){
		zTreeObj = $.fn.zTree.init($("#tree"), setting);
                zTreeObj.expandAll(true);
                $("#bt_add").bind("click", add);
                $("#bt_remove").bind("click", remove);
                $('#bt_addCourse').bind("click",addCourse);
            });
        </script>
</body>

</html>