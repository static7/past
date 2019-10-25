<?php
/**
 * Description of ConfigService.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/7/16 20:27
 */

namespace app\admin\service;


use app\admin\repository\ConfigRepository;
use app\admin\traits\BaseService;

class ConfigService
{
    use BaseService;

    public function __construct()
    {
        $this->repository=new ConfigRepository();
    }

    /**
     * 前端导航列表
     * @author staitc7 <static7@qq.com>
     * @param array|null $param
     * @return mixed
     */
    public function getListPage(?array $param=[])
    {
        $map = array_merge([
            'status' => ['status', '>=', 0]
        ], $param);
        $field=['id','area','group','name','sort','status','title','type'];
        return $this->repository->getListPage($map,$field,['create_time'=>'desc']);
    }


    /**
     * 获取列表
     * @author staitc7 <static7@qq.com>
     * @param array|null $param
     * @return mixed
     */
    public function getConfigList(?array $param=[])
    {
        if (empty($param['id'])){
            return $this->error('参数错误');
        }
        $map=[
            ['group','=',(int)$param['id']],
            ['status','=', 1]
        ];
        $field = ['id','name','title','extra','value','remark','type'];
        return $this->repository->getList($map,$field,['sort'=>'desc']);
    }

    /**
     * 批量保存
     * @author staitc7 <static7@qq.com>
     * @param array|null $param
     * @return mixed
     */
    public function batchSave(? array $param=[])
    {
        if (empty($param['config']) === true && is_array($param['config']) === false) {
            return $this->error('数据有误，请检查后在保存');
        }
        return $this->repository->batchSave($param['config']);
    }

    /**
     * 编辑
     * @author staitc7 <static7@qq.com>
     * @param int|null   $id
     * @param array|null $field
     * @param array|null $affixation
     * @return mixed
     */
    public function edit(?int $id=0,?array $field = [],?array $affixation =[])
    {
        if ((int)$id < 1) {
            return $this->error('参数错误');
        }
        return $this->repository->first((int)$id,$field,$affixation);
    }



}