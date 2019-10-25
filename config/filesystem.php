<?php
/**
 * Description of filesystem.php.
 * User: static7 <static7@qq.com>
 * Date: 2019/6/19 11:23
 */

use think\facade\{
    App,Env
};

return [
    'default' => Env::get('filesystem.driver', 'public'),
    'disks'   => [
        //本地
        'local'  => [
            'type' => 'local',
            'root'   => App::getRuntimePath() . 'storage',
        ],
        //public
        'public' => [
            'type'     => 'local',
            'root'       => App::getRootPath() . 'public/storage',
            'url'        => '/storage',
            'visibility' => 'public',
        ],
        // 更多的磁盘配置信息
    ],
];