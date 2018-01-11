<?php
namespace M\Controller\CLI;

class ORM extends \M\Controller\CLI
{

    public function actionUpdate()
    {
        
        echo "\e[32m  更新数据库表结构...\e[0m\n";

        // 获取orm下所有文件集合
        $orm_dirs = \M\Core::pharFilePaths(SYS_ORM_PATH);

        // 循环文件更新表结构
        foreach ($orm_dirs as $orm) {

            $orm = basename($orm, '.php');

            // 反转ORM类
            $className = '\M\ORM\\'.str_replace('/', '\\', $orm);

            $orms[$orm] = \M\IoC::construct($className);

            $o = $orms[$orm];

            // 连接数据库
            $db = $o->db();

            $db->adjustTable($o->tableName(), $o->schema());
            
            echo "    ".$o->tableName()."\n";
        }

        echo "\e[32m  done...\e[0m\n";

    }
}