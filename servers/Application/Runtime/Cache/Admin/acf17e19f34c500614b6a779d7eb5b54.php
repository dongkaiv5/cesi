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
<head/>
<body>
<div class="wrap J_check_wrap">
  <ul class="nav nav-tabs">
     <?php if(is_role('rbac','index')) { ?><li class="active"><a href="<?php echo U('rbac/index');?>">角色管理</a></li><?php } ?>
     <?php if(is_role('rbac','roleadd')) { ?><li><a href="<?php echo U('rbac/roleadd');?>">添加角色</a></li><?php } ?>
  </ul>
  <div class="table_list">
  <form name="myform" action="<?php echo U('Rbac/listorders');?>" method="post">
    <table width="100%" cellspacing="0" class="table table-hover">
      <thead>
        <tr>
          <th width="30">ID</th>
          <th align="left" >角色名称</th>
          <th align="left" >角色描述</th>
          <th width="40" align="left" >状态</th>
          <th width="200">管理操作</th>
        </tr>
      </thead>
      <tbody>
        <?php if(is_array($roles)): foreach($roles as $key=>$vo): ?><tr>
          <td><?php echo ($vo["id"]); ?></td>
          <td><?php echo ($vo["name"]); ?></td>
          <td><?php echo ($vo["remark"]); ?></td>
          <td>
          <?php if($vo['status'] == 1): ?><font color="red">√</font>
          <?php else: ?>
          <font color="red">╳</font><?php endif; ?>
          </td>
          <td  class="text-c">
			<?php if(is_role('rbac','authorize')) { ?><a href="<?php echo U('Rbac/authorize',array('id'=>$vo['id']));?>">权限设置</a>  | <?php } ?>
                        <?php if(is_role('rbac','member')) { ?><a href="javascript:open_iframe_dialog('<?php echo U('rbac/member',array('id'=>$vo['id']));?>','成员管理');">成员管理</a>| <?php } ?>
                        <?php if(is_role('rbac','roleedit')) { ?><a href="<?php echo U('Rbac/roleedit',array('id'=>$vo['id']));?>">修改</a> | <?php } ?>
                        <?php if(is_role('rbac','roledelete')) { ?><a class="J_ajax_del" href="<?php echo U('Rbac/roledelete',array('id'=>$vo['id']));?>">删除</a><?php } ?>
          </td>
        </tr><?php endforeach; endif; ?>
      </tbody>
    </table>
  </form>
  </div>
</div>
<script type="text/javascript" src="/servers/Application/Admin/Public/js/common.js"></script>
</body>
</html>