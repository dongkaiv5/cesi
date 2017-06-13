<?php
namespace Admin\Controller;
use Think\Controller;
class MenuController extends PrivateController {

	protected $Menu;
    function __construct() {
        parent::__construct();
        $this->Menu = D("Menu");
    }
	
	public function index() {
		session('admin_menu_index', 'Menu/index');
        $result = $this->Menu->order(array("listorder" => "DESC"))->select();
		
		$tree = new \Org\Util\Tree;
		$tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        foreach ($result as $r) {
            $r['str_manage'] = '<a href="' . U("Menu/add", array("parentid" => $r['id'], "menuid" => $_GET['menuid'])) . '">添加子菜单</a> | <a href="' . U("Menu/edit", array("id" => $r['id'], "menuid" => $_GET['menuid'])) . '">修改</a> | <a class="J_ajax_del" href="' . U("Menu/delete", array("id" => $r['id'], "menuid" => I("get.menuid")) ). '">删除</a> ';
            $r['status'] = $r['status'] ? "显示" : "隐藏";
            if(APP_DEBUG){
            	$r['app']=$r['app']."/".$r['model']."/".$r['action'];
            }
            $array[] = $r;
            
        }
        $tree->init($array);
        $str = "<tr>
					<td><input name='listorders[\$id]' type='text' size='3' value='\$listorder' class='input input-order'></td>
					<td>\$id</td>
        			<td>\$app</td>
					<td>\$spacer\$name</td>
				    <td>\$status</td>
					<td>\$str_manage</td>
				</tr>";
        $categorys = $tree->get_tree(0, $str);
        $this->assign("categorys", $categorys);
        $this->display();
	}
	
    //排序
    public function listorders() {
		$status = parent::_listorders($this->Menu);
        if ($status) {
            success("排序更新成功！");
        } else {
            error("排序更新失败！");
        }
    }
	
    /**
     *  添加
     */
    public function add() {
    	$tree = new \Org\Util\Tree;
    	$parentid = intval(I("get.parentid"));
    	$result = $this->Menu->order(array("listorder" => "DESC"))->select();
    	foreach ($result as $r) {
    		$r['selected'] = $r['id'] == $parentid ? 'selected' : '';
    		$array[] = $r;
    	}
    	$str = "<option value='\$id' \$selected>\$spacer \$name</option>";
    	$tree->init($array);
    	$select_categorys = $tree->get_tree(0, $str);
    	$this->assign("select_categorys", $select_categorys);
    	$this->display();
    }
	
    /**
     *  添加
     */
    public function add_post() {
    	if (IS_POST) {
    		if ($this->Menu->create()) {
    			if ($this->Menu->add()!==false) {
					$to = session('admin_menu_index');
					if($to) {
						$this->success("添加成功！", U($to));
					} else {
						$this->success("添加成功！", U('Menu/index'));
					}
    			} else {
					$this->error("添加失败！");
    			}
    		} else {
				$this->error($this->Menu->getError());
    		}
    	}
    }
	
    /**
     *  编辑
     */
    public function edit() {
		$tree = new \Org\Util\Tree;
        $id = intval(I("get.id"));
        $rs = $this->Menu->where(array("id" => $id))->find();
        $result = $this->Menu->order(array("listorder" => "DESC"))->select();
        foreach ($result as $r) {
        	$r['selected'] = $r['id'] == $rs['parentid'] ? 'selected' : '';
        	$array[] = $r;
        }
        $str = "<option value='\$id' \$selected>\$spacer \$name</option>";
        $tree->init($array);
        $select_categorys = $tree->get_tree(0, $str);
        $this->assign("data", $rs);
        $this->assign("select_categorys", $select_categorys);
        $this->display();
    }
	
    /**
     *  编辑
     */
    public function edit_post() {
    	if (IS_POST) {
    		if ($this->Menu->create()) {
    			if ($this->Menu->save() !== false) {
					success("更新成功！");
    			} else {
					error("更新失败！");
    			}
    		} else {
				error($this->Menu->getError());
    		}
    	}
    }
	
    /**
     *  删除
     */
    public function delete() {
        $id = intval(I("get.id"));
        $count = $this->Menu->where(array("parentid" => $id))->count();
        if ($count > 0) {
			error("该菜单下还有子菜单，无法删除！");
        }
		
        if ($this->Menu->delete($id)!==false) {
			success("删除菜单成功！");
        } else {
			error("删除失败！");
        }
    }

    /**
     *  所有菜单
     */
    public function lists(){
		session('admin_menu_index', '"Menu/lists');
    	$result = $this->Menu->order(array("app" => "ASC","model" => "ASC","action" => "ASC"))->select();
    	$this->assign("menus",$result);
    	$this->display();
    }

}