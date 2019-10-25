<?php
/**
 * Description of Navigation.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/11/7 11:57
 */

namespace app\admin\controller;


use app\admin\service\NavigationService;
use app\admin\traits\{
    Admin,Jump
};
use app\facade\Parameter;

class Navigation
{
    use Jump,Admin;

    /**
     * 导航
     * @param NavigationService $navigationService
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function index(NavigationService $navigationService)
    {
        $param=$this->app->request->only([
            'pid'=>0,
        ]);
        $father= $navigationService->father($param);
        $value   = [
            'pid' => $param['pid'],
            'father' => $father ?: null,
            'metaTitle' => '导航列表'
        ];
        return $this->setView($value);
    }

    /**
     * 导航接口
     * @author staitc7 <static7@qq.com>
     * @param NavigationService $navigationService
     * @return mixed
     */
    public function navigationInterface(NavigationService $navigationService)
    {
        $param = $this->app->request->params([
            'pid'=>['pid','=']
        ]);
        $data=$navigationService->getNavigationList($param);
        return $this->layuiJson($data->toArray());
    }

    /**
     * 设置状态
     * @param NavigationService $navigationService
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function setStatus(NavigationService $navigationService)
    {
        $param = $this->app->request->param();
        $info  = $navigationService->setStatus($param);
        if ($info === false) {
            return $this->error($navigationService->getError());
        }
        $this->app->cache->delete('navigation_list');
        return $this->success('更新成功');
    }

    /**
     * 用户更新或者添加导航
     * @param NavigationService $navigationService
     * @return \think\Response
     * @author staitc7 <static7@qq.com>
     */
    public function renew(NavigationService $navigationService)
    {
        $param = $this->app->request->param();
        $info  = $navigationService->renew($param);
        if ($info === false) {
            return $this->error($navigationService->getError());
        }
        $this->app->cache->delete('navigation_list');
        $url = $this->app->route->buildUrl('Navigation/index', ['pid' => $info['pid'] ?? 0, 'module' => $info['module'] ?? '']);
        return $this->success('操作成功', $url ?? '');
    }

    /**
     * 新增导航
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function add()
    {
        $param=$this->app->request->only([
            'pid'=>0,
            'module'=>''
        ]);
        $param['metaTitle']='新增导航';
        return $this->setView($param);
    }

    /**
     * 编辑导航
     * @param NavigationService $navigationService
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function edit(NavigationService $navigationService)
    {
        $param=$this->app->request->only([
            'id'=>0,
        ]);
        $data=$navigationService->edit($param);
        return $this->setView(['info' => $data ?: null, 'metaTitle' => '编辑导航']);
    }
}