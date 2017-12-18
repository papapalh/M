<?php 

namespace M;

// 基础搭建-包括目录定义-Config定义-Event定义等
require_once 'base.php';

class Application
{
    // CLI启动
    public static function setup()
    {
        CLI::setup();
    }

    // CLI路由分发
    public static function main($args)
    {
        CLI::main($args);
    }
}