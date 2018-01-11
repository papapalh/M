<?php

namespace M;

class File
{
    static $error;

    /**
     * @param array file    上传图片信息
     * @param string status 上传类型
     * @param string id     上传ID
     * @return img_id
     */
    public static function save($file = [])
    {
        $path = SYS_UPLOAD_PATH.'/';
        // 格式验证
        $type = $file['type'];
        $name = $file['name'];
        $size = $file['size'];
        $tmp_name = $file['tmp_name'];

        if ($type == 'image/png' || $type == 'image/jpg' || $type == 'image/jpeg') {
        }
        else {
            self::$error['error'][] = '格式错误!';
        }

        // 大小验证--20M
        if ($file['size'] > 20 * 1024 * 1024) {
            self::$error['error'][] = '上传文件过大!';
        }

        // 文件唯一性检查
        if (file_exists($path.$name)) {
            self::$error['error'][] = '文件已存在!';
        }

        // 如果没错误-则存入图片
        if (!empty(self::$error)) {
            return self::$error;
        }

        move_uploaded_file($tmp_name, $path . $name);
        return (string)'upload/' . (string)$name;
    }
}