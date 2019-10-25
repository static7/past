<?php
/**
 * Description of Channel.php.
 * User: static7 <static7@qq.com>
 * Date: 2017/9/18 12:42
 */

namespace app\admin\validate;


use think\Validate;

class Navigation extends Validate
{
    protected $rule   = [
        'title' => "require|max:30",
        'url' => "require",
        'module'=>"require",
    ];
    protected $message    = [
        'title.require' => '标题不能为空',
        'title.max' => '标题最多不能超过30个字符',
        'url.require' => '链接不能为空',
        'module.require' => '所属模块不能为空'
    ];
}