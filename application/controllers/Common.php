<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends CI_Controller {

    /**
    * 验证码
    */
    public function captcha()
    {
        $this->load->library('Captcha');
        //英文
        $config = array(
            'seKey'     =>  'Huanshuo',          // 验证码加密密钥
            'codeSet'   =>  '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',             // 验证码字符集合
            'expire'    =>  1800,                   // 验证码过期时间（s）
            'useZh'     =>  FALSE,                  // 使用中文验证码 
            'useImgBg'  =>  FALSE,                  // 使用背景图片 
            'fontSize'  =>  16,                     // 验证码字体大小(px)
            'useCurve'  =>  TRUE,                   // 是否画混淆曲线
            'useNoise'  =>  FALSE,                  // 是否添加杂点  
            'imageW'    =>  0,                      // 验证码图片宽度
            'imageH'    =>  32,                     // 验证码图片高度
            'length'    =>  4,                      // 验证码位数
            'fontttf'   =>  'texb.ttf',             // 验证码字体，不设置随机获取
            'bg'        =>  array(243, 251, 254),   // 背景颜色
            'reset'     =>  TRUE,                   // 验证成功后是否重置
        );
        /*
        // 中文
        $config = array(
            'seKey'     =>  'Huanshuo',             // 验证码加密密钥
            'codeSet'   =>  '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',             // 验证码字符集合
            'expire'    =>  1800,                   // 验证码过期时间（s）
            'useZh'     =>  TRUE,                   // 使用中文验证码 
            'useImgBg'  =>  FALSE,                  // 使用背景图片 
            'fontSize'  =>  16,                     // 验证码字体大小(px)
            'useCurve'  =>  TRUE,                   // 是否画混淆曲线
            'useNoise'  =>  FALSE,                  // 是否添加杂点  
            'imageW'    =>  0,                      // 验证码图片宽度
            'imageH'    =>  40,                     // 验证码图片高度
            'length'    =>  3,                      // 验证码位数
            'fontttf'   =>  'zhttfs/fzstk.ttf',     // 验证码字体，不设置随机获取
            'bg'        =>  array(243, 251, 254),   // 背景颜色
            'reset'     =>  TRUE,                   // 验证成功后是否重置
        );
        */
        $captcha = new Captcha($config);
        $captcha->generate();

    }
}
