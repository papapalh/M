<?php
namespace M\Controller\CLI;

class ORM extends \M\Controller\CLI
{

	public function actionUpdate(){
		
    // echo "Updating database structures according ORM definition...\n";

    // 获取orm下所有文件集合
    $orm_dirs = \M\Core::pharFilePaths(SYS_ORM_PATH);

    // 循环
    foreach ($orm_dirs as $orm) {

        $orm = basename($orm, '.php');

        // 反转ORM类
        $className = '\M\ORM\\'.str_replace('/', '\\', $orm);

        $orms[$orm] = \M\IoC::construct($className);

        $o = $orms[$orm];

        // 连接数据库
        $db = $o->db();
        // print_r(var_dump($db));

        // print_r($o->schema());
        $db->adjustTable($o->tableName(), $o->schema());
        

        

        // $relations = $o->relations();
        // $structure = $o->structure();

        // printf("   %s\n", $orm);

        // print_r($orms);       
    }

	}
}