<?php

namespace M;

class CGI
{
	public static function setup()
	{
		URI::setup();
    static::$route = trim($_SERVER['PATH_INFO'] ?: $_SERVER['ORIG_PATH_INFO'], '/');
    Session::setup();
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