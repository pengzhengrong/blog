<?php 

namespace Home\Model;

class BaseModelDB extends \Think\Model {


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