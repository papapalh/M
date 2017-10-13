<?php
	namespace M\lib;

	class File
	{
		static function relative_path($path, $base=NULL) {
			if(!$base) $base = ROOT_PATH;
			/*
				Cheng.Liu@2010.11.13
				兼容去除路径中'/'的问题
			*/
			return preg_replace('|^'.preg_quote($base, '|').'/?(.*)$|', '$1', $path);
		}
		
	}



?>