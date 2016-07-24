<?php
namespace Home\Controller;
use Think\Controller;
Class UserController extends CommonController {

	Public function index() {
		$field = array('id','username','status','role','extra','name','email','last_login','last_ip');
		$this->rest = M('user')->field($field)->fetchSql(false)->select();
		$this->display();
	}

	Public function add() {
		if( IS_POST ) {
			$data = I('post.');
			$data['created'] = time();
			$data['password'] = '123456';
			// $data['extra'] = json_encode( $data['extra'] );
			$rest = M('user')->add($data);
			$this->ajaxReturn( setAjaxReturn( $rest ) );
		}
		//角色选项
		$role = M('role')->field( array('id','name') )->where('status=0')->order('id')->select();
		$this->options = options($role, 'id', 'name');
		$this->display();
	}

	Public function edit() {
		if( IS_POST ) {
			$data = I('post.');
			// $data['extra'] = json_encode( $data['extra'] );
			$rest = M('user')->save($data);
			$this->ajaxReturn( setAjaxReturn( $rest ) );
		}
		$field = array('id','username','name','status','role_id');
		$this->rest  = M('user')->field($field)->find(I('id'));
		// $this->extra = json_decode($this->rest['extra'],true);
		$this->selected = $this->rest['role_id'];
		//角色选项
		$role = M('role')->field( array('id','name') )->where('status=0')->order('id')->select();
		$this->options = options($role, 'id', 'name');
		$this->display();
	}

	Public function delete() {
		$rest = M('user')->delete(I('id'));
		$this->ajaxReturn( setAjaxReturn( $rest ) );
	}

	//修改口令
	Public function card() {
		if( IS_POST ) {
			$data = array(
				'password' => I('new_password'),
				'id' => I('id')
				);
			$where = array(
				'password' => I('old_password'),
				'id' => I('id')
				);
			$rest = M('user')->where($where)->fetchSql(false)->find();
			//验证原始密码
			if( $rest ) {
				$rest = M('user')->save($data);
				$this->ajaxReturn( setAjaxReturn( $rest ) );
			} else {
				$this->ajaxReturn( setAjaxReturn( $rest, '原始密码输入有误！' ) );
			}
		}
		$this->rest = M('user')->field(array('id','username'))->find();
		$this->display();
	}

	//设置个人信息
	Public function profile() {
		if( IS_POST ) {
			$extra = I('extra');
			$data = array(
				'extra' => json_encode($extra),
				'id' => I('id')
				);
			if( !empty( I('password') ) ) {
				$data['password'] = I('password');
			}
			$rest = M('user')->save($data);
			$this->ajaxReturn( setAjaxReturn( $rest ) );
		}
		$user_id = session('user_id');
		$this->rest = M('user')->field(array('id','password','extra'))->find($user_id);
		// P($this->rest);
		$this->display();
	}
}

