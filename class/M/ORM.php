<?php
namespace M;

// ORM初始定义

class ORM {

    // ORM-名字
    private $_name;
    private $_tableName;

	// 魔术方法Call
	public function __call($method, $params) {
		echo '老铁,你想调用我不存在的方法',$method,'方法<br/>';  
	    echo '还传了一个参数【';  
	    echo print_r($arg),'】<br/>';
	    echo '检查一下呗';
	}
	
	// 魔术方法
	function __construct($criteria = null) {
    	// $structure = $this->structure();
  	}


  	private static $_structures;
  	public function structure() {
  		// 获取当前对象的类名
  		$class_name = get_class($this);

        if (!isset(static::$_structures[$class_name])) {
            $properties = $this->properties();
            return $properties;
        }

  	}

    // 取出实例ORM所有public方法里面的方法和参数
  	public function properties() {
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
  	public function schema() {
  		$structure = $this->structure();
        return $structure;
  	}

    // 连接数据库
    public function db() {
        return \M\Database::db();
    }

    // 获取调用ORM类名
    public function tableName() {

        if (!isset($this->_tableName)) {
            $this->_prepareName();
        }

        return $this->_tableName;
    }

    // 获取调用ORM类名
    private function _prepareName() {
        // 获取当前类名
        list(, , $name) = explode('/', str_replace('\\', '/', strtolower(get_class($this))), 3);
        $this->_name = $name;
        $this->_tableName = str_replace('/', '_', $name);
    }

    // save数据
    public function save() {

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
            if ($key == '_name' || $key == '_tableName') {
                unset($db_data[$key]);
            }
        }

        $sql = 'INSERT INTO '.$this->tableName().' (' . implode(',', array_keys($db_data)) .') VALUES (\''.implode('\',\'', $db_data).'\')';


        $aaa = $db->result($sql);
        print_r( $aaa );



        // $aaa = $db->query('select * from user;');
        // print_r(($structure));
    }
}