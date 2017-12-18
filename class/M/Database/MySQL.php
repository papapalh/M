<?php

namespace M\Database;

class MySQL extends \PDO { 

    private $_table_status;

    function __construct($dsn, $username = null, $password = null, $options = null)
    {
        // PDO方式连接数据库
        parent::__construct($dsn, $username, $password);

        //enable ANSI mode
        $this->query('SET NAMES \'utf8\'');
        $this->query('SET sql_mode=\'ANSI\'');
    }

    // orm 建表核心方法
    public function adjustTable($table, $schema)
    {
        // 建立数据表
        $this->createTable($table, $schema);     

    }

    // 建立对应table表
    public function createTable($table, $schema)
    {
        $SQL = sprintf('CREATE TABLE IF NOT EXISTS %s (%s) ENGINE = %s DEFAULT CHARSET=utf8',
                    $this->quoteIdent($table),
                    $this->quoteMust(),
                    $this->quote('Innodb')
                );

        $result = $this->result($SQL);
        if (!$result) {
            die($this->quoteIdent($table)."表创建失败！\n".'失败SQL:'.$SQL);
        }
        else {
            // 成功插入表之后插入相应字段
            $this->quoteField($table, $schema);
            // 插入索引
            $this->quoteIndex($table, $schema['db_index']);
        }
    }

    // 处理表名
    public function quoteIdent($name)
    {
        return addslashes($name);
    }

    // 处理主键索引
    public function quoteField($table, $schema)
    {

        foreach ($schema as $key => $fileds) {

            if ($key == 'db_index'){
                break;
            }
 
            $filed = explode(':', $fileds);

            $type = array_shift($filed);

            switch ($type) {
                case 'int':
                    $sql = sprintf('alter table %s add %s %s %s;',
                                $this->quoteIdent($table),
                                $key,
                                strtoupper($type),
                                $this->addFiled($filed)
                            );
                    break;
                case 'double':
                    $sql = sprintf('alter table %s add %s %s(16,2) %s;',
                                $this->quoteIdent($table),
                                $key,
                                strtoupper($type),
                                $this->addFiled($filed)
                            );
                    break;
                case 'string':
                    $sql = sprintf('alter table %s add %s VARCHAR (%s);',
                                $this->quoteIdent($table),
                                $key,
                                $this->addFiled($filed)
                            );
                    break;
                case 'object':
                    $sql = sprintf('alter table %s add %s_id BIGINT NOT NULL;',
                                $this->quoteIdent($table),
                                $key
                            );
                    break;
                case 'datetime':
                    $sql = sprintf('alter table %s add %s DATETIME;',
                                $this->quoteIdent($table),
                                $key
                            );
                    break;
                default:
                    return false;
                    break;
            }
            $this->query($sql);
        }

        return true;
    }

    // 建立必须字段
    public function quoteMust()
    {
        // 建表必须属性,虚属性
        $sql .= '`_extra` TEXT NULL ,';
        // 建表必须属性,自增ID
        $sql .= '`id` BIGINT UNSIGNED AUTO_INCREMENT, PRIMARY KEY ( `id` )';
        return $sql;
    }

    // 建立相应字段
    public function addFiled($filed)
    {
        $file = '';
        foreach ($filed as $f) {
            $f = strtoupper($f);
            $file .= $f .' ';
        }
        return $file;
    }

    // 返回sql是否正确执行
    public function result($sql)
    {
        $result = $this->query($sql);
        if (is_object($result)) {
            return true;
        }
        else {
            return false;
        }
    }

    // 建立索引和约束
    public function quoteIndex($table, $indexs)
    {
        $unique = array_shift($indexs);
        $unique = explode(':', $unique);

        // 添加唯一约束
        if (array_shift($unique) == 'unique') {
            foreach ($unique as $u) {
                $sql = sprintf('alter table %s add unique key `%s` (%s);',
                          $this->quoteIdent($table),
                          $u,
                          $u  
                        );
                $this->query($sql);
            }
        }

        // 添加索引
        if (count($indexs) > 0) {
            foreach ($indexs as $index) {
                $sql = sprintf('ALTER TABLE %s ADD INDEX %s (%s);',
                        $this->quoteIdent($table),
                        $index,
                        $index
                    );
                $this->query($sql);
            }
        }
    }
}