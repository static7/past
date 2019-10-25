<?php
/**
 * Description of Document.php.
 * User: static7 <static7@qq.com>
 * Date: 2017/10/8 22:11
 */

namespace app\admin\validate;


use think\facade\Log;
use think\Validate;

class Document extends Validate
{
    protected $rule = [
        'title' => "require|max:80",
        'name' => "alphaDash|unique:document,name",
        'description' => 'max:200',
        'level' => 'number',
        'category_id' => 'require|checkCategoryAllowPublish:true',
        'type' => 'number',
    ];
    protected $message = [
        'title.require' => '标题不能为空',
        'title.max' => '标题不能超过80个字符',
        'name.unique' => '标识已经存在',
        'name.alphaDash' => '标识只能为字母和数字，下划线_及破折号-',
        'description.max' => '描述不能超过200个字符',
        'level.number' => '优先级只能填整数',
        'category_id.require' => '分类不能为空',
        'category_id.checkCategoryAllowPublish'=>'该分类不允许发布内容',
        'type.number' => '内容类型不正确',
    ];

    /**
     * 分类验证是否允许发布
     * @author staitc7 <static7@qq.com>
     * @param $value
     * @return mixed
     */
    protected function checkCategoryAllowPublish($value)
    {
        return check_category((int)$value, 'allow_publish') ? true : false;
    }
}