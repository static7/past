<?php
/**
 * Description of Category.php.
 * User: static7 <static7@qq.com>
 * Date: 2017/9/18 11:58
 */

namespace app\admin\validate;


use think\Validate;

class Category extends Validate
{
    protected $rule = [
        'title' => "require|max:30",
        'name' => "require|alpha|unique:category",
        'meta_title' => 'max:50',
        'keywords' => 'max:200',
        'description' => 'max:200'
    ];
    protected $message = [
        'title.require' => '分类名称不能为空',
        'title.max' => '分类名称最多不能超过30个字符',
        'name.require' => '分类标识不能为空',
        'name.unique' => '分类标识已经存在',
        'name.alpha' => '行为标识只能为字母',
        'meta_title.max' => '网页标题不能超过50个字符',
        'keywords.max' => '网页关键字不能超过200个字符',
        'description.max' => '网页描述不能超过200个字符'
    ];

    // edit 验证场景定义
    public function sceneEdit()
    {
        return $this->only(['title']);
    }

    /**
     * 移动分类验证
     * @author staitc7 <static7@qq.com>
     * @return mixed
     */
    public function sceneMove()
    {
        return ;
    }
}