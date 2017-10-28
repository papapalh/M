<?php

namespace M;

class Event
{
	public static function setup()
	{
		// 遍历hooks配置json
		foreach ((array) \M\Config::get('hooks') as $event => $event_ooks) {
			foreach ((array) $event_hooks as $key => $hook) {
				if (!is_string($key)) {
          $key = null;
        }
			}


		}
	}

	// ["hooks"]=>
 //  array(12) {
 //    ["user.isAllowedTo[查看所有仪器]"]=>
 //    array(1) {
 //      [0]=>
 //      string(33) "callback:\Gini\ORM\User::user_ACL"
 //    }
 //    ["user.isAllowedTo[查看负责仪器]"]=>
 //    array(1) {
 //      [0]=>
 //      string(33) "callback:\Gini\ORM\User::user_ACL"
 //    }
 //    ["user.isAllowedTo[修改所有仪器]"]=>
 //    array(1) {
 //      [0]=>
 //      string(33) "callback:\Gini\ORM\User::user_ACL"
 //    }
 //    ["user.isAllowedTo[修改负责仪器]"]=>
 //    array(1) {
 //      [0]=>
}