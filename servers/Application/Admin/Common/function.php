<?php
 
function sp_password($val) { //后台密码加密
    return md5($val);
}

function is_admin($user_id = 0) {   //是否超级管理员
    if ($user_id) {
        $role_id = M('users')->where(array('id' => $user_id))->getField('role_id');
        if ($role_id == -1) {
            return true;
        } else {
            return false;
        }
    } else {
        if (session('role_id') == -1) {
            return true;
        } else {
            return false;
        }
    }
}

function is_role($m, $a) {
    $role_id = session('role_id');
    if($role_id == '-1') {
        return true;
    } else {
        if(M('access')->where(array('role_id'=>$role_id, 'g'=>'Admin', 'm'=>$m, 'a'=>$a))->count()) {
            return true;
        } else {
            return false;
        }
    }
}

function get_user_id() { //获取当前登录用户的ID
    return intval(session('user_id'));
}

function get_user_org_id() { //获取当前用户的org_id
    return intval(session('org_id'));
}

function set_user_org_id($org_id) { //设置当前用户的机构
    session('org_id', $org_id);
}

/**
 * +----------------------------------------------------------
 * 获取机构名称
 * +----------------------------------------------------------
 * @Author TQH  Date:2014/8/15
 * +----------------------------------------------------------
 * @parame $org_id   机构id
 * +----------------------------------------------------------
 * @return array()
 * +----------------------------------------------------------
 * */
function get_org_name($org_id) {
    if ($org_id) {
        $name = M('org')->where(array('id' => $org_id))->getField('name');
        return $name;
    } else {
        return false;
    }
}

function get_user_name($user_id){
    if(intval($user_id)){
        $name = M("users")->where(array("id"=>$user_id))->getField("user_nicename");
        return $name;
    }
}

function get_coordinate($area, $detail_addr){
    //1.尝试用城市+详细地址获取坐标
    $area_str = areaToName($area);
    $area_str = str_replace(' ', '', $area_str);

    $address = $area_str.$detail_addr;
    $address = str_replace(' ', '', $address);

    $geocoder = json_decode(geocoder($address), 1);
    if(!empty($geocoder)){
        $ret['lng'] = $geocoder['result']['location']['lng'];
        $ret['lat'] = $geocoder['result']['location']['lat'];

        // error_log('get_coordinate, 1='.print_r($ret, 1));
        return $ret;
    }

    //2.如果第一步失败，尝试用城市获取坐标
    $geocoder = json_decode(geocoder($area_str), 1);
    if(!empty($geocoder)){
        $ret['lng'] = $geocoder['result']['location']['lng'];
        $ret['lat'] = $geocoder['result']['location']['lat'];
        // error_log('get_coordinate, 2='.print_r($ret, 1));
    }else{
        $ret['lng'] = 0;
        $ret['lat'] = 0;
        // error_log('get_coordinate, 3='.print_r($ret, 1));
    }

    return $ret;
}

/**
 * function: 地区编码转成地区名称
 * @Author: zhangnan <zhangnan@easycomm.cn>
 * @Date:   2015-06-03
 * @Last Modified time: 2015-06-03
 */
function geocoder($address){
    $url = "http://api.map.baidu.com/geocoder/v2/?address=$address&output=json&ak=5c7c1c1c4f4b39ec0f54e32f4589c7ca";
    // error_log('geocoder, url='.$url);
    $ret = file_get_contents($url);
    return $ret;
} 


