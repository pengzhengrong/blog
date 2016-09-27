<?php

namespace Home\Controller;
use Think\Controller;
use Library;
use Home\Model;

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
		// logger(json_encode($rtn));
		$fields = array(
			'id' => '_id',
			'score' => '_score',
			'title' => 'title',
			'cat_id' => 'cat_id',
			'created' => 'created',
			'status' => 'status'
			);
		$this->rest = getSearch( $rtn , $fields);
		// P($this->rest);die;
		$this->page = $page->showPage();
	}

	public function create(){
		$this->delete();
		$fields = array('id','cat_id','status','title','created');
		$rest = M('blog')->field($fields)->where('status=0 AND isdisplay=0')->select();
		$rest = $this->getContent($rest);
		for( $i=0;$i<count($rest);$i++ ){
			$temp = $this->dataclean( $rest[$i]['content'] );
			$rest[$i]['content'] = $temp;
		}
		$fields[] = 'content';
		$rest = $this->elastic->create_index_by_rest( $rest , $fields );
	}

	Public function getContent($rest) {
		$model = new Model\BlogDataModel();
		$databack = array();
		foreach ($rest as $v) {
			$content = $model->getFieldsCache('content', array('id'=>$v['id']));
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



	Private function delete() {
		$param = array(
			'index' => C('DEFAULT_INDEX'),
			'type' => C('DEFAULT_TYPE'),
			'body' => array(
				'query' =>  array(
					'match_all' => array()
					),
				'fields' => array('id')
				)
			);
		$data = $this->elastic->search($param);
		// P($data);die;
		$data = isset($data['hits']['hits'])?$data['hits']['hits']:array();
		foreach ($data as $k => $v) {
			$this->elastic->delete(array('id'=>$v['_id']));
		}
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
				'fields' => array('title','id','cat_id','created','status')
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