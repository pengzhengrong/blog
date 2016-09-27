<?php

namespace Home\Controller;
use Think\Controller;
use Home\Model;
use Think\Log;

Class BlogController extends CommonController {

	Public function index() {
		/*$blog = new Model\BlogModel('blog');
		// $data = $blog->getBlogInfo();
		$data = $blog->getRow(array(), array('id'=>2));
		// $data = D('Blog')->getBlogInfo();
		P($data);
		die;*/
		$where = array('status'=>0);
		//条件搜索
		$cat = F('CAT_TREE');
		//只获取根栏目
		$this->options = options( $cat,'id','title','pid','0'); 
		if ( I('cat_id') == 'default' ) {
			$_POST['cat_id'] = null;
			cookie('cat_id', null);
		}
		// P(I('cat_id'));
		// P(cookie('cat_id'));
		if ( I('cat_id') != null || cookie('cat_id') != null ) {
			I('cat_id') != null && cookie('cat_id', I('cat_id'), 60);
			$cat_id = I('cat_id') == null ? cookie('cat_id') : I('cat_id');
			$cat_ids = $this->getChildrens($cat, $cat_id); // is string like 1,2,3,4
			$where['cat_id'] = array('in', $cat_ids );
			$this->selected = $cat_id;
		}
		//-----博客名称
		if ( I('title') != null ) {
			$where['title'] = array('like', I('title').'%');
			$this->title = I('title');
		}
		

		$field = array('id','cat_id','title','click','created','update_time','isdisplay','author');
		
		$totalRows = M('blog')->where($where)->count();
		$page = new \Think\Page( $totalRows , C('PAGE_SIZE') );
		$limit = $page->firstRow.','.$page->listRows;
		$this->rest = M('blog')->cache(false,3600)->field($field)->where($where)->order('created desc')->limit($limit)->select();
		$this->page = $page->showPage();
		$this->display();
	}

	Public function add() {
		if( IS_POST ){
			$data = $_POST;
			$data['created'] = time();
			$data['update_time'] = time();
			$data['time'] = time();
			$data['author'] = session('username');
			$rest = M('blog')->add( $data );
			if( $rest ) {
				$data = array(
					'content' => htmlspecialchars($data['content']),
					'id' => $rest,
					'isdisplay' => $data['isdisplay'],
					'extra' => json_encode($data['extra'])
					);
				$rest = M('blog_data')->add($data);
			}
			$this->ajaxReturn( setAjaxReturn($rest) );
		}
		//博客栏目
		$cat = F('CAT_TREE');
		$this->category = tree($cat);
		$this->options = options( $this->category);
		$this->selected = isset($_GET['cat_id'])?$_GET['cat_id']:0; //从栏目跳入增加博客
		$this->title = isset($_GET['title'])?$_GET['title']:'世界那么大,Title想去逛逛!';
		$this->view();
	}

	public function view($from='') {
		if (I('editor') != null) {
			$this->display('add_markdown');
		} elseif ($from=='markdown') {
			$this->display('edit_markdown');
		} else {
			$this->display();
		}
	}

	Public function edit() {
		if( IS_POST ){
			$data = $_POST;
			$data['update_time'] = time();
			$rest = M('blog')->save($data);
			$blog_data = array(
				'id' => $data['id'],
				'content' => htmlspecialchars($data['content']),
				'isdisplay' => $data['isdisplay'],
				'extra' => json_encode($data['extra'])
				);
			$rest2 = M('blog_data')->save($blog_data);
			// logger($rest.'_'.$rest2);
			$rest =  ($rest||$rest2);
			$this->ajaxReturn( setAjaxReturn( $rest ));
		}
		$rest = M('blog')->find(I('id'));
		$blog_data = M('blog_data')->find(I('id'));
		$rest['content'] = $blog_data['content'];
		$extraJson = json_decode($blog_data['extra'], true);
		if ($extraJson) {
			$from = $extraJson['from'];
			if ($from == 'markdown') {
				$source = $extraJson['source'];
				$rest['contentSource'] = $source;
			}
		}

		$this->rest = $rest;
		//博客栏目
		// $cat = A('Cat')->getCache();
		$cat = F('CAT_TREE');
		$this->category = tree($cat);
		$this->options = options( $this->category);

		$this->view($from);
	}

	/**
	* 删除博客
	* 彻底删除,恢复删除,逻辑删除
	 */
	Public function delete() {
		//彻底删除
		if( I('type') == 'delete' ){
			$rest = M('blog')->delete(I('id'));
			$this->ajaxReturn( setAjaxReturn($rest) );
		}
		//恢复删除
		if( I('type') == 'reback' ){
			$rest = M('blog')->save( array('id'=>I('id'),'status'=>0,'time'=>time()) );
			$this->ajaxReturn( setAjaxReturn($rest) );
		}
		//逻辑删除
		$rest = M('blog')->save( array('id'=>I('id'),'status'=>1,'time'=>time()) );
		$this->ajaxReturn( setAjaxReturn($rest) );
	}

	Public function gc() {
		$field = array('id','cat_id','title','click','created','update_time');
		$where = array('status'=>1);
		$totalRows = M('blog')->where($where)->count();
		$page = new \Think\Page( $totalRows , C('PAGE_SIZE') );
		$limit = $page->firstRow.','.$page->listRows;
		$this->rest = M('blog')->field($field)->where($where)->order('created desc')->limit($limit)->select();
		$this->page = $page->showPage();
		$this->display();
	}

	Public function detail() {
		$id = I('id');
		$this->title = I('title');
		$where = array('id' => $id);
		!IS_LOGIN?$where['isdisplay']=0:'';
		$this->rest = M('blog_data')->cache(true,60)->where($where)->find();

		increBlogClick($id);

		$this->display();
	}

	Private function getChildrens( $arr , $id ){
		$databack = $id;
		foreach ($arr as $key => $value) {
			if( $value['pid'] == $id ){
				$databack .=  ','.getChildrens( $arr , $value['id'] );
			}
		}
		return $databack;
	}

	Public function show() {
		$data = I('post.');
		$data['time'] = time();
		$rest = M('blog')->save($data);
		$rest2 = M('blog_data')->save(I('post.'));
		$this->ajaxReturn(setAjaxReturn($rest));
	}

}