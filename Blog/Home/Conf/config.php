<?php
return array(
	'DB_NAME' => 'blog',
	'DB_PREFIX' => 'think_',
	/*'RBAC_SUPERADMIN' => 'admin',
	'ADMIN_AUTH_KEY' => 'superadmin',
	'USER_AUTH_ON' => true ,
	'USER_AUTH_TYPE' => 1,
	'USER_AUTH_KEY' => 'uid',
	'NOT_AUTH_MODULE' => 'Login,Index',
	'NOT_AUTH_ACTION' => '',
	'RBAC_ROLE_TABLE' => 'think_role',
	'RBAC_USER_TABLE' => 'think_role_user',
	'RBAC_ACCESS_TABLE' => 'think_access',
	'RBAC_NODE_TABLE' => 'think_node',*/
	// 'SHOW_PAGE_TRACE' => true,
	'PAGE_SIZE' => 10,
	// 'DEFAULT_CACHE_TIME' => 3600*24,

	'ELASTIC_ON' => false,
	//超级管理员的权限id
	'ADMIN_AUTH_ID' => '8',
	'NOT_AUTH_PAGE' => array(
		'Home/Index/404',
		'Home/Index/index',
		)
);

