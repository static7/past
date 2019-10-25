<?php
/**
 * Description of ActionLogService.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/7/15 21:41
 */

namespace app\admin\service;


use app\admin\repository\ActionLogRepository;
use app\admin\traits\BaseService;

class ActionLogService
{
    use BaseService;

    public function __construct()
    {
        $this->repository=new ActionLogRepository();
    }


    /**
     * 行为日志列表
     * @param array|null $param
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getListPage(?array $param=[])
    {
        $map = array_merge([
           'status'=> ['status', '>=', 0]
        ],$param);
        return $this->repository->getJoinListPage($map, null, ['create_time' => 'desc']);
    }

    /**
     * 日志详细
     * @param array|null $param
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function first(?array $param=[])
    {
        if (!isset($param['id']) || (int)$param['id'] < 1) {
            return $this->error('参数错误');
        }
        return $this->repository->first((int)$param['id']);
    }

    /**
     * 清空日志
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function clearAll()
    {
        return $this->repository->clearAll();
    }
}