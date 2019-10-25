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
// | 缓存设置
// +----------------------------------------------------------------------

use think\facade\{
    Env,App
};

return [

    // 默认缓存驱动
    'default' => Env::get('cache.driver', 'file'),

    // 缓存连接方式配置
    'stores'  => [
        //file缓存
        'file' => [
            // 驱动方式
            'type'       => 'File',
            // 缓存保存目录
            'path'       => '',
            // 缓存前缀
            'prefix'     => Env::get('cache.prefix', 'cache_'),
            // 缓存有效期 0表示永久缓存
            'expire'     => 0,
            // 缓存标签前缀
            'tag_prefix' => 'tag:',
            // 序列化机制 例如 ['serialize', 'unserialize']
            'serialize'  => [],
        ],
        //redis缓存
        'redis'=>[
            // 驱动方式
            'type'       => 'redis',
            // 缓存前缀
            'prefix'   => Env::get('cache.prefix', 'cache_'),
            // 缓存有效期 0表示永久缓存
            'expire'   => Env::get('redis.expire', 86400),
            // redis主机
            'host'     => Env::get('redis.host', '127.0.0.1'),
            // redis端口
            'port'     => Env::get('redis.port', 6379),
            // redis密码
            'password' => Env::get('redis.password', ''),
            // 缓存标签前缀
            'tag_prefix' => 'tag:',
            // 序列化机制 例如 ['serialize', 'unserialize']
            'serialize'  => [],
            //选择库
            'select'   => 7,
        ],
        // 更多的缓存连接
    ],
];
