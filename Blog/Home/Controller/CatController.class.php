<?php

namespace  Home\Controller;
use Think\Controller;
Class CatController extends CommonController {

	Public function index() {
		/*条件搜索*/
		if( IS_POST ) {
			$rest = $this->getCache();
			$this->options = options( $rest , 'id', 'title', 'pid', '0');
			$this->selected = I('id');
			$this->rest = tree($rest,I('id',0,'intval'),true);
			$this->display();
			exit;
		}
		$rest = $this->getCache();
		//只获取根栏目
		$this->options = options( $rest,'id','title','pid','0');
		$this->rest = tree($rest);
		$this->display();
	}

	Public function add() {
		if( IS_POST ) {
			$data = I('post.');
			$rest = M('category')->data($data)->add();
			//销毁缓存
			$CACHE_KEY = 'CAT_TREE';
			F($CACHE_KEY,null);
			$this->ajaxReturn( setAjaxReturn($rest) );
		}
		/*添加子菜单*/
		if( I('id') ) {
			$this->id = I('id');
		}
		/*添加一级菜单*/
		$cat = $this->getCache();
		$cat = tree($cat);
		$this->options = options($cat);
		$this->display();
	}

	Public function edit() {
		if( IS_POST ){
			$data = I('post.');
			$rest = M('category')->save($data);
			//销毁缓存
			$CACHE_KEY = 'CAT_TREE';
			F($CACHE_KEY,null);
			$this->ajaxReturn( setAjaxReturn($rest) );
		}
		$cat = $this->getCache();
		$this->rest = tree($cat);
		$this->options = options($this->rest);
		$field = array('pid','id','title','isdisplay','sort');
		$this->rest = M('category')->field($field)->find( I('id') );
		// P($this->rest);die;
		$this->display();
	}

	Public function delete() {
		$rest = M('category')->delete( I('id') );
		//销毁缓存
		$CACHE_KEY = 'CAT_TREE';
		F($CACHE_KEY,null);
		$this->ajaxReturn( setAjaxReturn($rest) );
	}

	//从栏目跳转到对应的博客
	Public function blog() {
		if ( IS_POST ) {
			$rest = M('blog')->where("cat_id=".I('id'))->count();
			$this->ajaxReturn( setAjaxReturn($rest, '没有对应的文章，请添加！') );
		}
		$rest = M('blog')->where("cat_id=".I('id'))->fetchSql(false)->select();
		foreach ($rest as $key => $value) {
			// P($value['id']);
			$where = array(
				'id' => $value['id'],
				'isdisplay' => 0
				);
			$content = M('blog_data')->where($where)->getField('content');
			$value['content'] = htmlspecialchars_decode($content);
			$rest[$key] = $value;
		}
		// P($rest);
		$this->title = I('title');
		$this->rest = $rest ;
		$this->display();
	}

	Public function getCache() {
		$CACHE_KEY = 'CAT_TREE';
		if( F( $CACHE_KEY ) ) {
			$rest = F($CACHE_KEY);
		} else {
			$field = array('id','pid','title','sort','isdisplay');
			$rest = M('category')->field( $field )->where('status=0')->order( 'sort' )->select();
			F( $CACHE_KEY , $rest );
		}
		return $rest;
	}


}