<?php

namespace Home\Controller;
use Think\Controller;

Class CommonController extends Controller {

	Public function _initialize() {
		/*S(array(
			'type'=>'memcache',
			'host'=>'127.0.0.1',
			'port'=>'11211',
			'prefix'=>'App_',
			'expire'=>60
			)
		);*/
		S(array(
			'type'=>'redis',
			'host'=>'127.0.0.1',
			'port'=>'6379',
			'prefix'=>'App_',
			'expire'=>60
			)
		);
		define('CACHE_TIME',empty(C('CACHE_TIME')?60:C('CACHE_TIME')));
		// P(CACHE_TIME);
	}

}