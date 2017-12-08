<?php
namespace M {

    class URI 
    {
        public static function url($url = null) {

            $url = self::$_base . $url;

            return $url;
        }


        protected static $_base;
        protected static $_rurl;

        public static function setup()
        {
            // 获取访问ip
            $host = $_SERVER['HTTP_HOST'];
            // 查看是哪种访问方式-http/https
            $scheme =$_SERVER['HTTP_X_FORWARDED_PROTO'] ?: ($_SERVER['HTTPS'] ? 'https' : 'http');

            $dir = dirname($_SERVER['SCRIPT_NAME']);

            if (substr($dir, -1) != '/') {
                $dir .= '/';
              }
              // 组合当前url访问路径，例如"http://192.168.17.17/"
              self::$_base = $scheme.'://'.$host.$dir;
        }    
    }
}

namespace {
    if (function_exists('URL'))
    {
        die('URL() 函数已经被定义');
    }
    else {
        function URL($url = null)
        {
            return \M\URI::url($url);
        }
    }
}