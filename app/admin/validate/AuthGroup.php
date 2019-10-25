<?php
/**
 * Description of AuthGroup.php.
 * User: static7 <static7@qq.com>
 * Date: 2017/9/18 12:42
 */

namespace app\admin\validate;


use think\Validate;

class AuthGroup extends Validate
{
    protected $rule = [
        'title' => "require|max:20"
    ];
    protected $message = [
        'title.require' => '标题不能为空',
        'title.max' => '标题最多不能超过20个字符',
        'id.require' =>'权限组ID不能为空',
        'rules.require' =>'权限规则不能为空',
    ];

    /**
     * 更新验证场景
     */
    public function sceneUpdateAuthorization()
    {
        return $this->only(['id','rules'])
            ->remove('title', ['require', 'max'])
            ->append('id', 'require')
            ->append('rules', 'require');
    }
}