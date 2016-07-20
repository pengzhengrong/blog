<?php
namespace Library;
require_once('vendor/autoload.php');
Class Elastic {

	public $client;

	public $config;

	/**
	 * @param  $params 类似 $params = array( 'hosts' => array('localhost:9202') )
	 */
	public function __construct($params , $config=array() ){
		$this->client = new \Elasticsearch\Client($params);
		$this->config = $this->setConfig($config);
	}

	public function get_conn(){
		$host = C('DB_HOST');
		$dbname = C('DB_NAME');
		$user = C('DB_USER');
		$passwd = C('DB_PWD');
		// $dsn = 'mysql:dbname=test;host=127.0.0.1';
		// mysqli:dbname=test;host=127.0.0.1;charset=utf8
		$conn = new \PDO("mysqli:dbname=$dbname;host=$host;charset=utf8",$user,$passwd);
		return $conn;
	}
	/**
	 * @param  $sql 同步的sql语句
	 * @param  $fields ES字段
	 * @param  string
	 * @return [type]
	 */
	public function create_index( $sql , $fields , $conf=array() ){
	//Elastic search php client
		// $this->client = new \Elasticsearch\Client();
		// $sql = "SELECT * FROM log";
		$index = $this->getParams($conf , 'index');
		$type = $this->getParams($conf , 'type');
		$conn = $this->get_conn();
		$stmt = $conn->query($sql);
		$rtn = $stmt->fetchAll();
	//delete index which already created
		/*$params = array();
		$index = empty($index)?C('DEFAULT_INDEX'):$index;
		$params['index'] = $index;
		$this->client->indices()->delete($params); */
	//create index on log_date,src_ip,dest_ip
		$rtnCount = count($rtn);
		for($i=0;$i<$rtnCount;$i++){
			$params = array();
			foreach ($fields as $k => $v) {
				if( $v == 'id' ){
					$params['id'] = $rtn[$i][$v];
					continue;
				}
				$params['body'][$v] = $rtn[$i][$v];
			}
			/*$params['body'] = array(
				'id' => $rtn[$i]['id'],
				'cat_id' => $rtn[$i]['cat_id'],
				'title' => $rtn[$i]['title']
				); */
			$params['index'] = $index;
			$params['type'] = empty($type)?C('DEFAULT_TYPE'):$type;
			// p($params); die;
		//Document will be indexed to log_index/log_type/autogenerate_id
			$this->client->index($params);
		}
		// echo 'create index done!';
		// return $rtnCount;
		$databack = array(
			'status'=>200,
			'msg' => 'ok',
			'data' => $rtnCount
			);
		 header('Content-Type:application/json; charset=utf-8');
                	exit(json_encode($databack));
	}

	public function update_index($params){

	}

	public function create_index_one($fields , $conf ){
		$index = $this->getParams($conf , 'index');
		$type = $this->getParams($conf , 'type');
		$id = $this->getParams($conf, 'id');
		if( empty($id) )
			notice('ES create failed',1);
		$params['index'] = empty($index)?C('DEFAULT_INDEX'):$index;
		$params['type'] = empty($type)?C('DEFAULT_TYPE'):$type;
		$params['id'] = $id;
		foreach ($fields as $k => $v) {
			$params['body'][$k] = $v;
		}
		// p($params);die;
		$data = $this->client->index($params);
		return $data;
	}

	public function create_index_by_rest( $rtn , $fields , $conf=array()){
		$index = $this->getParams($conf , 'index');
		$type = $this->getParams($conf , 'type');
		$rtnCount = count($rtn);
		for($i=0;$i<$rtnCount;$i++){
			$params = array();
			foreach ($fields as $k => $v) {
				if( $v == 'id' ){
					$params['id'] = $rtn[$i][$v];
					continue;
				}
				$params['body'][$v] = $rtn[$i][$v];
			}
			/*$params['body'] = array(
				'id' => $rtn[$i]['id'],
				'cat_id' => $rtn[$i]['cat_id'],
				'title' => $rtn[$i]['title']
				); */
			$params['index'] = empty($index)?C('DEFAULT_INDEX'):$index;
			$params['type'] = empty($type)?C('DEFAULT_TYPE'):$type;
			// p($params); die;
		//Document will be indexed to log_index/log_type/autogenerate_id
			$this->client->index($params);
		}
		$databack = array(
			'status'=>200,
			'msg' => 'ok',
			'data' => $rtnCount
			);
		 header('Content-Type:application/json; charset=utf-8');
                	exit(json_encode($databack));
	}

	public function search($params){
		return $this->client->search($params);
	}

	public function delete($params){
		return $this->client->delete($params);
	}

	/**
	 * 和match_phrase查询几乎一样.但是它允许查询文本的最后一个单词只做前缀匹配.
	 * @param  $index ,$type,
	 * @param  $slop: 词条和词条之间允许的未知词条数
	 * @param  $max_expansions: 定义了有多少前缀将被重写成最后的词条.
	 * @param  $highlight : true || false
	 * @param  $operator : and || or(default)
	 * @return
	 */
	public function match_phrase_prefix_search( $params ){
		// p($params);die;
		$index = $this->getParams($params , 'index');
		$type = $this->getParams($params , 'type');
		// $fields = $this->getParams($params, 'fields');
		$search_value = $this->getParams($params , 'search_value');
		$search_key = $this->getParams($params , 'search_key');
		// $highlight = $this->getParams($params , 'highlight');
		$operator = $this->getParams($params, 'operator');
		$slop = $this->getParams($params , 'slop');
		$max_expansions = $this->getParams($params , 'max_expansions');
		$databack = array(
			'index' => empty($index)?C('DEFAULT_INDEX'):$index,
			'type' => empty($type)?C('DEFAULT_TYPE'):$type,
			'body' => array(
				// 'fields' => array('_id','cat_id','title'),
				'query' => array(
					'match_phrase_prefix' => array(
						$search_key => array(
							'query' => $search_value,
							'operator' => empty($operator)?'or':$operator,
							'slop' => empty($slop)?C('SLOP'):$slop,
							'max_expansions' => empty($max_expansions)?C('MAX_EXPANSIONS'):$max_expansions
							)
						)
					)
				/*,'highlight' => array(
					'fields' => array( 'content' => (object)array() )
					)*/
				)
			);
		$databack = $this->query_common_search( $databack , $params );
		return $databack;
	}

	public function query_string_search( $params ){
		$index = $this->getParams($params , 'index');
		$type = $this->getParams($params , 'type');
		$search_key = $this->getParams( $params, 'search_key' );
		$search_value = $this->getParams($params ,'search_value');
		$fields = $this->getParams($params, 'fields');
		$highlight = $this->getParams($params , 'highlight');
		$search_fields = $this->getParams($params ,'search_fields');
		$slop = $this->getParams($params , 'slop');
		$operator = $this->getParams($params , 'operator');
		$databack = array(
			'index' => empty($index)?C('DEFAULT_INDEX'):$index,
			'type' => empty($type)?C('DEFAULT_TYPE'):$type,
			'body' => array(
				'query' => array(
					'query_string' => array(
						'query' => "$search_value" ,
						'phrase_slop' => empty($slop)?C('SLOP'):$slop,
						'default_operator' => empty($operator)?'or':$operator,
						// 'minimum_should_match' => 6,
						// 'lenient' => true
						)
					)
				)
			);
		if( $search_fields ){
			$databack['body']['query']['query_string']['fields'] = $search_fields;
		}
		$databack = $this->query_common_search($databack , $params);
		return $databack;
	}

	private function query_common_search( $databack , $params ){
		$search_key = $this->getParams( $params, 'search_key' );
		$highlight = $this->getParams($params , 'highlight');
		$fields = $this->getParams($params, 'fields');
		$highlight_fields = $this->getParams( $params , 'highlight_fields' );
		if( $fields ){
			$databack['body']['fields'] = $fields;
		}
		if( $highlight ){
			$databack['body']['highlight'] = array(
					'term_vector' => "with_positions_offsets",
					'fields' => array(
						$search_key =>array('fragment_size' => 20)
						)
					);
			if( $highlight_fields ){
				foreach ($highlight_fields as $k => $v) {
					$fields_temp[$k] = empty($v)?(object)$v:$v;
				}
				$databack['body']['highlight']['fields'] = $fields_temp;
			}
		}
		return $databack;
	}


	private function getParams( $params , $key ){
		if( !isset($params[$key]) ){
			return '';
		}
		return $params[$key];
	}
	/**
	 * @param array $params
	 */
	private function setConfig( $params ){
		if( empty($params) ){
			return null;
		}
		foreach ($params as $key => $value) {
			$config[$key] = $this->getParams($params , $key);
		}
		return $config;
	}
}
?>