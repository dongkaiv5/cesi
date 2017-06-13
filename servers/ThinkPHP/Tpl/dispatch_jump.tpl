<?php
    if(C('LAYOUT_ON')) {
        echo '{__NOLAYOUT__}';
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>跳转提示</title>
<style type="text/css">
*{ padding: 0; margin: 0; }
body{ background: #fff; font-family: '微软雅黑'; color: #333; font-size: 16px; }
.system-message .detail{ font-size: 12px; line-height: 20px; margin-top: 12px; display:none}
.tip{
	width: 860px;
	margin: 0 auto;
	padding: 250px 0 0 250px;
	overflow: hidden;
}
.tip_bg_success,.tip_bg_error{
	background: url(__ROOT__/ThinkPHP/Tpl/images/error.png) no-repeat;	
	width: 154px;
	height: 185px;
	float: left;
	display: inline;
	overflow: hidden;
}
.tip_bg_success{
	background-position: 0 0;
}
.tip_bg_error{
	background-position: 0 -315px;
}
.tip_con{
	padding: 32px 0 0 72px;
	float: right;
	width: 620px;
	display: inline;
}
.tip_tit{
	padding: 10px 0 0 72px;
	display: block;
	float: right;
	width: 620px;
	display: inline;
}
.tip_back{
	background: url(__ROOT__/ThinkPHP/Tpl/images/error.png) no-repeat;
	background-position: 0 -223px;
	color: #666666;
	padding-left: 28px;
	text-decoration: none;
	display: block;
	height: 30px;
	line-height: 24px;
	font-size: 18px;
	margin-top: 32px;
}
</style>
</head>
<body>
<div class="tip">
<?php if(isset($message)) {?>
	<div class="tip_bg_success"></div>
		<h1 class="tip_tit"><?php echo($message); ?></h1>
<?php }else{?>
	<div class="tip_bg_error"></div>
		<h1 class="tip_tit"><?php echo($error); ?></h1>
<?php }?>
<p class="detail"></p>
<div class="tip_con">
	<h3>如果您不做选择系统将自动跳转：<b id="wait"><?php echo($waitSecond); ?></b></h3>
	<a id="href" class="tip_back" href="<?php echo($jumpUrl); ?>">返回</a>
</div>
</div>
<script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time <= 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
})();
</script>
</body>
</html>
