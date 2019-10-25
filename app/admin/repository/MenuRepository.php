<?php
/**
 * Description of MenuRepository.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/6/22 23:47
 */

namespace app\admin\repository;


use app\admin\model\Menu;
use app\admin\traits\BaseRepository;
use think\facade\{
    App
};

class MenuRepository
{
    use BaseRepository;

    private $model;

    public function __construct()
    {
        $this->model=new Menu();
    }


    /**
     * 查询父级菜单
     * @param int $pid 父级ID
     * @return null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author staitc7 <static7@qq.com>
     */

    public function father(?int $pid = 0)
    {
        $object = $this->model->where('id', '=', $pid)->field(['pid', 'title', 'main_id'])->find();
        return $object ?: null;
    }


    /**
     * 获得菜单全部数据
     * @author staitc7 <static7@qq.com>
     * @param array $map 条件
     * @param array $field 字段
     * @param array $order 排序
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function lists(?array $map = [], ?array $field = null, ?array $order = null)
    {
        $object = $this->model->where(array_values($map))->field($field ?: null)->order($order ?: ['id' => 'asc'])->select();
        return $object ?: null;
    }

    /**
     * 获取所有菜单
     * @author staitc7 <static7@qq.com>
     * @param array|null $map
     * @param array|null $field
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function menuAll(?array $map = [], ?array $field = null)
    {
        $object = $this->model->where(array_values($map))->field($field)->select();
        return !$object->isEmpty() ? $object : null;
    }


    /**
     * 查询菜单详情
     * @author staitc7 <static7@qq.com>
     * @param array|null $map
     * @param array|null $field
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit(?array $map = [], ?array $field = null)
    {
        $object = $this->model->where(array_values($map))->field($field)->find();
        return $object ?: null;
    }

}