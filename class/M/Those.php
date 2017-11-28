<?php
namespace M {

	class Those
	{
		private $_name;
		private $_table;
        private $_field;
        private $_sql;
        private $_join;
        private $_alias;

        function __construct($name)
		{
			$this->_name = $name;
		}

		// public function whose ($field)
		// {
		// 	if ($this->_where) {
  //               $this->_where[] = 'AND';
  //           }
  //           $this->_field = $field;

  //           var_dump($this->_where);
  //           die;
  //           return $this;
		// }

		public function redis($key = null, $time = null)
		{
			if ($key) {
				$redis = new \M\Redis();

	            // 检查键是否存在
	            $result = $redis->get($key);

	            // 返回结果
	            if ($result) {
	                $result = json_decode($result, true);
	            }
	            // 没有则数据查询--获取数据对象集合
	            else {
                	if (!$time) $time = 60;

                	// 获取数据集合
                	$result = $this->_getValue();

					// 存入redis
	                $redis->set($key, json_encode($result), $time);
	            }
			}
			else {
				// 获取数据集合
                $result = $this->_getValue();
			}

			// 数据对象填充
			$those =[];

			foreach ($result as $one) {
				// 实例化对应ORM
				$object = \M\IoC::construct('\M\ORM\\'.$this->_name);

				foreach ($one as $o => $v) {
					if ($object->$o) {
						$object->$o = $v;
					}
				}
				array_push($those, $object);
			}
			return $those;
		}

		// 获取拼接sql
		public function _getSql()
		{
			$values = func_get_args();

			switch (reset($values)) {
				case '*':
					$sql = 'SELECT * FROM '. $this->_name;
					break;
				default:
					# code...
					break;
			}
			return $sql;
		}

		// 执行数据库操作 -- 获取全部数据
		public function _getValue()
		{
			$db = \M\Database::db();

			if (!$this->_sql) {
				$sql =  $this->_getSql('*');
			}

			$result = $db->query($sql)->fetchAll();

			return $result;
		}
	}
}


namespace {
	if (function_exists('a')) {
		die('a 函数是数据库占用函数,请检查');
	} else {
		function a($name, $criteria = null) {
			$class_name = '\M\ORM\\'.str_replace('/', '\\', $name);

			return \M\IoC::construct($class_name, $criteria);
		}
	}

	if (function_exists('those')) {
        die('those 函数是数据库占用函数,请检查!');
    } else {
        function those($name)
        {
            return \M\IoC::construct('\M\Those', $name);
        }
    }
}