<?php

namespace Home\Controller;
use Think\Controller;
Class BlogController extends CommonController {

	Public function index() {

		$field = array('id','cat_id','title','click','created','update_time');
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
					'content' => $data['content'],
					'id' => $rest
					);
				$rest = M('blog_data')->add($data);
			}
			$this->ajaxReturn( setAjaxReturn($rest) );
		}
		$cat = A('Cat')->getCache();
		$this->category = tree($cat);
		$this->options = options( $this->category);
		$this->display();
	}

	Public function edit() {
		if( IS_POST ){
			$data = $_POST;
			$data['update_time'] = time();
			$rest = M('blog')->save($data);
			$rest = M('blog_data')->save($data);
			if( !$rest ) {
				$rest = M('blog_data')->add($data);
			}
			$this->ajaxReturn( setAjaxReturn($rest) );
		}
		$rest = M('blog')->find(I('id'));
		$blog_data = M('blog_data')->find(I('id'));
		$rest['content'] = $blog_data['content'];
		$this->rest = $rest;
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
			$rest = M('blog')->save( array('id'=>I('id'),'status'=>0,'update_time'=>time()) );
			$this->ajaxReturn( setAjaxReturn($rest) );
		}
		//逻辑删除
		$rest = M('blog')->save( array('id'=>I('id'),'status'=>1,'update_time'=>time()) );
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
		$this->gc = true;
		$this->display();
	}

}