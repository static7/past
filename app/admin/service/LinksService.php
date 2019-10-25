<?php
/**
 * Description of LinksService.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/11/9 11:26
 */

namespace app\admin\service;

use app\admin\repository\LinksRepository;
use app\admin\traits\BaseService;

class LinksService
{
    use BaseService;

    public function __construct()
    {
        return $this->repository=new LinksRepository();
    }

    /**
     * 获取列表
     * @author staitc7 <static7@qq.com>
     * @param array|null $param
     * @return mixed
     * @throws DbException
     */
    public function getListPage(?array $param=[])
    {
       $map =array_merge([
            'status'=>['status', '>=', 0]
        ],$param);
        return $this->repository->getListPage($map,null,['create_time'=>'desc']);
    }


    /**
     * 编辑或者添加
     * @author staitc7 <static7@qq.com>
     * @param array|null $param
     * @return mixed
     * @throws DbException
     */
    public function edit(?array $param=[])
    {
        if ((int)$param['id'] === 0){
            return null;
        }
        return $this->repository->first((int)$param['id']);
    }
}