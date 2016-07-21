<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller {

	Public function _initialize () {
		//校验登入
		$this->isLogin();
		//校验权限
		$auth = $this->authPrivilege();
		if( !$auth ) {
			//跳转到404页面
			// $this->redirect('/Home/Index/404');
		}
		//获取栏目缓存
		$field = array('id','pid','title','sort','m','c','a');
		$nav = M('navigation')->cache(true,60)->field( $field )->where('status=0')->order( 'sort' )->select();
		$where = array(
			'm' => MODULE_NAME,
			'c' => CONTROLLER_NAME,
			'a' => ACTION_NAME
			);
		$nav_id = M('navigation')->where($where)->fetchSql(false)->order('id desc')->getField('id');
		$this->navigation = getParents($nav, $nav_id);
		// P($this->navigation);
	}

	//验证权限
	Public function authPrivilege() {
		$role_id = session('role_id');
		//超级管理员
		if( $role_id == C('ADMIN_AUTH_ID') ) {
			return true;
		}
		//无需验证的页面
		$not_auth_page = $this->notAuthPage();
		$node_ids = M('access')->field('node_id')->where('role_id='.$role_id)->select();
		$node_ids = array_column( $node_ids , 'node_id' );
		//模块功能的过滤条件
		session( 'node_ids', $node_ids );
		//无需验证页面
		if( $not_auth_page ) {
			return $not_auth_page;
		}
		logger( json_encode($node_ids) );
		$where = array(
			'm' => MODULE_NAME,
			'c' => CONTROLLER_NAME,
			'a' => ACTION_NAME
			);
		$nav_id = M('navigation')->where($where)->fetchSql(false)->getField('id');
		//校验是否权限验证
		if( in_array(  $nav_id, $node_ids) ) {
			return true;
		}
		return false;
	}

	//无需验证页面
	Public function notAuthPage() {
		$page = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
		$not_auth_page = C('NOT_AUTH_PAGE');
		if( in_array($page, $not_auth_page) ) {
			return true;
		}
		return false;
	}

	Public function isLogin() {
		logger(session('user_id'));
		if( session('user_id') == null ) {
			redirect('/login.html');
		}
	}


}