<?php 

namespace Home\Model;

abstract class BaseModelDB extends \Think\Model {

	private $fetch = false;

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
		$this->fetch = I('_fetch_sql') == 1?true:false;
	}

	public function getData(array $field=array(), array $where=array(), $sort='order by id') {
		$data = $this->field($field)->where($where)->fetchSql($this->fetch)->select();
		return $data;
	}

	public function getRow($field=array(), $where=array()) {
		$data = $this->field($field)->where($where)->fetchSql($this->fetch)->find();
		return $data;
	}

	public function getFields($field='', $where=array()) {
		$data = $this->where($where)->fetchSql($this->fetch)->getField($field);
		return $data;
	}

	public function __call($name, $args) {
		if ( substr($name,-5) == 'Cache' ) {
			$_CacheTime = array_pop($args);
			$_CacheTime =  $_CacheTime>0?$_CacheTime:300;
			$method = substr($name, 0, -5);
			$key = md5(__CLASS__.'|'.$method.'|'.$_CacheTime.'|'.serialize($args));
			// 清除缓存
			if (I('_flush_cache') != null) {
				S($key, null);
			}
			if( $data = S($key) ) {
				// debug($data, $key, 'INFO');
				return $data;
			}
			$data = call_user_func_array( array($this, $method), $args);
			S($key, $data, $_CacheTime);
			return $data;
		}
	}
}