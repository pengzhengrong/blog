<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends CommonController {
	public function index() {
		$field = array('id','pid','title','m','c','a');
		$where = array(
			'status' => 0
			);
		//如果用户不是超级用户，那么只显示对用的模块功能
		if( session('role_id') != C('ADMIN_AUTH_ID') ) {
			$where['id'] = array( 'in',session('node_ids') );
		}
		logger( json_encode($where) );
		$rest = M('navigation')->cache(true,60)->field($field)->where($where)->order('sort')->fetchSql(false)->select();
		$this->rest = node_merge( $rest );
		$this->display();
	}

	public function welcome() {
		$this->username = session('username');
		$uid = session(C('USER_AUTH_KEY'));
		$rest = D('UserRelation')->relation( true )->where("id=$uid")->select();
		// p($rest);
		if( $rest ){
			$role_desc = array();
			foreach ($rest[0]['role'] as $key => $value) {
				$role_desc[] = $value['remark'];
			}
			$this->login_time = $rest[0]['login_time'];
			$this->login_ip = $rest[0]['login_ip'];
			$this->role = implode(',', $role_desc);
		}
		if( in_array($this->username, explode(',',C('RBAC_SUPERADMIN')) ) ){
			$this->role = '超级管理员';
		}
		$this->display();
	}

	Public function test() {
		$this->display();
	}

}