$(function() {
    //图片宽高
    /*$(window).resize(function(){
     $('#main_body .back_img').width($(document.body).width())
     $('#main_body .back_img').height($(document.body).height())
     })*/
    //初始化时判断有无背景进行弹窗
    //type 1背景图，2小地图
    var issetbg = $('#main_body').attr('issetbg');
    if (issetbg != 1) {
        show_image_dialog_html(1, 1);
    }
    //面包屑菜单
    $('.bread_menu').on('mouseover', function() {
        $(this).css({'width': 'auto', 'height': 'auto'});
    }).on('mouseout', function() {
        $(this).css({'width': '20px', 'height': '20px'});
    })

    //点击修改背景图
    $('.setbg').on('click', function(e) {
        show_image_dialog_html(1, 1);
        return false;
    })
    //body体弹窗添加图片
    $('#main_body').on('click', function(e) {
        if (issetbg != 1) {
            show_image_dialog_html(1, 1);
            return
        }
        $("#imageupload").attr('current_id', '');
        show_image_dialog_html(2, 1, '10%', '10%', e.pageX, e.pageY);
        //plug_dialog(e);
    });
    //子级地图编辑
    $('#main_body .small_map').on('click', function(e) {

        var sec_map_id = $(this).attr('sec_map_id');
        var current_img_url = $(this).find('img').attr('src');
        var leaf = $(this).attr('leaf');
        var map_type = $(this).attr('map_type');
        var point_id = $(this).attr('point_id');//墓穴id
        var serial_number = $(this).attr('serial_number');//墓穴编号
        $('#second_map_dialog .controls .map_number input').attr('value', serial_number);
        $('#second_map_dialog option:eq(' + map_type + ')').attr('selected', 'selected').siblings().removeAttr('selected');
        if (map_type == 1) {
            $('#second_map_dialog .controls .map_number').show();
            if (point_id.length > 0) {
                $('#second_map_dialog .controls .unset_map_number').show();
            }
        }else{
            $('#second_map_dialog .controls .map_number').hide();
        }
        $('#second_map_dialog .controls').attr('leaf', leaf)
        $('#second_map_dialog img').attr('src', current_img_url)
        $('#second_map_dialog .controls').attr('sec_map_id', sec_map_id);
        show_image_dialog_html(2, 2, '10%', '10%', e.pageX, e.pageY);
        return false;
    });
    //小地图图片更换
    $('.update_small_img').live('click', function(e) {
        //将form表单currentid改为当前值
        var current_id = $(this).parents('.controls').attr('sec_map_id');
        $("#imageupload").attr('current_id', current_id);
        art.dialog({id: 'div_bgimg_upload'}).close();
        show_image_dialog_html(2, 1, '10%', '10%', e.pageX, e.pageY);
    });
    //修改小图片类型  地图类型继续加载
    $('.controls .submit_btn').live('click', function() {
        window.location.href = $('body').attr('url') + '/gardenid/' + $('#imageupload').attr('garden_id') + '/layout_id/' + $(this).parents('.controls').attr('sec_map_id');
    });
    //$('.update_small_img p').on('click',function(){
    //删除功能
    $('.J_ajax_del_layout').live('click', function(e) {
        var $_this = this;
        var url = $(this).attr('href');
        var id = $(this).parents('.controls').attr('sec_map_id')
        art.dialog({
            id: 'delete_dialog',
            title: false,
            icon: 'question',
            content: '确定要删除吗？',
            follow: $_this,
            close: function() {
                $_this.focus();
                ; //关闭时让触发弹窗的元素获取焦点
                return true;
            },
            ok: function() {
                $.get(url + '/map_id/' + id, function(data) {
                    art.dialog({id: 'delete_dialog'}).content(data.info).time(1);
                    if (data.status == 1) {
                        window.location.reload();
                    }
                }, 'json');

            },
            cancelVal: '关闭',
            cancel: true
        });
        return false;
    });
    //墓穴解绑功能
    $('.aui_main .unbundling').live('click', function(e) {
        var $_this = this;
        var url = $(this).attr('href');
        var map_id = $(this).parents('.controls').attr('sec_map_id');
        var grave_number = $(this).parents('.controls').find('input').val();

        art.dialog({
            id: 'delete_dialog',
            title: false,
            icon: 'question',
            content: '确定解绑该墓穴吗，解绑后不可恢复？',
            follow: $_this,
            close: function() {
                $_this.focus();
                ; //关闭时让触发弹窗的元素获取焦点
                return true;
            },
            ok: function() {
                $.get(url, {map_id: map_id, grave_number: grave_number}, function(e) {
                    art.dialog({content: e.info, time: 1});
                    if (e.status) {
                        window.location.reload();
                    }
                }, 'json');

            },
            cancelVal: '关闭',
            cancel: true
        })
        return false;
    })
    //选择墓穴时显示编码框
    $('.aui_main .map_type').live('change', function() {
        var select_val = $(this).children('option:selected').val();
        if (select_val == 1) {
            $('.aui_main .map_number').show();
            if ($('.aui_main .map_number input').val() != 0) {
                $('.aui_main .unset_map_number').show();
            }
        } else {
            $('.aui_main .map_number').hide();
        }
    })
});
var postData = {};
function show_image_dialog_html(type, content_type, width, height, pageX, pageY) {
    //全局变量或者放到html标签上
    postData.width = (typeof(width) == 'undefined') ? '100%' : width;//宽度
    postData.height = (typeof(height) == 'undefined') ? '100%' : height;//高度
    postData.pagex = (typeof(pageX) == 'undefined') ? 0 : pageX;//横坐标
    postData.pagey = (typeof(pageY) == 'undefined') ? 0 : pageY;//纵坐标
    postData.type = type;
    var title = (type == 1) ? '请上传背景图片' : '请上传标签图片';
    var html = (content_type == 1) ? $('#map_dialog').html() : $('#second_map_dialog').html();
    //dialog弹窗参数
    var options = {
        id: 'div_bgimg_upload',
        lock: true,
        title: title,
        background: '#cccccc',
        opacity: 0.7,
        width: postData.width,
        height: postData.height,
        content: html,
        esc: false,
        drag: false,
        resize: false,
    }
    //确定按钮显示以及事件
    if (content_type == 2) {
        options.cancelVal = '关闭';
        options.cancel = function() {
            //art.dialog({id: 'div_bgimg_upload'}).close();
        };
        options.ok = function() {
            var map_type = $('.aui_main select option:selected').val();
            var sec_map_id = $('.aui_main .controls').attr('sec_map_id');
            var point_id = $('.aui_main .controls').attr('point_id')
            var garden_id = $('#imageupload').attr('garden_id');
            var org_id = $('#imageupload').attr('org_id');
            if (map_type == 0) {//跳转到地图同时将类型改为地图
                window.location.href = $('body').attr('url') + '/gardenid/' + garden_id + '/layout_id/' + sec_map_id;
            } else if (map_type == 1) {//墓穴
                var grave_number = $('.aui_main .controls .map_number input').val();
                var grave_url = $('.aui_main .controls option:eq(1)').attr('url');
                if (grave_number == '') {
                    art.dialog({content: '请填写墓穴编号', time: 1})
                    $('.aui_main .controls .map_number input').focus();
                    return false;
                }
                $.get(grave_url, {point_id: point_id, map_id: sec_map_id, grave_number: grave_number, garden_id: garden_id, org_id: org_id}, function(data) {
                    art.dialog({id: 'div_bgimg_upload'}).content(data.info).time(1);
                    if (data.status) {
                        window.location.reload();
                    }
                }, 'json');
                //$('.aui_main .controls .map_number').val();
            } else if (map_type == 2) {//往生院
                var service_url = $('.aui_main .controls option:eq(2)').attr('url');
                var post_data = {};
                post_data.map_id = $('.aui_main .controls').attr('sec_map_id');
                post_data.garden_id = $('#imageupload').attr('garden_id');
                post_data.org_id = $('#imageupload').attr('org_id');
                $.post(service_url, post_data, function(data) {
                    art.dialog({id: 'div_bgimg_upload'}).content(data.info).time(1);
                    if (data.status) {
                        windows.location.reload();
                    }
                }, 'json')
            }
        };
    }
    art.dialog(options);


}
//背景图片上传
var mubiao_objs;
function getimg(obj) {
    objs = $(obj);
    mubiao_objs = objs.clone(true);
    objs.parent().append(mubiao_objs.val(""));
    $("#imageupload").html(objs);
    $("#imageupload").submit();
}
//图片form表单提交
$('#imageupload').submit(function() {
    art.dialog({id: 'div_bgimg_upload'}).close();
    art.dialog({id: 'main_plug_dialog'}).close();
    var type = postData.type;

    art.dialog({
        id: 'bgimg_up',
        lock: true,
        title: '请上传背景图',
        background: '#cccccc',
        opacity: 0.7,
        content: '请稍候......'
    });
    var url = $('#imageupload').attr('url') + '/garden_id/' + $('#imageupload').attr('garden_id') + '/org_id/' + $('#imageupload').attr('org_id') + '/type/' + type;
    if ($('#imageupload').attr('current_id') != '') {
        url += '/id/' + $('#imageupload').attr('current_id');
    }
    if ($('body').attr('top_id') != '') {
        url += '/top_id/' + $('body').attr('top_id');
    }
    url += '/leaf/' + $('#imageupload').attr('leaf');

    $(this).ajaxSubmit({
        type: "post",
        dataType: "json",
        url: url,
        data: {
            'clientx': postData.pagex,
            'clienty': postData.pagey
        },
        success: function(e) {
            art.dialog({id: 'bgimg_up'}).content(e.info).time(1);
            if (e.status) {
                window.location.reload();
            }
        },
    });
    return false;
});
//自地图编辑



