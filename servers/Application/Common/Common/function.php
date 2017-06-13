<?php


function array_string($array) {
	$array_string = '';
	foreach($array as $val) {
		if(is_array($val)) {
			$array_string .= array_string($val);
		} else {
			$array_string .= $val;
		}
	}
	return $array_string;
}

function token($data=array(), $state=false) { //密钥生成，用于接口校验以及前台用户密码生成
	$token = '';
	if(is_array($data)) {
		unset($data['token']);
		unset($data['user_token']);	
		$token = array_string($data);
		$token = hash_hmac('sha256', $token, C('APIKEY'));
	} else {
		$token = hash_hmac('sha256', $data, ''); 
	}
	
	if($state) $token = hash_hmac('sha256', $token, time().'_'.mt_rand(100000,999999));
	
	return $token;
}

function get_user_menu(){
    return session('user_menu');
}

function set_user_menu($user_menu){
    session('user_menu', $user_menu);
}

function unset_user_menu(){
    unset($_SESSION['user_menu']);
}

function get_user_info(){
    return session('user_info');
}

function set_user_info($user_info){
    session('user_info', $user_info);
}

function unset_user_info(){
    unset($_SESSION['user_info']);
}

function get_user_token(){
    return session('user_token');
}

function set_user_token($user_token){
    session('user_token', $user_token);
}

function unset_user_token(){
    unset($_SESSION['user_token']);
}

function curls($url, $ispg='get' ,$data, $type=false, $time=120) {
	
	$data['token_time'] = time();
	$data['token'] = token($data);
	if(get_user_token()) {
		$data['user_token'] = get_user_token();
	}

	if($ispg == 'get') {
		$url = $url.'?'.http_build_query($data);
	}
	
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);								
    curl_setopt($ch, CURLOPT_HEADER, false);							
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $time);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, false); 
	
	if($ispg == 'post') {
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	}
    
	$ret = curl_exec($ch);
    curl_close($ch);
	$data = json_decode($ret, true);
	
	$info = '';
	$status = false;
	if($data['status'] == 0) {
		$status = true;
	} else if($data['status'] == '-2') { //接口数据调试用参数
		$ret = json_decode($ret, true);
		$ret = $ret['data'];
		echo '<pre>';print_r($ret);exit;
    } else if($data['status'] == '10002') {
        unset_user_token();
        unset_user_info();
        unset_user_menu();
        $status = false;
        $info = C('ERRORKEY')[$data['status']];
	} else {
		$status = false;
		$info = C('ERRORKEY')[$data['status']];
	}
	
	$data = array('info' => $info, 'data' => $data['data'], 'status' => $status, 'code'=>$data['status']); 
	if(!$type) $data = json_encode($data);
	return $data;
}

function output($status=0, $data=array()) {	//返回API接口信息
	exit(json_encode(array('data' => $data, 'status' => $status)));
}

function dumps($val) {
    exit(dump($val));
}

function success($info, $url, $data) { //成功提示
    exit(success_ajax($info, $url, $data));
}

function success_ajax($info, $url, $data) { //成功提示
    return json_encode(array('info' => $info, 'url' => $url, 'data' => $data, 'status' => 1));
}

function error($info, $url, $data) { //失败提示
    exit(error_ajax($info, $url, $data));
}

function error_ajax($info, $url, $data) { //失败提示
    return json_encode(array('info' => $info, 'url' => $url, 'data' => $data, 'status' => 0));
}

/**
 * 字符串截取，支持中文和其他编码
 * @static
 * @access public
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * @return string
 */
function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true) {
    $string = '';
    if (function_exists("mb_substr")) {
        $string = mb_substr($str, $start, $length, $charset);
    } else if (function_exists("iconv_substr")) {
        $string = iconv_substr($str, $start, $length, $charset);
    } else {
        $re["utf-8"] = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef][x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";
        $re["gb2312"] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";
        $re["gbk"] = "/[x01-x7f]|[x81-xfe][x40-xfe]/";
        $re["big5"] = "/[x01-x7f]|[x81-xfe]（[x40-x7e]|xa1-xfe]）/";
		
        preg_match_all($re[$charset], $str, $match);
        $string = join("", array_slice($match[0], $start, $length));
    }
	
    if ($suffix) {
        preg_match_all("/./us", $str, $matchs);
		$match_length = count($matchs[0]);
		if ($match_length > $length) {
            $string .= '...';
        }
    }
	
    return $string;
}

//计算字符串长度
function string_length($string) {
    preg_match_all("/./us", $string, $match);
    return count($match[0]);
}

//输出安全的html
function hh($text, $tags = null) {
    $text = trim($text);
    //完全过滤注释
    $text = preg_replace('/<!--?.*-->/', '', $text);
    //完全过滤动态代码
    $text = preg_replace('/<\?|\?' . '>/', '', $text);
    //完全过滤js
    $text = preg_replace('/<script?.*\/script>/', '', $text);
    //过滤危险的属性，如：过滤on事件lang js
    while (preg_match('/<[^><]+\s(lang|on|action|background|codebase|dynsrc|lowsrc)[^=]*=[^>]+>/i', $text, $mat)) {
        $text = preg_replace("/(\s)(lang|on|action|background|codebase|dynsrc|lowsrc)[^=]*=[^\s>]*/", "", $text);
    }
    $text = str_replace('  ', ' ', $text);
    return $text;
}

/**
 *+--------------------------------------------------------------------------------------------------------------------
 * 文件上传,用于base64编码图像源文件
 *+--------------------------------------------------------------------------------------------------------------------
 * @Author baoxu  Date:2016/02/23
 *+--------------------------------------------------------------------------------------------------------------------
 * @access public
 *+--------------------------------------------------------------------------------------------------------------------
 * @parame String 必填 $img base64       
 *+--------------------------------------------------------------------------------------------------------------------
 * @parame String 必填 $m 文件存于的第一级目录
 *+--------------------------------------------------------------------------------------------------------------------
 * @parame String 必填 $f 文件存于的第二级目录
 *+--------------------------------------------------------------------------------------------------------------------
 * @parame Int $utype 当前操作的用户类型 (0:暂无,1:前台user表用户,2后台users表用户) 
 *+--------------------------------------------------------------------------------------------------------------------
 * @parame Int $uid 当前操作的用户ID
 *+--------------------------------------------------------------------------------------------------------------------
 * @parame Boolean $type 返回数据类型 {true:数组,false:json}
 *+--------------------------------------------------------------------------------------------------------------------
 * @return JSON
 *+--------------------------------------------------------------------------------------------------------------------
 **/
function upload_mobile_image($img, $m, $f, $utype=0, $uid=0, $type=false) {

        $info = explode(',', $img);
        $type = explode(';', $info[0]);
        $type = explode(':', $type[0]);

        $data = array();
        $data['content_files'] = base64_decode($info[1]) ;
        $data['type'] = $type[1];
        $data['m'] = $m;
        $data['f'] = $f;
        $data['uid'] = $uid;
        $data['utype'] = $utype;
        
        return curls(C('APIURL').'Files/Upload', 'post', $data, $type);
}

/**
 *+--------------------------------------------------------------------------------------------------------------------
 * 文件上传
 *+--------------------------------------------------------------------------------------------------------------------
 * @Author LWX  Date:2015/12/5
 *+--------------------------------------------------------------------------------------------------------------------
 * @access public
 *+--------------------------------------------------------------------------------------------------------------------
 * @parame String 必填 $tmp_name $_FILES[*]		 废弃参数
 *+--------------------------------------------------------------------------------------------------------------------
 * @parame String 必填 $m 文件存于的第一级目录
 *+--------------------------------------------------------------------------------------------------------------------
 * @parame String 必填 $f 文件存于的第二级目录
 *+--------------------------------------------------------------------------------------------------------------------
 * @parame Int $utype 当前操作的用户类型 (0:暂无,1:前台user表用户,2后台users表用户) 
 *+--------------------------------------------------------------------------------------------------------------------
 * @parame Int $uid 当前操作的用户ID
 *+--------------------------------------------------------------------------------------------------------------------
 * @parame Boolean $type 返回数据类型 {true:数组,false:json}
 *+--------------------------------------------------------------------------------------------------------------------
 * @return JSON
 *+--------------------------------------------------------------------------------------------------------------------
 **/
function upload_files($tmp_name, $m, $f, $utype=0, $uid=0, $type=false) {
		
		$data = array();
		$tmp_name = key($_FILES);

		$handle = fopen($_FILES[$tmp_name]['tmp_name'], "r");
		$data['content_files'] = fread($handle,filesize($_FILES[$tmp_name]['tmp_name']));
		fclose($handle);
		
		$data['size'] = $_FILES[$tmp_name]['size'];
		$data['type'] = $_FILES[$tmp_name]['type'];
		$data['m'] = $m;
		$data['f'] = $f;
		$data['uid'] = $uid;
		$data['utype'] = $utype;
			
		return curls(C('APIURL').'Files/Upload', 'post', $data, $type);
}

/**
 *+--------------------------------------------------------------------------------------------------------------------
 * 文件上传(预览保存)
 *+--------------------------------------------------------------------------------------------------------------------
 * @Author zhangnan  Date:2016/07/08
 *+--------------------------------------------------------------------------------------------------------------------
 * @access public
 *+--------------------------------------------------------------------------------------------------------------------
 * @parame String/Array	必填 $key 预览文件的key  String:大于64KEY  Array('key'=>大于64KEY)
 *+--------------------------------------------------------------------------------------------------------------------
 * @parame String	必填 $m 文件存于的第一级目录 
 *+--------------------------------------------------------------------------------------------------------------------
 * @parame String	必填 $f 文件存于的第二级目录 
 *+--------------------------------------------------------------------------------------------------------------------
 * @parame Int 非必 $utype 当前操作的用户类型 (0:暂无,1:前台user表用户,2后台users表用户)	
 *+--------------------------------------------------------------------------------------------------------------------
 * @parame Int 非必 $uid 当前操作的用户ID					
 *+--------------------------------------------------------------------------------------------------------------------
 * @parame Boolean $type 返回数据类型 {true:数组,false:json}
 *+--------------------------------------------------------------------------------------------------------------------
 * @return JSON
 *+--------------------------------------------------------------------------------------------------------------------
 **/
function upload_preview_save($key, $m, $f, $utype=0, $uid=0, $type) {
		
		$data = array();
		
		$data['key'] = $key;
		$data['ms'] = $m;
		$data['fs'] = $f;
		$data['uid'] = $uid;
		$data['utype'] = $utype;
		
		$urls = 'UploadPreviewSave';
		if(is_array($data['key'])) {
			$urls = 'UploadPreviewSaveBatch';
		}
			
		return curls(C('APIURL').'Files/'.$urls, 'get', $data, $type);
}

/**
 *+--------------------------------------------------------------------------------------------------------------------
 * 文件预览(大于一天的文件保存时间，长时间使用请调用保存方法)
 *+--------------------------------------------------------------------------------------------------------------------
 * @Author LWX  Date:2016/6/30
 *+--------------------------------------------------------------------------------------------------------------------
 * @access public
 *+--------------------------------------------------------------------------------------------------------------------
 * @parame Boolean $type 返回数据类型 {true:数组,false:json}
 *+--------------------------------------------------------------------------------------------------------------------
 * @return JSON
 *+--------------------------------------------------------------------------------------------------------------------
 **/
function upload_preview($type=false) {
	$data = array();
	$tmp_name = key($_FILES);
	
	foreach ($_FILES[$tmp_name] as $key => $value) {
		if(is_array($value)){
			$_FILES[$tmp_name][$key] = $value[0];
		}
	}
	$handle = fopen($_FILES[$tmp_name]['tmp_name'], "r");
	$data['content_files'] = fread($handle,filesize($_FILES[$tmp_name]['tmp_name']));
	fclose($handle);
	$data['type'] = $_FILES[$tmp_name]['type'];
	return curls(C('APIURL').'Files/UploadPreview', 'post', $data, $type);
}

/**
 *+--------------------------------------------------------------------------------------------------------------------
 * 文件剪切预览(大于一天的文件保存时间，长时间使用请调用保存方法)
 *+--------------------------------------------------------------------------------------------------------------------
 * @Author LWX  Date:2016/6/30
 *+--------------------------------------------------------------------------------------------------------------------
 * @access public
 *+--------------------------------------------------------------------------------------------------------------------
 * @parame Boolean $type 返回数据类型 {true:数组,false:json}
 *+--------------------------------------------------------------------------------------------------------------------
 * @return JSON
 *+--------------------------------------------------------------------------------------------------------------------
 **/
function upload_cut_preview($type=false) {
	$data = array();
	$tmp_name = key($_FILES);
	
	foreach ($_FILES[$tmp_name] as $key => $value) {
		if(is_array($value)){
			$_FILES[$tmp_name][$key] = $value[0];
		}
	}
	$handle = fopen($_FILES[$tmp_name]['tmp_name'], "r");
	$data['content_files'] = fread($handle,filesize($_FILES[$tmp_name]['tmp_name']));
	fclose($handle);
	$data['type'] = $_FILES[$tmp_name]['type'];
	return curls(C('APIURL').'Files/UploadCutPreview', 'post', $data, $type);
}

/**
 *+--------------------------------------------------------------------------------------------------------------------
 * 返回地区名称
 *+--------------------------------------------------------------------------------------------------------------------
 * @Author liwenxuan Date:2016/8/18
 *+--------------------------------------------------------------------------------------------------------------------
 * @access public
 *+--------------------------------------------------------------------------------------------------------------------
 * @type 
 *+--------------------------------------------------------------------------------------------------------------------
 * @parame String 必填 area_id 地区编码
 *+--------------------------------------------------------------------------------------------------------------------
 * @parame String 选填 times 样式(默认 %S%s%q )	%S省，%s市，%q区
 *+--------------------------------------------------------------------------------------------------------------------
 * @return String times 格式化后的地区信息
 *+--------------------------------------------------------------------------------------------------------------------
**/
function areaToName($code, $times) {
	
	$code = $code.'';
	$S = substr($code, 0,2)."0000";
	$s = '';
	$q = '';
	if($S != $code) {
		$s = substr($code, 0, -2)."00";
		if($s != $code) {
			$q = $code;
		}
	}

	if(empty($times)) {
		$times = '%S%s%q';
	}

	$area = C('AREA');
	$times = preg_replace("/%S/", $area[$S], $times);
	$times = preg_replace("/%s/", $area[$s], $times);
	$times = preg_replace("/%q/", $area[$q], $times);
	
	return $times;
}
/**
 *+--------------------------------------------------------------------------------------------------------------------
 * 文件删除
 *+--------------------------------------------------------------------------------------------------------------------
 * @Author baoxu  Date:2016/04/21
 *+--------------------------------------------------------------------------------------------------------------------
 * @access public
 *+--------------------------------------------------------------------------------------------------------------------
 * @parame String 必填 $array       
 *+--------------------------------------------------------------------------------------------------------------------
 * @return JSON
 *+--------------------------------------------------------------------------------------------------------------------
 **/   
function delfiles($array){
    foreach ($array as $key => $value) {
        if(!empty($value)){
            $url[$value] = $value ;
        }
    }
    unset($array) ;
    return curls(C('APIURL').'Files/Del', 'get', array('key_json'=>$url), true);
}

/**
 *+----------------------------------------------------------
 * b2b2c商城，判断订单号是否是总订单号
 *+----------------------------------------------------------
 * @Author zhangnan  Date:2016/08/1
 *+----------------------------------------------------------
 * @access public
 *+----------------------------------------------------------      
 * @return Int 1总订单号,0子订单号
 *+----------------------------------------------------------
**/
function b2b2c_is_order_sum_sn($sn){
    return substr($sn, 0, 1) == '9' ? 1 : 0;
}

/**
 *+----------------------------------------------------------
 * b2b2c商城，秒数转时间
 *+----------------------------------------------------------
 * @Author zhangnan  Date:2016/08/17
 *+----------------------------------------------------------
 * @access public
 *+----------------------------------------------------------      
 * @return String 格式化的时间
 *+----------------------------------------------------------
**/
function Sec2Time($time){
    if(is_numeric($time)){
	    $value = array(
	      "days" => 0, "hours" => 0,
	      "minutes" => 0, "seconds" => 0,
	    );

	    if($time >= 86400){
	      $value["days"] = floor($time/86400);
	      return $value["days"] ."天";
	    }
	    if($time >= 3600){
	      $value["hours"] = floor($time/3600);
	      return $value["hours"] ."小时";
	    }
    }else{
    	return (bool) FALSE;
    }
 }

 /**
 *+----------------------------------------------------------
 * b2b2c商城，生成售后用的退款单号
 *+----------------------------------------------------------
 * @Author zhangnan  Date:2016/08/13
 *+----------------------------------------------------------
 * @access public
 *+----------------------------------------------------------      
 * @return String
 *+----------------------------------------------------------
**/
function refund_number(){
    $part_date = date("Ymd",time());
    $rand_part = rand(1000000, 9999999);
    $part3 = substr($rand_part, 0, 4);
    $part4 = substr($rand_part, 4, 3);
    return $part_date.'7'.$part3.$part4;
}

 /**
 *+----------------------------------------------------------
 * b2b2c商城，生成退单用的退款单号
 *+----------------------------------------------------------
 * @Author zhangnan  Date:2016/09/19
 *+----------------------------------------------------------
 * @access public
 *+----------------------------------------------------------      
 * @return String
 *+----------------------------------------------------------
**/
function order_refund_number(){
    $part_date = date("Ymd",time());
    $rand_part = rand(1000000, 9999999);
    $part3 = substr($rand_part, 0, 4);
    $part4 = substr($rand_part, 4, 3);
    return $part_date.'6'.$part3.$part4;
}