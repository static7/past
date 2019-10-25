<?php
/**
 * Description of Menu.php.
 * User: static7 <static7@qq.com>
 * Date: 2017/9/18 12:45
 */

namespace app\admin\validate;


use think\Validate;

class Menu extends Validate
{
    protected $rule = [
        'title' => "require|max:30",
        'url' => "require"
    ];
    protected $message = [
        'title.require' => '标题不能为空',
        'title.max' => '标题最多不能超过30个字符',
        'url.require' => '链接不能为空',
        'url.unique'=>'链接不能重复'
    ];

    /**
     * 编辑验证
     * @author staitc7 <static7@qq.com>
     * @return mixed
     */
    public function sceneEdit()
    {
        return $this->only(['title','url'])
            ->remove('url','unique');
    }
}