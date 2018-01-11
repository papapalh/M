<?php
namespace M\Controller
{
    class API extends \M\Controller\CGI
    {

        static $info;

        public function check_token()
        {
            // 抓取Token
            $token = $_SERVER['HTTP_TOKEN'];

            $value = R($token);

            if (!$value) {
                $this->error();
            }

            static::$info = $value;
        }

        public function user_info()
        {
            $token = json_decode(static::$info);
            return $token;
        }

        public function error()
        {
            echo  json_encode(['error' => 'API Fail']);
            die;
        }
    }
}
namespace
{
    if (function_exists('api_form')) {
        die('系统占用');
    }
    else {
        function api_form(){
            return file_get_contents("php://input");
        }
    }
}

