<?php
namespace M
{
    class URI 
    {
        protected static $_base;

        // 相对URL
        public static function url($url = null) {
            $url = self::$_base . $url;
            return $url;
        }

        // 组合当前url访问路径，例如"http://192.168.17.17/"
        public static function setup()
        {
            // 访问ip/端口
            $host = $_SERVER['HTTP_HOST'];

            // 访问方式http/https
            $scheme = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?: ($_SERVER['HTTPS'] ? 'https' : 'http');

            $dir = dirname($_SERVER['SCRIPT_NAME']);

            if (substr($dir, -1) != '/') {
                $dir .= '/';
            }

            self::$_base = $scheme.'://'.$host.$dir;
        }    
    }
}

namespace {
    if (function_exists('URL')) {
        die('URL() Function existence！');
    }
    else {
        function URL ($url = null) {
            return \M\URI::url($url);
        }
    }
}