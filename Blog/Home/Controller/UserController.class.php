<?php
namespace Home\Controller;
use Think\Controller;
Class UserController extends CommonController {

	public  function index() {
		// $this->rest = M('user')->select();
		$this->rest = D('UserRelation')->relation( true )->select();
		// my_log( 'sql', D('UserRelation')->relation( true )->getlastsql() );
		// p( $this->rest ); die;
		$this->display();
	}

	public function  add() {
		// p($_POST); exit;
		if( IS_POST ) {
			$login_ip = get_client_ip();
			// p( $login_ip );die;
			$login_time = time();
			$data = array(
				'username' => I('username'),
				'password' => I('passwd','','md5'),
				'login_ip' => $login_ip,
				'login_time' => $login_time
				);
			$rest = M('user')->data( $data )->add();
			$rest || $this->error('INSERT FAILED');
			$this->success('INSERT SUCCESS',MODULE_NAME.'/User/index');
		}
		$this->display();
	}

	public function edit() {
		$user_id = I('uid');
		if( IS_POST ){
			// p( I('post.') ); die;
			$data = array(
				'id'=>I('uid',0,'intval'),
				'lock' => I('lock')
				);
			if( I('passwd') != null ){
				$data['passwd'] = I('passwd',0,'md5');
			}
			$rest = M('user')->data( $data )->save();
			$rest || $this->error('UPDATE FAILED');
			$this->success('UPDATE SUCCESS',U(MODULE_NAME.'/User/index'));
			return;
		}
		$this->rest = M('user')->find( $user_id );
		$this->display();
	}

	public function user_role() {
		$this->user_id = I('uid',0,'intval');
		$rest = M('role')->select();
		$user = M('role_user')->where('user_id='.$this->user_id)->getField('role_id',true);
		// p( $user ); die;
		$this->rest = node_merge( $rest , $user );
		$this->display();
	}

	public function user_role_add() {
		$role_ids = I('role_id');
		$user_id = I('user_id');
		$values = array();
		foreach ($role_ids as $key => $value) {
			$values[] = "({$user_id},{$value})";
		}
		$sql = 'insert into '.C('DB_NAME').'.think_role_user(`user_id`,`role_id`) values'.implode(',', $values);
		// p($sql); exit;
		M('role_user')->where('user_id='.$user_id)->delete();
		$rest = M('role_user')->query( $sql );
		// $rest || $this->error('INSERT ERROR');
		$this->redirect( 'index' );
	}

	public function delete(){
		$uid = I('uid');
		D('UserRelation')->relation(true)->delete( $uid );
		// D('UserRelation')->relation(true)->where(array('user_id'=>$uid))->delete();
		// echo D('UserRelation')->relation(true)->getlastsql();
		$this->redirect( 'index' );
	}


}

