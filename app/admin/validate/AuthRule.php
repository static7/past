<?php
/**
 * Description of AuthGroup.php.
 * User: static7 <static7@qq.com>
 * Date: 2017/9/18 12:42
 */

namespace app\admin\validate;


use think\Validate;

class AuthRule extends Validate
{
    protected $rule = [
        'name' => "require",
        'title' => "require",
        'type' => "require",
        'module' => "require",
    ];
    protected $message = [
        'name.require' => '规则唯一英文标识不能为空',
        'title.require' => '规则名称不能为空',
        'type.require' => '节点类型不能为空',
        'module.require' => '模块名称不能为空',
    ];
}