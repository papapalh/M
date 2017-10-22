<?php

	// 添加包含服务器信息的全局变量
	$_SERVER += $_ENV;

	// 定义项目工作目录
	defined('SYS_PATH') or define('SYS_PATH',dirname( __DIR__ ));

	// 定义项目Class目录
	defined('SYS_CLASS_PATH') or define('SYS_CLASS_PATH',SYS_PATH . '/class');

	// 定义项目View目录
	defined('SYS_VIEW_PATH') or define('SYS_CLASS_PATH',SYS_PATH . '/view');

	// 定义项目raw目录
	defined('SYS_RAW_PATH') or define('SYS_CLASS_PATH',SYS_PATH . '/raw');

  // 定义项目data目录
	defined('SYS_DATA_PATH') or define('SYS_CLASS_PATH',SYS_PATH . '/data');

	// 定义项目cache目录
	defined('SYS_CACHE_PATH') or define('SYS_CLASS_PATH',SYS_PATH . '/data');


	// 定义框架M工作目录
	defined('M_PATH') or define('M_PATH',SYS_PATH . '/M');

	// 定义框架M/Class目录
	defined('M_CLASS_PATH') or define('M_CLASS_PATH',M_PATH . '/class');


	// 引入入口文件
	require M_CLASS_PATH . '/M/Core.php';

	\M\Core::start();