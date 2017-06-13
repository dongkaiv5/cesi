
$(function(){
    var host = $('#wrapper').attr('data-host');
    var tabChange = function(name) {
        $('.list>li.' + name).addClass('on').siblings().removeClass('on');
        $('.forms>div.' + name).show().siblings().hide();
    };
    var checkHash = function() {
        if(window.location.hash === '#vote') {
            tabChange('vote');
        } else if(window.location.hash === '#survey') {
            tabChange('survey');
        } else {
            tabChange('notice');
        }
    };
    //添加选项option
    $('#page-wrapper').on('click', '.add-option', function(){
        var imagsrc = $("input[name='icon_del']").val();
        $options = $(this).prev();
        var number = $options.children().length + 1;
        $options.append('<div class="in-group"><span class="fa fa-photo btn-option-upload fileinput-button"><input class="option-image-upload" type="file" name="carousel_file" onchange="option_image_upload(this)"></span><span class="btn-option-del fa fa-remove"></span><span class="option-title">选项' + number + '</span><input class="option" type="text" placeholder="50个汉字内 "><span class="survey_option_del" style="position:absolute;right:33px;top:3px;"><img src="'+imagsrc+'" /></span></div>');
        //optionUploadInit();
    });
    //删除选项option

    $('#page-wrapper').on('click', '.survey_option_del', function(){
        $(this).parent().remove();
    });
    $('#page-wrapper').on('focus', '.in-group input', function(){
        $(this).addClass('focus');
    });
    $('#page-wrapper').on('blur', '.in-group input', function(){
        $(this).removeClass('focus');
    });
    $('#page-wrapper').on('focus', '.box2 input, .box input', function(){
        if($(this).parent().siblings().length > 1) {
            $(this).siblings('.btn-option-del').show();
        }
    }).on('blur', '.box2 input, .box input', function(){
        var $this = $(this);
        setTimeout(function(){
            $this.siblings('.btn-option-del').hide();
        }, 1);
    });
    $('#page-wrapper').on('click', '.question-edit .btn-del', function(){
        $(this).parents('.survey-question').remove();
        refreshQuestionNumber();
    });
    $('#page-wrapper').on('click', '.question-edit .btn-copy', function(){
        var $item = $(this).parents('.survey-question');
        $item.clone().insertAfter($item);
        refreshQuestionNumber();
        //optionUploadInit();
    });
    $('#page-wrapper').on('click', '.btn-option-del', function(){
        $(this).parent().remove();
        refreshOptionNumber();
    });
    $('#page-wrapper').on('click', '.btn-file-del', function(){
        $(this).parent().remove();
    });

    $(window).on('hashchange', function(){
        checkHash();
    });

    var single_html = $('#data-survey-single').val();
    var multi_html = $('#data-survey-multi').val();
    var text_html = $('#data-survey-text').val();

    $("#survey-questions").sortable({
        axis: 'y',
        handle: '.survey-drop-area',
        start: function(event, ui){
            $(ui.item).css('background', '#f8f8f8');
        },
        stop: function(event, ui){
            $(ui.item).css('background', 'none');
        },
        update: function(){
            refreshQuestionNumber();
        }
    });
    var newQuestion = function(question_type){
        if(typeof question_type === 'undefined') {
            question_type = 'single';
        }
        var question_type = $('[name=question-type]:checked').val();
        $('#survey-questions').append(eval(question_type + '_html'));
        $('#survey-questions>li:last .box2').sortable({
            axis: 'y',
            handle: '.option-title',
            update: function(event, ui){
                var question_order = $('.survey-question').index($(ui.item).parents('.survey-question'));
                refreshOptionNumber();
            }
        });
        refreshQuestionNumber();
        //optionUploadInit();
    };

    $('#survey-question-add').on('click', function(){
        newQuestion();
    });
    $('.box').sortable({
        axis: 'y',
        handle: '.option-title',
        update: function(event, ui){
            refreshOptionNumber();
        }
    });
    var refreshQuestionNumber = function() {
        var order = 0;
        $('.survey-question .left .question-order').each(function(){
            order++;
            $(this).text('Q' + order);
        });
    };
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
    $('#survey-submit').on('click', function(){
        submitSurveyData();
    });
    /*
    $('#vote-submit').on('click', function(){
        submitVoteData();
    });
    $('#notice-submit').on('click', function(){
        submitNoticeData();
    });*/
    var submitSurveyData = function() {
        var data = {};
        try {
            data.title = $.trim($('#survey-title').val());
            if(data.title == '') {
                
                art.dialog({
                    icon: "succeed",
                    fixed: true,
                    lock: true,
                    background: "#CCCCCC",
                    opacity: 0.87,  // 透明度
                    content: "问卷标题不能为空",
                    time: 2,
                });
                $('#survey-title').focus();
                throw 'survey title is empty';
            }
            if(data.title.length > 30){
                
                art.dialog({
                    icon: "succeed",
                    fixed: true,
                    lock: true,
                    background: "#CCCCCC",
                    opacity: 0.87,  // 透明度
                    content: "字数限制为30字，当前为"+data.title.length+"字",
                    time: 2,
                });
                $('#survey-title').focus();
                throw "survey title is too lang";
            }
            data.image = $(".carousel_option-image").attr("data-file-id");
            data.class_id = $.trim($('input[name="class_id"]').val());
            data.source_types = $.trim($('input[name="source_types"]').val());
            data.types = $.trim($('input[name="types"]').val());
            data.start_time = $.trim($('#survey-start-date').val());
            layout_id = $.trim($('input[name="layout_id"]').val());
            http_url = $.trim($('input[name="http_url"]').val());
            var carouselimglength = $(".carousel_option-image").length;
            var image_info; 
            if(carouselimglength == 0){
                image_info ="";
            }else{
                image_info = $(".carousel_option-image").attr("data-file-id");
            }
            if(data.source_types == "Carousel" && image_info == ''){
                art.dialog({
                        icon: "succeed",
                        fixed: true,
                        lock: true,
                        background: "#CCCCCC",
                        opacity: 0.87,  // 透明度
                        content: "问卷头图不能为空",
                        time: 2,
                    });
                 throw '问卷头图不能为空';
            }
            if(data.start_time == '') {
                $('#survey-start-date').focus();
                throw 'start time is empty';
            }
            data.end_time = $.trim($('#survey-end-date').val());
            if(data.end_time == '') {
                $('#survey-end-date').focus();
                throw 'end time is empty';
            }
            data.items = [];
            $('.survey-question').each(function(){
                var row_data = {};
                var $this = $(this);
                row_data.title = $.trim($this.find('.survey-question-title').val());
                if(row_data.title.length > 50){
                    art.dialog({
                        icon: "succeed",
                        fixed: true,
                        lock: true,
                        background: "#CCCCCC",
                        opacity: 0.87,  // 透明度
                        content: "字数限制为50字，当前为"+row_data.title.length+"字",
                        time: 2,
                    });
                    $this.find(".survey-question-title").focus();
                    throw '选项字数过长';
                }
                if(row_data.title == '') {      
                    art.dialog({
                        icon: "succeed",
                        fixed: true,
                        lock: true,
                        background: "#CCCCCC",
                        opacity: 0.87,  // 透明度
                        content: "选项标题不能为空",
                        time: 2,
                    });
                    $this.find('.survey-question-title').focus();
                    throw 'question title empty'
                }
                row_data.type = $this.find('.survey-question-type').attr('data-question-type');
                if(row_data.type != '3') {
                    row_data.options = [];
                    $this.find('.option').each(function(){
                        $that = $(this);
                        var content = $.trim($that.val());
                        var imagelength = $that.siblings('.option-image').length;
                        if (imagelength > 0) {
                            var image = $that.siblings('.option-image').attr('data-image');
                        } else {
                            var image = '';
                        }
                        if (imagelength == 0) {
                            if (content == "") {
                                art.dialog({
                                    fixed: true,
                                    lock: true,
                                    background: "#CCCCCC",
                                    opacity: 0.87,
                                    content: "投票选项不能为空",
                                    time: 1,
                                });

                                $that.focus();
                                $("#survey-submit").val("提交问卷");

                                throw 'content is empty';
                            }
                        }else{
                            if (content == "" && !image) {
                                art.dialog({
                                    fixed: true,
                                    lock: true,
                                    background: "#CCCCCC",
                                    opacity: 0.87,
                                    content: "投票选项不能为空",
                                    time: 1,
                                });
                                $("#survey-submit").val("提交问卷");
                                $that.focus();

                                throw 'content is empty';
                            }
                        }
                         if (content.length > 50) {
                            art.dialog({
                                icon: "succeed",
                                fixed: true,
                                lock: true,
                                background: "#CCCCCC",
                                opacity: 0.87, // 透明度
                                content: "字数限制为50字，当前为" + content.length + "字",
                                time: 2,
                            });
                            $that.focus();
                            $that.addClass("focus");

                            $("#survey-submit").val("提交问卷");
                            throw '选项字数过长';
                        }
                        var file_id = $that.siblings('.option-image').attr('data-file-id');
                        if(!content && !file_id) {
                            $that.focus();

                            throw 'option content empty';
                        } else {
                            var tmp = {content: '', files: []};
                            if(content) {
                                tmp.content = content;
                            }
                            if(file_id) {
                                tmp.files.push({
                                    type: 1,
                                    file_name: file_id,
                                    attri: 0
                                });
                            }
                            row_data.options.push(tmp)
                        }
                    });
                }
                data.items.push(row_data);
            });
            if(data.items.length === 0) {
                throw 'at least 1 question';
            }
            data.result_public = $('.survey-result-public:checked').val();
            data.ispush = $('.survey-result-ispush:checked').val();
        } catch(err) {
            return false;
        }
//        alert(host);
        $.post(host + '/add', {'type': 'survey', 'data': data,'layout_id':layout_id,'http_url':http_url}, function(e){
            console.log(e)    
            art.dialog({
                    icon: "succeed",
                    fixed: true,
                    lock: true,
                    background: "#CCCCCC",
                    opacity: 0.87,  // 透明度
                    content: e.info,
                    time: 2,
                });
            if(e.status){
                window.location.href = e.url;
            }
        },'json');
    };
   
    $('#notice-intended-time, #vote-start-date, #vote-end-date, #survey-start-date, #survey-end-date').datetimepicker({
        'todayHighlight': true,
        'format': 'yyyy-mm-dd hh:00',
        'minView': 'day',
        'minuteStep': 60,
        'language': 'zh-CN',
        'autoclose': true
    });

    $('#notice-image-upload').fileupload({
        url: host + '/upload/image',
        dropZone: null,
        add: function(e, data) {
            var uploadErrors = [];
            var acceptFileTypes = /(\.|\/)(gif|jpe?g|png)$/i;
            if(data.originalFiles[0]['type'].length && !acceptFileTypes.test(data.originalFiles[0]['type'])) {
                uploadErrors.push('Not an accepted file type');
            }
            if(data.originalFiles[0]['size'].length && data.originalFiles[0]['size'] > 5242880) {
                uploadErrors.push('Filesize is too big');
            }
            if(uploadErrors.length > 0) {
                alert(uploadErrors.join("\n"));
            } else {
                data.submit();
            }
        },
        dataType: 'json',

        done: function (e, data) {
            if(data.result.success) {
                $('#files').append('<li data-file-id="'+data.result.file.id+'"><i class="icon-clip fa fa-paperclip"></i><span>'+data.result.file.name+'&nbsp;('+data.result.file.size+'KB)</span><i class="btn-file-del fa fa-remove"></i></li>');
            } else {
                alert(data.result.msg);
            }
        }
    });
    
    newQuestion();
    refreshQuestionNumber();
    checkHash();
    //optionUploadInit();
    
});