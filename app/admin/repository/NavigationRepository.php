<?php
/**
 * Description of NavigationRepository.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/11/7 13:44
 */

namespace app\admin\repository;


use app\admin\model\Navigation;
use app\admin\traits\BaseRepository;

class NavigationRepository
{
    use BaseRepository;

    private $model;

    public function __construct()
    {
        $this->model = new Navigation();
    }

    /**
     * 查询父级导航
     * @param int $pid 父级导航ID
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author staitc7 <static7@qq.com>
     */

    public function father(int $pid = 0)
    {
        return $this->model->where('id', '=', $pid)->field(['pid', 'title', 'module'])->find();
    }
}