<?php
/**
 * Description of AuthGroupService.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/7/21 11:11
 */

namespace app\admin\service;


use app\admin\repository\AuthGroupRepository;
use app\admin\traits\BaseService;
use think\Exception;
use think\facade\{Config, Log, Request};

class AuthGroupService
{
    use BaseService;

    public function __construct()
    {
        $this->repository = new AuthGroupRepository();
    }

    /**
     * 查询父级菜单
     * @param int|null $pid
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author staitc7 <static7@qq.com>
     */
    public function father(?int $pid = 0)
    {
        return $this->repository->father($pid);
    }

    /**
     * 获取权限组分页
     * @param array|null $param
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getGroupListPage(?array $param = [])
    {
        $map   = [
            'status' => ['status', '>=', 0],
            'module' => ['module', '=', Request::module()],
            'pid'    => ['pid', '=', (int)$param['pid'] ?? 0],
        ];
        $field = ['id', 'pid', 'main_id', 'module', 'type', 'title', 'description', 'status'];
        return $this->repository->getListPage($map, $field, ['id' => 'asc']);
    }

    /**
     * 获取权限组分页
     * @param array|null $param
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getGroupList(?array $param = [])
    {
        $map   = [
            'status' => ['status', '>=', 0],
        ];
        $field = ['id', 'pid', 'main_id', 'module', 'type', 'title', 'description', 'status'];
        return $this->repository->getList($map, $field, ['id' => 'asc']);
    }


    /**
     * 获取所有所有权限组
     * @param array|null $param
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function groupAll(?array $param = [])
    {
        $map = [
            ['status', '=', 1],
            ['module', '=', $param['module'] ?? '']
        ];
        return $this->repository->getList($map, ['id', 'title', 'pid', 'main_id']);
    }


    /**
     * 获取权限组
     * @param array|null $param
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getAuthGroup(?array $param = [])
    {
        if (!isset($param['group_id']) || empty($param['group_id'])) {
            return $this->error('权限组ID不能为空');
        }
        $map = [
            'status' => ['status', '=', 1],
            'module' => ['module', '=', $param['module']],
            'type'   => ['type', '=', Config::get('app.auth_config.type_admin', 1)]
        ];
        return $this->repository->first((int)$param['group_id'], ['id', 'title', 'rules'], $map);
    }

    /**
     * 获取权限组
     * @param array|null $param
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getAuthGroupList(?array $param = [])
    {
        $map = [
            'status' => ['status', '=', 1],
            //            'module'=>['module' ,'=', $param['module']],
            //            'type'=>['type' ,'=',Config::get('app.auth_config.type_admin',1)]
        ];
        return $this->repository->getList($map, ['id', 'title', 'main_id']);
    }


    /**
     * 获取授权节点
     * @param array|null $param
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author staitc7 <static7@qq.com>
     */
    public function getAuthNode(?array $param = [])
    {
        $group    = $this->getAuthGroup($param);
        $menuNode = $this->getMenuNode($param, false);
        $authRule = $this->getAuthRule($param);
        $rules    = [];
        if (empty($group['rules']) === false) {
            $rules = explode(',', $group['rules'] ?? []);
        }
        if (empty($menuNode) === false && empty($authRule) === false) {
            foreach ($menuNode as $k => &$v) {
                $authId = isset($authRule[ strtolower($v['url']) ]) ? $authRule[ strtolower($v['url']) ] : false;
                if ($authId === false) {
                    continue;
                }
                (int)$v['pid'] !== 0 && $v['value'] = $authId;
                if (empty($rules) === false && in_the_array($authId, $rules)) {
                    $v['checked'] = true;
//                    $v['spread']  = true;
                }
            }
        }
        unset($group, $rules, $authRule);
        return list_to_tree($menuNode, 'id', 'pid', 'children', 0);
    }

    /**
     * 获取权限节点
     * @param array|null $param
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getAuthRule(?array $param = [])
    {
        $auth = (new AuthRuleService())->getAuthRuleColumn($param);
        if (empty($auth) === false) {
            $data = array_map('strtolower', $auth);
            unset($auth);
            return array_flip($data);
        }
        return null;
    }

    /**
     * 编辑权限组
     * @param array|null $param
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function edit(?array $param = [])
    {
        if (isset($param['id']) === false || (int)$param['id'] < 1) {
            return $this->error('ID不能为空');
        }
        return $this->repository->first($param['id']);
    }

    /**
     * 更新授权规则
     * @param array|null $param
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function updateAuthorization(?array $param = [])
    {
        if (isset($param['group_id']) === false || empty($param['group_id']) === true) {
            return $this->error('权限组ID不能为空');
        }
        if (isset($param['rules']) === false || empty($param['rules']) === true) {
            return $this->error('权限规则不能为空');
        }
        //去除重复
        $array = explode(',', $param['rules']);
        sort($array);
        $rules = array_unique($array);
        $data  = [
            'id'    => $param['group_id'],
            'rules' => implode(',', $rules)
        ];
        unset($array, $rules);
        return $this->repository->renew($data, 'updateAuthorization');
    }


    /**
     * 获取菜单节点
     * @param array|null $param
     * @param bool       $tree
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author staitc7 <static7@qq.com>
     */
    public function getMenuNode(?array $param = [], $tree = false)
    {
        return (new MenuService())->nodes($param['module'] ?? '', $tree);
    }

    /**
     * 获取用户单个用户组
     * @param int|null   $id
     * @param array|null $field
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function first(?int $id = 0, ? array $field = [])
    {
        return $this->repository->first($id, $field ?: '*');
    }

    /**
     * 取消权限
     * @param array|null $param
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function clearAuth(?array $param = [])
    {
        $map = [
            'status' => ['status', '=', 1],
            'id'     => ['id', '=', $param['id'] ?? ''],
            'module' => ['module', '=', $param['module'] ?? ''],
        ];
        return $this->repository->clearAuth($map);
    }

    /**
     * 更新菜单节点
     * @param array|null $param
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author staitc7 <static7@qq.com>
     */
    public function updateRule(?array $param = [])
    {
        $nodes = $this->getMenuNode($param, false);//获取菜单节点
        //保存需要插入和更新的新节点
        $data = [];
        foreach ($nodes as $k => $v) {
            $tmpNode                                                                          = [
                'menu_id' => $v['id'],
                'name'    => $v['url'],
                'title'   => $v['title'],
                'module'  => $param['module'] ?? '',
                'type'    => $v['pid'] > 0 ? Config::get('app.auth_rule.rule_url') : Config::get('app.auth_rule.rule_main'),
            ];
            $data[ strtolower("{$tmpNode['name']}_{$tmpNode['module']}_{$tmpNode['type']}") ] = $tmpNode; //去除重复项
            unset($tmpNode);
        }
        unset($nodes);
        try {
            $AuthRule = new AuthRuleService();
            $rules    = $AuthRule->getAllRule($param);
            if ($rules->isEmpty() === false) {
                $updateRule     = []; //保存需要更新的节点
                $deleteRule     = []; //保存需要删除的节点的id
                $differenceRule = []; //差异节点
                foreach ($rules->toArray() as $k => $v) {
                    $mark = strtolower("{$v['name']}_{$v['module']}_{$v['type']}");
                    //如果数据库中的规则与配置的节点匹配,说明是需要更新的节点
                    if (isset($data[ $mark ])) {
                        $data[ $mark ]['id'] = $v['id']; //为需要更新的节点补充id值
                        $updateRule[]        = $data[ $mark ];
                        unset($data[ $mark ], $rules[ $k ], $v['condition']);
                        $differenceRule[ $v['id'] ] = $v;
                    } elseif ($v['status'] == 1) {
                        $deleteRule[] = $v['id'];
                    }
                }
                //更新差异节点
                if (empty($updateRule) === false) {
                    foreach ($updateRule as $k => $v) {
                        array_diff_assoc($v, $differenceRule[ $v['id'] ]) && $AuthRule->renew($v);
                    }
                }
                //删除节点
                empty($deleteRule) || $AuthRule->setStatus(['value' => 0, 'id' => $deleteRule]);
            }
            //没有则添加规则
            empty($data) || $AuthRule->batchAdd(array_values($data));
            unset($data, $rules, $updateRule, $differenceRule, $deleteRule);
        } catch (\Exception $exception) {
            Log::record($exception, '规则更新信息错误');
            return false;
        }
        return true;
    }

}