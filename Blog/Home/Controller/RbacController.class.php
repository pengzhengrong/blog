<?php
namespace Home\Controller;
use Think\Controller;
Class RbacController extends CommonController {


	Public function index() {

	}

	//权限设置  > 角色模块
	Public function role () {
		$this->rest = M('role')->field(array('id','name','status','remark'))->order('id')->select();
		$this->display();
	}

	Public function role_add () {
		$this->pid = I('pid',0,'intval');
		if( IS_POST ){
			$rest = M('role')->data( I('post.') )->add();
			$this->ajaxReturn( setAjaxReturn( $rest ) );
		}
		$this->display();
	}

	public function role_edit() {
		if( IS_POST ){
			$rest = M('role')->save(I('post.'));
			$this->ajaxReturn( setAjaxReturn( $rest ) );
		}
		$this->rest = M('role')->field(array('id','name','status','remark'))->find(I('id'));
		$this->display();
	}

	Public function role_delete() {
		$id = I('id' ,0 ,'intval');
		$rest = M('role')->delete( $id );
		$this->ajaxReturn( setAjaxReturn( $rest ) );
	}

	//权限设置  > 角色绑定节点模块
	Public function access () {
		/*条件搜索*/
		if( IS_POST ) {
			$where = array('status'=>0);
			$rest = A('Nav')->getCache();
			//只显示根菜单
			// $this->options = options( $rest , 'id', 'title', 'pid', '0');
			// $this->selected = I('id');
			$this->rest = tree2($rest,I('id',0,'intval'),true);
			$this->display();
			exit;
		}

		$node_ids = M('access')->field(array('node_id'))->where('role_id='.I('id'))->select();
		$node_ids = array_column($node_ids,'node_id');
		// P($node_ids);die;
		//获取菜单列表
		$this->role_id = I('id');
		$nav = A('Nav')->getCache();
		$this->rest = tree2($nav,0,true,$node_ids);
		// $this->options = options($this->rest, 'id', 'title', 'pid', '0');

		$this->display();
	}

	Public function access_node() {
		$values = array();
		$node_id = I('node_id');
		$role_id = I('role_id');
		foreach ($node_id as $v ) {
			$values[] = "({$role_id},{$v})";
		}
		$rest = M('access')->where('role_id='.$role_id)->delete();
		$sql = "insert into think_access(`role_id`,`node_id`) values ".implode(',', $values);
		$rest = M('access')->execute($sql);
		// logger( json_encode($sql) );
		$this->ajaxReturn( setAjaxReturn( $rest ) );
	}

	Public function node () {
		$field = array('id','name','remark','pid','level');
		$rest = M('node')->field( $field )->select();
		$this->rest = node_merge( $rest );
		// p( $this->rest ); exit;
		$this->display();
	}

	Public function node_add () {
		if( IS_POST ){
			// p( I('post.') ); exit;
			$data = array(
				'name' => I('name'),
				'remark' => I('remark'),
				'pid' => I('pid' , 0 ,'intval'),
				'level' => I('level' , 0 ,'intval'),
				'status' => 1
				);
			// p( $data ); exit;
			$rest = M('node')->data( $data)->add();
			$rest || $this->error( 'save failed' );
			// U('Admin/Rbac/node','','',true);
			$this->redirect('node');
		}
		$this->level = I('level',1,'intval');
		switch ( $this->level ) {
			case 2:
				$type = 'ACTION';
				break;
			case 3:
				$type = 'METHOD';
				break;
			default:
				$type = 'MODULE';
				break;
		}
		$this->type = $type;
		$this->pid = I('pid',0,'intval');
		$this->display();
	}

	Public function node_edit () {
		if( IS_POST && I('submit') != null){
			$data = array(
				'id' => I('pid'),
				'name' => I('name'),
				'remark' => I('remark')
				);
			$rest = M('node')->save( $data );
			$rest || $this->error('UPDATE FAILED');
			$this->success('UPDATE SUCCESS',U(MODULE_NAME.'/Rbac/node'));
			return;
		}
		$this->rest = M('node')->find( I('id') );
		// p( $this->rest ); die;
		$this->level = I('level',1,'intval');
		switch ( $this->level ) {
			case 2:
				$type = 'ACTION';
				break;
			case 3:
				$type = 'METHOD';
				break;
			default:
				$type = 'MODULE';
				break;
		}
		$this->type = $type;
		$this->display();
	}

	Public function node_delete () {
		$rest = M('node')->delete(I('id',0,'intval'));
		$rest || $this->error( 'delete failed' );
		$this->redirect( 'node' );
	}





}
