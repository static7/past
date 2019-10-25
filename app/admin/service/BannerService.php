<?php
/**
 * Description of BannerService.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/11/24 12:10
 */

namespace app\admin\service;


use app\admin\repository\BannerRepository;
use app\admin\traits\BaseService;

class BannerService
{
    use BaseService;

    public function __construct()
    {
        return $this->repository=new BannerRepository();
    }

    /**
     * 获取Banner列表
     * @author staitc7 <static7@qq.com>
     * @param array|null $param
     * @return mixed
     * @throws DbException
     */
    public function getListPage(?array $param=[])
    {
        $map = array_merge([
            'status' => ['status', '>=', 0]
        ], $param);
        return $this->repository->getListPage($map,null,['sort'=>'desc'],['pictureUrl']);
    }

    /**
     * 编辑
     * @author staitc7 <static7@qq.com>
     * @param array|null $param
     * @return mixed
     * @throws DbException
     */
    public function edit(?array $param=[])
    {
        if (empty($param['id']) === true){
            return null;
        }
        return $this->repository->first((int)$param['id']);
    }
}