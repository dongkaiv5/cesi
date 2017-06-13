$(function() {
    //添加选项option
    $('#page-wrapper').on('click', '.add-option', function(){
        $options = $(this).prev();
        var number = $options.children().length + 1;
        $options.append('<div class="in-group"><span class="fa fa-photo btn-option-upload fileinput-button"><input class="option-image-upload" type="file" name="image" onchange="uploads_image(this);"></span><span class="btn-option-del">删</span><span class="option-title">选项' + number + '</span><input class="option" type="text" placeholder="50个汉字内"  ></div>');
    });
    $('#page-wrapper').on('focus', '.in-group input', function(){
        $(this).addClass('focus');
    });
    $('#page-wrapper').on('blur', '.in-group input', function(){
        $(this).removeClass('focus');
    });
    $('#page-wrapper').on('focus', '.box2 input, .box input', function(){
        if($(this).parent().siblings().length > 1) {
            $(this).siblings('.btn-option-del').fadeIn(500);
        }
    }).on('blur', '.box2 input, .box input', function(){
        var $this = $(this);
        setTimeout(function() {
            $this.siblings('.btn-option-del').fadeOut(500);
        }, 1);
    });
    $('#page-wrapper').on('click', '.question-edit .btn-del', function(){
        $(this).parents('.survey-question').remove();
    });
    $('#page-wrapper').on('click', '.question-edit .btn-copy', function(){
        var $item = $(this).parents('.survey-question');
        $item.clone().insertAfter($item);
    });
    $('#page-wrapper').on('click', '.btn-option-del', function(){
        if($(this).parent().siblings().length > 1) {
            $(this).parent().remove();
        }
        refreshOptionNumber();
    });
    $('#page-wrapper').on('click', '.btn-file-del', function(){
        $(this).parent().remove();
    });

    $('.box').sortable({
        axis: 'y',
        handle: '.option-title',
        update: function(event, ui){
            refreshOptionNumber();
        }
    });

    var refreshOptionNumber = function() {
        var order;
        $('.survey-question, .box').each(function(){
            order = 0;
            $(this).find('.option-title').each(function(){
                order++;
                $(this).text('选项' + order);
            });
        });
    };

    $('#vote-submit').on('click', function(){
        var data = {};
        $(this).html("提交中...");
        try {
            data.title = $.trim($('#vote-title').val());
            if(data.title == '') {
                art.dialog({
                        fixed: true,
                        lock: true,
                        background: "#CCCCCC",
                        opacity: 0.87,
                        content: "投票标题不能为空",
                        time:1,
                    }); 
                $('#vote-title').focus();
                $("#vote-submit").html("提交")
                throw 'vote title is empty';
            }
            if(data.title.length >30){
                art.dialog({
                    fixed: true,
                    lock: true,
                    background: "#CCCCCC",
                    opacity: 0.87,
                    content: "投票标题字数限制30字，当前"+data.title.length+"字",
                    time:1,
                }); 
                $('#vote-title').focus();
                $("#vote-submit").html("提交")
                throw "title is to large";
            }
            data.start_time = $.trim($('#vote-start-date').val());
            if(data.start_time == '') {
                $('#vote-start-date').focus();
                
                $("#vote-submit").html("提交")
                throw 'start time is empty';
            }
            data.end_time = $.trim($('#vote-end-date').val());
            if(data.end_time == '') {
                $('#vote-end-date').focus();
                
                $("#vote-submit").html("提交")
                throw 'end time is empty';
            }
            data.options = [];
            $('#vote-option .option').each(function(){
                $this = $(this);
                var content = $.trim($this.val());
                var imagelength = $this.siblings('.option-image').length;
                if(imagelength > 0){
                    var image = $this.siblings('.option-image').attr('data-image');
                }else{
                    var image ='';
                }
                if(imagelength == 0){
                    if(content == ""){
                        art.dialog({
                            fixed: true,
                            lock: true,
                            background: "#CCCCCC",
                            opacity: 0.87,
                            content: "投票选项不能为空",
                            time:1,
                        }); 
                        $("#vote-submit").html("提交")
                        $this.focus();
                        
                        throw 'content is empty';
                    }
                }else{
                    if(content == "" && !image){
                        art.dialog({
                            fixed: true,
                            lock: true,
                            background: "#CCCCCC",
                            opacity: 0.87,
                            content: "投票选项不能为空",
                            time:1,
                        }); 
                        $("#vote-submit").html("提交")
                        $this.focus();
                       
                        throw 'content is empty';
                    }
                }
                if(content.length > 50){
                        art.dialog({
                            fixed: true,
                            lock: true,
                            background: "#CCCCCC",
                            opacity: 0.87,
                            content: "投票标题字数限制50字，当前"+content.length+"字",
                            time:1,
                        }); 
                        $("#vote-submit").html("提交");
                        $this.focus();
                        throw 'content is to largey';
                    }
                if(!content && !image) {
                    $this.focus();
                    $("#vote-submit").html("提交");
                    throw 'option content empty';
                } else {
                    var tmp = {content: '', image: ''};
                    if(content) {
                        tmp.content = content;
                    }
                    if(image) {
                        tmp.image = image;
                    }
                    data.options.push(tmp)
                }
            });
            data.vote_type = $('input[name="vote_type"]:checked').val();
            data.open = $('input[name="open"]:checked').val();
            data.class_id = $('#class_id').val();
            data.layout_id = $('#layout_id').val();
            data.source_types = $('#source_types').val();
            
            data.ispush = $("input[name='ispush']:checked").val();
            data.image =  $('.coursel_option-image').attr('data-image');
            var host = $("#page-wrapper").attr("data_url");
            $.post(host+'/add', {'data': data}, function(e){
                if(e.status == 1){
                    art.dialog({
                        fixed: true,
                        lock: true,
                        background: "#CCCCCC",
                        opacity: 0.87,
                        content: e.info,
                        time:1,
                    }); 
                    window.location.href = $('#url').val();
                }else{
                   art.dialog({
                        fixed: true,
                        lock: true,
                        background: "#CCCCCC",
                        opacity: 0.87,
                        content: e.info,
                        time:1,
                    }); 
                }
            }, 'json');
        } catch(err) {
            return false;
        }
    });
});

var $uploads_image_obj;
function uploads_image($objs) {
    $objs = $($objs);
    $uploads_image_obj = $objs.clone(true);
    $objs.parent().append($uploads_image_obj);
    $('#uploads_image').children("input[type='file']").remove();
    $('#uploads_image').append($objs).hide();
    $("#uploads_image").submit();
}

$("#uploads_image").submit(function(){
    $(this).ajaxSubmit({ 
        type:"post", 
        dataType:"json", 
        url:GV.MODULE_URL+'Vote/uploads_image', 
        success:function(e) {
            if(e.status) {
                var $htmls = '';
                    $htmls += '<div data-image="'+e.data.image+'" class="option-image">';
                        $htmls += '<i><img src="'+e.data.image_url+'" height="30px" ></i>';
                        //$htmls += '<span>('+e.data.size+'KB)</span>';
                        $htmls += '<i class="btn-file-del fa fa-remove">删除</i>';
                    $htmls += '</div>';  
                    $uploads_image_obj.parents(".in-group").children('.option-image').remove();
                    $uploads_image_obj.parents(".in-group").append($htmls);
            } else {
                alert(e.info);
            }
        }
    });
    return false;
});
//封面图上传
var carousel_image_upload;
function carousel_file_upload($objs){
    
    $objs = $($objs);
    $objs.parent().children("div").hide();
    carousel_image_upload = $objs.clone(true);
    $objs.parent().append(carousel_image_upload);
    $("#carousel_uploads_image").children("input[type='file']").remove();
    $("#carousel_uploads_image").append($objs);
    $("#carousel_uploads_image").append("<input type='hidden' value='Article'name='target_types'/>").hide();
    
    $("#carousel_uploads_image").submit();
}
$("#carousel_uploads_image").submit(function(){
    $(this).ajaxSubmit({ 
        type:"post", 
        dataType:"json", 
        url:GV.MODULE_URL+'Vote/uploads_image', 
        success:function(e) {
            if(e.status) {
                var $htmls = '';
                    $htmls += '<div data-image="'+e.data.image+'" class="coursel_option-image"  style="display:inline-block">';
                        $htmls += '<i><img src="'+e.data.image_url+'" height="30px" ></i>';
                        //$htmls += '<span>('+e.data.size+'KB)</span>';
                        $htmls += '<i class="btn-file-del fa fa-remove">删除</i>';
                    $htmls += '</div>';  
                    $("input[name='carousel_file']").parent().children(".coursel_option-image").remove();
                    $("input[name='carousel_file']").parent().append($htmls);
            } else {
                alert(e.info);
            }
        }
    });
    return false;
});
