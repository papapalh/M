<?php

namespace M;

class IoC
{
    // 将字符转换为小写
    protected static function key($name)
    {
        return strtolower($name);
    }
    
    public static function construct()
    {
        $args = func_get_args();
        $name = array_shift($args);
        $key = self::key($name);

        // 建立这个类的映射,也就可以调用这个类的方法
        $rc = new \ReflectionClass($name);
        return $rc->newInstanceArgs($args);
    }
}