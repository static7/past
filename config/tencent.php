<?php
/**
 * Description 腾讯云各种
 * User: static7 <static7@qq.com>
 * Date: 2018/8/4 21:03
 */
use think\facade\{
    Env
};
return [

    //腾讯验证码服务 免费版本
    'captcha'=>[
        'status'=>0, //0 关闭,1开启
        'appid'=>Env::get('captcha.appid',''),
        'appkey'=>Env::get('captcha.appkey',''),
    ],

    //腾讯验证码服务 付费版本
/*    'captcha'=>[
        'secretId'=>'',
        'secretKey'=>'',
        'other'=>[
            //用户ip
            'userIp'       => '127.0.0.1',
            //用户账号类型
            'accountType'   =>0,
            //验证码类型
            'captchaType'   =>9,
            //业务ID
            'businessId'    =>1,
        ]
    ],*/

    //腾讯云 COS 上传 tencent
    'tencent_cos' => [
        //存储桶区域
        'region' => Env::get('cos.region', ''),
        //超时时间 秒
        'timeout' => 30,
        //存储桶名称
        'bucket' => Env::get('cos.bucket', ''),
        //自定义域名转换访问
        'domain' => Env::get('cos.domain', ''),
        'credentials' => [
            'appId' => Env::get('cos.appId', ''),
            'secretId' => Env::get('cos.secretId', ''),
            'secretKey' => Env::get('cos.secretKey', ''),
        ],
    ],

    //腾讯云短信
    'tencent_sms'=>[
        'appid'=>'',
        'appkey'=>''
    ],
];