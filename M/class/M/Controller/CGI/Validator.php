<?php
namespace M\Controller\CGI;

class Validator
{
    private $_errors;

    public function errors()
    {
        return $this->_errors;
    }     

    // 验证类主方法
    public function validate($key, $func, $message)
    {
        if (!$func) {
            $this->_errors[$key] = $message;
        }
        return $this;
    }

    public function done()
    {
        if (count($this->_errors) > 0) {
            print_r($this->_errors);
            die;
        }
    }

}
