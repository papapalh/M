<?php

namespace M;

class Config
{

    public static $items = [];

    public static function setup()
    {
        self::clear();
        $config_file = SYS_PATH.'/cache/config.json';

        // 获取配置文件推入数组
        if (file_exists($config_file)) {
            self::$items = (array) @json_decode(file_get_contents($config_file), true);
        }
        // 如未定义，则遍历yml读取配置文件
        else{
            self::$items = self::fetch();
        }
    }
        
    // 清空配置数组
    public static function clear()
    {
        self::$items = [];
    }

    public static function fetch()
    {
        $item = [];

        // 检查是否有raw目录
        if (!is_dir(SYS_RAW_PATH)) {
            die('没有定义raw目录');
        }

        $paths = scandir(SYS_RAW_PATH);

        foreach ($paths as $path) {
            self::_load_config_dir($path, $items);
        }
        return $items;
    }

    // 加载config目录内容    
    private static function _load_config_dir($base, &$items)
    {
        $base =  SYS_RAW_PATH .'/'.$base;

        // 排除其他无用目录
        if( $base == '.' || $base == '..' ){
            return false;
        }
        // 检查配置文件文件是否存在
        if (!file_exists($base)) {
            return false;
        }
        // 检查文件后缀并写入配置JSON
        $dh = pathinfo($base, PATHINFO_EXTENSION);
        // 检查文件名写入配置Config
        $dir = basename($base, '.yml');
        switch ($dh) {
            case 'yml':
                // 获取当前配置文件内容
                $content = file_get_contents($base);

                $content = trim($content);
                $content = yaml_parse($content);

                $items[$dir] = $content;
                break;
            default:
                break;
        }
    }

    // 获取配置信息
    public static function get($key)
    {
        list($category, $key) = explode('.', $key, 2);

        // 为空返回大数组信息
        if ($key === null) {
            return self::$items[$category];
        }

        // 有值则返回指定值
        return self::$items[$category][$key];
    }

    // 设置配置信息
    public static function set($key ,$val = null)
    {
        list($category, $key) = explode('.', $key, 2);

        if ($key) {
            if ($val === null) {
                unset(self::$items[$category][$key]);
            } else {
                self::$items[$category][$key] = $val;
            }
        } else {
            if ($val === null) {
                unset(self::$items[$category]);
            } else {
                self::$items[$category] = $val;
            }
        }
    }
}

