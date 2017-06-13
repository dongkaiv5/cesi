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
     <?php if(is_role('user','index')) { ?><li><a href="<?php echo U('user/index');?>">管理员</a></li><?php } ?>
     <?php if(is_role('user','add')) { ?><li class="active"><a href="<?php echo U('user/add');?>">添加管理员</a></li><?php } ?>
  </ul>
  <div class="common-form">
    <form method="post" class="form-horizontal J_ajaxForm" action="<?php echo U('User/add_post');?>">
      <div class="table_list">
        <table cellpadding="2" cellspacing="2" class="table_form" width="100%">
          <tbody>
            <tr>
              <td width="180">用户名:</td>
              <td><input type="text" class="input" name="user_login" value=""><span class="must_red">*</span></td>
            </tr>
            <tr>
              <td>密码:</td>
              <td><input type="password" class="input" name="user_pass" value="" ><span class="must_red">*</span></td>
            </tr>
            <tr>
              <td>邮箱:</td>
              <td><input type="text" class="input" name="user_email" value=""><span class="must_red">*</span></td>
            </tr>
            <tr>
              <td>机构设置:</td>
              <td>
                <input type="text" id="org_name_text"  name="org_name_text" class="xd" onclick="org_list(this);" value="" >*</td>
              <input type="hidden" name="org_id" id="org_id" value="0"> 
              </td>
            </tr>
            <tr>
              <td>角色:</td>
              <td>
 				<select name="role_id"  class="normal_select">
          <option>请选择</option>
 					<?php if(is_array($roles)): foreach($roles as $key=>$vo): ?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; ?>
        </select>
				</td>
            </tr>
           
            
          </tbody>
        </table>
      </div>
      <div class="form-actions">
            <button type="submit" class="btn btn-primary btn_submit J_ajax_submit_btn">添加</button>
            <!--<a class="btn" href="/servers/index.php/Admin/User">返回</a>-->
      </div>
    </form>
  </div>
</div>
<script type="text/javascript" src="/servers/Application/Admin/Public/js/common.js"></script>
<script type="text/javascript">
  function org_list($this) {

    art.dialog({
      id:'div_org_list',
      lock : true,
      ok : true ,
      cancel : true,
      title : '选择机构',
      background : '#cccccc',
      opacity : 0.80,
      width : 700,
      height : 500

    });

    $.get('/servers/index.php/Admin/Train/id_list', {}, function(e) {
      art.dialog({id: 'div_org_list'}).content(e);
    }, 'html');

  }
</script>

</body>
</html>