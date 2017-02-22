<?php
return array(
	//'配置项'=>'配置值'
	'URL_ROUTER_ON' => true,
	'URL_ROUTE_RULES' => array(
		'/^([a-z]+)_(\d+)$/' => 'Home/Index/:1?id=:2',
		// '/^blog_(\d+)$/' => 'Home/Index/blog?id=:1',
		// '/^category_(\d+)$/' => 'Home/Index/page_category?id=:1',
		'/^(\w+)$/' => 'Home/Index/:1',
		),
	'CACHE_TIME' => 60,
	// 'ERROR_PAGE' => 'http://'.HTTP_HOST.'/page_error.html',
	// 'SHOW_PAGE_TRACE' => true,
);