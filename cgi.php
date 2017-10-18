<?php 

	namespace M;

	require_once 'base.php';
	
	// 定义M框架版本
	define('M_VERSION', 'v1.0');

	// 记录框架开始时间
	define('M_START_TIME', microtime(true));

	// 记录框架开始内存
	define('M_START_MEM', memory_get_usage());

	// 记录框架分隔符('由于WIN和Linux下的不同,不过由于开发环境就是linux，其实也不用写')
	define('DS', DIRECTORY_SEPARATOR);

	// require 'base.php';