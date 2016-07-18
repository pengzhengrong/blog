<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller {

	public function _initialize () {
		//获取栏目缓存
		$field = array('id','pid','title','sort','m','c','a');
		$nav = M('navigation')->cache(true,60)->field( $field )->where('status=0')->order( 'sort' )->select();
		$where = array(
			'm' => MODULE_NAME,
			'c' => CONTROLLER_NAME,
			'a' => ACTION_NAME
			);
		$nav_id = M('navigation')->/*field(array('id'))->*/where($where)->fetchSql(false)->order('id desc')->getField('id');
		$this->navigation = getParents($nav, $nav_id);
		// P($this->navigation);	
	}


}