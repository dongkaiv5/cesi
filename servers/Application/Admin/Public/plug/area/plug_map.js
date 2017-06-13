  
  var add_overlay_flag = false;

  function init_map(lng, lat){
    add_overlay_flag = false;
    // console.log("areastr="+areastr);
    // console.log("address="+address);
    var fulladdress = areastr+address;
    // console.log("fulladdress="+fulladdress);
    // 百度地图API功能
    var map = new BMap.Map("allmap");    // 创建Map实例
    // map.clearOverlays();
    var localSearch = new BMap.LocalSearch(map);
　　localSearch.setSearchCompleteCallback(function (searchResult) {
        // console.log("setSearchCompleteCallback");
        if(add_overlay_flag){
          return;
        }
　　　　var poi = searchResult.getPoi(0);
        // console.log(poi);
        if(poi){
          // console.log("lng="+lng+", lat="+lat);
          if(lng== ""){
            lng = poi.point.lng;
          }
          if(lat== ""){
            lat = poi.point.lat;
          }
          // map.centerAndZoom(new BMap.Point(poi.point.lng, poi.point.lat), 17);  // 初始化地图,设置中心点坐标和地图级别
          // console.log("定位1, lng="+lng+", lat="+lat);
          map.centerAndZoom(new BMap.Point(lng, lat), 17);  // 初始化地图,设置中心点坐标和地图级别
          map.addControl(new BMap.MapTypeControl({mapTypes:[BMAP_NORMAL_MAP]}));   //添加地图类型控件
          map.setCurrentCity(city);          // 设置地图显示的城市 此项是必须设置的
          map.enableScrollWheelZoom(true);     //开启鼠标滚轮缩放

          // var marker = new BMap.Marker(new BMap.Point(poi.point.lng, poi.point.lat));
          var marker = new BMap.Marker(new BMap.Point(lng, lat));
          add_overlay(map, marker);                    
        }else{
          $('#allmap').html("");
          var map2 = new BMap.Map("allmap");    // 创建Map实例
          var localSearch = new BMap.LocalSearch(map2);
      　　localSearch.setSearchCompleteCallback(function (searchResult) {
              if(add_overlay_flag){
                return;
              }
      　　　　var poi = searchResult.getPoi(0);
              // console.log(poi);
              if(poi){
                  if(!lng){
                    lng = init_long;
                  }else{
                    lng = poi.point.lng;
                  }
                  if(!lat){
                    lat = init_lat;
                  }else{
                    lat = poi.point.lat;
                  }
                  // console.log("定位2, lng="+lng+", lat="+lat);
                  map2.centerAndZoom(new BMap.Point(lng, lat), 17);  // 初始化地图,设置中心点坐标和地图级别
                  map2.addControl(new BMap.MapTypeControl({mapTypes:[BMAP_NORMAL_MAP]}));   //添加地图类型控件
                  map2.setCurrentCity(city);          // 设置地图显示的城市 此项是必须设置的
                  // map2.enableScrollWheelZoom(true);     //开启鼠标滚轮缩放

                  var marker = new BMap.Marker(new BMap.Point(lng, lat));
                  add_overlay(map2, marker);                    
              }else{

                  $('#allmap').html("对不起，无法获取地图信息。");
              }
      　　});    
          localSearch.search(areastr);          
        }
    });

    localSearch.search(fulladdress);  
    //添加覆盖物
    function add_overlay(map, marker){
        map.addOverlay(marker);            //增加点
        // marker.enableDragging();//关闭标注拖拽功能
        add_overlay_flag = true;
        update_position(marker);

        marker.addEventListener("dragend", function(e){
          // console.log("dragend");
          update_position(marker);
        });       

        function update_position(marker){
          //获取覆盖物位置
          var o_Point_now =  marker.getPosition();
          var lng = o_Point_now.lng;
          var lat = o_Point_now.lat;
          // console.log(lng + "---, " + lat);
          $('#long').val(lng);
          $('#lat').val(lat);
        }   
    }
  }

  function update_map(){
    var area_province = $.trim($('#area_province').find("option:selected").text());
    var area_city = $.trim($('#area_city').find("option:selected").text());
    var area_district = $.trim($('#area_district').find("option:selected").text());
    address = $.trim($('#address').val());

    // console.log("area_district="+area_district);

    if(area_district != "请选择"){
      areastr = area_province+' '+area_city+' '+area_district;
    }else if(area_city != "请选择"){
      areastr = area_province+' '+area_city;
    }else if(area_province != "请选择"){
      areastr = area_province;
    }else{
      areastr = "北京市";
    }

    init_map("","");
  }