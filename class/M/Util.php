<?php
namespace M;

class Util 
{
    // 路由路径处理
    public static function pathAndArgs(array $argv)
    {
        // 因为没有加环境变量--所以如果为CLI则过滤第一个参数
        if (PHP_SAPI == 'cli') {
            array_shift($argv);
        }

        $candidates = [];

        // 控制器
        $class  = array_shift($argv);
        // 方法
        $action = array_shift($argv);
        // 参数
        $params = $argv;

        if (!empty($class)) {
            if (!preg_match('|^[a-z][a-z0-9-_]+$|i', $class)) {
                die('控制器错误');
            }
        }
        if (!empty($action)) {
            if (!preg_match('|^[a-z][a-z0-9-_]+$|i', $action)) {
                die('方法错误');
            }
        }

        // 处理控制器
        if ($class) {
            switch ($class) {
                case 'orm':
                    $candidates['class'] = strtoupper($class);
                break;
                default:
                    $candidates['class'] = ucfirst($class);
                break;
            }
        }
        else {
            $candidates['class'] = 'Index';
        }

        // 处理方法
        if ($action) {
            switch ($action) {
                default:
                  $candidates['action'] = ucfirst($action);
                break;
            }
        }
        else {
            $candidates['action'] = '__index';
        }
        
        $candidates['params'] = $params;

        return $candidates;
    }
}