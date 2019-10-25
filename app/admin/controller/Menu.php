<?php
/**
 * Description of Menu.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/6/22 22:52
 */

namespace app\admin\controller;


use app\admin\service\MemberService;
use app\admin\service\MenuService;
use app\admin\traits\{
    Admin,Jump
};
use app\facade\Parameter;
use app\facade\Tree;

class Menu
{
    use Jump, Admin;

    /**
     * 菜单首页
     * @param MenuService $menuService
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function index(MenuService $menuService)
    {
        $param  = $this->app->request->only(['pid'=>0, 'module'=>$this->app->http->getName()]);
        $father = $menuService->father($param['pid']);
        return $this->setView([
            'pid' => $param['pid'],
            'module' => $param['module'],
            'father' => $father ?: null,
            'mainId' =>(int)$father['main_id'] > 0 ? (int)$father['main_id'] : (int)$param['pid'],
            'metaTitle' => '菜单列表'
        ]);
    }

    /**
     * 菜单列表
     * @author staitc7 <static7@qq.com>
     * @param MenuService $menuService
     * @return mixed
     */
    public function menuInterface(MenuService $menuService)
    {
        $param = $this->app->request->params([
            'pid'=>['pid','=']
        ]);
        $data  = $menuService->getListPage($param);
        return $this->layuiJson($data->toArray());
    }

    /**
     * 添加菜单
     * @param MenuService $menuService
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function add(MenuService $menuService)
    {
        $param = $this->app->request->params(null, true);
        //获取所有的菜单
        $menu  = $menuService->menuAll([
            'module'=>$param['module']
        ]);
        if (empty($menu) === false) {
            $tree = Tree::toFormatTree($menu->toArray());
        }
        return $this->setView([
            'menus' => $tree ?? null,
            'pid' => $this->app->request->param('pid', 0),
            'main_id' => $this->app->request->param('main_id', 0),
            'metaTitle' => '添加菜单'
        ]);
    }

    /**
     * 用户更新或者添加菜单
     * @param MenuService $menuService
     * @return \think\Response
     * @throws \think\exception\HttpResponseException
     * @author staitc7 <static7@qq.com>
     */

    public function renew(MenuService $menuService)
    {
        $param = $this->app->request->param();
        $info  = $menuService->renew($param);
        if ($info === false) {
            return $this->error($menuService->getError());
        }
        $this->app->cache->tag(['admin_menu'])->clear();
        $url = $this->app->route->buildUrl('Menu/index', ['pid' => $info['pid'] ?? 0, 'module' => $info['module'] ?? '']);
        return $this->success('操作成功', (string)$url);
    }

    /**
     * 菜单详情
     * @param MenuService $menuService
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */

    public function edit(MenuService $menuService)
    {
        $param=$this->app->request->params(null,true);
        $info  = $menuService->edit($param);
        $menu  = $menuService->menuAll([
            'module'=>$param['module']
        ]); //获取所有的菜单
        if (empty($menu) === false) {
            $tree = Tree::toFormatTree($menu->toArray());
        }
        return $this->setView(['info' => $info ?? null, 'menus' => $tree ?? null, 'metaTitle' => '菜单详情']);
    }

    /**
     * 更新排序
     * @param MenuService $menuService
     * @return mixed
     * @throws \think\exception\HttpResponseException
     * @author staitc7 <static7@qq.com>
     */
    public function currentSort(MenuService $menuService)
    {
        $param = $this->app->request->param();
        $info  = $menuService->currentSort($param);
        if ($info === false) {
            return $this->error($menuService->getError());
        }
        $this->app->cache->tag(['admin_menu'])->clear();
        return $this->success('更新成功');
    }

    /**
     * 公用的更新方法
     * @param MenuService $menuService
     * @return \think\Response
     * @throws \think\exception\HttpResponseException
     * @author staitc7 <static7@qq.com>
     */

    public function toogle(MenuService $menuService)
    {
        $param = $this->app->request->param();
        $info  = $menuService->toogle($param);
        if ($info === false) {
            return $this->error($menuService->getError());
        }
        $this->app->cache->tag(['admin_menu'])->clear();
        return $this->success('更新成功');
    }


    /**
     * 设置状态
     * @param MenuService $menuService
     * @return mixed
     * @throws \think\exception\HttpResponseException
     * @author staitc7 <static7@qq.com>
     */
    public function setStatus(MenuService $menuService)
    {
        $param = $this->app->request->param();
        $info  = $menuService->setStatus($param);
        if ($info === false) {
            return $this->error($menuService->getError());
        }
        $this->app->cache->tag(['admin_menu'])->clear();
        return $this->success('更新成功');
    }


    /**
     * 获取控制器文件名称
     * @param MenuService $menuService
     * @return mixed
     * @throws \think\exception\HttpResponseException
     * @author staitc7 <static7@qq.com>
     */
    public function getControllerFileName(MenuService $menuService)
    {
        $data=$menuService->getControllerFileName();
        return $this->success('成功','',$data);
    }

    /**
     * 控制器获取方法
     * @param MenuService $menuService
     * @return mixed
     * @throws \ReflectionException
     * @author staitc7 <static7@qq.com>
     */
    public function getFunctionName(MenuService $menuService)
    {
        $param=$this->app->request->only(['controller'=>'']);
        $data=$menuService->getFunctionName($param);
        return $this->success('成功','',$data);
    }

}