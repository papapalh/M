<?php
namespace M;

class Database {
    private $_driver;
    public static $DB = array();

    // 魔术方法连接数据库
    public function __construct($dsn, $username = null, $password = null, $options = null ) {

        list($driver_name) = explode(':', $dsn, 2);

        // 定义driver判断数据库类型--可扩展为其他数据库
        $driver_class = '\M\Database\\'.$driver_name;

    	$this->_driver = \M\IoC::construct($driver_class, $dsn, $username, $password, $options);

        
    }

    // 连接数据库
    public static function db($name = null) {
        $name = $name ? $name :'default';

        $opt = \M\Config::get('database.' . $name);

        // 回调自己魔术方法,连接数据库
        $db = \M\IoC::construct('\M\Database', $opt['dsn'], $opt['username'], $opt['password'], $opt['options']);

        static::$DB[$name] = $db;

        return static::$DB[$name];
    }

    // orm建表核心方法
    public function adjustTable($table, $schema) {
    	return $this->_driver->adjustTable($table, $schema);
    }
}