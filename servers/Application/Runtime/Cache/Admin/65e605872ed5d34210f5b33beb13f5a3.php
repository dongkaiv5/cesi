<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8">
    <meta http-equiv=X-UA-Compatible content="IE=edge,chrome=1">
	<meta content="always" name="referrer">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="stylesheet" type="text/css" href="/servers/Application/Admin/Public/css/theme.min.css" />
	<link rel="stylesheet" type="text/css" href="/servers/Application/Admin/Public/css/simplebootadmin.css" />
	<link rel="stylesheet" type="text/css" href="/servers/Application/Admin/Public/css/font-awesome.min.css" />
	<link rel="stylesheet" type="text/css" href="/servers/Application/Admin/Public/js/artDialog/skins/default.css" />
	
    <style>
		.length_3{width: 180px;}
		/*==================Global Reset====================*/
		body, h1, h2, h3, h4, h5, h6, hr, p, blockquote, dl, dt, dd, ul, ol, li, pre, form, fieldset, legend, button, input, textarea, th, td { margin: 0; padding: 0; }
		body, button, input, select, textarea { font: 12px/1.5 tahoma, arial, \5b8b\4f53, sans-serif; }
		h1, h2, h3, h4, h5, h6 { font-size: 100%; }
		ul, ol { list-style: none; }
		a { text-decoration: none; }
		a:hover { text-decoration: underline; }
		img { border: 0; }
		button, input, select, textarea { font-size: 100%; }
		.clearfix:before,
		.clearfix:after {
		    content: "";
		    display: table;
		}
		.clearfix:after {
		    clear: both;
		    overflow:hidden;
		}
		.clearfix {
		    clear: both;
		    zoom: 1;
		}
	</style>

	<!--[if IE 7]>
	<link rel="stylesheet" type="text/css" href="/servers/Application/Admin/Public/css/font-awesome-ie7.min.css" />
	<![endif]-->

<script type="text/javascript">
//全局变量
var GV = {
	HOST:"<?php echo ($_SERVER['HTTP_HOST']); ?>",
    DIMAUB: "/servers/",
    JS_ROOT: "Application/Admin/Public/js/",
    MODULE_URL:"/servers/index.php/Admin/",
    TOKEN: ""
};
</script>

<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<script type="text/javascript" src="/servers/Application/Admin/Public/js/jquery.js"></script>
	<script type="text/javascript" src="/servers/Application/Admin/Public/js/wind.js"></script>
	<script type="text/javascript" src="/servers/Application/Admin/Public/js/jquery.smooth-scroll.min.js"></script>
	<script type="text/javascript" src="/servers/Application/Admin/Public/js/bootstrap.min.js"></script>

<?php if(APP_DEBUG): ?><style>
		#think_page_trace_open{
			z-index:9999;
		}
	</style><?php endif; ?>
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
   <ul class="nav nav-tabs">
     <?php if(is_role('user','index')) { ?><li class="active"><a href="<?php echo U('user/index');?>">管理员</a></li><?php } ?>
     <?php if(is_role('user','add')) { ?><li><a href="<?php echo U('user/add');?>">添加管理员</a></li><?php } ?>
  </ul>
   <div class="table_list">
   <table width="100%" cellspacing="0" class="table table-hover">
        <thead>
          <tr>
            <th width="50">ID</th>
            <th>用户名</th>
            <th>所属角色</th>
            <th>所属机构</th>
            <th>最后登录IP</th>
            <th>最后登录时间</th>
            <th>E-mail</th>
            <th width="120">管理操作</th>
          </tr>
        </thead>
        <tbody>
        <?php if(is_array($users)): foreach($users as $key=>$vo): ?><tr>
            <td><?php echo ($vo["id"]); ?></td>
            <td><?php echo ($vo["user_login"]); ?></td>
            <td><?php echo ($roles[$vo['role_id']]['name']); ?></td>
            <td><?php if(empty($vo['name'])){ ?> 管理后台 <?php } echo ($vo["name"]); ?></td>
            <td><?php echo ($vo["last_login_ip"]); ?></td>
            <td>
	            <?php if($vo['last_login_time'] == 0): ?>该用户还没登陆过
	            <?php else: ?>
	            <?php echo ($vo["last_login_time"]); endif; ?>
            </td>
            <td><?php echo ($vo["user_email"]); ?></td>
            <td>
	            <?php if($vo['id'] == 1): if(is_role('user','edit')) { ?><font color="#cccccc">修改</font> | <?php } ?>
	            <?php if(is_role('user','delete')) { ?><font color="#cccccc">删除</font><?php } ?>
	            <?php else: ?>
	            <?php if(is_role('user','edit')) { ?><a href='<?php echo U("user/edit",array("id"=>$vo["id"]));?>'>修改</a> | <?php } ?>
	            <?php if(is_role('user','delete')) { ?><a class="J_ajax_del" href="<?php echo U('user/delete',array('id'=>$vo['id']));?>">删除</a><?php } endif; ?>
            </td>
          </tr><?php endforeach; endif; ?>
        </tbody>
      </table>
   </div>
</div>
<script type="text/javascript" src="/servers/Application/Admin/Public/js/common.js"></script>
</body>
</html>