<?php
// 这是系统自动生成的admin应用event定义文件


use app\listener\admin\{
    LogRecord,SystemConfig,UserLoginRecord
};

return [
    //绑定标签位
    'bind' => [],

    //监听
    'listen' => [
        //应用初始化标签位
        'AppInit'  => [],
        //应用开始标签位
        'HttpRun'  => [
            SystemConfig::class,
        ],
        //行为开始标签位
        'ActionBegin'  => [
            LogRecord::class,
        ],
        //记录登录日志
        'UserLoginRecord'=>[
            UserLoginRecord::class,
        ],
        //应用结束标签位
        'HttpEnd'  => [],
        //日志write方法标签位
        'LogLevel' => [],
        //日志写入标签位
        'LogWrite' => [],
    ],
    //事件订阅
    'subscribe' => [],
];
