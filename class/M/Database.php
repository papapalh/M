<?php
namespace M;

class Database
{
	private $_driver;
	// 魔术方法连接数据库
	public function __construct($dsn, $username = null, $password = null, $options = null ) {
		// print_r('12312sdfds');
    // list($driver_name) = explode(':', $dsn, 2);
    // $driver_class = '\M\Database\\'.$driver_name;

    $driver_class = '\M\Database\mysql';
    $dsn = 'mysql:dbname=girl';
    $username = 'root';
    $password = '83719730';

    $this->_driver = \M\IoC::construct($driver_class, $dsn, $username, $password, $options);
		
	}

	// 连接数据库
	public static function db($name = null) {
		\M\IoC::construct('\M\Database', '12');
	}
}