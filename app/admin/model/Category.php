<?php
/**
 * Description of Category.php.
 * User: static7 <static7@qq.com>
 * Date: 2017-08-03 17:26
 */

namespace app\admin\model;

use think\Model;
use app\admin\traits\Models;
use think\facade\{Request, Cache, App};

class Category extends Model
{
    use Models;
    protected $autoWriteTimestamp = true; //自动写入创建和更新的时间戳字段
    protected $insert             = ['status' => 1,'meta_title'=>''];
    protected $update             = ['title'=>'','meta_title'=>''];

    /**
     * 相对关联
     * @author staitc7 <static7@qq.com>
     */

    public function document()
    {
        return $this->belongsTo(Document::class, 'category_id','id');
    }

    /* =================自动完成===================== */

    /**
     * 自动完成Name
     * @param $value
     * @return string
     */
    public function setNameAttr($value)
    {
        return $value ? strtolower($value) : '';
    }

    /**
     * 自动完成Title
     * @param $value
     * @return string
     */
    public function setTitleAttr($value)
    {
        return $value ? htmlspecialchars($value) : '';
    }

    /**
     * 自动完成Description
     * @param $value
     * @return string
     */
    public function setDescriptionAttr($value)
    {
        return $value ? htmlspecialchars($value) : '';
    }

    /**
     * 自动完成MetaTitle
     * @param $value
     * @return string
     */
    public function setMetaTitleAttr($value)
    {
        return $value ? htmlspecialchars($value) : '';
    }
}