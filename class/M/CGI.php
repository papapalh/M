<?php

namespace M;

class CGI
{
	public static function setup()
	{
		// URI开始
		URI::setup();
    static::$route = trim($_SERVER['REQUEST_URI'] ?: $_SERVER['ORIG_PATH_INFO'], '/');
    // Session开始
    Session::setup();
	}

	public static function main($argv)
	{
		$response = static::request(static::$route, ['1']);
	}

	public static function request($route, array $env = array())
	{
		// 切分路由为字符串并进行字符自动解码
		$args = array_map('rawurldecode', explode('/', $route));
		// 定义路径
    $path = '';
    // 处理路由传入数据，过滤字符
    $candidates = \M\Util::pathAndArgs($args);
    if(empty($candidates)) {
    	$candidates['class'] = 'Index';
    }
    // 定义类
    $class = null;
    // 循环路由到指定类
    $class = '';
    $class_namespace = '\M\Controller\CGI\\';
    // 类路径
    $class = $class_namespace . $candidates['class'];
    if (class_exists($class)) {
      var_dump('11');
    }



    return $candidates;


	}

	protected static $route;
  public static function route($route = null)
  {
    if (is_null($route)) {
        return static::$route;
    }
    static::$route = $route;
  }
}