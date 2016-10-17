<?php 

namespace Home\Controller;
use Think\Controller;
Class TimelineController extends CommonController {

	Public function index() {
		$where = array();
		if (I('title') != null) {
			$where['title'] = array('like', I('title').'%' );
		}

		$field = array('id','title','created','updated','isdisplay');
		$totalRows = M('timeline')->count();
		$page = new \Think\Page( $totalRows , C('PAGE_SIZE') );
		$limit = $page->firstRow.','.$page->listRows;
		$this->rest = M('timeline')->cache(false,60)->where($where)->field($field)->fetchSql(false)->order('id desc')->limit($limit)->select();
		$this->page = $page->showPage();
		$this->display();
	}

	/*
	* array(
		'0' => array(
			'subTitle' => 'test',
			'content' => 'content',
			'desc' => 'desc'
		)
		)
	*/
		Public function detail() {
			if ( IS_POST ) {
				$info = I('info');
				$subTitle = $info['subTitle'];
				$content = $info['content'];
				$desc = $info['desc'];
				$title = $info['title'];
				$timeline = array();
				foreach ($subTitle as $k=>$v) {
					$timeline[] = array(
						'subTitle' => $v,
						'desc' => $desc[$k],
						'content' => $content[$k]
						);
				}
				$data = array(
					'title' => $title,
					'extra' => json_encode($timeline),
					'updated' => time()
					);
				if ( $info['id'] > 0 ) {
					$data['id'] = $info['id'];
					$rest = M('timeline')->save($data);
				} else {
					$data['created'] = time();
					$rest = M('timeline')->add($data);
				}
				
				$this->ajaxReturn( setAjaxReturn($rest) );
				
			}
			$id = I('id',0,'intval');
			if ( $id > 0 ) {
				$this->id = $id;
				$where = array('id'=>$id);
				!IS_LOGIN?$where['isdisplay']=0:'';
				$rest = M('timeline')->where($where)->find();
				$this->rest = json_encode($rest);
			}
			$this->display();

		}

		Public function delete() {
			$id = I('id',0,'intval');
			$rest = M('timeline')->delete($id);
			$this->ajaxReturn( setAjaxReturn($rest) );
		}

		Public function show() {
			$rest = M('timeline')->fetchSql(false)->save(I('post.'));
			debug($rest);
			$this->ajaxReturn(setAjaxReturn($rest));
		}

	}

