<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title>学生选择的信息</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="<?php echo ADMIN_CSS_URL;?>bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo ADMIN_CSS_URL;?>bootstrap-table.css" rel="stylesheet">
        <link href="<?php echo ADMIN_CSS_URL;?>styles.css" rel="stylesheet">
    </head>
    <body id="mybody">
        <div>
            <h1 class="page-header">当前<span id="number"><?php echo $name;?></span>信息</h1>
            <hr/>
            <p><?php echo $message;?></p>
            <button class="btn btn-info" id="btn_clear">清除</button>
            <div class="row">
                <table id="choice_table" class="table table-hover" >
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>课程名称</th>
                            <th>人数</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $table;?>
                    </tbody>
                </table>
            </div>
        </div>
        <script src="<?php echo ADMIN_JS_URL;?>jquery-3.1.1.js"></script>
        <script src="<?php echo ADMIN_JS_URL;?>bootstrap.min.js"></script>
        <script>
             $('button').click(
                function(){
                    if(confirm("确定删除？")){
                        $number = $('#number').html();
//                        alert($number);
                        $.get("/PShunt/index.php/Admin/Choice/clearChoice",{number:$number},
                        function(data){
                            alert(data);
                            window.close();
                        });
                    }else{
                        return;
                    }
                });
        </script>
            

    </body>
</html>