<?php

namespace M;

class CLI
{
    public static function setup()
    {
        URI::setup();
        Session::setup();
    }

    public static function main(array $argv) {
        // 获取方法
        $cmd = count($argv) > 0 ? strtolower($argv[1]) : '';

        // 挂载@文件,敏感文件
        // if ($cmd[0] == '@') {}

        // 分发初始方法
        switch ($cmd) {
            case '-v':
                $method = 'commandVersion';
            break;
            case '--':
                $method = 'commandAvailable';
            break;
          case '-h':
                $method = 'commandHelp';
            break;
          case 'root':
                $method = 'commandRoot';
            break;
        }

        // 调用自带方法,否则调用项目CLI
        if ($method && method_exists(__CLASS__, $method)) {
            call_user_func(array(__CLASS__, $method), $argv);
        } else {
            static::dispatch($argv);
        }
    }

    public static function dispatch(array $argv) {
        // 路由
        $candidates = Util::pathAndArgs($argv, true);

        // 路由class路径
        $path = '\M\Controller\CLI\\'.$candidates['class'];

        // CLI无方法-则跳转到M-App方法
        if (!class_exists($path)) {
            $candidates['class']  = '\M\Controller\CLI\App';
            $candidates['params'] = array_splice($argv, 3);
        }

        \M\Config::set('runtime.controller_path', $path);
        \M\Config::set('runtime.controller_class', $candidates['class']);

        // 反转实例化CLI
        $controller = \M\IoC::construct($path);

        // 赋予控制器-参数与方法-和数据
        $controller->action = $candidates['action'];
        $controller->params = $candidates['params'];
        $controller->execute();
    }
}