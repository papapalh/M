<?php

namespace M\View;

class HTML
{
    private $_path;
    private $_vars;

    public function __construct($path, array $vars)
    {
        $this->_path = $path;
        $this->_vars = $vars;
    }

    public function __toString()
    {
        if ($this->_path) {
            // 开启缓冲
            ob_start();

            // 赋予变量
            extract($this->_vars);

            // 包含文件
            include SYS_VIEW_PATH.'/'.$this->_path.'.phtml';

            // var_dump(SYS_VIEW_PATH.'/'.$this->_path.'.phtml');die;

            // 获取缓冲
            $output = ob_get_contents();

            // 清理缓冲
            ob_end_clean();
        }

        return $output;
    }
}