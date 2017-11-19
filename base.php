<?php

    // 定义M框架版本
    define('M_VERSION', 'v1.0');

    // 记录框架开始时间
    define('M_START_TIME', microtime(true));

    // 记录框架开始内存
    define('M_START_MEM', memory_get_usage());

    // 添加包含服务器信息的全局变量
    $_SERVER += $_ENV;

    // 定义项目工作目录
    defined('SYS_PATH') or define('SYS_PATH',dirname( __DIR__ ));

    // 定义项目Class目录
    defined('SYS_CLASS_PATH') or define('SYS_CLASS_PATH',SYS_PATH . '/class');

    // 定义项目View目录
    defined('SYS_VIEW_PATH') or define('SYS_VIEW_PATH',SYS_PATH . '/view');

    // 定义项目raw目录
    defined('SYS_RAW_PATH') or define('SYS_RAW_PATH',SYS_PATH . '/raw');

    // 定义项目data目录
    defined('SYS_DATA_PATH') or define('SYS_DATA_PATH',SYS_PATH . '/data');

    // 定义项目cache目录
    defined('SYS_CACHE_PATH') or define('SYS_CACHE_PATH',SYS_PATH . '/cache');

    // 定义项目ORM目录
    defined('SYS_ORM_PATH') or define('SYS_ORM_PATH',SYS_CLASS_PATH .'/M/ORM');

    // 定义Public目录
    defined('SYS_PUBLIC_PATH') or define('SYS_PUBLIC_PATH',SYS_CLASS_PATH .'/M/public');



    // 定义框架M工作目录
    defined('M_PATH') or define('M_PATH',SYS_PATH . '/M');

    // 定义框架M/Class目录
    defined('M_CLASS_PATH') or define('M_CLASS_PATH',M_PATH . '/class');

    // 引入入口文件
    require M_CLASS_PATH . '/M/Core.php';

    \M\Core::start();