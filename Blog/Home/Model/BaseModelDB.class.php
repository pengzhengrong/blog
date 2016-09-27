<?php 

namespace Home\Model;

abstract class BaseModelDB extends \Think\Model {

	private $fetch = false;

	public function __construct($tableName, $tablePrefix='think_') {
		parent::__construct($tableName, $tablePrefix);
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
				return $data;
			}
			$data = call_user_func_array( array($this, $method), $args);
			S($key, $data, $_CacheTime);
			return $data;
		}
	}
}