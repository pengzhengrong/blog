<?php

namespace Home\Controller;
use Think\Controller;

Class NavController extends CommonController {

	Public function index() {
		/*条件搜索*/
		if( IS_POST ) {
			$where = array('status'=>0);
			$rest = $this->getCache();
			//只显示根菜单
			$this->options = options( $rest , 'id', 'title', 'pid', '0');
			$this->selected = I('id');
			$this->rest = tree($rest,I('id',0,'intval'),true);
			$this->display();
			exit;
		}
		$rest = $this->getCache();
		//只显示根菜单
		$this->options = options( $rest , 'id', 'title', 'pid', '0');
		$this->rest = tree($rest);
		// p($this->rest); die;
		$this->display();
	}

	Public function add() {
		if( IS_POST ) {
			$data = I('post.');
			$data['a'] = I('action');
			$rest = M('navigation')->data($data)->add();
			//销毁缓存
			$CACHE_KEY = 'NAV_TREE';
			F($CACHE_KEY,null);
			$this->ajaxReturn( setAjaxReturn($rest) );
		}
		/*添加子菜单*/
		if( I('id') ) {
			$field = array('id','m','c');
			$this->rest = M('navigation')->field($field)->/*where('status=0')->*/find(I('id'));
		}
		/*添加一级菜单*/
		$nav = $this->getCache();
		$nav = tree($nav);
		//树菜单选项
		$this->options = options($nav);
		$this->display();
	}

	Public function edit() {
		if( IS_POST ){
			$data = I('post.');
			$data['a'] = I('action');
			$rest = M('navigation')->save($data);
			//销毁缓存
			$CACHE_KEY = 'NAV_TREE';
			F($CACHE_KEY,null);
			$this->ajaxReturn( setAjaxReturn($rest) );
		}
		$nav = $this->getCache();
		$this->rest = tree($nav);
		$this->options = options($this->rest);
		$field = array('pid','id','title','m','c','a','status','sort');
		$this->rest = M('navigation')->field($field)->find( I('id') );
		$this->display();
	}

	Public function delete() {
		$rest = M('navigation')->delete( I('id') );
		//销毁缓存
		$CACHE_KEY = 'NAV_TREE';
		F($CACHE_KEY,null);
		$this->ajaxReturn( setAjaxReturn($rest) );
	}

	Public function getCache() {
		$CACHE_KEY = 'NAV_TREE';
		if( F( $CACHE_KEY ) ) {
			$rest = F($CACHE_KEY);
		} else {
			$field = array('id','pid','title','sort');
			$rest = M('navigation')->field( $field )->/*where('status=0')->*/order( 'sort' )->select();
			F( $CACHE_KEY , $rest );
		}
		return $rest;
	}
}