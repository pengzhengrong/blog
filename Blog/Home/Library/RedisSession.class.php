<?php

namespace Home\Library;
Class RedisSession {

	public  $redis;

	public function execute(){
		echo 111;
		// &$this = new RedisSession();
		session_set_save_handler(
			array(&$this,"open"), 
			array(&$this,"close"), 
			array(&$this,"read"), 
			array(&$this,"write"), 
			array(&$this,"destroy"), 
			array(&$this,"gc")); 
		// session_set_save_handler('open', 'close', 'read', 'write', 'destroy', 'gc');
		// session_start();
	}
	

	/**
	connect redis
	*/
	public function open($savePath, $sessionName) {
		$redis = new Redis();
		$redis->connect('127.0.0.1',6379);
		$this->redis = $redis;
		echo 'connect success';
		my_log('test' , 'success');
		return true;
	}

	public function close(){
		echo 'close';
	}

	public function read($key){

	}

	public function write($key , $value){

	}

	public function destroy($key){

	}

	public function gc($expire){

	}

}