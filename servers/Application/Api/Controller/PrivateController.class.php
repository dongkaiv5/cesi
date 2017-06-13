<?php
namespace Api\Controller;
use Think\Controller;
class PrivateController extends Controller {
	
	private $user_id = 0;
	
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

		if($data['user_token']) {
			$redis = S(C('REDIS'));
			if($user_id = intval($redis->get('user_token_'.$data['user_token']))) {
				$this->user_id = $user_id;
				$redis->set('user_id_'.$user_id, $data['user_token']);
				$redis->set('user_token_'.$data['user_token'], $user_id);
			} else {
				output(10002);
			}
		} else {
			output(10003);
		}
	}

	public function guid() {
		return $this->user_id;
	}


}