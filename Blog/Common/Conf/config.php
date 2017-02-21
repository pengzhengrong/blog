<?php
return array(
	//'配置项'=>'配置值'
	'URL_MODE' => 2,
	// 'TMPL_FILE_DEPR' => '_',
	'DB_TYPE' => 'mysqli',
	'DB_HOST' => $_SERVER['DB_HOST'],
	'DB_NAME' => 'test',
	'DB_USER' => $_SERVER['DB_USER'],
	'DB_PWD' => $_SERVER['DB_PWD'],
	'DB_PREFIX' => 'think_',
	'COMMENT_ON' => true,
	'APP_SUB_DOMAIN_DEPLOY' => 1,
	'APP_SUB_DOMAIN_RULES' => array(
		'APP_DOMAIN_SUFFIX' => 'ticp.io',
		),
	'URL_ROUTER_ON' => true,
	'URL_ROUTE_RULES' => array(
		/*'/^blog_(\d+)$/' => 'Admin/Index/index?id=:1',
		'/^content_(\d+)$/' => 'Admin/Index/content?id=:1',
		'/^admin$/' => 'Home/Index/index',
		'/^blog$/' => 'Admin/Index/index',
		'/^login$/' => 'Home/Login/index',
		'/^logout$/' => 'Home/Login/logout',
		'/^blogin$/' => 'Admin/Login/index',
		'/^blogout$/' => 'Admin/Login/logout',
		'/^search$/' => 'Admin/Search/index',
		'/^page_error$/'=> 'Home/Index/page_error',*/
		'/^login$/' => 'Home/Login/index',
		'/^logout$/' => 'Home/Login/logout',
		'/^admin$/' => 'Home/Index/index',
		'/^404$/' => 'Home/Index/404',
		'/^admin$/' => 'Home/Index/index',
		// '/^([0-9a-zA-Z_-]+)$/' => 'Home/Index/:1',
		),
	// 'DEFAULT_MODULE' => 'Admin',
	'LOAD_EXT_CONFIG' => 'elastic',
	// 'SHOW_PAGE_TRACE' => true,
	'DEFAULT_CACHE_TIME' => 3600*24,
		'SESSION_OPTIONS' => array(
		'name' => 'SESSION_NAME',
		'expire' => 3600*24,
		'type' => 'db',
		),
	'DB_SQL_BUILD_CACHE' => true,
	'DB_SQL_BUILD_QUEUE' => 'xcache',
	'DB_SQL_BUILD_LENGTH' => 20, // SQL缓存的队列长度
	'ERROR_PAGE' => 'http://'.HTTP_HOST.'/404.html',
	// 'HTML_CACHE_ON'     =>    true, // 开启静态缓存
	// 'HTML_CACHE_TIME'   =>    60,   // 全局静态缓存有效期（秒）
	// 'HTML_FILE_SUFFIX'  =>    '.shtml', // 设置静态缓存文件后缀

);