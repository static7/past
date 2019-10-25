<?php
/**
 * Description of AuthGroupRepository.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/7/21 11:08
 */

namespace app\admin\repository;


use app\admin\model\AuthGroup;
use app\admin\traits\BaseRepository;
use think\facade\App;

class AuthGroupRepository
{
    use BaseRepository;

    private $model;

    public function __construct()
    {
        $this->model=new AuthGroup();
    }

    /**
     * 查询父级权限组
     * @param int|null $pid 父级ID
     * @return null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */

    public function father(?int $pid = 0)
    {
        return $this->model->where('id', '=', $pid)->field(['pid', 'title', 'main_id'])->find();
    }

    /**
     * 取消该组权限
     * @param array|null $param
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function clearAuth(?array $param=[])
    {
        return $this->model->where(array_values($param))->save(['rules'=>'']);
    }

}