<?php 

namespace Home\Model;

abstract class BaseModelDB extends \Think\Model {

	// 链操作方法列表
	protected $methods          =   array('strict','order','alias','having','group','lock','distinct','auto','filter','validate','result','token','index','force');

	/**
	 * 数据库DAO层基类
	 * @param string $tableName   数据表名，eg：db.tableName || tableName
	 * @param string $tablePrefix 数据表前缀
	 * @param string $connection  多DB操作的连接信息
	 *
	 * 在公共配置文件中配置如下：
	 * 'DB_CONFIG1' = array(
	    'db_type'  => 'mysql',
	    'db_user'  => 'root',
	    'db_pwd'   => '1234',
	    'db_host'  => 'localhost',
	    'db_port'  => '3306',
	    'db_name'  => 'thinkphp'
	),
	$connection = 'DB_CONFIG1',则启用DB_CONFIG1配置项连接数据库
	*
	* 当然也可以用DSN的方式连接数据库
	*
	* protected $connection = 'mysql://root:1234@localhost:3306/blog';
	* 如此配置之后，则连接的数据库对象是blog.tableName。
	* 需要注意的是，两者使用其一即可。
	 */
	public function __construct($tableName, $tablePrefix='think_', $connection='') {
		parent::__construct($tableName, $tablePrefix, $connection);
	}

	public function getData(array $field=array(), array $where=array(), $order='') {
		$field = $this->getMyField($field);
		$data = $this->field($field)->where($where)->order($order)->select();
		// fb($data, __FUNCTION__.':sql result');
		return $data;
	}

	public function getDataByPage(array $field=[], array $where=[], $order='') {
		$field = $this->getMyField($field);
		$totalRows = $this->where($where)->count();
		$page = new \Think\Page( $totalRows , C('PAGE_SIZE') );
		$limit = $page->firstRow.','.$page->listRows;
		$rest =$this->field($field)->where($where)->order($order)->limit($limit)->select();
		$page = $page->showPage();
		$data = [ 'page' => $page, 'data' => $rest ];
		// fb($data, __FUNCTION__.':sql result');
		return $data;
	}

	public function getRow($field=array(), $where=array()) {
		$field = $this->getMyField($field);
		$data = $this->field($field)->where($where)->find();
		// fb($data, __FUNCTION__.':sql result');
		return $data;
	}

	public function getColumn($field, $where=[]) {
		$data = $this->where($where)->getField($field);
		// fb([$data], __FUNCTION__.':sql result');
		return $data;
	}

	public function update($data) {
		$rest = $this->save($data);
		// fb($rest, 'sql update result');
		return $rest;
	}

	public function insert($data) {
		$rest = $this->data($data)->add();
		// fb($rest, 'sql insert result');
		return $rest;
	}

	/**
	 * 为了防止误删, 必须严格把控
	 * @param  [type] $where [description]
	 * @return [type]        [description]
	 */
	public function del($where) {
		foreach ($where as $k => $v) {
			if ( is_numeric($k) ) {
				// fb('查询条件的field不能为数字', 'warn');
				return false;
			}
		}
		$rest = $this->where($where)->delete();
		// fb($rest, 'sql delete result');
		return $rest;
	}

	public function __call($name, $args) {
		if(in_array(strtolower($name),$this->methods,true)) {
            		// 连贯操作的实现
			$this->options[strtolower($name)] =   $args[0];
			return $this;
		}elseif(in_array(strtolower($name),array('count','sum','min','max','avg'),true)){
           		 // 统计查询的实现
			$field =  isset($args[0])?$args[0]:'*';
			return $this->getField(strtoupper($name).'('.$field.') AS tp_'.$method);
		}elseif(strtolower(substr($name,0,5))=='getby') {
            		// 根据某个字段获取记录
			$field   =   parse_name(substr($name,5));
			$where[$field] =  $args[0];
			return $this->where($where)->find();
		}elseif(strtolower(substr($name,0,10))=='getfieldby') {
           		 // 根据某个字段获取记录的某个值
			$name   =   parse_name(substr($name,10));
			$where[$name] =$args[0];
			return $this->where($where)->getField($args[1]);
        }elseif(isset($this->_scope[$name])){// 命名范围的单独调用支持
        	return $this->scope($name,$args[0]);
        }

        if ( substr($name,-5, 5) == 'Cache' ) {
        	$_cacheTime = array_pop($args);
        	if ( !isset($_cacheTime['expire']) ) {
        		$expire =  300;
        		array_push($args, $_cacheTime);
        	} else {
        		$expire = $_cacheTime['expire'];
        	}
        	$method = substr($name, 0, -5);
        	$key = md5(__CLASS__.'|'.$method.'|'.$expire.'|'.serialize($args));
			// 清除缓存
        	if ($_GET['_flush_cache'] == 1) {
        		S($key, null);
        	}
        	if( $data = S($key) ) {
        		// fb($data, 'From Cache Key:'.$key);
        		return $data;
        	}
        	$data = call_user_func_array( array($this, $method), $args);
        	S($key, $data, $expire);
        	return $data;
        }
      }

      protected function getMyField($field) {
      	$field = empty($field) ? '*' : $field;
      	$field = is_string($field) ? $field : '`'.implode( '`,`', $field ).'`';
      	return $field;
      }
    }