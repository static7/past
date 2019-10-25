<?php
/**
 * Description of DocumentArticle.php.
 * User: static7 <static7@qq.com>
 * Date: 2017/10/8 15:58
 */

namespace app\admin\model;

use think\Model;
use app\admin\traits\Models;

class DocumentArticle extends Model
{
    use Models;
    protected $pk='document_id';
    protected $autoWriteTimestamp = false;

    /**
     * 相对关联
     * @author staitc7 <static7@qq.com>
     * @return mixed
     */
    public function document()
    {
        return $this->belongsTo(Document::class,'document_id','id');
    }
}