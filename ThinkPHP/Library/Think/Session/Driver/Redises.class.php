<?php

namespace Think\Session\Driver;

Class Redises {

	private  $redis;
	

	/**
	connect redis
	*/
	public function open($savePath, $sessionName) {
		$this->redis = new \Redis();
		$rest = $this->redis->connect('127.0.0.1',6379);
		$rest || $this->error('Redis connect failed!');
		return true;
	}

	public function close(){
		$this->redis->close();
	}

	public function read($key){
		$key = C('session_prefix')?C('session_prefix').$key:$key;
		return $this->redis->get( $key );
	}

	public function write($key , $value){
		$key = C('session_prefix')?C('session_prefix').$key:$key;
		$expire = C('session_expire');
		return $this->redis->set( $key , $value , $expire );

	}

	public function destroy($key){
		return $this->redis->delete($key);
	}

	public function gc($expire){
		return true;
	}

}