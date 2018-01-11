<?php

namespace M;

class View
{
     
    protected $_path;
    protected $_vars;
    private   $view;
    private   $_ob_cache;

    // 视图初始-赋予变量
    public function __construct($path, $vars)
    {
        $this->_path = $path;
        $this->_vars = (array) $vars;
    }

    // 直接输出对象时调用函数
    public function __toString()
    {
        if ($this->_view !== null) {
            return $this->_view;
        }
        
        $path = $this->_path;

        $class = '\M\View\HTML';
        $output = \M\IoC::construct($class, $path, $this->_vars);

        return $this->view = (string) $output;
    }
 } 