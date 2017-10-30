<?php
namespace M;

class Util 
{
	// 路由路径处理
	public static function pathAndArgs(array $argv)
	{

		$path = '';
    $candidates = [];
    if (count($argv) > 1) {
    	// 控制器
      $class 	= array_shift($argv);
      // 方法
      $action = array_shift($argv);
      // 参数
      $params = $argv;

      if (!preg_match('|^[a-z][a-z0-9-_]+$|i', $class)) {
        die('控制器错误');
      }
      if (!preg_match('|^[a-z][a-z0-9-_]+$|i', $action)) {
        die('方法错误');
      }
      $candidates['class'] = $class;
      $candidates['action'] = $action;
      $candidates['params'] = $params;
    }
    return $candidates;
	}
}