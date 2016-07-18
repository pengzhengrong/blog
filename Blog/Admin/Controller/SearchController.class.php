<?php

namespace Admin\Controller;
use Think\Controller;
use Library;
// require_once('Blog/Library/vendor/autoload.php');
Class SearchController extends Controller {
public $elastic;
public function _initialize(){
	$param = C('DEFAULT_HOST');
	$this->elastic = new \Library\Elastic($param);
}

public function index() {
	$this->module_name = MODULE_NAME;
	$rest = $this->search();
	// p($rest);
	$fields = array(
		'id' => '_id',
		'score' => '_score',
		'title' => 'title',
		'cat_id' => 'cat_id',
		'highlight' => 'highlight'
		);
	$this->rest = getSearch( $rest , $fields);
	// p($this->rest);die;
	$this->display();
}

public function search(){
		//Elastic search php client
		$search_value = I('search_key');

		$params_arr = array(
			'index' => 'test',
			'type' => 'think_blog',
			'fields' => array('_id','cat_id','title'),
			'search_value' => $search_value,
			'search_key' => 'content',
			'max_expansions' => 20,
			// 'slop' => 0,
			// 'operator' => 'and',
			'highlight' => true,
			'highlight_fields' => array(
				'title' => array('fragment_size' => 10),
				'content' => array( 'pre_tags'=>array('<em>'),'post_tags'=>array('</em>'),'fragment_size' => 10 )
				),
			'search_fields' => array('title','content'), //query_string
			);
		// $params = $this->elastic->match_phrase_prefix_search($params_arr);

		// $rtn = $this->elastic->search($params);

		$params = $this->elastic->query_string_search($params_arr);
		// p($params);die;
		$rtn = $this->elastic->search($params);

		// var_dump($rtn);
		return $rtn;
	}

}
