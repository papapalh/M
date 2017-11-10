<?php

namespace M\Database;

class MySQL extends \PDO { 

	function __construct($dsn, $username = null, $password = null, $options = null) {
		parent::__construct('mysql:host=localhost;dbname=girl', 'root', '83719730');
		echo "连接成功<br/>";
	} 

}