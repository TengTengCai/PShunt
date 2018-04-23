<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Expires" CONTENT="0"> 
<meta http-equiv="Cache-Control" CONTENT="no-cache"> 
<meta http-equiv="Pragma" CONTENT="no-cache"> 
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
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?php echo $name;?> <span class="caret"></span></a>
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
			<li class="active"><a href="/PShunt/index.php/Home/User/choice"><span class="glyphicon glyphicon-dashboard"></span>专业方向选择</a></li>
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
                <div class="ztree_wrap">
                    <ul id="tree" class="ztree"></ul>
                    <button id="bt_sure" class="btn btn-info">确定选择</button>
                </div>
                
            </div><!--/.row-->
            <div id="property">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>课程名称</th>
                                <th>教师</th>
                                <th>当前人数</th>
                                <th>课程介绍</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th id="courseName"></th>
                                <th id="teacherName"></th>
                                <th id="count"></th>
                                <th id="introduce"></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
        <script>
            var setting = {
                treeId:"tree",
                async:{
                    enable: true,
                    dataType:"text",
                    url: "/PShunt/index.php/Home/User/getChoice",
                            
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
                check: {
		enable: true,
                chkStyle: "radio",
                chkboxType: { "Y": "s", "N": "s" },
		radioType: "level"
                },
                data:{
                    simpleData:{
                        enable:true,
                        idKey:"id",
                        pIdKey:"pid",
                        rootPid:0
                    }
                },
                callback: {
                    onAsyncSuccess: zTreeOnAsyncSuccess,
                    onClick: zTreeOnClick
                }
            }
            function zTreeOnAsyncSuccess(event, treeId, treeNode, msg) {
                var zTreeObj = $.fn.zTree.getZTreeObj(treeId);
                var nodes = zTreeObj.getNodes();
                var nodes_array = zTreeObj.transformToArray(nodes);
//                alert(nodes_array.length);
                for(var i = 0;i<nodes_array.length;i++){
                    if(nodes_array[i].level==0||nodes_array[i].level==1||nodes_array[i].level==3){
                        nodes_array[i].nocheck = true;
                    }
                }
//                alert(nodes.nocheck);
                zTreeObj.expandAll(true);
            };
            function zTreeOnClick(event, treeId, treeNode) {
                if(treeNode.course_id){
                    $('#courseName').html(treeNode.name);
                    $.post("/PShunt/index.php/Home/User/getCourse",{
                        c_id:treeNode.course_id,
                    },function(data){
                        data = eval('('+data+')');
                        $('#teacherName').html(data.teacherName);
                        $('#count').html(data.count);
                        $('#introduce').html(data.introduce);
                    });
                }
                if(treeNode.level == 2 || treeNode.level == 4){
                    var treeObj = $.fn.zTree.getZTreeObj(treeId);
                    treeObj.checkNode(treeNode,true,false);
                }
            };
            function confirmSelection(){
                var treeObj = $.fn.zTree.getZTreeObj("tree");
                var nodes = treeObj.getNodesByParam("nocheck", false, null);
                var temp = new Array(nodes.length),j=0;
                for(var i = 0;i<nodes.length;i++){
                    temp[i] = nodes[i].parentTId;
                }
                var a = unique1(temp);
                var nodes = treeObj.getCheckedNodes(true);
                var cIds = [],mName = [],cName = [];
                if(nodes.length==a.length){
                    for(var i = 0;i<nodes.length;i++){
                        if(nodes[i].level == 2){
                            mName.push(nodes[i].name);
                            var childrenNodes = nodes[i].children;
                            for(var j = 0;j<childrenNodes.length;j++){
                                if(!childrenNodes[j].isParent){
                                    cIds.push(childrenNodes[j].course_id);
                                    cName.push(childrenNodes[j].name);
                                }
                            }
                        }else if(nodes[i].level == 4){
                            cIds.push(nodes[i].course_id);
                            cName.push(nodes[i].name);
                        }
                    }
                    var r=confirm("你的选择:"+mName+"\n课程有："+cName);
                    if (r==true){
                        //发送
                        $.post("/PShunt/index.php/Home/User/setChoice",{
                            cIds:cIds.toString()
                        },function(data){
                            if(data == 0){
                                alert("选择失败");
                            }else if(data == 1){
                                alert("选择成功");
                            }
                            location.reload();
                        });
                    }else{
                        return;
                    }
                }else{
                    alert("未完全选择");
                }
            }
            // 最简单数组去重法 
            function unique1(array){ 
                var n = []; //一个新的临时数组 
                //遍历当前数组 
                for(var i = 0; i < array.length; i++){ 
                //如果当前数组的第i已经保存进了临时数组，那么跳过， 
                //否则把当前项push到临时数组里面 
                    if (n.indexOf(array[i]) == -1) n.push(array[i]); 
                } 
                return n; 
            }
            $().ready(function(){
                
                zTreeObj = $.fn.zTree.init($("#tree"), setting);
                $('#bt_sure').bind('click',confirmSelection);
            });
        </script>
</body>

</html>