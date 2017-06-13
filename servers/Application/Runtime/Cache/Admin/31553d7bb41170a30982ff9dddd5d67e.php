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
<div class="wrap jj">
  <ul class="nav nav-tabs">
     <li><a href="<?php echo U('menu/index');?>">后台菜单</a></li>
     <li class="active"><a href="<?php echo U('menu/add');?>">添加菜单</a></li>
     <?php if(APP_DEBUG): ?><li><a href="<?php echo U('menu/lists');?>">所有菜单</a></li>
		<!--
     	<li><a href="<?php echo U('menu/spmy_import_menu');?>">恢复菜单</a></li>
     	<li><a href="<?php echo U('menu/spmy_export_menu');?>">备份菜单</a></li>
		--><?php endif; ?>
  </ul>
  <div class="common-form">
    <form method="post" class="form-horizontal J_ajaxForm" action="<?php echo U('Menu/add_post');?>">
      <div class="table_list">
        <table cellpadding="2" cellspacing="2" width="100%">
          <tbody>
            <tr>
              <td width="180">上级:</td>
              <td><select name="parentid" class="normal_select">
                  <option value="0">作为一级菜单</option>
                  
                     <?php echo ($select_categorys); ?>
                
                </select></td>
            </tr>
            <tr>
              <td>名称:</td>
              <td><input type="text" class="input" name="name" value=""><span class="must_red">*</span></td>
            </tr>
            <tr>
              <td>应用:</td>
              <td><input type="text" class="input" name="app" id="app" value=""><span class="must_red">*</span></td>
            </tr>
            <tr>
              <td>模块:</td>
              <td><input type="text" class="input" name="model" id="model" value=""><span class="must_red">*</span></td>
            </tr>
            <tr>
              <td>方法:</td>
              <td><input type="text" class="input" name="action" id="action" value=""><span class="must_red">*</span></td>
            </tr>
            <tr>
              <td>参数:</td>
              <td><input type="text" class="input length_5" name="data" value="">
                例:id=3&amp;p=3</td>
            </tr>
            <tr>
              <td>图标:</td>
              <td><input type="text" class="input" name="icon" id="action" value=""></td>
            </tr>
            <tr>
              <td>备注:</td>
              <td><textarea name="remark" rows="5" cols="57"></textarea></td>
            </tr>
            <tr>
              <td>状态:</td>
              <td><select name="status" class="normal_select">
                  <option value="1">显示</option>
                  <option value="0">隐藏</option>
                </select></td>
            </tr>
            <tr>
              <td>类型:</td>
              <td><select name="type" class="normal_select">
                  <option value="1" selected>权限认证+菜单</option>
                  <option value="0" >只作为菜单</option>
                </select>
                注意：“权限认证+菜单”表示加入后台权限管理，纯碎是菜单项请不要选择此项。</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary btn_submit  J_ajax_submit_btn">添加</button>
        <a class="btn" href="/servers/index.php/Admin/Menu">返回</a>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript" src="/servers/Application/Admin/Public/js/common.js"></script>
</body>
</html>