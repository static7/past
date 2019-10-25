<?php
/**
 * Description of CategoryService.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/10/9 17:36
 */

namespace app\admin\service;


use app\admin\repository\CategoryRepository;
use app\admin\traits\BaseService;
use think\facade\{Cache, Request, Session, Log};

class CategoryService
{
    use BaseService;

    public function __construct()
    {
        $this->repository = new CategoryRepository();
    }

    /**
     * 获取树
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author staitc7 <static7@qq.com>
     */
    public function getTree()
    {
        return $this->repository->getCategoryList([
            'id',
            'name',
            'title',
            'sort',
            'pid',
            'allow_publish',
            'status',
            'level'
        ]);
    }

    /**
     * 获取分类父级详情
     * @param array|null $param
     * @return mixed
     * @throws DbException
     * @author staitc7 <static7@qq.com>
     */
    public function getCategoryPid(?array $param = [])
    {
        if (isset($param['pid']) === false || (int)$param['pid'] === 0) {
            return null;
        }
        return $this->repository->first((int)$param['pid'], ['id', 'name', 'title', 'level']);
    }

    /**
     * 获取编辑详情
     * @param array|null $param
     * @return mixed
     * @throws DbException
     * @author staitc7 <static7@qq.com>
     */
    public function edit(?array $param = [])
    {
        if (isset($param['id']) === false) {
            return $this->error('分类ID不能为空');
        }
        return $this->repository->first((int)$param['id']);
    }


    /**
     * 移除
     * @param array|null $param
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author staitc7 <static7@qq.com>
     */
    public function remove(?array $param = [])
    {
        if (isset($param['id']) === false || (int)$param['id'] === 0) {
            return $this->error('分类id错误');
        }
        //检查子分类
        $subset = $this->repository->subset($param['id'], ['id']);
        if (empty($subset['id']) === false) {
            return $this->error('请先删除该分类下的子分类');
        }
        //检测分类下的文章（包含回收站）
        $count = $this->getByCategoryIdToDocumentCount($param['id']);
        if ($count > 0) {
            return $this->error('请先删除该分类下的文章（包含回收站）');
        }
        $info = $this->repository->remove($param);
        if ($info === false) {
            return $this->error('删除失败');
        }
        return $info;
    }

    /**
     * 移动分类
     * @param array|null $param
     * @return mixed
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     * @author staitc7 <static7@qq.com>
     */
    public function move(?array $param = [])
    {
        if ((int)$param['id'] === 0) {
            return $this->error('分类id错误');
        }
        $map    = [
            ['status', '=', 1],
            ['level', '<', 3],
            ['id', '<>', $param['id']],
        ];
        $object = $this->repository->getList($map, ['id', 'pid', 'title']);
        return $object->isEmpty() ? [] : $object->toArray();
    }

    /**
     * 更新移动分类
     * @param array|null $param
     * @return mixed
     * @throws DbException
     * @author staitc7 <static7@qq.com>
     */
    public function moveRenew(?array $param = [])
    {
        if ((int)$param['id'] < 0) {
            return $this->error('分类id错误');
        }
        if ((int)$param['pid'] < 0) {
            return $this->error('参数错误');
        }

        if ((int)$param['pid'] === 0) {
            $level = 1;
        } else {
            $pidLevel = $this->repository->first((int)$param['pid'], ['level']);
            $level    = (int)$pidLevel['level'] + 1;
        }
        $map  = [
            ['id', '=', (int)$param['id']]
        ];
        $data = $this->repository->moveRenew($map, ['pid' => $param['pid'], 'level' => $level]);
        if ($data === false) {
            return $this->error('移动失败');
        }
        return true;
    }


    /**
     * 获取分类菜单
     * @param array|null $param
     * @return mixed
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     * @author staitc7 <static7@qq.com>
     */
    public function categoryMenu(?array $param = [])
    {
        $categoryId = $param['category_id'] ?? 0;
        $category   = Cache::get('admin_category_menu',[]);
        if (empty($category) === true) {
            $categoryList = $this->repository->getList([['status', '=', 1]], ['id', 'title', 'pid', 'allow_publish'], ['pid' => 'desc', 'sort' => 'desc']);
            $category     = list_to_tree($categoryList->toArray());        //生成分类树
            Cache::tag(['category'])->set('admin_category_menu', $category);
            unset($categoryList);
        }
        //是否展开分类
        if (in_the_array(Request::action(true), ['recycle', 'examine', 'draftbox', 'mydocument'])) {
            $hideCategory = false;
        } else {
            $hideCategory = true;
        }
        foreach ($category as $key => &$value) {
            ((int)$categoryId === (int)$value['id'] && $hideCategory) && $value['active'] = 'layui-nav-itemed';
            if ((int)$categoryId == (int)$value['id']) {
                $value['expansion'] = 'layui-this';
            }
            if (empty($value['_child']) === true) {
                continue;
            }
            $isChild = false;
            foreach ($value['_child'] as $ka => &$va) {
                if (empty($va['_child']) === false) {
                    foreach ($va['_child'] as $k => &$v) {
                        if ((int)$v['id'] === (int)$categoryId) {
                            $v['active'] = 'layui-this';
                            $isChild     = true;
                        }
                    }
                }
                if ((int)$va['id'] === (int)$categoryId || $isChild === true) {
                    $isChild = false;
                    if ($hideCategory === true) {
                        $value['active'] = 'layui-nav-itemed';
                        $va['active']    = ((int)$va['id'] === (int)$categoryId) ? 'layui-this' : 'layui-nav-itemed';
                    }
                }
            }
        }
        return $category;
    }

    /**
     * 检测分类下的文章
     * @param int $categoryId 分类ID
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getByCategoryIdToDocumentCount(?int $categoryId = 0)
    {
        if ($categoryId === 0) {
            return 0;
        }
        return (new DocumentService())->getByCategoryIdToDocumentCount($categoryId);
    }
}