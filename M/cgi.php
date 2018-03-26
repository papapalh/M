<?php 

namespace M;

// 基础搭建-包括目录定义-Config定义-Event定义等
require_once 'base.php';

class Application
{
    public static function setup()
    {
        CGI::setup();
    }

    // 路由分发
    public static function main($args)
    {
        CGI::main($args);
    }

    public static function shutdown()
    {
        CGI::shutdown();
    }

    public static function exception($e)
    {
    }
}