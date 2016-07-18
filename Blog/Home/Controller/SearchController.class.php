<?php

namespace Home\Controller;
use Think\Controller;
use Library;
require_once('Blog/Library/vendor/autoload.php');
Class SearchController extends Controller {
	public $client;
	public $elastic;
	public function _initialize(){
		$param = C('DEFAULT_HOST');
		$this->client = new \Elasticsearch\Client($param);
		$this->elastic = new \Library\Elastic($param);
	}

	public function index() {
		$this->module_name = MODULE_NAME;
		$this->display();
	}

	public function search(){
		//Elastic search php client
		$search_key = I('search_key');

		$params = array(
			'index' => 'test',
			'type' => 'think_blog',
			'body' => array(
				'fields' => array('_id','title'),
				'query' => array(
					'match_phrase_prefix' => array(
						'content' => array(
							'query' => $search_key,
							'operator' => 'and'
							)
						)
					)
				/*,'highlight' => array(
					'fields' => array( 'content' => (object)array() )
					)*/
					)
			);

		$rtn = $this->client->search($params);
		// var_dump($rtn);
		return $rtn;
	}

	/**
	 * @return 创建索引
	 */
	/*public function create(){
		$sql =  'select id ,cat_id,status,title,content from test.think_blog where status=0';
		$fields = array('id','cat_id','status','title','content');
		$params = array('index'=>'test','type'=>'think_blog');
		$this->elastic->create_index($sql , $fields , $params);
	}*/

	public function create(){
		$fields = array('id','cat_id','status','title','content','created');
		$rest = M('blog')->field($fields)->where('status=0')->select();
		for( $i=0;$i<count($rest);$i++ ){
			$temp = $this->dataclean( $rest[$i]['content'] );
			$rest[$i]['content'] = $temp;
		}
		// p($rest);die;
		$this->elastic->create_index_by_rest( $rest , $fields );
	}

	public function syncBlog(){
		$fields = array('id','cat_id','status','title','content','created');
		$rest = M('blog')->field($fields)->where('status=0')->select();
		for( $i=0;$i<count($rest);$i++ ){
			$temp = dataclean( $rest[$i]['content'] );
			$rest[$i]['content'] = $temp;
		}
		// p($rest);die;
		$this->elastic->create_index_by_rest( $rest , $fields );
	}

	private function dataclean( $data ){
		//trim &nbsp;
		$temp = preg_replace('/&nbsp;/', ' ', $data);
		$temp = preg_replace('/<br\/>/', '', $temp);
		$temp = preg_replace('/(<\/pre>)|(<pre.*?[^>]>)/', ' ', $temp);
		$temp = preg_replace('/(<\/p>)|(<p.*?[^>]>)/', ' ', $temp);
		// $temp = htmlspecialchars_decode($temp , ENT_QUOTES);
		$temp = html_entity_decode($temp,ENT_QUOTES);
		//trim html&php tags
		// $temp = strip_tags($temp);
		return $temp;
	}


}