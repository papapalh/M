<?php
namespace M\Controller\CLI;

class ORM extends \M\Controller\CLI
{

	public function actionUpdate(){
		
    echo "Updating database structures according ORM definition...\n";

    // 获取orm下所有文件集合
    $orm_dirs = \M\Core::pharFilePaths(SYS_ORM_PATH);

    // foreach ($orm_dirs as $orm_dir) {
    // 	print_r($orm_dir);
    // }

    \M\IoC::construct('\M\ORM\User');
	}
}