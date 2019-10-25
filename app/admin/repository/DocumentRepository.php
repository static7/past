<?php
/**
 * Description of DocumentRepository.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/10/9 22:08
 */

namespace app\admin\repository;


use app\admin\model\Document;
use app\admin\traits\BaseRepository;

class DocumentRepository
{
    use BaseRepository;

    private $model;

    public function __construct()
    {
        $this->model=new Document();
    }


    /**
     * 通过类别获取文档计数
     * @author staitc7 <static7@qq.com>
     * @param int|null $categoryId 分类ID
     * @return mixed
     */
    public function getByCategoryIdToDocumentCount(?int $categoryId)
    {
        return $this->model->where('category_id','=', $categoryId)->count('id');
    }

    /**
     * 物理删除
     * @author staitc7 <static7@qq.com>
     * @param int|null $id 文章ID
     * @return mixed
     * @throws DbException
     */
    public function physicalDelete(?int $id)
    {
        $object=$this->model->get($id,'documentArticle');
        $object->delete();
        $object->documentArticle->delete();
    }


}