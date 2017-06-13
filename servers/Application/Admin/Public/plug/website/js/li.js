
function resize_main_body() {
	var h = 0;
	var top = $('#body_id').offset().top;
	$('#body_id > DIV').each(function(k,v){
		var v = $(v);
		h = Math.max(v.offset().top + v.height(), h);
	});
	$('#body_id').css('height', h - top);
}                      

function div_add($type) {
	var $style = '';
	var $width = 0;
	var $height = 0;
	if($type == 'div_head') {
		$width = 1000;
		$height = 100;
		$style = 'width:100%;height:100px;background:transparent;';
	} else if($type == 'div_body') {
		$width = 200;
		$height = 100;
		$style = 'width:200px;height:100px;margin:0 auto;float:left;background:transparent;';
	} else if($type == 'div_footer') {
		$width = 1000;
		$height = 100;
		$style = 'width:100%;height:100px;background:transparent;';
	} else {
		return false;
	}

	var $template_id = $('#template_id').val();
	$.post(GV.MODULE_URL+'Layout/add', {'template_id':$template_id, 'type':$type, 'style':$style, 'width':$width, 'height':$height}, function(e) {
		if(e.status) {
			var $layout_id = e.data;
			var htmls = '';
				htmls += '<div id="layout_id_'+$layout_id+'" class="'+$type+'" layout_class="mdDivs" style="'+$style+'">';
					htmls += '<div id="div_app_'+$layout_id+'" style="height:100%"></div>';
				htmls += '</div>';
				switch($type) {
					case 'div_head' : $('#head_id').append(htmls); break;
					case 'div_body' : $('#body_id').append(htmls); break;
					case 'div_footer' : $('#footer_id').append(htmls); break;
					default : break;
				}
				resize_main_body();
		} else {
			alert('添加失败!');
		}
	}, 'json');
}

function div_body_add($layout_id) {
    var $template_id = $('#template_id').val();
    var $type = 'div_body';
    var $style = 'width:40%;height:40%;margin:0 auto;background:transparent;';
    var $width = '40';
    var $height = '40';
    $.post(GV.MODULE_URL+'Layout/add', {'template_id':$template_id, 'pid':$layout_id, 'type':$type, 'style':$style, 'width':$width, 'height':$height}, function(e) {
    	if(e.status) {
			location.reload();
    	} else {
    		alert(e.info);
    	}
    }, 'json');
}

function div_del($obj) {
	//$obj = $($obj).parent();
	$obj = $($obj);
	$id = $obj.attr('id');
	$id = $id.split('layout_id_')[1];
	
	art.dialog({
		lock : true,
		cancel : true,
		title : '布局删除',
		background : '#cccccc', // 背景色
		opacity : 0.80,	// 透明度
		content : '您确认要删除该布局？',
		ok : function () {
			art.dialog({id:'div_edit_dialog'}).close();
			$.post(GV.MODULE_URL+'Layout/del', {'id':$id}, function(e) {
				if(e.status) {
					$obj.remove();
					resize_main_body();
				} else {
					alert(e.info);
				}
			}, 'json');
		}
	});
}

function div_edit($obj) {
	//$obj = $($obj).parent();
	$obj_one = $obj;
	$obj = $($obj);
	var $id = $obj.attr('id');
	var $class = $obj.attr('class');
	var $width = $obj.width();
	var $height = $obj.height();
	var $styles = $obj.attr('style');
	
    var $layout_id = $id;
    $layout_id = $layout_id.split('layout_id_');
    $layout_id = $layout_id[1];
    
	var htmls = '';
	htmls += '<form id="div_edit_form" action="'+GV.MODULE_URL+'Layout/edit" method="post" enctype="multipart/form-data">';
	htmls += '<table>';
	switch($class) {
		case 'div_head' : 
			htmls += '<tr><td>高度值:<input id="'+$id+'_h'+'" name="height" type="text" value="'+$height+'"></td></tr>';
		break;
		case 'div_body' : 
			htmls += '<tr><td>宽度值:<input id="'+$id+'_w'+'" name="width" type="text" value="'+$width+'"></td></tr>';
			htmls += '</br>';
			htmls += '<tr><td>高度值:<input id="'+$id+'_h'+'" name="height" type="text" value="'+$height+'"></td></tr>';
			htmls += '</br>';
			htmls += '<tr>';
				var $select_val = $obj.css('float');
				htmls += '<td>位置值:<select id="left_center_right" name="left_center_right" >';
					if($select_val == 'left') {
						htmls += '<option value="left" selected="selected">居左</option>';
					} else {
						htmls += '<option value="left">居左</option>';
					}
					if($select_val == 'none') {
						htmls += '<option value="none" selected="selected">居中</option>';
					} else {
						htmls += '<option value="none">居中</option>';
					}
					if($select_val == 'right') {
						htmls += '<option value="right" selected="selected">居右</option>';
					} else {
						htmls += '<option value="right">居右</option>';
					}
				htmls += '</select></td>';
			htmls += '</tr>';
		break;
		case 'div_footer' : 
			htmls += '<tr><td>高度值:<input id="'+$id+'_h'+'" name="height" type="text" value="'+$height+'"></td></tr>';
		break;
		default : break;
	}
	
	if($obj_one.style.backgroundColor == 'transparent') {
		htmls += '<tr><td>背景色:<input id="'+$id+'_color'+'" name="layout_color" type="text" value=""></td></tr>';
	} else {
		htmls += '<tr><td>背景色:<input id="'+$id+'_color'+'" name="layout_color" style="color:'+$obj.css('background-color')+';" type="text" value="'+rgb2hex($obj.css('background-color'))+'"></td></tr>';
	}
	
	htmls += '<tr><td>背景图:<select name="layout_image" onchange="layout_image_select(this);">';
		htmls += '<option value="0">请选择</option>';
		htmls += '<option value="1">添加背景图</option>';
		htmls += '<option value="2">删除背景图</option>';
	htmls += '</select></td></tr>';

	htmls += '<tr><td>平铺项:<select name="background-repeat">';
		htmls += '<option value="no-repeat" ';
			if($obj.css('background-repeat') == 'no-repeat') { htmls += 'selected="selected"'; }
		htmls += ' >不平铺</option> ';
		htmls += '<option value="repeat" ';
			if($obj.css('background-repeat') == 'repeat') { htmls += 'selected="selected"'; }
		htmls += ' >平铺</option> ';
		htmls += '<option value="repeat-x" ';
			if($obj.css('background-repeat') == 'repeat-x') { htmls += 'selected="selected"'; }
		htmls += ' >延X轴平铺</option>';
		htmls += '<option value="repeat-y" ';
			if($obj.css('background-repeat') == 'repeat-y') { htmls += 'selected="selected"'; }
		htmls += ' >延Y轴平铺</option>';
	htmls += '</select></td></tr>';

	htmls += '<tr><td id="layout_image_td_id">';	
	htmls += '</td></tr>';
	
	htmls += '<tr><td>边宽值:<input id="border-width" name="border-width" type="text" value="'+$obj.css('border-bottom-width')+'"></td></tr>';	
	
	if($obj_one.style.borderBottomColor == 'transparent') {
		htmls += '<tr><td>边色值:<input type="text" id="border-color-id" name="border-color" value=""></td></tr>';	
	} else {
		htmls += '<tr><td>边色值:<input type="text" id="border-color-id" name="border-color" style="color:'+$obj.css('border-bottom-color')+'" value="'+rgb2hex($obj.css('border-bottom-color'))+'"></td></tr>';
	}
	
	htmls += '<tr><td>圆角值:<input name="border-radius" type="text" value="'+$obj.css('borderBottomLeftRadius')+'"></td></tr>';	

	htmls += '<tr><td>';
		htmls += '内边框:';
		htmls += '<input style="width:50px" name="padding-top" type="text" value="'+$obj.css('padding-top')+'" title="上边距" placeholder="上边距">';
		htmls += '<input style="width:50px" name="padding-right" type="text" value="'+$obj.css('padding-right')+'" title="右边距" placeholder="右边距">';
		htmls += '<input style="width:50px" name="padding-bottom" type="text" value="'+$obj.css('padding-bottom')+'" title="下边距" placeholder="下边距">';
		htmls += '<input style="width:50px" name="padding-left" type="text" value="'+$obj.css('padding-left')+'" title="左边距" placeholder="左边距">';
	htmls += '</td></tr>';
	
	htmls += '<tr><td>';
		htmls += '外边框:';
		htmls += '<input style="width:50px" name="margin-top" type="text" value="'+$obj.css('margin-top')+'" title="上边距" placeholder="上边距">';
		htmls += '<input style="width:50px" name="margin-right" type="text" value="'+$obj[0].style.marginRight+'" title="右边距" placeholder="右边距">';
		htmls += '<input style="width:50px" name="margin-bottom" type="text" value="'+$obj.css('margin-bottom')+'" title="下边距" placeholder="下边距">';
		htmls += '<input style="width:50px" name="margin-left" type="text" value="'+$obj[0].style.marginLeft+'" title="左边距" placeholder="左边距">';
	htmls += '</td></tr>';
	
	htmls += '</table>';
	htmls += '<input type="hidden" id="style_id" name="style">';
	htmls += '<input type="hidden" id="layout_id" name="id">';
	htmls += '</form>';
	
	/*
	htmls += '<script>';
		htmls += '$("#div_edit_form").submit(function(){';
			htmls += '$(this).ajaxSubmit({';
				htmls += 'type:"post",';
				htmls += 'dataType:"html",';
				htmls += 'url:"'+GV.MODULE_URL+'Layout/edit",';
				htmls += 'success:function(e){';
					//htmls += 'alert(e);';
					htmls += '$("#layout_id_'+$layout_id+'").attr("style", e);';
					//htmls += '$("#layout_id_'+$layout_id+'").css(jQuery.parseJSON(e));';
					htmls += 'resize_main_body();';
				htmls += '}';
			htmls += '});';
			htmls += 'return false;';
		htmls += '});';
	htmls += '</script>';
	*/
	art.dialog({
		id:'div_edit_dialog',
		lock : true,
		cancel : true,
		title : '布局修改--(编号'+$layout_id+')',
		background : '#cccccc', // 背景色
		opacity : 0.80,	// 透明度
		width : 500,
		height : 200,
		content : htmls,
		init : function() {
			$("#"+$id+"_color").colorpicker({ fillcolor:true, success:function(o,color){ $(o).css("color",color); } });
			$("#border-color-id").colorpicker({ fillcolor:true, success:function(o,color){ $(o).css("color",color); } });
		},
		close : function() {
			$('#colorpanel').hide();
		},
		button: [{
			name: '布局修改',
            callback: function () {
				$('#layout_id').val($layout_id);
				$('#div_edit_form').submit();
            },
            focus: true
        },{
        	name: '布局添加',
            callback: function () {
            	div_body_add($layout_id);
            }
       	},{
        	name: '布局删除',
            callback: function () {
            	div_del($obj_one);
            	return false;
            }
       	},{
        	name: '插件添加',
            callback: function () {
            	div_app_add($layout_id);
            	return false;
            }
        },{
        	name: '插件删除',
            callback: function () {
            	div_app_del($layout_id);
            	return false;
            }
        },{
        	name: '插件编辑',
        	callback: function () {
        		div_app_edit($layout_id);
        	}
        }]
	});
}

function layout_image_select($obj) {
	if($($obj).val() == '1') {
		$('#layout_image_td_id').html('请选择:<input type="file" name="image">');
	} else {
		$('#layout_image_td_id').html('');
	}
}

function url($url) {
	return $url;
}
function rgb($r, $g, $b) {
	return new Array($r, $g, $b);
}

function rgb2hex(rgb) {
	if (/^#[0-9A-F]{6}$/i.test(rgb)) return rgb;
 	rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
 	return (rgb && rgb.length === 4) ? "#" +
  	("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
  	("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
  	("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : '';
}

function toHex(N) {
	if (N==null) return "00"; N=parseInt(N); 
	if (N==0 || isNaN(N)) return "00"; N=Math.max(0,N); N=Math.min(N,255); N=Math.round(N); 
	return "0123456789ABCDEF".charAt((N-N%16)/16) + "0123456789ABCDEF".charAt(N%16); 
}

function div_app_del($layout_id) {
	art.dialog({
		lock : true,
		cancel : true,
		title : '插件删除',
		background : '#cccccc', 
		opacity : 0.80,	
		content : '您确认要删除该插件?',
		ok : function () {
			//art.dialog({id:'div_edit_dialog'}).close();
			$.post(GV.MODULE_URL+'Layout/div_app_del', {'layout_id':$layout_id}, function(e) {
				if(e.status) {
					//dialog.content(e.info).title('插件删除提示'); //.time(3);
					art.dialog({
						lock : true,
						ok : true,
						time:2,
						background : '#cccccc', 
						opacity : 0.80,	
					    title: '布局插件删除提示',
					    content: e.info
					});
					$('#div_app_'+$layout_id).empty();
					resize_main_body();
				} else {
					art.dialog({
						lock : true,
						ok : true,
						time:2,
						background : '#cccccc', 
						opacity : 0.80,	
					    title: '布局插件删除提示',
					    content: e.info
					});
				}
			}, 'json');
		}
	});
}

function div_app_add($layout_id) {
	var $dialog = art.dialog({
		id: 'div_app_add_dl',
		lock : true,
		cancel : true,
		width: 800,
		height: 400,
		title : '插件选择',
		background : '#cccccc', // 背景色
		opacity : 0.80	// 透明度
	});
	$.post(GV.MODULE_URL+'Plug/plug_list', {'layout_id':$layout_id}, function(e) {
		$dialog.content(e);
	}, 'html');
	resize_main_body();
}

function div_app_edit($layout_id) {
	$.post(GV.MODULE_URL+'Plug/div_app_edit', {'layout_id':$layout_id}, function(e) {
		if(e.status) {
			window.location.href = e.url;
		} else {
			$('#div_app_'+$layout_id).html(e.info);
		}
	}, 'json');
}

$(function() {
	$(document).on('click', '[layout_class=mdDivs]', function(event) {
		div_edit(this);
		return false;
	});
	$(document).on('mouseenter mouseout', '[layout_class=mdDivs]', function(event) {
		if(event.type=="mouseenter") {
			$(this).css({"outline":"4px dashed #0000CC"});
		} else {
			$(this).css({"outline": "1px dashed #339966"});
			
		}
		event.stopPropagation();
	});
});