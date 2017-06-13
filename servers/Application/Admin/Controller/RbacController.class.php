<?php
/* * 
 * 系统权限配置，用户角色管理
 */
namespace Admin\Controller;
use Think\Controller;
class RbacController extends PrivateController {

    protected $User, $Role, $Access, $Role_user;

    function __construct() {
        parent::__construct();
        $this->Role = D("Role");
    }

    /**
     * 角色管理，有add添加，edit编辑，delete删除
     */
    public function index() {
        $data = $this->Role->order(array("listorder" => "asc", "id" => "DESC"))->select();
        $this->assign("roles", $data);
        $this->display();
    }

    /**
     * 添加角色
     */
    public function roleadd() {
        $this->display();
    }
    
    /**
     * 添加角色
     */
    public function roleadd_post() {
    	if (IS_POST) {
            $name=trim($_POST['name']);
            $_POST['name']=$name;
    		if ($this->Role->create()) {
    			if ($this->Role->add()!==false) {
    				$this->assign("jumpUrl", U("Rbac/rolemanage"));
    				$this->success("添加角色成功",U("rbac/index"));
    			} else {
    				$this->error("添加失败！");
    			}
    		} else {
    			$this->error($this->Role->getError());
    		}
    	}
    }

    /**
     * 删除角色
     */
    public function roledelete() {
    	$users_obj = D("Users");
        $id = intval(I("get.id"));
        if (is_admin($id)) {
            $this->error("超级管理员角色不能被删除！");
        }
        $count=$users_obj->where(array('role_id'=>$id))->count();
        if($count){
        	$this->error("该角色已经有用户！");
        }else{
        	$status = $this->Role->delete($id);
        	if ($status!==false) {
        		$this->success("删除成功！", U('Rbac/index'));
        	} else {
        		$this->error("删除失败！");
        	}
        }
        
    }

    /**
     * 编辑角色
     */
    public function roleedit() {
        $id = intval(I("get.id"));
        if ($id == 0) {
            $id = intval(I("post.id"));
        }
        $data = $this->Role->where(array("id" => $id))->find();
        if (!$data) {
        	$this->error("该角色不存在！");
        }
        $this->assign("data", $data);
        $this->display();
    }
    
    /**
     * 编辑角色
     */
    public function roleedit_post() {
    	$id = intval(I("get.id"));
    	if ($id == 0) {
    		$id = intval(I("post.id"));
    	}
    	if (IS_POST) {
    		$data = $this->Role->create();
    		if ($data) {
    			if ($this->Role->save($data)!==false) {
    				$this->success("修改成功！", U('Rbac/index'));
    			} else {
    				$this->error("修改失败！");
    			}
    		} else {
    			$this->error($this->Role->getError());
    		}
    	}
    }

    /**
     * 角色授权
     */
    public function authorize() {
        $this->Access = D("Access");
       //角色ID
        $role_id = intval(I("get.id"));
        if (!$role_id) {
        	$this->error("参数错误！");
        }
		
        $menu = new \Org\Util\Tree;
        $menu->icon = array('', '', '');
        $menu->nbsp = '&nbsp;&nbsp;&nbsp;';
        $result = $this->initMenu();
        $newmenus=array();
        $priv_data = $this->Access->where(array("role_id" => $role_id))->select(); //获取权限表数据
        foreach ($result as $m){
        	$newmenus[$m['id']]=$m;
        	
        }
        
        foreach ($result as $n => $t) {
        	$result[$n]['checked'] = ($this->_is_checked($t, $role_id, $priv_data)) ? ' checked' : '';
        	$result[$n]['level'] = $this->_get_level($t['id'], $newmenus);
        	$result[$n]['parentid_node'] = ($t['parentid']) ? ' class="child-of-node-' . $t['parentid'] . '"' : '';
        }
        $str = "<tr id='node-\$id' \$parentid_node><td style='padding-left:30px;'>\$spacer<input type='checkbox' name='menuid[]' value='\$id' level='\$level' \$checked onclick='javascript:checknode(this);'> \$name</td></tr>";
        $menu->init($result);
        $categorys = $menu->get_tree(0, $str);
        
        $this->assign("categorys", $categorys);
        $this->assign("role_id", $role_id);
        $this->display();
    }
    
    /**
     * 角色授权
     */
    public function authorize_post() {
    	$this->Access = D("Access");
    	if (IS_POST) {
    		$role_id = intval(I("post.role_id"));
    		if(!$role_id){
				error("需要授权的角色不存在！");
    		}
    		if (is_array($_POST['menuid']) && count($_POST['menuid'])>0) {
    			//取得菜单数据
    			$menuinfo = M("Menu")->select();
    			foreach ($menuinfo as $_v) {
    				$menu_info[$_v["id"]] = $_v;
    			}
    			C('TOKEN_ON', false);
    			$addauthorize = array();
    			//检测数据合法性
    			foreach ($_POST['menuid'] as $menuid) {
    				$info = array();
    				$info = $this->_get_menuinfo((int) $menuid, $menu_info);
    				if($info == false){
    					continue;
    				}
    				$info['role_id'] = $role_id;
    				$data = $this->Access->create($info);
    				if (!$data) {
						error($this->Access->getError());
    				} else {
    					$addauthorize[] = $data;
    				}
    			}
    			C('TOKEN_ON', true);
    			$this->Access->where(array('role_id'=>$role_id))->delete();
    
    			if($this->Access->rbac_authorize($role_id,$addauthorize)){
					success("授权成功！", U("Rbac/index"));
    			}else{
					error("授权失败！");
    			}
    		}else{
    			//当没有数据时，清除当前角色授权
    			$this->Access->where(array("role_id" => $role_id))->delete();
				error("没有接收到数据，执行清除授权成功！");
    		}
    	}
    }
    /**
     *  检查指定菜单是否有权限
     * @param array $data menu表中数组
     * @param int $role_id 需要检查的角色ID
     */
    private function _is_checked($data, $role_id, $priv_data) {
    	
    	$priv_arr = array('app', 'model', 'action');
    	if ($data['app'] == '') {
    		return false;
    	}
    	$mdata['role_id'] = $role_id;
    	$mdata["g"] = $data['app'];
    	$mdata["m"] = $data['model'];
    	$mdata["a"] = $data['action'];
    	$info = in_array($mdata, $priv_data);
    	if ($info) {
    		return true;
    	} else {
    		return false;
    	}
    }

    /**
     * 获取菜单深度
     * @param $id
     * @param $array
     * @param $i
     */
    protected function _get_level($id, $array = array(), $i = 0) {
        
        	if ($array[$id]['parentid']==0 || empty($array[$array[$id]['parentid']]) || $array[$id]['parentid']==$id){
        		return  $i;
        	}else{
        		$i++;
        		return $this->_get_level($array[$id]['parentid'],$array,$i);
        	}
        		
    }
    
    /**
     * 获取菜单表信息
     * @param int $menuid 菜单ID
     * @param int $menu_info 菜单数据
     */
    private function _get_menuinfo($menuid, $menu_info) {
        $info = $menu_info[$menuid];
        if(!$info){
            return false;
        }
        $return['g'] = $info['app'];
        $return['m'] = $info['model'];
        $return['a'] = $info['action'];
        return $return;
    }
    
    public function member() {
    	$lists = M()->table(C('DB_PREFIX').'role role')
					->join(C('DB_PREFIX').'users users ON role.id=users.role_id')
					->field('role.name, users.id, users.user_login, users.user_nicename, users.user_email')
					->where(array('role.id'=>$_GET['id']))
					->select();
    	$this->assign("lists",$lists);
    	$this->display();
    }

}

?>
