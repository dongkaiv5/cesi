<?php
namespace Admin\Controller;
use Think\Controller;
class PrivateController extends Controller {
	
	public function __construct() {
		parent::__construct();
		if($id = get_user_id()) {
			$users_obj = D("Users");
			$user = $users_obj->where(array('id'=>$id))->find();
			if($this->check_access($user['role_id'])) {
				if($org_id = get_user_org_id()) {
					$this->assign("org_name", M('org')->where(array('id'=>$org_id))->getField('name'));
				} else {
					$this->assign("org_name", '请选机构');
				}
				$this->assign("admin",$user);
			} else {
				$this->error("您没有访问权限！", U("Public/login"));
			}
		} else {
			if(IS_AJAX){
				$this->error("您还没有登录！", U("Public/login"));
			}else{
				$this->redirect('Public/login');
			}
		}
	}
	
	/**
     * 图片预览
     */
	public function upload_previews() {
		echo upload_preview();
	}

	/**
     *  排序 排序字段为listorders数组 POST 排序字段为：listorder
     */
    protected function _listorders($model) {
        if (!is_object($model)) {
            return false;
        }
        $pk = $model->getPk(); //获取主键名称
        $ids = $_POST['listorders'];
        foreach ($ids as $key => $r) {
            $data['listorder'] = $r;
            $model->where(array($pk => $key))->save($data);
        }
        return true;
    }
    protected function _listorder($model) {
        if (!is_object($model)) {
            return false;
        }
        $pk = $model->getPk(); //获取主键名称
        $ids = $_POST['listorders'];
        // error_log(print_r($ids,1));
        // error_log(print_r($model,1));
        foreach ($ids as $key => $r) {
            $data['listorders'] = $r;
            $model->where(array($pk => $key))->save($data);
        }
        return true;
    }
    
	private function check_access($role_id) {
		if($role_id == -1) { //如果用户较色是-1超级管理员不用判断
			return true;
    	} else {
			$role_obj= D("Role");
			$status = $role_obj->where(array('id'=>$role_id))->getField('status');
			if($status == 1) {
				$group=MODULE_NAME;
				$model=CONTROLLER_NAME;
				$action=ACTION_NAME;
				if(MODULE_NAME.CONTROLLER_NAME!="AdminIndex") {
					if(M("Access")->where(array('role_id'=> $role_id, 'g'=>$group, 'm'=>$model, 'a'=>$action))->count()) {
						return true;
					} else {
						return false;
					}
				} else {
					return true;
				}
			} else {
				return false;
			}		
		}	
    }

	/**
     * 初始化后台菜单
     */
    public function initMenu() {
        $Menu = F("Menu");
        if (!$Menu) {
            $Menu=D("Menu")->menu_cache();
        }
        return $Menu;
    }
}