<?php

namespace M {

	// public static $GLOBALS;

	class Core
	{
		// 框架开始
		public static function start()
		{
			// 定义报告错误
      error_reporting(E_ALL & ~E_NOTICE);

      // 自动引入调用类文件
      spl_autoload_register('\M\Core::autoload');

			// 脚本执行完成/意外死掉/即将关闭时调用函数
      register_shutdown_function('\M\Core::shutdown');

      // 自定义错误处理程序
      set_exception_handler('\M\Core::exception');
			
			$obj = new \M\Controller\CGI\Login();
			$obj->aaa();
		}

		/**
		 * 自动引入调用类文件
		 * @param string $class Autoload class file 
		 **/
		public static function autoload($class)
		{
			// 获取类名
      $path = str_replace('\\', '/', $class);

			$file = SYS_CLASS_PATH .'/'. $path .'.php';  

			if (is_file($file)) {  
				require_once($file);  
			}
			else {
				die("Missing Autoloading Class!\n");
			}
		}

		/**
		 * php执行完成/意外死掉/即将关闭时调用函数
		 **/
		public static function shutdown()
    {    
    	echo "\n";
      echo 'php finish!';
    }

    /**
     * 自定义错误处理程序
     **/
    public static function exception($e)
    {
    	
    }



	}
}