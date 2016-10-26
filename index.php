<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
// define('APP_DEBUG',true);

// phpinfo();die;


/**
× 自定义常量
*/
define('HTTP_HOST',$_SERVER['HTTP_HOST']);



// define('APP_NAME','Blog');

// 定义应用目录
// echo '<pre>';
// print_r($_SERVER);


// $_SERVER['HTTP_USER_AGENT'] = 'android';
if( preg_match('/(android)|(iphone)/i', strtolower($_SERVER['HTTP_USER_AGENT']) ) ) {
	define('APP_PATH','./APP/');
	define('APP_DEBUG',false);
	define('__PUBLIC__','/Public/App/Home');
} else {
	define('APP_PATH','./Blog/');
	define('APP_DEBUG',true);
}

include('./Blog/Library/FirePHPCore/fb.php');
// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单