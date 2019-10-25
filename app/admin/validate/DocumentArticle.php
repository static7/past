<?php
/**
 * Description of DocumentArticle.php.
 * User: static7 <static7@qq.com>
 * Date: 2017/10/8 22:10
 */

namespace app\admin\validate;


use think\Validate;

class DocumentArticle extends Validate
{
    protected $rule = [
        'content' => "require",
        'bookmark' => 'number',
    ];
    protected $message = [
        'content.require' => '内容不能为空',
        'bookmark.number' => '收藏数只能填整数',
    ];
}