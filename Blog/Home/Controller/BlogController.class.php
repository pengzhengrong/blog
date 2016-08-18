<?php

namespace Home\Controller;
use Think\Controller;
Class BlogController extends CommonController {

	Public function index() {

		$field = array('id','cat_id','title','click','created','update_time','isdisplay');
		$where = array('status'=>0);
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
			$rest = M('blog')->add( $data );
			if( $rest ) {
				$data = array(
					'content' => htmlspecialchars($data['content']),
					'id' => $rest
					);
				$rest = M('blog_data')->add($data);
			}
			$this->ajaxReturn( setAjaxReturn($rest) );
		}
		//博客栏目
		$cat = F('CAT_TREE');
		$this->category = tree($cat);
		$this->options = options( $this->category);
		$this->display();
	}

	Public function edit() {
		if( IS_POST ){
			$data = $_POST;
			$data['update_time'] = time();
			$rest = M('blog')->save($data);
			$blog_data = array(
				'id' => $data['id'],
				'content' => htmlspecialchars($data['content'])
				);
			$rest2 = M('blog_data')->save($blog_data);
			// logger($rest.'_'.$rest2);
			$rest =  ($rest||$rest2);
			$this->ajaxReturn( setAjaxReturn( $rest ));
		}
		$rest = M('blog')->find(I('id'));
		$blog_data = M('blog_data')->find(I('id'));
		$rest['content'] = $blog_data['content'];
		$this->rest = $rest;
		//博客栏目
		// $cat = A('Cat')->getCache();
		$cat = F('CAT_TREE');
		$this->category = tree($cat);
		$this->options = options( $this->category);

		$this->display();
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
		$this->rest = M('blog_data')->cache(true,60)->find($id);
		$this->display();
	}

}