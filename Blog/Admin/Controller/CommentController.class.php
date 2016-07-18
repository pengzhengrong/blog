<?php


namespace Admin\Controller;
use Think\Controller;

Class CommentController extends CommonController {

	public function index() {
		if( !C('COMMENT_ON') ){
			$this->display();
			return;
		}
		$blog_id = I('blog_id');
		$where = array(
			'status' => 0,
			'blog_id' => $blog_id
			);
		$rest = M('blog_comment')->where($where)->order('top_num desc,created ')->select();
		// p($rest); die;
		$this->comment = node_merge($rest);
		$this->blog_id = $blog_id;
		$this->display();
	}

	public function add() {
		if( !C('COMMENT_ON') ){
			exit('-1');
		}
		if( session('username') == null ){
			exit('0');
		}
		$data = array(
			'blog_id' => I('blog_id'),
			'content' => I('content','',''),
			'username' => session('username'),
			'pid' => I('pid',0,'intval'),
			'created' => time()
			);
		$rest = M('blog_comment')->add($data);
		exit (''.$rest);
	}

	Public function vote() {
		if( IS_POST){
			$key = get_client_ip().'_'.I('id').date('Y-m-d',time());
			if( S($key) ){
				exit('-1');
			}
			$column = I('type',0,'intval')==1?'top_num':'base_num';
			$rest = M('blog_comment')->where('id='.I('id'))->setInc( $column );
			if( $rest ){
				S( $key , $rest , 60*60*24 );
			}
			exit(''.$rest);
		}
		exit('0');
	}



}