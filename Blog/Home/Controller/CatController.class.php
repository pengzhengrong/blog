<?php

namespace  Home\Controller;

use Think\Controller;
use Home\Model;

Class CatController extends CommonController {

	private $model;
	private $catResult;

	public function _initialize() {
		$this->model = new Model\CatModel();
		$this->catResult = $this->model->getListCache();
	}

	Public function index() {
		$cookieKey = 'COOKIE_'.__CLASS__.__FUNCTION__;
		$rest = $this->catResult;
		/*条件搜索*/
		if( IS_POST ) {
			// $rest = $this->getCache();
			// $rest = $this->model->getList();
			$this->options = options( $rest , 'id', 'title', 'pid', '0');
			I('id') == 'default' ? cookie($cookieKey, null) :cookie($cookieKey, I('id'));
			$id = cookie($cookieKey)==null?I('id',0,'intval'):cookie($cookieKey);
			$this->selected = $id;
			$this->rest = tree($rest, $id, true);
			$this->display();
			exit;
		}
		//只获取根栏目
		$this->options = options( $rest,'id','title','pid','0');
		$this->testRest = $rest;
		$id = cookie($cookieKey);
		if ( $id == null ) {
			$this->rest= tree($rest);
		} else {
			$this->rest = tree($rest, $id, true);
			$this->selected = $id;
		}
		/*$this->tree($rest , 0);
		fb($this->treeData, 'treeData');
		$this->tree =json_encode( $this->treeData);
		$this->display('test');*/
		$this->display();
	}

	Public function add() {
		if( IS_POST ) {
			$data = I('post.');
			// $rest = M('category')->data($data)->add();
			$rest = $this->model->insert($data);
			$this->ajaxReturn( setAjaxReturn($rest) );
		}
		/*添加子菜单*/
		if( I('id') ) {
			$this->id = I('id');
		}
		/*添加一级菜单*/
		$cat = $this->catResult;
		$cat = tree($cat);
		$this->options = options($cat);
		$this->display();
	}

	Public function edit() {
		if( IS_POST ){
			$data = I('post.');
			// $rest = M('category')->save($data);
			$rest = $this->model->update($data);
			$this->ajaxReturn( setAjaxReturn($rest) );
		}
		$cat = $this->catResult;
		$this->rest = tree($cat);
		$this->options = options($this->rest);
		$field = array('pid','id','title','isdisplay','sort');
		// $this->rest = M('category')->field($field)->find( I('id') );
		$this->rest = $this->model->getRow($field, ['id'=>I('id')]);
		$this->display();
	}

	Public function delete() {
		// $rest = M('category')->delete( I('id') );
		$rest = $this->model->del(I('id'));
		$this->ajaxReturn( setAjaxReturn($rest) );
	}

	//从栏目跳转到对应的博客
	Public function blog() {
		$blogModel = new Model\BlogModel();
		$blogDataModel = new Model\BlogDataModel();
		if ( IS_POST ) {
			// $rest = M('blog')->where("cat_id=".I('id'))->count();
			$rest = $blogModel->getRow('*', ['cat_id'=>I('id')]);
			$this->ajaxReturn( setAjaxReturn($rest, '没有对应的文章，请添加！') );
		}
		$id = I('id');
		// $rest = M('blog')->where("cat_id={$id} AND `status`=0")->fetchSql(false)->select();
		$rest = $blogModel->getData('*', ['cat_id'=>$id, 'status'=>0]);
		foreach ($rest as $key => $value) {
			$where = array(
				'id' => $value['id']
				);
			if (!IS_LOGIN) {
				$where['isdisplay'] = 0;
			}
			// $content = M('blog_data')->cache(true,60)->where($where)->fetchSql($fetchSql)->getField('content');
			$content = $blogDataModel->getColumnCache('content', $where);
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

	private $treeData = [];
	protected function tree($rest, $pid=0) {
		// static $data = [];
		foreach ($rest as $k => $v) {
			if($v['pid'] == $pid) {
				$this->treeData["{$v['pid']}_{$v['id']}"] = "text:{$v['title']};";
				$this->tree($rest, $v['id']);
			}
		}
	}


}