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
// | 会话设置
// +----------------------------------------------------------------------

use think\facade\Env;

return [
    // session name
    'name'           => Env::get('session.name','PHPSESSID'),
    // SESSION_ID的提交变量,解决flash上传跨域
    'var_session_id' => Env::get('session.var_session_id', ''),
    // 驱动方式 支持file cache
    'type'           => Env::get('session.type', 'File'),
    // 存储连接标识 当type使用cache的时候有效
    'store'          => Env::get('session.store', null),
    // 前缀
    'prefix'         => Env::get('session.prefix', 's_'),
    // 过期时间
    'expire'         => 3600,

];
