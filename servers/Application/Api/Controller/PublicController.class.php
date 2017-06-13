<?php
namespace Api\Controller;
use Think\Controller;
class PublicController extends Controller {
	
	public function __construct() {
		parent::__construct();
		
		$token_time = time();
		$data = array();
		if(IS_GET) {
			$data = $_GET;
		} else if(IS_POST) {
			$data = $_POST;
		}
		
		$data['token'] = trim($data['token']);
		$data['user_token'] = trim($data['user_token']);
				
		if($data['token'] != token($data)) {
			output(10003);
		} else {
			$data['token_time'] = intval($data['token_time']);
			$data['token_time'] += 120;
			if($data['token_time'] <= $token_time) {
				output(10010);
			} 
		}
	}
}