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
        try {
            //host = $.trim($("#page-wrapper").attr("data_url"));
            data.title = $.trim($('#vote-title').val());
            if(data.title == '') {
                $('#vote-title').focus();
                art.dialog({
                    icon: "succeed",
                    fixed: true,
                    lock: true,
                    background: "#CCCCCC",
                    opacity: 0.87,  // 透明度
                    content: "投票标题不能为空",
                    time: 2
                });
                throw 'vote title is empty';
            }
            if(data.title.length >30){
                $('#vote-title').focus();
                art.dialog({
                    icon: "succeed",
                    fixed: true,
                    lock: true,
                    background: "#CCCCCC",
                    opacity: 0.87,  // 透明度
                    content: "字数过多限制30字以内，当前"+data.title.length+"字",
                    time: 2
                });
                throw "title is to large";
            }
            
            data.source_types = $('#source_types').val();
            var image_info = $(".coursel_option-image").attr("data-image");
            if(image_info == '' && data.source_types == "Carousel"){
                art.dialog({
                    icon: "succeed",
                    fixed: true,
                    lock: true,
                    background: "#CCCCCC",
                    opacity: 0.87,  // 透明度
                    content: "投票头图不能为空",
                    time: 2
                });
                $(".coursel_option-image").focus();
                throw 'banner can not empty';
            }
            data.start_time = $.trim($('#vote-start-date').val());
            if(data.start_time == '') {
                $('#vote-start-date').focus();
                throw 'start time is empty';
            }
            data.end_time = $.trim($('#vote-end-date').val());
            if(data.end_time == '') {
                $('#vote-end-date').focus();
                throw 'end time is empty';
            }
            data.options = [];
            $('#vote-option .option').each(function(){
                $this = $(this);
                var content = $.trim($this.val());
                var imagelength = $this.siblings('.option-image').length;
                if(imagelength >0){
                    var image = $this.siblings('.option-image').attr('data-image');
                }else{
                    var image = "";
                }
                
                if(imagelength == 0){
                    if(content == ""){
                        $("#vote-submit").html("提交")
                        art.dialog({
                            icon: "succeed",
                            fixed: true,
                            lock: true,
                            background: "#CCCCCC",
                            opacity: 0.87,  // 透明度
                            content: "投票选项内容不能为空",
                            time: 2
                        });
                        $this.focus();
                        
                        throw 'content is empty';
                    }
                }else{
                    if(content == "" && !image){
                        $("#vote-submit").html("提交")
                        art.dialog({
                            icon: "succeed",
                            fixed: true,
                            lock: true,
                            background: "#CCCCCC",
                            opacity: 0.87,  // 透明度
                            content: "投票选项不能为空",
                            time: 2
                        });
                        $this.focus();
                        
                        throw 'content is empty';
                    }
                }
                if(content.length > 50){
                    art.dialog({
                        icon: "succeed",
                        fixed: true,
                        lock: true,
                        background: "#CCCCCC",
                        opacity: 0.87,  // 透明度
                        content: "投票选项内容过长，限制字数20.当前"+content.length+"字",
                        time: 2
                    });
                        $this.focus();
                        
                        throw 'content is to largey';
                    }
                
                if(!content && !image) {
                    $this.focus();
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
            data.ispush = $("input[name='ispush']:checked").val();
            
            data.image = $(".coursel_option-image").attr("data-image");
            http_url = $("input[name='http_url']").val();
            $.post(GV.MODULE_URL+'Carousel/add', {'data': data,'type':'vote','layout_id':data.layout_id,"http_url":http_url}, function(e){
                    var html = "<span>"+e.info+"</span>";
                    $("#vote-submit").parent().append(html);
                if(e.status){
                    if(e.status){
                        window.location.href = e.url;
                    }
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
        url:GV.MODULE_URL+'Carousel/upload', 
        success:function(e) {
            if(e.status) {
                var $htmls = '';
                    $htmls += '<div data-image="'+e.data.image+'" class="option-image">';
                        $htmls += '<i><img src="'+e.data.image_url+'" height="40px" ></i>';
                        //$htmls += '<span>('+e.data.size+'KB)</span>';
                        $htmls += '<i class="btn-file-del fa fa-remove">删除</i>';
                    $htmls += '</div>';  
                    $uploads_image_obj.parents(".in-group").children('.option-image').remove();
                    $uploads_image_obj.parents(".in-group").append($htmls);
            } else {
               art.dialog({
                    icon: "succeed",
                    fixed: true,
                    lock: true,
                    background: "#CCCCCC",
                    opacity: 0.87,  // 透明度
                    content: e.info,
                    time: 2
                });
            }
        }
    });
    return false;
});
//封面图上传
function carousel_file_upload($objs){
    $objs = $($objs);
    $objs.parent().children("div").hide();
    carousel_obj = $objs.clone(true);
    $objs.parent().append(carousel_obj);
    $("#carousel_uploads_image").children("input[type='file']").remove();
    $("#carousel_uploads_image").append($objs);
     $("#carousel_uploads_image").submit();
}
$("#carousel_uploads_image").submit(function(){
    $(this).ajaxSubmit({ 
        type:"post", 
        dataType:"json", 
        url:GV.MODULE_URL+'Carousel/upload', 
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
