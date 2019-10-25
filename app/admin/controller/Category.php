<?php
/**
 * Description of Category.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/10/9 16:47
 */

namespace app\admin\controller;


use app\admin\service\{
    CategoryService,FileService
};
use app\admin\traits\{
    Admin,Jump
};
use Exception;

class Category
{
    use Jump,Admin;

    /**
     * 分类管理
     * @return mixed
     * @throws Exception
     * @author staitc7 <static7@qq.com>
     */
    public function index()
    {
        return $this->setView(['metaTitle' => '文章列表']);
    }

    /**
     * 显示分类树，仅支持内部调
     * @param CategoryService $categoryService
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author staitc7 <static7@qq.com>
     */
    public function treeInterface(CategoryService $categoryService) {
        $data=$categoryService->getTree();
        return $this->json($data);
    }

    /**
     * 新增分类
     * @param CategoryService $categoryService
     * @return mixed
     * @throws Exception
     * @author staitc7 <static7@qq.com>
     */
    public function add(CategoryService $categoryService)
    {
        $param = $this->app->request->param();
        $info  = $categoryService->getCategoryPid($param);
        return $this->setView(['metaTitle' => '分类详情', 'category' => $info],'edit');
    }


    /**
     * 编辑分类
     * @param CategoryService $categoryService
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function edit(CategoryService $categoryService)
    {
        $param = $this->app->request->param();
        $data = $categoryService->edit($param);
        if (empty($data) === false){
            $category=$categoryService->getCategoryPid($data->toArray());
        }
        return $this->setView([
            'info'=>$data ?? null,
            'category'=>$category ?? null,
            'metaTitle' => '编辑分类'
        ],'edit');
    }


    /**
     * 分类更新或者添加
     * @param CategoryService $categoryService
     * @return \think\Response
     * @author staitc7 <static7@qq.com>
     */
    public function renew(CategoryService $categoryService)
    {
        $param=$this->app->request->param();
        $data= $categoryService->renew($param);
        if ($data===false) {
            return $this->error($categoryService->getError());
        }
        $this->app->cache->delete('home_side');
        $this->app->cache->delete('category_list', null); //更新分类缓存
        $this->app->session->delete('admin_category_menu', 'category_menu');
        return $this->success('操作成功','Category/index');
    }

    /**
     * 快捷更新
     * @param CategoryService $categoryService
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function renewFast(CategoryService $categoryService)
    {
        $param=$this->app->request->param();
        $data= $categoryService->renew($param,'Edit');
        if ($data===false) {
            return $this->error($categoryService->getError());
        }
        $this->app->cache->delete('home_side');
        $this->app->cache->delete('category_list', null); //更新分类缓存
        $this->app->session->clear('category_menu');
        return $this->success('操作成功');
    }

    /**
     * 设置状态
     * @author staitc7 <static7@qq.com>
     * @param CategoryService $categoryService
     * @return mixed
     */
    public function setStatus(CategoryService $categoryService)
    {
        $param = $this->app->request->param();
        $info  = $categoryService->setStatus($param);
        if ($info === false) {
            return $this->error($categoryService->getError());
        }
        return $this->success('更新成功');
    }

    /**
     * 分类图片
     * @param FileService $fileService
     * @return \think\Response
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function categoryPicture(FileService $fileService) {
        $param=$this->app->request->only(['field'=>'categoryPicture']);
        $data=$fileService->pictureUpload($param);
        if ($data === false){
            return $this->error($fileService->getError());
        }
        $this->success('上传成功!', '', $data);
    }

    /**
     * 移动分类
     * @param CategoryService $categoryService
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function move(CategoryService $categoryService) {
        $param=$this->app->request->only(['id'=>0]);
        $data=$categoryService->move($param);//获取分类
        array_unshift($data, ['id' => 0, 'title' => '根分类']);
        return $this->setView(['id' => $param['id'], 'list' => $data ?? null],'move',false);
    }

    /**
     * 更新移动分类
     * @param CategoryService $categoryService
     * @return \think\Response
     * @throws DbException
     * @author staitc7 <static7@qq.com>
     */
    public function moveRenew(CategoryService $categoryService) {
        $param=$this->app->request->only(['id'=>0,'pid'=>0]);
        $categoryService->moveRenew($param);
        $this->app->session->delete('admin_category_menu', 'category_menu');
        return $this->success('移动成功');

    }

    /**
     * 分类删除
     * @param CategoryService $categoryService
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author staitc7 <static7@qq.com>
     */
    public function remove(CategoryService $categoryService) {
        $param=$this->app->request->param();
        $categoryService->remove($param);
        $this->app->session->delete('admin_category_menu', 'category_menu');
        return $this->success('删除成功');
    }
}