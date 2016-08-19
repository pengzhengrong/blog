<?php

namespace Home\Controller;
use Think\Controller;
use Library;

Class SearchController extends Controller {

	public $elastic;
	public function _initialize(){
		vendor('Elastic.Elastic','','.class.php');
		$param = C('DEFAULT_HOST');
		$this->elastic = new \Elastic($param);
	}

	public function index() {


		$this->search();
		$this->display();
	}

	public function search(){
		$pageSize = C('PAGE_SIZE');
		$searchCount = $this->elastic->search( $this->search_count() );
		$totalRows = $searchCount['hits']['total'];
		$page = new \Think\Page(intval($totalRows) , $pageSize);
		$page->url = '/'.MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME.'?p='.urlencode('[PAGE]');
		$firstRow = $page->firstRow;
		$params = $this->query_string($firstRow);

		$rtn = $this->elastic->search($params);
		$fields = array(
			'id' => '_id',
			'score' => '_score',
			'title' => 'title',
			'cat_id' => 'cat_id',
			'created' => 'created',
			'status' => 'status'
			);
		$this->rest = getSearch( $rtn , $fields);
		$this->page = $page->showPage();
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
		$fields = array('id','cat_id','status','title','created');
		$rest = M('blog')->field($fields)->where('status=0 AND isdisplay=0')->select();
		$rest = $this->getContent($rest);
		for( $i=0;$i<count($rest);$i++ ){
			$temp = $this->dataclean( $rest[$i]['content'] );
			$rest[$i]['content'] = $temp;
		}
		// p($rest);die;
		$fields[] = 'content';
		$rest = $this->elastic->create_index_by_rest( $rest , $fields );
	}

	public function syncBlog(){
		$fields = array('id','cat_id','status','title','created');
		$rest = M('blog')->field($fields)->where('status=0')->select();
		$rest = $this->getContent($rest);
		for( $i=0;$i<count($rest);$i++ ){
			$temp = dataclean( $rest[$i]['content'] );
			$rest[$i]['content'] = $temp;
		}
		// p($rest);die;
		$fields[] = 'content';
		$this->elastic->create_index_by_rest( $rest , $fields );
	}

	Public function getContent( $rest , $flag=false ) {
		if( $flag ) {
			$content = M('blog_data')->where('id='.$rest['id'])->fetchSql(false)->getField('content');
			$rest['content'] = $content;
			return $rest;
		}
		$databack = array();
		foreach ($rest as $v) {
			$content = M('blog_data')->where('id='.$v['id'])->fetchSql(false)->getField('content');
			$v['content'] = $content;
			$databack[] = $v;
		}
		return $databack;
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








	Private function query_string($firstRow) {
		$param = array(
			'index' => C('DEFAULT_INDEX'),
			'type' => C('DEFAULT_TYPE'),
			'from' => $firstRow,
			'size' => C('ELASTIC_PAGE_SIZE'),
			'body' => array(
				'query' =>  array(
					'match_all' => array()
					),
				'fields' => array('title','id','cat_id','created')
				)
			);
		return $param;
	}

	Private function search_count() {
		$param = array(
			'index' => 'test',
			'type' => 'think_blog',
			'search_type' => 'count',
			'body' => array(
				'query' =>array(
					'match_all' => array()
					)

				)
			);
		return $param;
	}


}