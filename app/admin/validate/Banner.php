<?php
/**
 * Description of Banner.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/11/24 12:20
 */

namespace app\admin\validate;


use think\Validate;

class Banner extends Validate
{
    protected $rule = [
        'title' => "require|max:200",
        'position' => "require",
        'picture' => 'require',
        'url' => 'require',
    ];
    protected $message = [
        'title.require' => '分类名称不能为空',
        'title.max' => '分类名称最多不能超过200个字符',
        'position.require' => '位置不能为空',
        'picture.require' => '图片不能为空',
        'url.require' => '链接不能为空',
    ];
}