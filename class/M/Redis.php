<?php
namespace M;

class Redis
{
	public $_drive;

	function __construct($ip = '', $port = '')
	{
		if (!$ip || !$port) {
			$config = \M\Config::get('redis')['redis'];
			$ip = $config['ip'];
			$port = $config['port'];
		}

		// 连接Redis
		$this->_drive = new \Redis();

		$this->_drive->connect($ip, $port);
	}

	public function status()
	{
		return $this->_drive->ping();
	}

	public function get($key)
	{
		if (!$key) {
			return false;
		}
		return $this->_drive->get($key);
	}

	public function set($key, $value, $timeout)
	{
		return $this->_drive->set($key, $value, $timeout);
	}
}