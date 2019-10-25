<?php

use think\facade\{Request, Env};

//配置文件
return [

    //菜单缓存
    'menu_cache'           => true,

    //后台系统版本
    'system_version'       => '1.0',

    //超级管理员用户ID
    'user_administrator'   => 1,

    //内容进行字符替换
    'template'             => [
        //静态资源替换
        'tpl_replace_string' => [
            '__LAYUI__'  => '/layui',
            '__STATIC__' => '/static',
            '__JS__'     => '/admin/js',
            '__CSS__'    => '/admin/css',
            '__IMG__'    => '/admin/images',
        ],
    ],

    //权限配置
    'auth_config'          => [
        // 认证开关
        'auth_on'                   => true,
        // 认证方式，1为实时认证；2为登录认证。
        'auth_type'                 => 1,
        // 用户组数据表名
        'auth_group'                => 'auth_group',
        // 用户-用户组关系表
        'auth_group_access'         => 'auth_group_access',
        // 权限规则表
        'auth_rule'                 => 'auth_rule',
        // 用户信息表
        'auth_user'                 => [
            'table'      => 'member',
            'primaryKey' => 'user_id'
        ],
        //用户额外字段
        'auth_user_field'           => [],
        //管理员用户组类型标识
        'type_admin'                => 1,
        //用户中心表
        'user_center'               => 'user_center',
        //动态权限扩展信息表
        'auth_extend'               => 'auth_extend',
        //分类权限标识
        'auth_extend_category_type' => 1,
        //模型权限标识
        'auth_extend_model_type'    => 2,
    ],

    //权限规则设定
    'auth_rule'            => [
        'rule_url'  => 1, //url
        'rule_main' => 2, //主要节点
    ],

    //应用模块
    'module'               => [
        1 => 'admin',
        2 => 'home',
    ],

    //默认图片路径
    'default_images'       => 'https://source.calm7.com/null.gif',

    //行为类型
    'action_type'          => [1 => '系统', 2 => '用户'],

    //配置分组
    'config_group_list'    => [1 => '内容', 2 => '用户', 3 => '系统', 4 => '网站', 5 => '基本'],

    //配置类型 只能增加不能更改
    'config_type_list'     => [1 => '字符', 2 => '文本', 3 => '数组', 4 => '枚举', 5 => '数字'],

    //配置区域
    'config_area'          => [0 => '全局', 1 => '前端', 2 => '后端', 3 => '微信端'],

    //默认图片
    'default_picture'      => Request::root(true) . '/static/images/null.gif',

    //微信消息类型
    'weichat_message_type' => [
        'text'  => '文本',
        'image' => '图片',
        'voice' => '语音',
        'video' => '视频',
        'music' => '音乐',
        'news'  => '图文',
    ],

    //微信事件类型
    'weichat_event_type'   => [
        "SCAN"        => '扫码',
        'VIEW'        => '跳转链接',
        'subscribe'   => '关注',
        'CLICK'       => '自定义菜单',
        'LOCATION'    => '上报地理位置',
        'unsubscribe' => '取消订阅',
    ],

    //banner
    'banner_position'      => [
        '1' => '位置1(轮播)',
        '2' => '位置2',
    ],
];