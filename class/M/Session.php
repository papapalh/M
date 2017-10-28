<?php

namespace M;

class Session
{
	public static function setup() {
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
		
	}
}