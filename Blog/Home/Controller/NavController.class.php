<?php

namespace Home\Controller;
use Think\Controller;
use Home\Model;

Class NavController extends CommonController {

	protected $model = null;
	protected $navList = [];

	public function _initialize() {
		if ($this->model == null) {
			$this->model = new Model\NavigationModel();
		}
		$fields = ['id', 'pid', 'title', 'sort'];
		$this->navList = $this->model->getNavCache($fields, ['expire'=>3600]);
	}

	Public function index() {
		
		$cookieKey = 'COOKIE_'.__CLASS__.__FUNCTION__;
		$rest = $this->navList;
		/*条件搜索*/
		if( IS_POST ) {
			//只显示根菜单
			$this->options = options( $rest , 'id', 'title', 'pid', '0');
			I('id') == 'default' ? cookie($cookieKey, null) :cookie($cookieKey, I('id'));
			$id = cookie($cookieKey)==null?I('id',0,'intval'):cookie($cookieKey);
			$this->selected = $id;
			$this->rest = tree($rest, $id, true);
			$this->display();
			exit;
		}
		//只显示根菜单
		$this->options = options( $rest , 'id', 'title', 'pid', '0');
		$id = cookie($cookieKey);
		if ( $id == null ) {
			$this->rest= tree($rest);
		} else {
			$this->rest = tree($rest, $id, true);
			$this->selected = $id;
		}
		$this->display();
	}

	Public function add() {
		if( IS_POST ) {
			$data = I('post.');
			$data['a'] = I('action');
			// $rest = M('navigation')->data($data)->add();
			$rest = $this->model->insert($data);
			$this->ajaxReturn( setAjaxReturn($rest) );
		}
		/*添加子菜单*/
		if( I('id') ) {
			$field = array('id','m','c');
			$this->rest = $this->model->getRow($field, ['id'=>I('id')]);
			// $this->rest = M('navigation')->field($field)->/*where('status=0')->*/find(I('id'));
		}
		/*添加一级菜单*/
		$nav = tree($this->navList);
		//树菜单选项
		$this->options = options($nav);
		$this->display();
	}

	Public function edit() {
		if( IS_POST ){
			$data = I('post.');
			$data['a'] = I('action');
			// $rest = M('navigation')->save($data);
			$rest = $this->model->update($data);
			$this->ajaxReturn( setAjaxReturn($rest) );
		}
		$this->rest = tree($this->navList);
		$this->options = options($this->rest);
		$field = array('pid','id','title','m','c','a','status','sort');
		$this->rest = $this->model->getRow($field, ['id'=>I('id')]);
		$this->display();
	}

	Public function delete() {
		// $rest = M('navigation')->delete( I('id') );
		$rest = $this->model->del(['id'=>I('id')]);
		$this->ajaxReturn( setAjaxReturn($rest) );
	}
}