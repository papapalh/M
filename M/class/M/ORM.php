<?php

namespace M;

class ORM
{
    // ORM-表名
    private $_name;
    // ORM-字段名
    private $_tableName;
    // ORM-sql
    private $_sql;
    
    // 魔术方法
    function __construct($criteria = null)
    {
        // 找到ID对应的ORM
        if ($criteria) {
            $this->criteria($criteria);
        }
    }

    private static $_structures;
    public function structure()
    {
        // 获取当前对象的类名
        $class_name = get_class($this);

        if (!isset(static::$_structures[$class_name])) {
            $properties = $this->properties();
            return $properties;
        }
    }

    // 取出实例ORM所有public方法里面的方法和参数
    public function properties()
    {
        // 把自己这个ORM对象做一个映射
        $rc = new \ReflectionClass($this);
        $defaults = $rc->getDefaultProperties();

        $properties = [];

        foreach ($rc->getProperties() as $r) {
            $k = $r->getName();
            $properties[$k] = $defaults[$k];
        }

        return $properties;
      }

    // 获取orm定义对象中所有的方法
    public function schema()
    {
        $structure = $this->structure();
        return $structure;
    }

    // 连接数据库
    public function db()
    {
        return \M\Database::db();
    }

    // 获取调用ORM类名
    public function tableName()
    {
        if (!isset($this->_tableName)) {
            $this->_prepareName();
        }

        return $this->_tableName;
    }

    // 获取调用ORM类名
    private function _prepareName()
    {
        // 获取当前类名
        list(, , $name) = explode('/', str_replace('\\', '/', strtolower(get_class($this))), 3);
        $this->_name = $name;
        $this->_tableName = str_replace('/', '_', $name);
    }

    // save数据
    public function save()
    {
        // 获取orm模型定义属性与字段
        $schema = (array) $this->schema();

        // 数据库连接
        $db = $this->db();

        // 操作数据表
        $tbl_name = $this->tableName();

        $structure = get_object_vars($this);

        // 找出修改之后字段和ORM定义字段不同的
        $db_data = array_diff_assoc((array) $structure, (array) $schema);

        // 排除框架ORM属性定义影响
        foreach ($db_data as $key => $value) {
            if ($key == '_name' || $key == '_tableName' || $key == '_sql') {
                unset($db_data[$key]);
            }
        }

        // 更新
        if ($this->id) {

            $object = a($this->tableName(), $this->id);

            if (!is_object($object)) return false;

            // 对象转数组
            foreach ($object as $k => $v) {
                $arr[$k] = $v;
            }
            
            // 找差异
            $data = array_diff($structure, $arr);

            // 拼接sql
            foreach ($data as $k => $v) {
                if ($k == '_name' || $k == '_tableName') {
                    continue;
                }
                $update .= sprintf(',%s = \'%s\'', $k, $v);
            }

            $sql = sprintf('UPDATE %s SET %s WHERE id = %s',
                        $this->tableName(),
                        substr($update, 1),
                        $this->id
                    );
        }
        else {
            $sql = 'INSERT INTO '.$this->tableName().' (' . implode(',', array_keys($db_data)) .') VALUES (\''.implode('\',\'', $db_data).'\')';
        }

        $result = $db->result($sql);

        return $result;
    }


    public function criteria($criteria)
    {
        // 数据库连接
        $db = $this->db();

        $sql = 'SELECT * FROM '.$this->tableName().' WHERE id = '.(int) $criteria;

        $result = $db->query($sql);
        
        $o = $result->fetch();

        $structure = get_object_vars($this);

        // 排除框架ORM属性定义影响
        foreach ($structure as $key => $value) {
            $this->$key = $o[$key];
        }
        $this->id = $o['id'];
    }

    public function whose($name)
    {
        $this->_sql .= " WHERE `$name` ";
        return $this;
    }

    public function is($value)
    {
        $this->_sql .= "= '$value'";
        return $this;
    }

    // 数据库查询--redis(缓存)
    public function redis($key = null, $time = null)
    {
        if ($key) {
            $redis = new \M\Redis();

            // 检查键是否存在
            $o = $redis->get(S('username').$key);
            // 返回结果
            if ($o) {
                $o = json_decode($o, true);
            }
            else {
                // 没有则数据查询--获取数据对象集合
                if (!$time) $time = 60;

                $o = $this->_getValue();

                // 存入redis
                $redis->set(S('username').$key, json_encode($o), $time);
            }
        }
        else {
            $o = $this->_getValue();
        }

        // 数据集赋予对象
        $structure = get_object_vars($this);

        // 排除框架ORM属性定义影响
        foreach ($structure as $key => $v) {
            $this->$key = $o[$key];
        }
        $this->id = $o['id'];
        return $this;
    }

    // 连接数据库--获取全部数据集合
    public function _getValue()
    {
        // 数据库连接-查询
        $db = $this->db();

        $sql = 'SELECT * FROM :table :_sql LIMIT 1';

        $result = $db->query($sql,[
                    'table' => $this->tableName(),
                    '_sql'  => $this->_sql
                ]);
        // 获取数据集合
        $o = $result->fetch();

        return $o;
    }

    // 关联关系
    public function connect($connect)
    {
        if (!is_object($connect)) {
            return false;
        }

        // 关系表建立
        $connect_name = '_r_'.$this->tableName().'_'.$connect->tableName();

        // 数据库连接
        $db = $this->db();

        // 创建关联表
        $sql = sprintf('CREATE TABLE IF NOT EXISTS %s (`id1` INT, `id2` INT) ENGINE = %s',
                    $connect_name,
                    'Innodb'
                );

        if ($db->result($sql)) {

            $sql = sprintf("SELECT * FROM %s WHERE id1 = '%s' AND id2 = '%s'",
                        $connect_name,
                        $this->id,
                        $connect->id
                    );
            $result = $db->query($sql);
        
            $o = $result->fetchAll();

            if (count($o) > 0) {
                return false;
            }
            
            $sql = 'INSERT INTO '.$connect_name.' (id1 , id2) VALUES (\''. $this->id .'\',\''.$connect->id.'\')';;
            if (!$db->result($sql)) {
                return false;
            }
            return true; 
        }
        else {
            return false;
        }
    }

    // 获取关联数组
    public function getConnect($connect)
    {
        // 关系表建立
        $connect_name = '_r_'.$this->tableName().'_'.$connect;

        // 数据库连接
        $db = $this->db();

        $sql = sprintf("SELECT * FROM %s WHERE id1 = '%s'",
                        $connect_name,
                        $this->id
                    );
        $result = $db->query($sql);

        if (!$result) return false;

        $o = $result->fetchAll();

        if (count($o) <= 0) {
            return false;
        }

        foreach ($o as $k => $v) {
            $array[] = $v['id2'];
        }

        return $array;
    }

    public function delete()
    {
        if (!$this->id) return false;

        $db = $this->db();

        $sql = sprintf("DELETE FROM %s WHERE `id` = '%s'", $this->tableName(), $this->id);

        $result = $db->query($sql);

        if (!$result) return false;
    }

    // 对应关联只建立一次
    // public function one_connect($connect)
    // {
    //     if (!is_object($connect)) {
    //         return false;
    //     }

    //     // 关系表建立
    //     $connect_name = '_r_'.$this->tableName().'_'.$connect->tableName();

    //     // 数据库连接
    //     $db = $this->db();

    //     // 创建关联表
    //     $sql = sprintf('CREATE TABLE IF NOT EXISTS %s (`id1` INT, `id2` INT) ENGINE = %s',
    //                 $connect_name,
    //                 'Innodb'
    //             );

    //     if ($db->result($sql)) {

    //         // 删除多余关联关系
    //         $sql = 'DELETE FROM '.$connect_name. ' WHERE id = '.$this->id;
    //         if ($db->result($sql)) {
    //             $sql = 'INSERT INTO '.$connect_name.' (id1 , id2) VALUES (\''. $this->id .'\',\''.$connect->id.'\')';;
    //             if (!$db->result($sql)) {
    //                 return false;
    //             }
    //             return true;
    //         }
    //     }
    //     else {
    //         return false;
    //     }
    // }
}