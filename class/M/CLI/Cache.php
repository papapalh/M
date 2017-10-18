<?php

namespace M;

class Cache{
	function createCache(){
		printf("%s\n", 'Updating class cache...');
		echo '1';
	}
}
$a = new \M\Cache();
$a->createCache();

