<?php
namespace Admin\Controller;
use Think\Controller;
class SettingController extends PrivateController {
	
	protected $options_obj;
	function __construct() {
		parent::__construct();
	}
	
	function password(){
		$this->display();
	}

	function password_post(){
		if (IS_POST) {
			if(empty($_POST['old_password'])){
				error("原始密码不能为空！");
			}
			if(empty($_POST['password'])){
				error("新密码不能为空！");
			}
			$user_obj = D("Users");
			$uid = get_user_id();
			$admin=$user_obj->where(array('id'=>$uid))->find();
			$old_password=$_POST['old_password'];
			$password=$_POST['password'];
			if(sp_password($old_password)==$admin['user_pass']){
				if($_POST['password']==$_POST['repassword']){
					if($admin['user_pass']==sp_password($password)){
						error("新密码不能和原始密码相同！");
					}else{
						$data['user_pass']=sp_password($password);
						$data['id']=$uid;
						$r=$user_obj->save($data);
						if ($r!==false) {
							success('修改成功',U('User/userinfo'));
						} else {
							error("修改失败！");
						}
					}
				}else{
					error("密码输入不一致！");
				}
			}else{
				error("原始密码不正确！");
			}
		}
	}

	
}