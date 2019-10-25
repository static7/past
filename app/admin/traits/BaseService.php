<?php
/**
 * Description of BaseService.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/6/26 11:39
 */

namespace app\admin\traits;


use think\facade\{
    Config
};

trait BaseService
{
    use Jump;

    private $repository;

    /**
     * 捕获错误
     * @author staitc7 <static7@qq.com>
     * @return mixed
     */
    public function getError()
    {
        return $this->repository->getError();
    }


    /**
     * 设置当前排序
     * @author staitc7 <static7@qq.com>
     * @param array|null  $param
     * @param null|string $key
     * @return mixed
     */
    public function currentSort(?array $param=[],?string $key='id')
    {
        $map=$this->primaryKey($param[$key] ?? null);
        unset($param[$key]);
        return $this->repository->setStatus($map,$param);
    }

    /**
     * 检测管理员
     * @author staitc7 <static7@qq.com>
     * @param int|null $userId 用户ID
     * @return mixed
     */
    private function checkAdministrator($userId=null)
    {
        $adminId = (int)Config::get('app.user_administrator');
        if (preg_match('/\,+/', $userId)) {
            if (in_the_array($adminId, explode(',', $userId))) {
                return $this->error('对超级管理员无效');
            }
        }
        if ($adminId === (int)$userId) {
            return $this->error('对超级管理员无效');
        }
        return true;
    }

    /**
     * 主键分析
     * @author staitc7 <static7@qq.com>
     * @param null|string $ids
     * @param null|string $field
     * @return mixed
     */
    protected function primaryKey(?string $ids=null, ?string $field='id') : array
    {
        if (empty($ids)) {
            return $this->error($field.'字段不能为空');
        }
        if (preg_match('/\,+/', $ids)){
            $map=[[$field,'in',explode(',',$ids)]];
        }else{
            $map=[[$field,'=',$ids]];
        }
        return $map;
    }

    /**
     * 数据库新增或者编辑
     * @author staitc7 <static7@qq.com>
     * @param array|null  $param
     * @param null|string $scene
     * @param null|string $validateName
     * @return mixed
     */
    public function renew(?array $param = [],?string $scene = null, ? string $validateName = null)
    {
        if (empty($param) === true) {
            return $this->error('数据为空');
        }
        return $this->repository->renew($param,$scene,$validateName);
    }

    /**
     * 设置状态
     * @author staitc7 <static7@qq.com>
     * @param array|null  $param
     * @param null|string $key
     * @param null|string $field
     * @return mixed
     */
    public function setStatus(?array $param=[],?string $key='id',?string $field='status')
    {
        if (!isset($param['value']) || (string)$param['value'] === '') {
            return $this->error('更新数据值错误');
        }
        $data = [$field => (int)$param['value']];
        if (isset($param[$key]) && is_array($param[$key])){
            $map = [[$key,'in',$param[$key]]];
        }else{
            $map = $this->primaryKey($param[$key] ?? null,$key);
        }
        return $this->repository->setStatus($map, $data);
    }
}