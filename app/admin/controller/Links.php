<?php
/**
 * Description of Links.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/11/9 11:24
 */

namespace app\admin\controller;


use app\admin\service\LinksService;
use app\admin\traits\{
    Jump,Admin
};

class Links
{
    use Jump,Admin;

    /**
     * 友情链接
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function index()
    {
       return $this->setView();
    }

    /**
     * 友情链接
     * @author staitc7 <static7@qq.com>
     * @param LinksService $linksService
     * @return mixed
     * @throws DbException
     */
    public function linksInterface(LinksService $linksService)
    {
        $param=$this->app->request->params([
            'status'=>['status','='],
            'title'=>['title','like'],
        ]);
        $data=$linksService->getListPage($param);
        return $this->layuiJson($data->toArray());
    }

    /**
     * 编辑导航
     * @param LinksService $linksService
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */

    public function edit(LinksService $linksService)
    {
        $param=$this->app->request->only(['id'=>0]);
        $info =$linksService->edit($param);
        return $this->setView(['info'=> $info ?? null,'metaTitle' => '友情链接详情']);
    }

    /**
     * 设置状态
     * @author staitc7 <static7@qq.com>
     * @param LinksService $linksService
     * @return mixed
     */
    public function setStatus(LinksService $linksService)
    {
        $param = $this->app->request->param();
        $info  = $linksService->setStatus($param);
        if ($info === false) {
            return $this->error($linksService->getError());
        }
        $this->app->cache->rm('links_list');
        return $this->success('更新成功');
    }

    /**
     * 更新或者添加
     * @param LinksService $linksService
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function renew(LinksService $linksService)
    {
        $param = $this->app->request->param();
        $info  = $linksService->renew($param);
        if ($info === false) {
            return $this->error($linksService->getError());
        }
        $this->app->cache->delete('links_list');
        return $this->success('操作成功', 'Links/index');
    }
}