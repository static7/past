<?php
/**
 * Description of file.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/1/26 22:58
 */

return [

    //web访问目录
    'access'               => '/storage',
    //默认图片保存路径
    'picture_path'         => '/picture',
    //默认文件保存路径
    'file_path'            => '/file',
    //头像保存路劲
    'avatar_path'          => '/avatar',

    /* 文件限制 */
    'file_upload_restrict' => [
        //上传的文件大小限制
        'fileSize' => ['rule' => 10 * 1024 * 1024, 'message' => '上传文件大小超过限制'],
        //允许上传的文件后缀
        'fileExt'  => ['rule' => 'gif,jpg,jpeg,bmp,png,swf,fla,flv,zip,7z,tar.gz,rar', 'message' => '上传文件后缀名超过限制'],
        //允许
    ],

    //上传远程服务器
    'upload_remote_server' => [
        //是否开启
        'status'       => true,
        // 类名 必须加上命名空间 且仅通过代理静态访问
        'class'        => '\app\facade\QcloudCosClient',
        //上传的入口方法
        'method'       => 'upload',
        //远程上传后 是否删除本地
        'local_backup' => false,
    ],


];