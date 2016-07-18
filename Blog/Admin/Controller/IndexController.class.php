<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends CommonController {
	public function index(){
		$rest = S('blog_cate');
		if( !$rest ){
			$rest = M('category')->order('sort')->where('status=0')->select();
			S('blog_cate',$rest,C('DEFAULT_CACHE_TIME')) ;
		}
		$this->rest = node_merge( $rest );
		// p($this->rest);die;
		if( I('id') != null ){
			foreach ($this->rest as $key => $value) {
				if( $value['id'] == I('id') ){
					$this->cate = $value['child'];
					// p($this->cate);die;
				}
			}
		}
		$this->id = I('id');
		$this->display();
	}

	public function _after_index() {
		// my_log('id_test',I('id'));

	}

	public function content() {
		$where = array(
			'status' => 0,
			'cat_id' => I('id')
			);
		 // $this->blog = M('blog')->where($where)->find();
		$this->blog = D('Home/BlogRelation')->relation('attr')->where($where)->find();
		 // p($this->blog); die;
		$this->display();
	}

	public function attr(){
		$ip = get_client_ip();
		$date = date('Y-m-d');
		$blog_id = I('blog_id',0,'intval');
		$attr_id = I('attr_id',0,'intval');
		$key = 'ATTR_VOTE_IP'.$date.$blog_id.$attr_id.$ip;
		if( S($key) ){
			$databack = array(
				'status' => 200,
				'msg' => '3q4u had voted!',
				'data' => 0
				);
			$this->ajaxReturn($databack);
		}
		$where = array(
			'blog_id' => $blog_id,
			'attr_id' => $attr_id
			);
		$rest = M('blog_attr')->where($where)->setInc('attr_count');
		if( $rest ){
			$databack = array(
				'status' => 200,
				'msg' => '3q4u voted!',
				'data' => 1
				);
			S($key , true , 24*3600 );
		}else{
			$databack = array(
				'status' => 500,
				'msg' => 'sorry,some error happend!',
				'data' => 0
				);
		}
		$this->ajaxReturn($databack);
	}

}