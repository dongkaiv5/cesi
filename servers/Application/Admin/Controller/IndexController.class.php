<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends PrivateController {
    public function index() {
		$this->assign("SUBMENU_CONFIG", json_encode(D("Menu")->menu_json()));
		$this->display();
    }
	
	public function info() {
	}
public function day() {
echo "ok";
	}
}