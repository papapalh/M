<?php 

namespace M;

// 基础搭建-包括目录定义-Config定义-Event定义等
require_once 'base.php';

class Application
{
    public static function setup()
    {
        CLI::setup();
    }

    // 路由分发
    public static function main($args)
    {
        // array_shift($argv);
        CLI::main($args);
    }

    // public static function shutdown()
    // {
    //     CLI::shutdown();
    // }

    // public static function exception($e)
    // {

    // }
}