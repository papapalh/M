<?php
namespace M;

class Recovery
{
    private static $pass_phrase;

	public static function create () {
		 // /* 生成验证码序列 */
        define("CAPTCHA_NUMCHARS", 6);  //验证码长度
        $pass_phrase = "";              //验证码内容
        for ($i = 0; $i < CAPTCHA_NUMCHARS; $i++) {
            //随机生成字母添加至验证码
            $pass_phrase .= chr(rand(97, 122));
        }

        self::$pass_phrase = $pass_phrase;

        // 验证码内容存入Session
        S('recovery', self::$pass_phrase, 120);

        /* 生成验证码图像 */
        define("CAPTCHA_WIDTH", 300);    //验证码宽度
        define("CAPTCHA_HEIGHT", 100);    //验证码高度
        //创建空白画布
        $img = imagecreatetruecolor(CAPTCHA_WIDTH, CAPTCHA_HEIGHT);

        //设置主题颜色
        $bg_color = imagecolorallocate($img, 225, 225, 225);       //白色背景
        $text_color = imagecolorallocate($img, 0, 0, 0);           //黑色字体
        $graphic_color = imagecolorallocate($img, 64, 64, 64);     //灰色图像
        //填充背景
        imagefilledrectangle($img, 0, 0, CAPTCHA_WIDTH, CAPTCHA_HEIGHT, $bg_color);
        //绘制随机直线
        for ($i = 0; $i < 10; $i++) {
            imageline($img, 0, rand() % CAPTCHA_HEIGHT, CAPTCHA_WIDTH, rand() % CAPTCHA_HEIGHT, $graphic_color);
        }
        //绘制随机点
        for ($i = 0; $i < 20; $i++) {
            imagefilledellipse($img, rand() % CAPTCHA_WIDTH, rand() % CAPTCHA_HEIGHT, 10, 10, $graphic_color);
        }
        // 绘制验证码
        imagettftext($img, 60, 0, 20, CAPTCHA_HEIGHT - 20, $text_color, SYS_PUBLIC_PATH . "/recovery/recovery.ttf", $pass_phrase);
        // 作为PNG图像输出
        header("Content-type: image/png");
        imagepng($img);
        // 从内存从撤销图像
        imagedestroy($img);
	}

    public static function get () {
        return S('recovery');
    }

}
