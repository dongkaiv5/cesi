<?php
/**
 * 商城相关接口API
 * ============================================================================
 * 版权所有 (C) 
 * 网站地址:
 * ============================================================================
 * $Version: v1.0.0 Beta $
 * $Author: zhangnan <zhangnan@pokpets.com> $
 * $Date: 2016/06/13 $
 * $Id: B2b2cPrivateController.class.php 2016/06/13  $
**/
namespace Api\Controller;
use Think\Controller;
class B2b2cPrivateController extends PrivateController {
	
    public function __construct() {
        parent::__construct();
    }
	
    /**
     * +--------------------------------------------------------------------------------------------------------------------
     * 收藏商品
     * +--------------------------------------------------------------------------------------------------------------------
     * @Author yangjing   Date:2016/6/14
     * +--------------------------------------------------------------------------------------------------------------------
     * @access public
     * +--------------------------------------------------------------------------------------------------------------------
     * @type GET
     * +--------------------------------------------------------------------------------------------------------------------
     * @parame Int 必填 $goods_id 商品ID
     * +--------------------------------------------------------------------------------------------------------------------
     * @return JSON
			{
				'name':'value',	//返回值注释
				'name':'value'  //返回值注释
			}
     * +--------------------------------------------------------------------------------------------------------------------
    **/
    public function Favorite() {
        $b2b2c_favorite_db = M('b2b2c_favorite');
        $data = array();
        $data['goods_id'] = I('get.goods_id', 0, 'intval');
        $data['user_id'] = $this->guid();
        if (empty($b2b2c_favorite_db->where(array('user_id' => $data['user_id'], 'goods_id' => $data['goods_id']))->getField('id'))) {
            $data['shop_id'] = M('b2b2c_goods')->where(array('id' => $data['goods_id']))->getField('shop_id');
            $data['time_create'] = time();
            M('b2b2c_favorite')->add($data);
            output(0, array());
        }else{
            output(32009);
        }
        
    }

}