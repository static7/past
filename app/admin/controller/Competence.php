<?php
/**
 * Description of competence.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/7/21 10:48
 */

namespace app\admin\controller;

use app\admin\service\{AuthGroupAccessService, AuthGroupService, AuthRuleService};
use app\admin\traits\{
    Admin,Jump
};
use Exception;
use app\facade\Tree;
use think\facade\View;

class Competence
{
    use Jump,Admin;

    /**
     * 权限组
     * @return mixed
     * @throws Exception
     * @author staitc7 <static7@qq.com>
     */
    public function index()
    {
        $param  = $this->app->request->only(['pid'=>0]);
        return $this->setView([
            'pid' => $param['pid'],
            'metaTitle' => '权限管理'
        ]);
    }

    /**
     * 权限组
     * @param AuthGroupService $authGroupService
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function groupInterface(AuthGroupService $authGroupService)
    {
        $data=$authGroupService->getGroupList($this->app->request->param());
        return $this->result($data->toArray(),0,'成功');
    }

    /**
     * 添加用户组
     * @param AuthGroupService $authGroupService
     * @return mixed
     * @throws Exception
     * @author staitc7 <static7@qq.com>
     */
    public function add(AuthGroupService $authGroupService)
    {
        $param = $this->app->request->param();
        $group  = $authGroupService->groupAll($param); //获取所有的菜单
        if (empty($group)===false) {
            $tree = Tree::toFormatTree($group->toArray());
        }
        unset($group);
        return $this->setView([
            'groupTree' => $tree ?? null,
            'pid' => $param['id'] ?? 0,
            'mainId' => $param['main_id'] ?? 0,
            'metaTitle' => '添加菜单'
        ]);
    }

    /**
     * 用户组授权用户
     * @param AuthGroupService $authGroupService
     * @return mixed
     * @throws Exception
     * @author static7
     */
    public function user(AuthGroupService $authGroupService) {
        $param=$this->app->request->param();
        $authGroup = $authGroupService->getAuthGroupList($param);
        return $this->setView([
            'auth_group' => $authGroup,
            'metaTitle'=> '用户授权'
        ]);
    }

    /**
     * 权限组授权给用户
     * @author staitc7 <static7@qq.com>
     * @param AuthGroupAccessService $authGroupAccessService
     * @return mixed
     * @throws DbException
     */
    public function authAccessInterface(AuthGroupAccessService $authGroupAccessService)
    {
        $param=$this->app->request->param();
        $data=$authGroupAccessService->getAuthGroupAccessList($param);
        return $this->layuiJson($data->toArray());
    }


    /**
     * 将用户添加到用户组,入参user_id,group_id
     * @param AuthGroupAccessService $authGroupAccessService
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function addUserToGroup(AuthGroupAccessService $authGroupAccessService)
    {
        $param=$this->app->request->param();
        $data = $authGroupAccessService->addUserToGroup($param);
        if ($data===false){
            return $this->error($authGroupAccessService->getError());
        }
        $this->app->cache->tag(['admin_menu'])->clear();
        return $this->success('添加成功');
    }

    /**
     * 添加用户到多个组
     * @param AuthGroupAccessService $authGroupAccessService
     * @return mixed
     * @throws Exception
     * @author staitc7 <static7@qq.com>
     */
    public function addUserToMultipleGroups(AuthGroupAccessService $authGroupAccessService)
    {
        $param=$this->app->request->param();
        $data=$authGroupAccessService->addUsersToMultipleGroups($param);
        if ($data===false){
            return $this->error($authGroupAccessService->getError());
        }
        $this->app->cache->tag(['admin_menu'])->clear();
        return $this->success('操作成功');
    }

    /**
     * 将用户从组中移除
     * @author staitc7 <static7@qq.com>
     * @param AuthGroupAccessService $authGroupAccessService
     * @return mixed
     * @throws Exception
     */
    public function removeUserFromGroup(AuthGroupAccessService $authGroupAccessService)
    {
        $param=$this->app->request->param();
        $data = $authGroupAccessService->removeFromGroup($param);
        if ($data===false){
            return $this->error($authGroupAccessService->getError());
        }
        $this->app->cache->tag(['admin_menu'])->clear();
        return $this->success('解除成功');
    }

    /**
     * 批量移除用户组
     * @author staitc7 <static7@qq.com>
     * @param AuthGroupAccessService $authGroupAccessService
     * @return mixed
     * @throws Exception
     */
    public function removeToGroup(AuthGroupAccessService $authGroupAccessService)
    {
        $data=$authGroupAccessService->removeToGroup($this->app->request->param());
        if ($data===false){
            return $this->error($authGroupAccessService->getError());
        }
        $this->app->cache->tag(['admin_menu'])->clear();
        return $this->success('批量移除成功');
    }

    /**
     * 编辑权限组
     * @param AuthGroupService $authGroupService
     * @return mixed
     * @throws Exception
     * @author staitc7 <static7@qq.com>
     */

    public function edit(AuthGroupService $authGroupService)
    {
        $param = $this->app->request->param();
        $info  = $authGroupService->edit($param);
        $group  = $authGroupService->groupAll($param); //获取所有的菜单
        if (empty($group) === false) {
            $tree = Tree::toFormatTree($group->toArray());
        }
        return $this->setView(['info' => $info ?? null, 'groupTree' => $tree ?? null, 'metaTitle' => '权限组详情']);
    }

    /**
     * 用户更新或者添加导航
     * @param AuthGroupService $authGroupService
     * @return \think\Response
     * @author staitc7 <static7@qq.com>
     */
    public function renew(AuthGroupService $authGroupService)
    {
        $info    = $authGroupService->renew($this->app->request->param());
        if ($info===false) {
            return $this->error($authGroupService->getError());
        }
        $this->app->cache->tag(['admin_menu'])->clear();
        return $this->success('操作成功', (string)$this->app->route->buildUrl('Competence/index', ['pid' => $info['pid'] ?: 0]));
    }


    /**
     * 用户添加到组
     * @param AuthGroupService       $authGroupService
     * @param AuthGroupAccessService $authGroupAccessService
     * @return mixed
     * @throws Exception
     * @author staitc7 <static7@qq.com>
     */
    public function authUserToGroup(AuthGroupService $authGroupService,AuthGroupAccessService $authGroupAccessService)
    {
        $param=$this->app->request->param();
        //查询用户组
        $group=$authGroupService->getAuthGroupList();
        //查询用户所在的组权限
        $userGroup=$authGroupAccessService->getUserGroupList($param);
        $userGroup = $userGroup ? array_column($userGroup->toArray(), 'group_id') : null;
        $this->initView();
        $view=View::fetch('auth_group',[
            'user_id' => $param['user_id'] ?? 0,
            'auth_group' => $group,
            'user_group' => $userGroup ? implode(',', $userGroup) : null
        ]);
        return $this->template($view,1,'成功');
    }

    /**
     * 授权选项
     * @return mixed
     * @throws Exception
     * @author staitc7 <static7@qq.com>
     */
    public function access()
    {
        $param=$this->app->request->param();
        $this->initView();
        View::assign($param);
        $view=View::fetch('access');
        return $this->template($view,1,'成功');
    }

    /**
     * 节点接口
     * @param AuthGroupService $authGroupService
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author staitc7 <static7@qq.com>
     */
    public function nodeInterface(AuthGroupService $authGroupService)
    {
        $param=$this->app->request->param();
        $authGroupService->updateRule($param); //更新权限表
        $nodeRule =$authGroupService->getAuthNode($param);
        return $this->result($nodeRule,1,'获取成功');
    }


    /**
     * 更新规则
     * @author staitc7 <static7@qq.com>
     * @param AuthGroupService $authGroupService
     * @return mixed
     */
    public function updateAuthorization(AuthGroupService $authGroupService)
    {
        $data=$authGroupService->updateAuthorization($this->app->request->param());
        if ($data===false){
            return $this->error($authGroupService->getError());
        }
        $this->app->cache->tag(['admin_menu'])->clear();
        return $this->success('授权成功');
    }

    /**
     * 设置状态
     * @author staitc7 <static7@qq.com>
     * @param AuthGroupService $authGroupService
     * @return mixed
     */
    public function setStatus(AuthGroupService $authGroupService)
    {
        $info=$authGroupService->setStatus($this->app->request->param());
        if ($info === false) {
            return $this->error($authGroupService->getError());
        }
        $this->app->cache->tag(['admin_menu'])->clear();
        return $this->success('更新成功');
    }

    /**
     * 清理无用权限
     * @param AuthRuleService $authRuleService
     * @return mixed
     * @throws Exception
     * @author staitc7 <static7@qq.com>
     */
    public function cleanInvalidCompetence(AuthRuleService $authRuleService)
    {
        $authRuleService->cleanInvalidCompetence();
        return $this->success('操作成功');
    }

    /**
     * 取消该组权限
     * @param AuthGroupService $authGroupService
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function clearAuth(AuthGroupService $authGroupService)
    {
        $param=$this->app->request->param();
        $authGroupService->clearAuth($param);
        return $this->success('取消成功');
    }
}