<?php

namespace Home\Controller;
use Think\Controller;
use Home\Model;
use Think\Log;

Class BlogController extends CommonController {

	private $blogModel;
	private $blogDataModel;
	private $catResult;

	public function _initialize() {
		$this->blogModel = new Model\BlogModel();
		$this->blogDataModel = new Model\BlogDataModel();
		$catModel = new Model\CatModel();
		$this->catResult = $catModel->getListCache();
	}

	Public function index() {
		$where = array('status'=>0);
		//条件搜索
		$cat = $this->catResult;
		//只获取根栏目
		$this->options = options( $cat,'id','title','pid','0'); 
		if ( I('cat_id') == 'default' ) {
			$_POST['cat_id'] = null;
			cookie('cat_id', null);
		}
		
		if ( I('cat_id') != null || cookie('cat_id') != null ) {
			I('cat_id') != null && cookie('cat_id', I('cat_id'));
			$cat_id = I('cat_id') == null ? cookie('cat_id') : I('cat_id');
			$cat_ids = $this->getChildrens($cat, $cat_id); // is string like 1,2,3,4
			$where['cat_id'] = array('in', $cat_ids );
			$this->selected = $cat_id;
		}
		//-----博客名称
		if ( I('title') != null ) {
			$where['title'] = array('like', '%'.I('title').'%');
			$this->title = I('title');
		}
		

		$field = array('id','cat_id','title','click','created','update_time','isdisplay','author');
		
		/*$totalRows = M('blog')->where($where)->count();
		$page = new \Think\Page( $totalRows , C('PAGE_SIZE') );
		$limit = $page->firstRow.','.$page->listRows;
		$this->rest = M('blog')->cache(false,3600)->field($field)->where($where)->order('created desc')->limit($limit)->select();
		$this->page = $page->showPage();*/
		// $blogModel = new Model\BlogModel();
		$rest = $this->blogModel->getDataByPage($field, $where, 'created desc');
		$this->rest = $rest['data'];
		$this->page = $rest['page'];
		$this->display();
	}

	Public function add() {
		if( IS_POST ){
			$data = $_POST;
			$data['created'] = time();
			$data['update_time'] = time();
			$data['time'] = time();
			$data['author'] = session('username');
			$rest = $this->model->add( $data );
			if( $rest ) {
				$data = array(
					'content' => htmlspecialchars($data['content']),
					'id' => $rest,
					'isdisplay' => $data['isdisplay'],
					'extra' => json_encode($data['extra'])
					);
				// $rest = M('blog_data')->add($data);
				$rest = $this->blogDataModel->insert($data);
			}
			$this->ajaxReturn( setAjaxReturn($rest) );
		}
		//博客栏目
		$cat = $this->catResult;
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
			$data['time'] = time();
			// $rest = M('blog')->save($data);
			$rest = $this->blogModel->update($data);
			$blog_data = array(
				'id' => $data['id'],
				'content' => htmlspecialchars($data['content']),
				'isdisplay' => $data['isdisplay'],
				'extra' => json_encode($data['extra'])
				);
			$rest2 = $this->blogDataModel->update($blog_data);
			// $rest2 = M('blog_data')->save($blog_data);
			$rest =  ($rest||$rest2);
			$this->ajaxReturn( setAjaxReturn( $rest ));
		}
		// $rest = M('blog')->find(I('id'));
		$rest = $this->blogModel->getRow('*', ['id'=>I('id')]);
		// $blog_data = M('blog_data')->find(I('id'));
		$blog_data = $this->blogDataModel->getRow('*', ['id'=>I('id')]);
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
		$cat = $this->catResult;
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
			// $rest = M('blog')->delete(I('id'));
			$rest = $this->blogModel->del(['id'=>I('id')]);
			$this->ajaxReturn( setAjaxReturn($rest) );
		}
		//恢复删除
		if( I('type') == 'reback' ){
			// $rest = M('blog')->save( array('id'=>I('id'),'status'=>0,'time'=>time()) );
			$data = [
				'id' => I('id'),
				'status' => 0,
				'time' => time()
			];
			$rest = $this->blogModel->update( $data );
			$this->ajaxReturn( setAjaxReturn($rest) );
		}
		//逻辑删除
		// $rest = M('blog')->save( array('id'=>I('id'),'status'=>1,'time'=>time()) );
		$data = [
				'id' => I('id'),
				'status' => 0,
				'time' => time()
			];
		$rest = $this->blogModel->update($data);
		$this->ajaxReturn( setAjaxReturn($rest) );
	}

	Public function gc() {
		$field = array('id','cat_id','title','click','created','update_time');
		$where = array('status'=>1);
		/*$totalRows = M('blog')->where($where)->count();
		$page = new \Think\Page( $totalRows , C('PAGE_SIZE') );
		$limit = $page->firstRow.','.$page->listRows;
		$this->rest = M('blog')->field($field)->where($where)->order('created desc')->limit($limit)->select();*/
		$data = $this->blogModel->getDataByPage($field, $where);
		$this->rest = $data['data'];
		$this->page = $data['page'];
		$this->display();
	}

	Public function detail() {
		$id = I('id');
		$this->title = I('title');
		$where = array('id' => $id);
		!IS_LOGIN?$where['isdisplay']=0:'';
		// $this->rest = M('blog_data')->cache(true,60)->where($where)->find();
		$this->rest = $this->blogDataModel->getRow('*', $where);

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
		// $rest = M('blog')->save($data);
		$rest = $this->blogModel->update($data);
		// $rest2 = M('blog_data')->save($data);
		$rest2 = $this->blogDataModel->update($data);
		$this->ajaxReturn(setAjaxReturn($rest));
	}

}