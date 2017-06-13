<?php
/**
 * 公共控制器
 * ============================================================================
 * 版权所有 (C) 
 * 网站地址:
 * ============================================================================
 * $Version: v1.0.0 Beta $
 * $Author: LWX <liwenxuan@pokpets.com> $
 * $Date: 2015/12/2 18:10 $
 * $Id: PublicController.class.php 18:10 2015/12/2  $
**/
namespace Admin\Controller;
use Think\Controller;
class PublicController extends Controller {
    /**
     *+----------------------------------------------------------
     * 登录页
     *+----------------------------------------------------------
     * @Author LWX  Date:2014/08/08
     *+----------------------------------------------------------
     * @access public
     *+----------------------------------------------------------
     * @parame void
     *+----------------------------------------------------------
     * @return html
     *+----------------------------------------------------------
    **/
	public function login() {
		$this->display();
	}
	
    public function logout(){
    	session('[destroy]'); 
    	$this->redirect("public/login");
    }
    
	public function dologin() {
    	$name = I("post.username");
    	if(empty($name)){
    		$this->error("用户名或邮箱不能为空！");
    	}

    	$pass = I("post.password");
    	if(empty($pass)){
    		$this->error("密码不能为空！");
    	}

        /*
    	$verrify = I("post.verify");
    	if(empty($verrify)){
    		$this->error("验证码不能为空！");
    	}
    	//验证码
    	if($_SESSION['_verify_']['verify']!=strtolower($verrify)) {
    		$this->error("验证码错误！");
    	}
        */
		
	    $user = M('users');
		/*
		if(strpos($name,"@")>0) {//邮箱登录
			$where['user_email']=$name;
    	}else{
			
    	}
		*/
		$where['user_login']=$name;
		$result = $user->where($where)->find();
		
		if($result) {
            if($result['user_pass'] == md5($pass)) { //登入成功页面跳转
				session('user_id', $result["id"]);
				session('name', $result["user_login"]);
                session('role_id',$result['role_id']);
                session('org_id', $result['org_id']);
                session('enterprise_id', $result['org_id']);

                if($result['org_id']) {
                    //判断商家 状态
                    if (!M("org")->where(array("org_id"=>$result['org_id']))->getField("status")) {
                        $this->error("该机构已被禁用，请联系管理员！");
                    }
                    $enterprise_name =  M('org')->where(array('id'=>$result['org_id']))->getField('name');
                } else {
                    $enterprise_name = '管理后台';
                    session('enterprise_id', 0);
                }
                
                session('enterprise_name', $enterprise_name);
                $result['last_login_ip']=get_client_ip();
    			$result['last_login_time']=date("Y-m-d H:i:s");
    			$user->save($result);
                setcookie("admin_username",$name,time()+30*24*3600,"/");
                $this->success("登录验证成功！",U("Index/index"));
    		}else{
                $this->error("密码错误！");
    		}
    	}else{
    		$this->error("用户名不存在！");
    	}
    }

}