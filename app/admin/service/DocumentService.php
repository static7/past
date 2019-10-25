<?php
/**
 * Description of DocumentService.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/10/9 22:07
 */

namespace app\admin\service;


use app\admin\repository\DocumentRepository;
use app\admin\traits\BaseService;
use app\facade\UserInfo;
use think\facade\{Log, Session};

class DocumentService
{
    use BaseService;

    //文章通用条件
    protected $fields = [
        'id',
        'user_id',
        'title',
        'category_id',
        'create_time',
        'update_time',
        'description',
        'view',
        'status',
        'check'
    ];

    public function __construct()
    {
        return $this->repository = new DocumentRepository();
    }

    /**
     * 通过类别获取文档计数
     * @param int|null $categoryId
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getByCategoryIdToDocumentCount(?int $categoryId = 0)
    {
        if ($categoryId === 0) {
            return 0;
        }
        return $this->repository->getByCategoryIdToDocumentCount($categoryId);
    }


    /**
     * 获取列表
     * @param array|null $param
     * @return mixed
     * @throws DbException
     * @author staitc7 <static7@qq.com>
     */
    public function getDocumentListPage(?array $param = [])
    {
        $map = array_merge([
            'draft'   => ['draft', '=', 0],
//            'check'   => ['check', '=', 1],
            'status'  => ['status', '>=', 0],
            'user_id' => ['user_id', '=', UserInfo::getUserId()],
        ], $param);
        return $result = $this->repository->getListPage($map, $this->fields, ['create_time' => 'desc'], ['categoryTitle']);
    }

    /**
     * 审核文章
     * @param array|null $param
     * @return mixed
     * @throws DbException
     * @author staitc7 <static7@qq.com>
     */
    public function getCreativeWorkListPage(?array $param = [])
    {
        $map = array_merge([
            'user_id' => ['user_id', '=', UserInfo::getUserId()],
            'status'  => ['status', '>=', 0]
        ], $param);
        return $result = $this->repository->getListPage($map, $this->fields, ['create_time' => 'desc'], [
            'categoryTitle',
            'memberNickname'
        ]);
    }

    /**
     * 编辑文章
     * @param array|null $param
     * @return mixed
     * @throws DbException
     * @author staitc7 <static7@qq.com>
     */
    public function edit(?array $param = [])
    {
        if (isset($param['category_id']) === false || (int)$param['category_id'] === 0) {
            return $this->error('分类错误');
        }
        if (isset($param['id']) === false) {
            return null;
        }
        return $this->repository->first((int)$param['id']);
    }

    /**
     * 物理删除
     * @param array|null $param
     * @return mixed
     * @throws DbException
     * @author staitc7 <static7@qq.com>
     */
    public function physicalDelete(?array $param = [])
    {
        if (isset($param['id']) === false || empty($param['id']) === true) {
            return $this->error('请选择要操作的数据');
        }
        if (preg_match('/\,+/', $param['id'])) {
            $data = explode(',', $param['id']);
        } else {
            $data = $param['id'];
        }
        $status = null;
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $status = $this->repository->physicalDelete($v);
                if ($status === false) {
                    break;
                }
            }
        } else {
            $status = $this->repository->physicalDelete($data);
        }
        return $status;
    }

    /**
     * 自动保存
     * @param array|null $param
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function autoSave(?array $param = [])
    {
        //草稿 字段设置为1
        $param['draft'] = 1;
        return $this->repository->renew($param);
    }

    /**
     * 提交审核
     * @param array|null $param
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function censor(?array $param = [])
    {
        $map = $this->primaryKey($param['id']);
        return $this->repository->updateField($map, ['draft' => 0]);
    }

    /**
     * 通过审核
     * @param array|null $param
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function approved(?array $param = [])
    {
        $map = $this->primaryKey($param['id']);
        return $this->repository->updateField($map, ['check' => 1]);
    }

    /**
     * 移动分类
     * @param array|null $param
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function move(?array $param = [])
    {
        if (isset($param['category_id']) === false || (int)$param['category_id'] === 0) {
            return $this->error('分类参数错误');
        }
        if (empty($param['id']) === true) {
            return $this->error('请选择要移动的文章！');
        }
        $data = explode(',', $param['id']);
        Log::record($data, 'move');
        Session::set('moveArticle', $data, 'move_article');
        Session::delete('copyArticle', 'copy_article');
        return true;
    }

    /**
     * 移动分类
     * @param array|null $param
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function copy(?array $param = [])
    {
        if (isset($param['category_id']) === false || (int)$param['category_id'] === 0) {
            return $this->error('分类参数错误');
        }
        if (empty($param['id']) === true) {
            return $this->error('请选择要移动的文章！');
        }
        $data = explode(',', $param['id']);
        Log::record($data, 'move');
        Session::set('copyArticle', $data, 'copy_article');
        Session::delete('moveArticle', 'move_article');
        return true;
    }

    /**
     * 粘贴文章
     * @param array|null $param
     * @return mixed
     * @throws DbException
     * @author staitc7 <static7@qq.com>
     */
    public function paste(?array $param = [])
    {
        if (isset($param['category_id']) === false || (int)$param['category_id'] === 0) {
            return $this->error('请选择要粘贴到的分类!');
        }
        $moveDocument = Session::get('moveArticle', []);
        $copyDocument = Session::get('copyArticle', []);
        if (empty($moveDocument) === true && empty($copyDocument) === true) {
            return $this->error('请选择文章!');
        }
        if (empty($moveDocument) === false) {
            return $this->moveDocument($moveDocument, (int)$param['category_id']);
        }
        if (empty($copyDocument) === false) {
            return $this->copyDocument($copyDocument, (int)$param['category_id']);
        }
        return true;
    }

    /**
     * 移动文章
     * @param array|null $param
     * @param int|null   $categoryId 分类ID
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function moveDocument(?array $param = [], ?int $categoryId = 0)
    {
        $result = $this->repository->updateField([['id', 'in', $param]], ['category_id' => $categoryId]);
        if ($result === false) {
            return $this->error($this->repository->getError());
        }
        Session::delete('moveArticle');
        return true;
    }

    /**
     * 粘贴文章
     * @param array|null $params
     * @param int|null   $categoryId 分类ID
     * @return mixed
     * @throws DbException
     * @author staitc7 <static7@qq.com>
     */
    protected function copyDocument(?array $params = [], ?int $categoryId = 0)
    {
        if (empty($params) === true) {
            return true;
        }
        foreach ($params as $k => $v) {
            $document = $this->repository->first($v);
            $document->documentArticle;
            $data = $document->toArray();
            if (empty($data['documentArticle']) === false) {
                foreach ($data['documentArticle'] as $key => $value) {
                    $data[ $key ] = $value;
                }
            }
            $data['category_id'] = $categoryId;
            unset($data['id'], $data['update_time'], $data['documentArticle'], $data['document_id']);
            $this->repository->renew($data);
        }
        Session::delete('copyArticle');
        return true;
    }
}