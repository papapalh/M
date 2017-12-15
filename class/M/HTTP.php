<?php

namespace M;

class HTTP
{
    public static function get($url)
    {
    	return self::request('GET', $url, []);
    }

    public static function request($method, $url, $query)
    {
        // curl初始化
        $curl = curl_init();

        //设置抓取的url  
        curl_setopt($curl, CURLOPT_URL, $url);  
        //设置头文件的信息作为数据流输出  
        // curl_setopt($curl, CURLOPT_HEADER, 1);  
        //设置获取的信息以文件流的形式返回，而不是直接输出。  
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // 设置头以JSON形式返回
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		    	'Content-Type: application/json; charset=utf-8'
			)
		);

        if ($method == 'POST') {
            //设置post方式提交  
            curl_setopt($curl, CURLOPT_POST, 1); 
            //post提交的数据  
            curl_setopt($curl, CURLOPT_POSTFIELDS, $query); 
        }
        //执行命令  
        $data = curl_exec($curl);  
        $info = curl_getinfo($curl,CURLINFO_HTTP_CODE); //输出请求状态码  
        //关闭URL请求  
        curl_close($curl);  

        $data = json_decode($data, true);

		return $data;

    }
}