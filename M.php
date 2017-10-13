<?php
	
	// 记录开始运行时间
	$GLOBALS['SCRIPT_START_AT'] = microtime(TRUE);

	// 定义绝对路径Core目录
	define('CORE_PATH', dirname(__FILE__).'/core/');

	require CORE_PATH.'def.php';						// 系统常量定义

	require CORE_PATH.'exception.php';			// 定义php报错级别		

	require CORE_PATH.'cache.php';					// 缓存操作

	require CORE_PATH.'core.php';						// core目录

	class CGI
	{	
		// 定义脚本结束函数
		static function shutdown() {
			Event::trigger('system.output');		// 系统显示事件

			Event::trigger('system.shutdown');	// 系统关闭事件

			Core::shutdown();										// 内核关闭
		}

		// 定义异常函数处理
		static function exception($e) {
			$message = $e->getMessage();

			// 获取错误文件相对路径
			// $file = \M\lib\File::relative_path( $e->getFile() );
			// echo $file;

			// if( $message ) {

			// }
			echo '错误信息' . $message;
			echo '<br>';
			echo '错误行数' . $e->getLine();
			echo '<br>';
			echo '错误文件' . $e->getFile();

		}
	}

	// 脚本执行完成/意外死掉/即将关闭时调用函数
	register_shutdown_function('CGI::shutdown');

	// 设置自定义异常函数
	set_exception_handler('CGI::exception');

	// 函数设置用户自定义的错误处理程序
	set_error_handler();

	throw new Exception('aa');

?>