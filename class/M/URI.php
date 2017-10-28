<?php
namespace M {

	class URI 
	{

		protected static $_base;
		protected static $_rurl;

		public static function setup()
		{
			// 获取访问ip
			$host = $_SERVER['HTTP_HOST'];
			// 查看是哪种访问方式-http/https
			$scheme =$_SERVER['HTTP_X_FORWARDED_PROTO'] ?: ($_SERVER['HTTPS'] ? 'https' : 'http');

			$dir = dirname($_SERVER['SCRIPT_NAME']);

			if (substr($dir, -1) != '/') {
          $dir .= '/';
      }
      // 组合当前url访问路径，例如"http://192.168.17.17/"
      self::$_base = $scheme.'://'.$host.$dir;

      // self::$_rurl = \Gini\Core::moduleInfo(APP_ID)->rurl ?: ['*' => 'assets'];

      // var_dump(self::$_rurl);
		}	
	}

}