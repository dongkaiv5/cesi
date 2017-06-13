<?php
namespace Admin\Controller;
use Think\Controller;
class UserController extends PrivateController {
	
	protected $users_obj,$role_obj;
	function __construct() {
		parent::__construct();
		$this->users_obj = D("Users");
		$this->role_obj = D("Role");
	}
	
    public function index() {
		$users = M()->table(C('DB_PREFIX') . 'users users')
                ->field('users.*')
                ->where(array('role_id'=>array('neq', -1)))->select();      
		$roles_src=$this->role_obj->select();
		
		$roles=array();
		foreach ($roles_src as $r){
			$role_id=$r['id'];
			$roles["$role_id"]=$r;
		}
		
		$this->assign("roles",$roles);
		$this->assign("users",$users);
		$this->display();
    }
	
	public function userinfo() {
		$id = get_user_id();
		$user = M('users')->where(array('id'=>$id))->find();
		$this->assign($user);
		$this->display();
	}
	
	public function userinfo_post() {
		if (IS_POST) {
			if ($this->users_obj->create()) {
				if ($this->users_obj->save()!==false) {
					success('保存成功！');
				} else {
					error("保存失败！");
				}
			} else {
				error($this->users_obj->getError());
			}
		}
	}
	
	/**
	 *  删除
	 */
	function delete() {
		$id = intval(I("get.id"));
		if($id==1) {
			error("最高管理员不能删除！");
		}
		$uid = get_user_id();
		if ($this->users_obj->where(array('id'=>$id))->delete()!==false) {
			success("删除成功！");
		} else {
			error("删除失败！");
		}
	}
	
	function add() {
		$roles=$this->role_obj->where("status=1")->select();
		$this->assign("roles",$roles);
		$this->display();
	}
	
	function edit(){
		$id= intval(I("get.id"));
		$roles=$this->role_obj->where("status=1")->select();
		$user=M()->table(C('DB_PREFIX') . 'users users')
                ->join(C('DB_PREFIX') . "org org ON users.org_id = org.id", 'LEFT')
                ->field('users.*,org.name')
                ->where(array('users.id'=>$id))->find(); 
		$this->assign("roles",$roles);
		$this->assign($user);
		$this->display();
	}

	function edit_post(){
		if (IS_POST) {
			if(empty($_POST['user_pass'])){
				unset($_POST['user_pass']);
			}
			if ($this->users_obj->create()) {
				$result=$this->users_obj->save();
				if ($result!==false) {
					$this->success("保存成功！", U("User/index"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->users_obj->getError());
			}
		}
	}

	function add_post(){
		if(IS_POST){
			if ($this->users_obj->create()) {
				if ($this->users_obj->add()!==false) {
					$this->success("添加成功！", U("User/index"));
				} else {
					$this->error("添加失败！");
				}
			} else {
				$this->error($this->users_obj->getError());
			}
		}
	}
    public function org_list() {
    	
        // $where['status'] = 1;
        // $where['del'] = 0;      
        // if(isset($_GET['schoo_name'])) {
        //     $schoo_name = I('get.schoo_name');
        //     $schoo_name = urldecode($schoo_name);	
        //     $schoo_name = trim($schoo_name);
        //     $where['name'] = array('like', '%'.$schoo_name.'%');
        //     $list = M('org')->field('id, name, logo, info')->where($where)->select();
        //     echo json_encode($list);
        // } else {
        //     $list = M('org')->field('id, name, logo, info')->where($where)->select();
        //     $this->assign("list",$list);
            
    	// $org_sql=M('org')->field('id,name')->select();
    	// 	$this->assign('org_info',$org_sql);
     //        $this->display();

            $where['status'] = array('EQ', '1');
				$list = M('org')->where($where)->field('id, name')->order('create_time DESC')->select();
				// array_unshift($list, array('id'=>0, 'name'=>'管理后台', 'icon'=>''));
				$this->assign('org_info', $list);
				$this->display();
       //}   
	}
              function login(){
				return 0;
			  }

}