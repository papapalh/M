<?php

namespace M\Database;

class MySQL extends \PDO { 

	private $_table_status;

    function __construct($dsn, $username = null, $password = null, $options = null) {
        // PDO方式连接数据库
        parent::__construct($dsn, $username, $password);


        //enable ANSI mode
        $this->query('SET NAMES \'utf8\'');
        $this->query('SET sql_mode=\'ANSI\'');
    }

    // orm 建表核心方法
    public function adjustTable($table, $schema) {

    	// 这里应该加衣蛾判断数据表是否存在的方法
 		// 检查数据中是否建立了该表,如没有,则建立
        $this->createTable($table, $schema);
        

        // $fields = (array) $schema;

        // // $curr_schema = $this->tableSchema($table);
        // $field_sql = [];

        // foreach ($fields as $key => $field) {
        //     $field_sql[$key] = $field;
        // }

        // print_r($field_sql);

    }

    // 建立对应table表
    public function createTable($table, $schema) {
    	$SQL = sprintf('CREATE TABLE IF NOT EXISTS %s (%s %s) ENGINE = %s DEFAULT CHARSET=utf8',
    				$this->quoteIdent($table),
    				$this->quoteField($schema),
    				$this->quoteMust(),
    				$this->quote('Innodb')
    			);
    	print_r($SQL);
    	$this->query($SQL);
    }

    // 处理表名
    public function quoteIdent($name){
        return '`'.addslashes($name).'`';
    }

    // 处理添加字段
    public function quoteField($schema){

    	foreach ($schema as $key => $fileds) {

    		if ($key == 'db_index'){
    			continue;
    		}
 
    		$filed = explode(':', $fileds);

    		// 拼接SQL字符串,用于指定生成字段和类型
    		switch ($filed[0]) {
    			case 'int':
    				$sql .= '`'.$key.'` '.strtoupper($filed[0]);

    				if ($filed[1] == 'null' || $filed[1] == 'not null') {
    					$sql .= ' '.strtoupper($filed[1]);
    				} else {
    					$sql .= ' NULL';
    				}

    				if ($filed[2] == 'default') {
    					$sql .= ' '.strtoupper($filed[2]) . ' ' . $filed[3].',';
    				}
    				break;

    			case 'string':
    				$sql .= sprintf(' `%s` VARCHAR(%s) NOT NULL,', $key, $filed[1]);
    				break;

    			case 'object':
    				$sql .= sprintf(' `%s_id` BIGINT NOT NULL,', $key);
    				break;
    			default:
    				# code...
    				break;
    		}
	
    	}

    	return $sql;
    }

    // 建立必须字段
    public function quoteMust() {
    	// 建表必须属性,虚属性
    	$sql .= '`_extra` TEXT NOT NULL,';
    	// 建表必须属性,自增ID
    	$sql .= '`id` BIGINT UNSIGNED AUTO_INCREMENT, PRIMARY KEY ( `id` )';
    	return $sql;
    }
}