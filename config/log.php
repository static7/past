<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 日志设置
// +----------------------------------------------------------------------
use think\facade\{
    App,Env
};

return [
    // 默认日志记录通道
    'default'      => Env::get('log.channel', 'file'),
    // 日志记录级别
    'level'        => [
        'sql','notice','warning', 'error', 'critical', 'alert', 'emergency'
    ],
    // 日志类型记录的通道 ['error'=>'email',...]
    'type_channel' => [],
    // 关闭全局日志写入
    'close'        => Env::get('log.close', true),
    // 全局日志处理 支持闭包
    'processor'    => null,

    // 日志通道列表
    'channels'     => [
        //文件
        'file' => [
            // 日志记录方式
            'type'        => 'File',
            // 日志保存目录
            'path'        => '',
            // 单文件日志写入
            'single'      => false,
            // 独立日志级别
            'apart_level' => ['error','sql'],
            //单个日志文件的大小限制，超过后会自动记录到第二个文件
            'file_size'     =>30 *1024*1024,
            //日志的时间格式，默认是` c `
            'time_format'   =>'Y-m-d H:i:s',
            // 最大日志文件数量
            'max_files'   => 0,
            // 使用JSON格式记录
            'json'        => false,
            // 日志处理
            'processor'   => null,
            // 关闭通道日志写入
            'close'       => false,
            // 日志输出格式化
            'format'      => '[%s][%s] %s',
            // 是否实时写入
            'realtime_write' => false,
        ],

        'socket'=>[
            // 日志记录方式， socket扩展
            'type'                => 'SocketLog',
            //主机IP或者域名
            'host'                => Env::get('log.host', '127.0.0.1'),
            //日志强制记录到配置的client_id
            'force_client_ids'    => ['slog_12345678'],
            //限制允许读取日志的client_id
            'allow_client_ids'    => ['slog_12345678'],
            // 是否显示加载的文件列表
            'show_included_files' => false,
        ],

        // 其它日志通道配置
    ],
];
