<?php
/**
 * 开放接口API
 * ============================================================================
 * 版权所有 (C) 
 * 网站地址:
 * ============================================================================
 * $Version: v1.0.0 Beta $
 * $Author: liwenxuan <liwenxuan@pokpets.com> $
 * $Date: 2016/9/20 $
 * $Id: PubController.class.php 2016/9/20 $
**/
namespace Api\Controller;
use Think\Controller;
class PubController extends Controller {
	/**
	 *+--------------------------------------------------------------------------------------------------------------------
	 * 图片预览剪切接口
	 *+--------------------------------------------------------------------------------------------------------------------
	 * @Author LWX  Date:2016/9/20
	 *+--------------------------------------------------------------------------------------------------------------------
	 * @access public
	 *+--------------------------------------------------------------------------------------------------------------------
	 * @type GET
	 *+--------------------------------------------------------------------------------------------------------------------
	 * @parame 
	 *+--------------------------------------------------------------------------------------------------------------------
	 * @return JSON
	 *+--------------------------------------------------------------------------------------------------------------------
	**/
	public function Cropper() {
		if(IS_POST) {
						
			$time = time();
			$time_file = date('Y/m/d/H/i/s', $time);
			$key = token($time_file, true);
			$key = $key.''.$time;
		
			$rile_route = 'Uploads/Preview/'.$time_file;
			
			$File = new \Org\Util\File;
			$File->createDir($rile_route);
			
			$rile_route .= '/'.$key;
			
			$crop = new \Org\Util\CropAvatar($_POST['avatar_src'], $_POST['avatar_data'], $_FILES['avatar_file'], $rile_route);
			
			$state = 0;
			if($crop -> getMsg()) {
				$state = 200;
			}
			
			$response = array(
				'state'  => $state,
				'message' => $crop -> getMsg(),
				'key' => $key.'png',
				'url' => C('FILEURL').'/Preview/'.$time_file.'/'.$key.'png.png'
			);

			echo json_encode($response);
		} else {
			$width = I('get.width', 0, 'intval');
			$height = I('get.height', 0, 'intval');
			$rfname = I('get.rfname', '', 'trim');
			$aspectRatio = D('ApiPublic')->aspect_ratio($width, $height);
			$parameter = I('get.parameter', '', 'trim');
			
			$this->assign('width',$width);
			$this->assign('height',$height);
			$this->assign('rfname',$rfname);
			$this->assign('aspectRatio',$aspectRatio);
			$this->assign('parameter',$parameter);
				
			$this->display('Cropper');
		}
	}



}