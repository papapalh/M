<?php
namespace M;

// ORM初始定义

class ORM
{
	// 魔术方法Call
	public function __call($method, $params) {
		echo '老铁,你想调用我不存在的方法',$method,'方法<br/>';  
	    echo '还传了一个参数【';  
	    echo print_r($arg),'】<br/>';
	    echo '检查一下呗';
	}
	
	// 魔术方法
	function __construct($criteria = null) {
    	$structure = $this->structure();
  	}


  	private static $_structures;
  	public function structure() {
  		// 获取ORM对象的类名
  		$class_name = get_class($this);

  		$properties = $this->properties();

  	}

  	public function properties() {
  		// 把自己这个ORM对象做一个映射
  		$rc = new \ReflectionClass($this);
      $defaults = $rc->getDefaultProperties();
  	}

  	public function save() {
  		$schema = (array) $this->schema();
  	}

  	public function schema() {
  		$structure = $this->structure();
  	}

    public function db() {
      \M\Database::db('a');
    }
}