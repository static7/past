<?php
/**
 * Description of UcenterMember.php.
 * User: static7 <static7@qq.com>
 * Date: 2017/9/20 22:20
 */

namespace app\admin\validate;


use think\Validate;

class UserCenter extends Validate
{
    protected $rule    = [
        'username' => 'alphaDash|require|length:6,30|unique:user_center,username',
        'repassword'=>'require',
        'password' => 'require|min:6|confirm:repassword',
        'email' => "unique:user_center,email|email"
    ];
    protected $message = [
        'username.requier' => '用户名不能为空',
        'username.unique' => '用户名已经被注册',
        'username.length' => '用户名在6-20个字符之间',
        'username.alphaDash' => '用户名为字母和数字，下划线"_"及破折号"-"',
        'password.require' => '密码不能为空',
        'password.min' => '密码最低6个字符',
        'password.confirm' => '两次密码不相符',
        'repassword.require'=>'确认密码不能为空',
        'email' => '邮箱格式错误',
        'email.unique' => '邮箱已经被注册过',
        'id.require'=>'用户ID不能为空'
    ];

    // edit 验证场景定义
    public function sceneEdit()
    {
        return $this->only(['password','repassword']);
    }

    // Oauth 验证场景定义
    public function sceneOauth()
    {
        return $this->only(['email']);
    }

    /**
     * 更新密码验证场景
     * @author staitc7 <static7@qq.com>
     * @return mixed
     */
    public function scenePassword()
    {
        return $this->only(['password','repassword'])->append('id',['require']);
    }
}