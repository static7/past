<?php
/**
 * Description of Links.php.
 * User: static7 <static7@qq.com>
 * Date: 2017/9/18 12:16
 */

namespace app\admin\validate;


use think\Validate;

class Links extends Validate
{
    protected $rule = [
        'title' => "require|max:30",
        'link' => "require"
    ];
    protected $message = [
        'title.require' => '标题不能为空',
        'title.max' => '标题最多不能超过30个字符',
        'link.require' => '链接不能为空'
    ];
}