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

      // 设置用户自定义的错误处理程序
      set_error_handler('\M\Core::error', E_ALL & ~E_NOTICE);

      // Assert函数配置
      // Assert函数的开关
      assert_options(ASSERT_ACTIVE, 1);
      // 当表达式为false时，是否要输出警告性的错误提示
      assert_options(ASSERT_WARNING, 0);
      //是否关闭错误提示
      assert_options(ASSERT_QUIET_EVAL, 1);
      // 是否启用回调
      assert_options(ASSERT_CALLBACK, '\M\Core::assertion');

      // mb扩展设置字符编码
      mb_internal_encoding('utf-8');
      mb_language('uni');

      $info = self::import(SYS_PATH);

      // 加载Composer autoLoad
      $compose_path = M_PATH.'/vendor/autoload.php';
      if (file_exists($compose_path)) {
      	require_once $compose_path;
      }

      // 加载Db
      $db_path =  M_CLASS_PATH . '/M/Those.php';
      if (file_exists($db_path)) {
        require_once $db_path;
      }

      \M\Config::setup();
      // var_dump(getcwd());
      // \M\Event::setup();

      // CGI-setup-初始定义启动
      !method_exists('\M\Application', 'setup') or \M\Application::setup();

      // CGI-main启动-路由分发
      global $argv;
      !method_exists('\M\Application', 'main') or \M\Application::main($argv);

   //    define('APP_HASH', sha1());

   //    \M\Config::set("logger");
			// $aaa = \M\Config::get("logger");
   //    var_dump($aaa);
			// // $obj = new \M\Controller\CGI\Login();
			// echo "<br>";
			// // assert("\M\Core::shutdown()");
			// // var_dump( is_int( $obj ) );
			// echo "<br>";

			// $obj->aaa();
		}

		/**
		 * 自动引入调用类文件
		 * @param string $class Autoload class file 
		 **/
		public static function autoload($class)
		{
			// 获取类名
      $path = str_replace('\\', '/', $class);

			$M_file = M_CLASS_PATH .'/'. $path .'.php';

      $app_file = SYS_CLASS_PATH . '/'. $path .'.php';
      if (is_file($M_file)) {
        require_once($M_file);
      }
      elseif (is_file($app_file)) {
        require_once($app_file);
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
    	!method_exists('\M\Application', 'shutdown') or \M\Application::shutdown();
    }

    /**
     * 自定义错误处理程序
     **/
    public static function exception($e)
    {
    	$message = $e->getMessage();
      $file = $e->getFile();
      $line = $e->getLine();
      error_log(sprintf('[E] %s (%s:%d)', $message, $file, $line));
    }

    /**
     * 设置用户自定义的错误处理程序
     **/
    public static function error($errno, $errstr, $errfile, $errline, $errcontext)
    {
    	throw new \ErrorException($errstr, $errno, 1, $errfile, $errline);
    }

    /**
     *	assert函数回调
     **/
    public static function assertion($file, $line, $code, $desc = null)
    {
    	error_log('113123');
    	var_dump('2323123');
			throw new Exception("Error Processing Request", 1);
    }

    public static function import()
    {
    }

    public static function pharFilePaths($base, $file)
    {

    }

	}
}