<?php

namespace M {

    class Session
    {
        public static function setup()
        {
            // 如果有配置session文件，则读取
            $session_conf = (array) \M\Config::get('system.session');
            $cookie_params = (array) $session_conf['cookie'];

            // 读取seesion-name，如没有，则定义
            $session_name = $session_conf['name'] ?:'M-session';

            // 读取cookie-domain，如没有，则定义ip-Hash
            $host_hash = sha1($cookie_params['domain'] ?: $_SERVER['HTTP_HOST']);

            ini_set('session.name', $session_name.'_'.$host_hash);

            // 开启session
            self::open();
        }

        public static function open() {
            // 如果是CLI或者Session禁用/存在，则返回
            // session_status() == 0  PHP_SESSION_DISABLED  会话是被禁用的  
            // session_status() == 1  PHP_SESSION_NONE      会话是启用的，但不存在当前会话
            // session_status() == 2  PHP_SESSION_ACTIVE    会话是启用的，而且存在当前会话  

            if (PHP_SAPI == 'cli'
                || session_status() === PHP_SESSION_DISABLED
                || session_status() === PHP_SESSION_ACTIVE) {
                return;
            }

            // 定义错误函数和通知
            set_error_handler(function () {}, E_ALL ^ E_NOTICE);
            // Seesion开始
            session_start();
            // 恢复之前的错误处理程序
            restore_error_handler();
            // 定位当前时间
            $now = time();
            //定义销毁Session
        }

        public static function shutdown()
        {
            self::close();
        }

        public static function close()
        {
            if (PHP_SAPI == 'cli' || session_status() !== PHP_SESSION_ACTIVE) {
                return;
            }
            // 恢复Session文件锁
            session_commit();
        }
    }
}

namespace {

    // 设置session
    if (function_exists('S')) {
        die('S 系统占用');
    }
    else {
        function S($key, $value = null, $timeout = 1)
        {
            $redis = new \M\Redis();

            if (!isset($value)) {
                // if(!isset($_COOKIE[$key])) return false;


                //清除缓冲区
                ob_clean();

                $keys = $key.session_id();
                

                return $redis->get($keys);
            }
            else {
                $keys = $key.session_id();

                //cookie失效时间,默认1小时
                setcookie($key, $keys, time()+3600);

                if($timeout == 1){
                    $timeout = time()+3600;
                }

                return $redis->set($keys, $value, $timeout);
            }
        }
    }

    if (function_exists('G')) {
        die('G 是系统函数，请检查');
    } 
    else{
        function G($name = '') {
            if ($name == 'ME') {
                return S('username');
            }
        }
    }
}