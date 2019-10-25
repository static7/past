<?php
/**
 * Description of route.php.
 * User: static7 <static7@qq.com>
 * Date: 2019/5/14 15:20
 */

use think\facade\{Route};

Route::domain('past', function () {
    //首页 示例
//    Route::rule('/', 'Index/index', 'get');
})->ext('html')->middleware([
    //登录中间件 TODO 如果需要使用路由用这个中间件 否则就用控制器中间件
//    app\middleware\admin\LoginCheck::class,
])->https(false);
