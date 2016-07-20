<?php
namespace Home\Controller;
use Think\Controller;
Class RbacController extends CommonController {


	Public function index() {

	}

	//权限设置  > 角色模块
	Public function role () {
		$this->rest = M('role')->field(array('id','name','status','remark'))->select();
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
		$this->ajaxReturn( setAjaxReturn( $rest ) );
	}

}
