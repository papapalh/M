<?php

namespace M;

class Config
{

    public static $items = [];

    public static function setup()
    {
        self::clear();

        // 加载配置项
        self::$items = self::fetch();
    }
        
    // 清空配置数组
    public static function clear()
    {
        self::$items = [];
    }

    public static function fetch()
    {
        $item = [];

        // 检查配置目录
        if (!is_dir(SYS_RAW_PATH)) die('No Config Dir;');

        foreach (scandir(SYS_RAW_PATH) as $path) {
            self::_load_config_dir($path, $items);
        }

        return $items;
    }

    // 加载config   
    private static function _load_config_dir($base, &$items)
    {
        $base = SYS_RAW_PATH .'/'.$base;

        // 剔除多余目录
        if ( $base == '.' || $base == '..' ) return false;

        
        if (!file_exists($base)) return false;

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

