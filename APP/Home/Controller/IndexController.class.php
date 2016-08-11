<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends CommonController {

	/*public function test() {
		$this->display('test');
		// print_r(TMPL_PATH);
	}*/

	Public function _initialize() {
		/*S(array(
			'type'=>'memcache',
			'host'=>'127.0.0.1',
			'port'=>'11211',
			'prefix'=>'App_',
			'expire'=>60
			)
			);*/
		/*S(array(
			'type'=>'redis',
			'host'=>'127.0.0.1',
			'port'=>'6379',
			'prefix'=>'App_',
			'expire'=>60
			)
			);*/
			$this->cate = $this->getCategory();
		}

		Public function page_blog() {
			$where = array(
				'status' => 0,
				'isdisplay' => 0
				);
			$fields = array('id','title','created');

			$count = M('blog')->where($where)->fetchSql(false)->count();
			$pageSize = I('size',C('PAGE_SIZE'),'intval');
			$url = '/'.ACTION_NAME.'?p='.urlencode('[PAGE]');
			$limit = $this->page($count,$pageSize,$url);

			$rest = M('blog')->cache(false,C('CACHE_TIME'))->field($fields)->where($where)->order('created desc')->limit($limit)->fetchSql(false)->select();
			$this->rest = $this->getContent($rest);
			cookie('READ_BLOG_TYPE',null);
			$this->display();
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

	 /**
	  * 栏目列表
	  * @return [type] [description]
	  */
	 Public function category() {
	 	// P($_SERVER);
	 	$id= I('id',0,'intval');
	 	$cate = M('category')->cache(true,C('CACHE_TIME'))->field(array('id','pid'))->where('status=0 AND isdisplay=0')->select();
	 	// P($cate);
	 	$ids = getChildrens($cate,$id);
	 	// P($ids);
	 	$where = "status=0 AND isdisplay=0 AND cat_id in ($ids)";
	 	$count = M('blog')->where($where)->count();
	 	$pageSize = I('size',C('PAGE_SIZE'),'intval');
	 	$url = '/'.ACTION_NAME."_$id".'?p='.urlencode('[PAGE]');
	 	$limit = $this->page($count ,$pageSize, $url);

	 	$rest = M('blog')->cache(true,C('CACHE_TIME'))->field(array('id','title','created'))->where($where)->order('created desc')->limit($limit)->select();
	 	$this->rest = $this->getContent($rest);
	 	//记录当前浏览博客的类型是从栏目跳转过去，默认的是列表页跳转
	 	cookie('READ_BLOG_TYPE','category');
	 	//记录上下文章的栏目id，以此作为上下翻页的依据
	 	cookie('CATEGORY_IDS',$ids);
	 	$this->display();
	 }

	 Public function blog() {
	 	$id = I('id',0,'intval');
	 	$rest = M('blog')->find($id);
	 	$this->rest = $this->getContent($rest, true);
	 	$cacheKey = "BLOG_ID_{$id}";
	 	S($cacheKey,S($cacheKey)+1,24*3600);
	 	$this->get_next_prev($id , cookie('READ_BLOG_TYPE'));
	 	$this->display();
	 }

	 Private function getCategory() {
	 	$cacheKey = 'CATE_CACHE';
	 	if( S($cacheKey) ) {
	 		return S($cacheKey);
	 	}
	 	$where = array(
	 		'status' => 0,
	 		'isdisplay' => 0,
	 		'pid' => 0
	 		);
	 	$fields = array('id','title');
	 	$rest = M('category')->field($fields)->where($where)->order('sort')->select();
	 	S($cacheKey,$rest,C('CACHE_TIME'));
	 	return $rest;
	 }

	 Public function search() {
	 	$search_value = I('search_key');
	 	$pageSize = C('ELASTIC_PAGE_SIZE');

	 	vendor('Elastic/Elastic','','.class.php');
	 	$param = C('DEFAULT_HOST');
	 	$search_value = I('search_key');
	 	$elastic = new \Elastic($param);

	 	$searchCount = $elastic->search( $this->search_count($search_value) );
	 	$totalRows = $searchCount['hits']['total'];
	 	$page = new \Think\Page(intval($totalRows) , $pageSize);
	 	$page->url = '/'.ACTION_NAME.'?search_key='.$search_value.'&p='.urlencode('[PAGE]');
	 	$firstRow = $page->firstRow;
	 	$this->search_key = $search_value;
	 	$params = $this->query_string($search_value , $firstRow);

	 	$rtn = $elastic->search($params);
	 	$fields = array(
	 		'id' => '_id',
	 		'score' => '_score',
	 		'title' => 'title',
	 		'cat_id' => 'cat_id',
	 		'highlight' => 'highlight',
	 		'created' => 'created'
	 		);
	 	$this->rest = getSearch( $rtn , $fields);
		// P($this->rest); die;
	 	$page->setConfig('theme',  "%HEADER% %UP_PAGE%  %FIRST% %LINK_PAGE% %END% %DOWN_PAGE%");
	 	$this->show = $page->show();
	 	$this->display();
	 }

	 /**
	  * 获取一篇博客的上下篇
	  * @param  [int] $id   [当前博客的id]
	  * @param  string $type [进入当前博客的类型:进入的方式可以是栏目列表或者全部]
	  * @return [type]       [description]
	  */
	 Private function get_next_prev($id , $type='') {
	 	$rest = array();
	 	// P($type);
	 	switch ($type) {
	 		case 'category':
	 		$ids = cookie('CATEGORY_IDS');
	 		$rest = M('blog')->cache(true,C('CACHE_TIME'))->field(array('id','title'))->where("status=0 AND cat_id in ($ids)")->order('created desc')->select();
	 		break;
	 		default:
	 		$rest = M('blog')->cache(true,C('CACHE_TIME'))->field(array('id','title'))->where('status=0')->order('created desc')->select();
	 		break;
	 	}
	 	// P($rest);
	 	foreach ($rest as $k => $v) {
	 		if( $v['id'] == $id ) {
	 			$this->prev = $rest[$k-1];
	 			$this->next = $rest[$k+1];
	 		}
	 	}
	 	// P($this->prev);
	 	// P($this->next);
	 	// return $rest;
	 	return;
	 }

	 Private function page($totalRows , $pageSize , $url='') {
	 	$page = new \Think\Page(intval($totalRows) , $pageSize);
	 	if( !empty($url) ) {
	 		$page->url = $url;
	 	}
	 	$page->setConfig('theme',  "%HEADER% %UP_PAGE%  %FIRST% %LINK_PAGE% %END% %DOWN_PAGE%");
	 	$this->show = $page->show();
	 	$limit = $page->firstRow.','.$page->listRows;
	 	return $limit;
	 }

	 Private function query_string($search_value,$firstRow) {
	 	$param = array(
	 		'index' => C('DEFAULT_INDEX'),
	 		'type' => C('DEFAULT_TYPE'),
	 		'from' => $firstRow,
	 		'size' => C('ELASTIC_PAGE_SIZE'),
	 		'body' => array(
	 			'query' => array(
	 				'query_string' => array(
	 					'query' => $search_value,
	 					'default_operator' => 'and',
	 					'fields' => array('title','content'),
	 					'minimum_should_match' => 2,
	 					'auto_generate_phrase_queries' => true,
	 					)
	 				),
	 			'highlight' => array(
	 				'term_vector' => 'with_positions_offsets',
	 				'fields' => array(
	 					'title'=>array(
	 						'fragment_size' => 10
	 						),
	 					'content'=>array(
	 						'fragment_size' => 10,
	 						'pre_tags' => array('<b>'),
	 						'post_tags' => array('</b>')
	 						)
	 					)
	 				),
	 			'fields' => array('title','id','cat_id','created')

	 			)

	 		);
	 	return $param;
	 }

	 Private function search_count($search_key) {
	 	$param = array(
	 		'index' => 'test',
	 		'type' => 'think_blog',
	 		'search_type' => 'count',
	 		'body' => array(
	 			'query' => array(
	 				'query_string' => array(
	 					'query' => $search_key,
	 					'default_operator' => 'and',
	 					'fields' => array('title','content')
	 					)
	 				)
	 			)
	 		);
	 	return $param;
	 }



	}










/*$params_arr = array(
			'index' => 'test',
			'type' => 'think_blog',
			'fields' => array('_id','cat_id','title'),
			'search_value' => $search_value,
			'search_key' => 'content',
			'max_expansions' => 20,
			// 'slop' => 0,
			'operator' => 'and',
			'highlight' => true,
			'highlight_fields' => array(
				'title' => array('fragment_size' => 10),
				'content' => array( 'pre_tags'=>array('<em>'),'post_tags'=>array('</em>'),'fragment_size' => 10 )
				),
			'search_fields' => array('title','content'), //query_string
			);
		// $params = $elastic->match_phrase_prefix_search($params_arr);
		$params = $elastic->query_string_search($params_arr);
		p($params);
		$rtn = $elastic->search($params);
			*/

		// var_dump($rtn);

		// return $rtn;
		/*$data = array(
			'status' => 200,
			'msg' => 'ok',
			'data' => $rtn
			);
		$this->ajaxReturn($data);*/