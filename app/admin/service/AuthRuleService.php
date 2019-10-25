<?php
/**
 * Description of AuthRuleService.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/7/21 22:39
 */

namespace app\admin\service;


use app\admin\repository\AuthRuleRepository;
use app\admin\traits\BaseService;

class AuthRuleService
{
    use BaseService;

    public function __construct()
    {
        $this->repository= new AuthRuleRepository();
    }

    /**
     * 获取所有规则列表
     * @param array|null $param
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getAllRule(?array $param=[])
    {
        $map=[
          'module'=>['module' ,'=',$param['module']],
          'type'=>['type','in',[1,2]]
        ];
        return $this->repository->getList($map,null,['name'=>'asc']);
    }

    /**
     * 获取权限列表节点Column
     * @author staitc7 <static7@qq.com>
     * @param array|null $param
     * @return mixed
     */
    public function getAuthRuleColumn(?array $param=[])
    {
        return  $this->repository->getAuthRuleColumn($param);
    }

    /**
     * 批量新增规则
     * @author staitc7 <static7@qq.com>
     * @param array|null $data
     * @return mixed
     * @throws \Exception
     */
    public function batchAdd(?array $data=[])
    {
        if (empty($data)){
            return null;
        }
        return $this->repository->batch($data);
    }


    /**
     * 清理失效的菜单
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function cleanInvalidCompetence()
    {
        return $this->repository->cleanInvalidCompetence();
    }
}