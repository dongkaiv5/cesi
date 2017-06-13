var imgFlag = false;
var divFlag = false;

var t_img; // 定时器
var isLoad = true; // 控制变量

function showDeleteDiv(resourceCode){
    imgFlag = true;
    $("#"+resourceCode+"DIV").css("display","block");
};

function hideDeleteDiv(resourceCode){
    if(!imgFlag && !divFlag){
        $("#"+resourceCode+"DIV").css("display","none");   
    }
};

function imgOnmouseout(){
    imgFlag=false;
};

function divOnmouseover(resourceCode){
    divFlag = true;
    showDeleteDiv(resourceCode);
};

function divOnmouseout(resourceCode){
    divFlag = false;
    if(imgFlag){
        showDeleteDiv(resourceCode);
    }else{
        hideDeleteDiv(resourceCode);
    }
};

function removeGroupNotActivitySharingPhotoByResourceCode(resourceCode,type) {
    // alert("删除图片"+resourceCode);
    // $("#"+resourceCode+"IMG").parent().detach();
    if(type==1){
          $("#img_uploaded2").attr('src', '');
          $("#img_uploaded2").css('display', 'none');
          $("#img_path2").val('');
    }else{
        $("#img_uploaded1").attr('src', '');
        $("#img_uploaded1").css('display', 'none');
        $("#img_path1").val('');
    }
    $("#"+resourceCode+"DIV").detach();

}

// 判断图片加载的函数
function isImgLoad(callback){
  // 注意我的图片类名都是cover，因为我只需要处理cover。其它图片可以不管。
  // 查找所有封面图，迭代处理
  $('#img_uploaded1').each(function(){
      // 找到为0就将isLoad设为false，并退出each
      if(this.height === 0){
          isLoad = false;
          return false;
      }
  });
  // 为true，没有发现为0的。加载完毕
  if(isLoad){
      clearTimeout(t_img); // 清除定时器
      // 回调函数
      callback();
  // 为false，因为找到了没有加载完成的图，将调用定时器递归
  }else{
      isLoad = true;
      t_img = setTimeout(function(){
          isImgLoad(callback); // 递归扫描
      },500); // 我这里设置的是500毫秒就扫描一次，可以自己调整
  }
}

function add_delete_div(){
  $("#img_uploaded1").each(function(i){
      var id = $(this).attr("alt")+"DIV";
      if($("#"+id).length > 0){
        return;
      }
      var divObj=$("<div onclick=removeGroupNotActivitySharingPhotoByResourceCode('"+$(this).attr('alt')+"'); onmouseover=divOnmouseover('"+$(this).attr('alt')+"'); onmouseout=divOnmouseout('"+$(this).attr('alt')+"');>×</div>");
      divObj.addClass("divX");
      divObj.attr("id",$(this).attr("alt")+"DIV");
      divObj.attr("title","删除该图片");
      divObj.css({position:"absolute", left: $(this).position().left+90, top:$(this).position().top});
      $(this).parent().append(divObj);
  });    
}
function add_delete_div_2(){
  $("#img_uploaded2").each(function(i){
      var id = $(this).attr("alt")+"DIV";
      if($("#"+id).length > 0){
        return;
      }
      var filename=$(this).attr("alt");
      var divObj=$("<div onclick=removeGroupNotActivitySharingPhotoByResourceCode('"+filename+"',1); >×</div>");
      divObj.addClass("divD");
      divObj.attr("id",$(this).attr("alt")+"DIV");
      divObj.attr("title","删除该图片");
      divObj.css({position:"absolute", left: $(this).position().left+90, top:$(this).position().top});
      $(this).parent().append(divObj);
  });  
 
}