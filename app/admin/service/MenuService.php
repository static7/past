<?php
/**
 * Description of MenuService.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/6/22 23:46
 */

namespace app\admin\service;

use app\admin\repository\MenuRepository;
use app\admin\traits\BaseService;
use think\facade\{
    Request,Env,Config,App
};

class MenuService
{
    use BaseService;

    public function __construct()
    {
        $this->repository = new MenuRepository();
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
     * 查询列表
     * @author staitc7 <static7@qq.com>
     * @param array|null $param
     * @return mixed
     */
    public function getListPage(?array $param = [])
    {
        $map = array_merge([
            'status' => ['status', '>=', 0], 'pid' => ['pid', '=', Request::param('pid', 0)]
        ], $param);
        return $this->repository->getListPage($map, null, ['sort' => 'asc', 'id' => 'asc']);
    }

    /**
     * 获取模块下所有所有菜单
     * @param array|null $param
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author staitc7 <static7@qq.com>
     */
    public function menuAll(?array $param = [])
    {
        $map = array_merge([
            'status' => ['status', '>=', 0], 'module' => ['module', '=', Request::param('module', '')]
        ], $param);
        return $this->repository->menuAll($map, ['id', 'title', 'pid', 'main_id']);
    }


    /**
     * 获取菜单列表
     * @author staitc7 <static7@qq.com>
     * @param array|null $param
     * @return mixed
     */
    public function lists(?array $param = [])
    {
        $map = array_merge([
            'status' => ['status', '>=', 0], 'module' => ['module', '=', Request::param('module', '')]
        ], $param);
        return $this->repository->getListPage($map, null, ['sort' => 'asc', 'id' => 'asc']);
    }

    /**
     * 编辑菜单
     * @param array|null $param
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author staitc7 <static7@qq.com>
     */
    public function edit(?array $param = [])
    {
        if ((int)Request::param('id', 0) < 1) {
            return $this->error('菜单ID不能为空');
        }
        $map = array_merge([
            'status' => ['status', '>=', 0],
        ], $param);
        return $this->repository->edit($map);
    }

    /**
     * 菜单显示或者开发操作
     * @author staitc7 <static7@qq.com>
     * @param array|null $param
     * @return mixed
     */
    public function toogle(?array $param = [])
    {
        if (!isset($param['field']) || empty($param['field'])) {
            return $this->error('参数错误');
        }
        if (!isset($param['value']) || (string)$param['value'] === '') {
            return $this->error('更新数据值错误');
        }
        $data = [$param['field'] => (int)$param['value']];
        $map  = $this->primaryKey($param['id'] ?? null);
        return $this->repository->setStatus($map, $data);
    }


    /**
     * 生成节点数据
     * @param null|string $module 模块
     * @param boolean     $tree 是否返回多维数组结构(生成菜单时用到),为false返回一维数组(生成权限节点时用到)
     *                          注意,返回的主菜单节点数组中有'controller'元素,以供区分子节点和主节点
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author 朱亚杰 <xcoolcc@gmail.com>
     */
    final public function nodes(?string $module = null, ? bool $tree = true)
    {
        static $treeNode = null;
        if ($tree && empty($treeNode[ (int)$tree ]) === false) {
            return $treeNode[ (int)$tree ];
        }
        $module = $module ?: $this->app->request->module(); //当前模块名称
        $map    = [
            ['status', '=', 1],
            ['module', '=', $module],
            ['hide', '=', 0],
            ['is_dev', '=', 0]
        ];
        $list   = $this->repository->menuAll($map, ['id', 'pid', 'title', 'url', 'id' => 'value']);
        if (empty($list) === true) {
            return [];
        }
        foreach ($list as $key => &$value) {
            if (stripos($value['url'], $module) !== 0) {
                $value['url'] = "{$module}/{$value['url']}";
            }
        }
        $nodes = $list->toArray();
        if ($tree === true) {
            $nodes = list_to_tree($list->toArray(), 'id', 'pid', 'children', 0);
        }
        unset($list);
        $treeNode[ (int)$tree ] = $nodes;
        return $nodes;
    }


    /**
     * 获取控制器文件名称
     * @author staitc7 <static7@qq.com>
     * @param array|null $param
     * @return mixed
     */
    public function getControllerFileName(?array $param = [])
    {
        $path   = App::getAppPath() . "controller/";
        if (is_dir($path) === false) {
            return $this->error('目录不存在');
        }
        $files     = scandir($path);
        $fileArray = array_diff($files, ['.', '..']);
        if (empty($fileArray) === true) {
            return $this->error('目录为空');
        }
        foreach ($fileArray as $k => &$v) {
            $v = str_replace('.php', '', $v);
        }
        return $fileArray;
    }


    /**
     * 控制器获取名称
     * @author staitc7 <static7@qq.com>
     * @param array|null $param
     * @return mixed
     * @throws \ReflectionException
     */
    public function getFunctionName(?array $param = [])
    {
        //命名空间
        $appNamespace = App::getNamespace();
        //控制器命名空间
        $namespace = "$appNamespace\\controller\\{$param['controller']}";

        $class     = new \ReflectionClass($namespace);
        //过滤
        $methods      = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
        $methodsArray = [];
        if (empty($methods) === false) {
            foreach ($methods as $k => $v) {
                if (in_array($v->getName(), ['__construct']) === false) {
                    $methodsArray[ $k ]['name'] = $v->getName();
                }
            }
        }
        if (empty($methodsArray) === false) {
            foreach ($methodsArray as $k => $v) {
                $annotate                       = $class->getMethod($v['name'])->getDocComment();
                $annotate                       = explode(PHP_EOL, $annotate);
                $methodsArray[ $k ]['annotate'] = preg_replace('/\s+|\*+/', '', $annotate[1]);
            }
        }
        return $methodsArray;
    }
}