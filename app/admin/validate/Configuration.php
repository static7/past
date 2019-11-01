<?php
/**
 * Description of Deploy.php.
 * User: static7 <static7@qq.com>
 * Date: 2017/9/18 12:43
 */

namespace app\admin\validate;


use think\Validate;

class Configuration extends Validate
{
    protected $rule = [
        'title' => "require|max:30",
        'name' => "require|unique:configuration,name"
    ];
    protected $message = [
        'title.require' => '标题不能为空',
        'title.max' => '标题最多不能超过30个字符',
        'name.require' => '配置名称不能为空',
        'name.unique' => '配置名称已经存在'
    ];

    // edit 验证场景定义
    public function sceneEdit()
    {
        return $this->only(['name','value']);
    }
}