<?php

namespace M {

    class Core {

        // 全局静态内存
        public static $GLOBALS;

        // 框架开始
        public static function start() {
            // 定义报告错误
            error_reporting(E_ALL & ~E_NOTICE);

            // 自动引入
            spl_autoload_register('\M\Core::autoload');

            // mb扩展设置字符编码
            mb_internal_encoding('utf-8');
            mb_language('uni');

            // 加载Composer autoLoad
            $compose_path = M_PATH.'/vendor/autoload.php';
            if (file_exists($compose_path)) require_once $compose_path;

            // // 加载Db
            $db_path =  M_CLASS_PATH . '/M/Those.php';
            if (file_exists($db_path)) require_once $db_path;

            // // 加载Redis
            $redis_path =  M_CLASS_PATH . '/M/Redis.php';
            if (file_exists($redis_path)) require_once $redis_path;

            \M\Config::setup();

            // \M\Event::setup();

            // 初始加载
            !method_exists('\M\Application', 'setup') or \M\Application::setup();

            // 路由分发
            !method_exists('\M\Application', 'main') or \M\Application::main();
        }

        /**
         * 自动引入调用类文件
         * @param string $class Autoload class file 
         **/
        public static function autoload($class) {

            // 获取类名
            $path = str_replace('\\', '/', $class);

            $auto_file = M_CLASS_PATH .'/'. $path .'.php';

            $class_file = SYS_CLASS_PATH .'/'. $path .'.php';

            if (file_exists($auto_file)) require_once($auto_file);

            if (file_exists($class_file)) require_once($class_file);
        }


        // 返回目录下所有文件的集合
        public static function pharFilePaths($file) {
          $dirs = scandir($file);
          // 移除--.和..文件
          return array_splice($dirs, 2);
        }
    }
}

namespace {
    if (function_exists('V')) {
      die('V 是系统视图函数，请检查');
    } 
    else{
        function V($path, $vars = null) {
            return \M\IoC::construct('\M\View', $path, $vars);
        }
    }

    if (function_exists('_G')) {
        die('_G 是全局缓存函数，请检查');
    }
    else {
        function _G($key, $value = null) {
            if (is_null($value)) {
                return isset(\M\Core::$GLOBALS[$key]) ? \M\Core::$GLOBALS[$key] : null;
            } else {
                \M\Core::$GLOBALS[$key] = $value;
            }
        }
    }

    if (function_exists('_V')) {
        die('_V 是校验函数，请检查');
    }
    else {
        function _V($module, $form) {
            $validator = \M\Ioc::construct('\M\Module\Validator\\'.$module, $form);

            return $validator;
        }
    }

    if (function_exists('H')) {
        die('H() 是系统过滤字符函数，请检查');
    } else {
        ini_set('mbstring.substitute_character', 'none');
        function H() {
            $args = func_get_args();
            if (count($args) > 1) {
                $str = call_user_func_array('sprintf', $args);
            } else {
                $str = $args[0];
            }

            // iconv('UTF-8', 'UTF-8//TRANSLIT//IGNORE', $str)
            return htmlentities(mb_convert_encoding($str, 'UTF-8', 'UTF-8'), ENT_QUOTES, 'UTF-8');
        }
    }
}
