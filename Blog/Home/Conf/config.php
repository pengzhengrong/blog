<?php
return array(
	'CSS_VERSION' => '1.0',
	'DB_NAME' => 'blog',
	'DB_PREFIX' => 'think_',
	'RBAC_SUPERADMIN' => 'admin',
	'ADMIN_AUTH_KEY' => 'superadmin',
	'USER_AUTH_ON' => true ,
	'USER_AUTH_TYPE' => 1,
	'USER_AUTH_KEY' => 'uid',
	'NOT_AUTH_MODULE' => 'Login,Index',
	'NOT_AUTH_ACTION' => '',
	'RBAC_ROLE_TABLE' => 'think_role',
	'RBAC_USER_TABLE' => 'think_role_user',
	'RBAC_ACCESS_TABLE' => 'think_access',
	'RBAC_NODE_TABLE' => 'think_node',
	// 'SHOW_PAGE_TRACE' => true,
	'PAGE_SIZE' => 10,
	'NAVIGATION' => 'zn_name', // nav show en or zn
	// 'DEFAULT_CACHE_TIME' => 3600*24,

	'ELASTIC_ON' => false,
);

