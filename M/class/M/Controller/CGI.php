<?php
namespace M\Controller
{
    abstract class CGI {

        // 方法
        public $action;
        // 参数
        public $params;
        // GET/POST/FILE/Route等参数
        public $env;
        // 路由信息
        public $route;

        // 获取路由信息,调用方法,赋予参数
        public function execute()
        {
            $action = $this->action;

            // 判断是否有这个方法
            if ($action && method_exists($this, 'action'.$action)) {
                $action = 'action'.$this->action;
            }
            // 如这个方法为空,调用Index方法 
            elseif (method_exists($this, '__index')) {
                $action = '__index';
            } 
            // 如Index也没有,则报404
            else {
                die('调用方法不存在--');
            }
            
            // 获取所有参数
            $params = (array) $this->params;

            $response = call_user_func_array(array($this, $action), $params);

            return $response;
        }

        // 跳转
        public static function redirect($url = '')
        {
            header('Location:'.URL($url), true, 302);
            exit();
        }

        // 获取表单数据
        public function form($type = '*')
        {
            switch ($type) {
                case 'post':
                    return $this->env['post'];
                    break;
                case 'get':
                    return $this->env['get'];
                    break;
                case 'files':
                    return $this->env['files'];
                    break;
                default:
                    return array_merge($this->env['post'], $this->env['get']);
                    break;
            }
        }

        public function display($view, $params = [])
        {
            echo V($view, $params);
        }
    }
}

namespace
{
    if (function_exists('return_ajax')) {
        die('系统占用！');
    }
    else {
        function return_ajax($data)
        {

            if (is_array($data)) {
                foreach ($data as $d) {
                    if (is_array($d)) {
                        return_ajax($d);
                    }
                    else {
                        if ($d->main_img) {
                            $d->main_img = \M\URI::url().$d->main_img;
                        }
                        if ($d->head_img) {
                            $d->head_img = \M\URI::url().$d->head_img;
                        }
                        if ($d->url) {
                            $d->url = \M\URI::url().$d->url;
                        }
                    }
                }
            }
            else {
                if ($data->main_img) {
                    $data->main_img = \M\URI::url().$data->main_img;
                }
                if ($data->url) {
                    $data->url = \M\URI::url().$data->url;
                }

            }
        

            echo json_encode($data ,JSON_UNESCAPED_UNICODE);
        }
    }

    if (function_exists('success')) {
        die('系统占用！');
    }
    else {
        function success($msg = '失败', $code = 0) {
            $json = [
                        'code' => $code,
                        'msg'  => $msg
                    ];
            echo json_encode($json ,JSON_UNESCAPED_UNICODE);
            return false;
        }
    }

    if (function_exists('fail')) {
        die('系统占用！');
    }
    else {
        function fail($msg = '失败', $code = 1) {
            $json = [
                        'code' => $code,
                        'msg'  => $msg
                    ];
            echo json_encode($json ,JSON_UNESCAPED_UNICODE);
            return false;
        }
    }

    if (function_exists('api_url')) {
        die('系统占用！');
    }
    else {
        function api_url($url)
        {
            if ($url) {
                return \M\URI::url().substr($url, 1);
            }
            return false;
        }
    }
}
