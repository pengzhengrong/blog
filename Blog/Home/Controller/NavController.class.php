<?php

namespace Home\Controller;
use Think\Controller;

Class NavController extends CommonController {

	Public function index() {
		$cookieKey = 'COOKIE_'.__CLASS__.__FUNCTION__;
		/*条件搜索*/
		if( IS_POST ) {
			$where = array('status'=>0);
			$rest = $this->getCache();
			//只显示根菜单
			$this->options = options( $rest , 'id', 'title', 'pid', '0');
			I('id') == 'default' ? cookie($cookieKey, null) :cookie($cookieKey, I('id'));
			$id = cookie($cookieKey)==null?I('id',0,'intval'):cookie($cookieKey);
			$this->selected = $id;
			$this->rest = tree($rest, $id, true);
			$this->display();
			exit;
		}
		$rest = $this->getCache();
		//只显示根菜单
		$this->options = options( $rest , 'id', 'title', 'pid', '0');
		$id = cookie($cookieKey);
		if ( $id == null ) {
			$this->rest= tree($rest);
		} else {
			$this->rest = tree($rest, $id, true);
			$this->selected = $id;
		}
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