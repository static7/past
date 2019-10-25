<?php
/**
 * Description of NavigationService.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/11/7 13:43
 */

namespace app\admin\service;

use app\admin\repository\NavigationRepository;
use app\admin\traits\BaseService;
use think\facade\Request;

class NavigationService
{
    use BaseService;

    public function __construct()
    {
        $this->repository = new NavigationRepository();
    }

    /**
     * 查询父级ID
     * @author staitc7 <static7@qq.com>
     * @param array|null $param
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function father(?array $param=[])
    {
        if (isset($param['pid']) === false){
            return $this->error('父级导航不能为空');
        }
        return $this->repository->father($param['pid'] ?? 0);
    }


    /**
     * 获取导航列表
     * @author staitc7 <static7@qq.com>
     * @param array|null $param
     * @return mixed
     */
    public function getNavigationList(?array $param=[])
    {
        $map = array_merge([
            'status'=>['status', '>=', 0],
            'pid'=>['pid', '=', Request::param('pid',0)]
        ],$param);
        return $this->repository->getListPage($map, null, ['sort' => 'asc', 'id' => 'asc']);
    }

    /**
     * 编辑导航
     * @author staitc7 <static7@qq.com>
     * @param array|null $param
     * @return mixed
     */
    public function edit(?array $param = [])
    {
        if (isset($param['id']) === false && (int)$param['id']!==0 ) {
            return $this->error ('导航ID不能为空');
        }
        $map = [
            'status'=>['status', '>=',0],
        ];
        return $this->repository->first((int)$param['id'],null,$map);
    }
}