<?php

namespace M;

class CGI
{
    public static function setup()
    {
        // 加载URL
        \M\URI::setup();

        static::$route = trim($_SERVER['REQUEST_URI'] ?: $_SERVER['ORIG_PATH_INFO'], '/');

        // 加载Session
        Session::setup();
    }

    public static function main()
    {
        // 获取路由信息调用方法
        $response = static::request(static::$route, [
                'get' => $_GET, 'post' => $_POST,
                'files' => $_FILES, 'route' => static::$route,
                'method' => $_SERVER['REQUEST_METHOD'],
            ])->execute();
    }

    // 获取控制器和方法(反转)
    public static function request($route, array $env = array()) {

        // 切分路由为字符串并进行字符自动解码
        $args = array_map('rawurldecode', explode('/', $route));
        // 定义路径
        $path = SYS_CLASS_PATH . '/';
        // 处理路由传入数据，过滤字符
        $candidates = \M\Util::pathAndArgs($args);
        // 定义类
        $class = null;
        // 循环路由到指定类
        $class = '';

        // 路由CGI 
        $class_namespace = '\M\Controller\CGI\\';
        $class = $class_namespace . $candidates['class']; 

        if (!class_exists($class)) die('没有这个定义类');

        // 控制器反转
        $controller = \M\IoC::construct($class);

        // 赋予控制器-参数与方法-和数据
        $controller->action = $candidates['action'];
        $controller->params = $candidates['params'];
        $controller->env = $env;

        return $controller;
    }

    // 获取路由
    protected static $route;
    public static function route($route = null) {
        if (is_null($route)) {
            return static::$route;
        }
        static::$route = $route;
    }

    // 脚本关闭调用函数
    public static function shutdown() {
        Session::shutdown();
    }

}