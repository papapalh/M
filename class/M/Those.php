<?php

namespace {
	if (function_exists('a')) {
		die('a 函数是系统占用函数,请检查');
	} else {
		function a($name, $criteria = null) {
			$class_name = '\M\ORM\\'.str_replace('/', '\\', $name);
			// var_dump($criteria);

			return \M\IoC::construct($class_name, $criteria);
		}
	}
}