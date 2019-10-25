<?php
/**
 * Description of CategoryRepository.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/10/9 17:11
 */

namespace app\admin\repository;


use app\admin\model\Category;
use app\admin\traits\BaseRepository;
use Exception;
use think\facade\App;

class CategoryRepository
{
    use BaseRepository;

    private $model;

    public function __construct()
    {
        $this->model=new Category();
    }

    /**
     * 获取分类列表
     * @param array|null $field
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author staitc7 <static7@qq.com>
     */
    public function getCategoryList(?array $field = [])
    {
        $object = $this->model->where('status', '>', -1)
            ->field($field)
            ->order(['sort' => 'asc'])
            ->select();

        return $object->toArray();
    }

    /**
     * 通过ID获取树
     * @param null|string $name
     * @param array|null  $field
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author staitc7 <static7@qq.com>
     */
    public function getByNameToTree(?string $name = null, ?array $field = [])
    {
        if (empty($name) === true){
            return null;
        }
        //转化成id
        $CategoryInfo=$this->getByNameToInfo($name,['id']);
        $object = $this->model
            ->where('status', '>', -1)
            ->field($field)
            ->order(['sort' => 'asc'])
            ->select();
        if ($object) {
            $list = list_to_tree($object->toArray(), 'id', 'pid', '_', $CategoryInfo['id'] ?? 0);
            if (isset($info)) { //指定分类则返回当前分类极其子分类
                $info['_'] = $list;
            } else { //否则返回所有分类
                $info = $list;
            }
        }
        unset($CategoryInfo);
        return $info ?? null;
    }


    /**
     * 根据表示获取分类详细信息
     * @param null|string $name
     * @param array       $field 查询字段
     * @return array 分类信息
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author 麦当苗儿 <zuojiazivip.qq.com>
     */
    public function getByNameToInfo(?string $name = null, ?array $field = [])
    {
        $object =$this->model
            ->where( 'name','=', $name ?? '')
            ->field($field)
            ->find();
        return $object ?: null;
    }

    /**
     * 获取分类详情信息
     * @param int|null   $id
     * @param array|null $field
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author staitc7 <static7@qq.com>
     */
    public function getByIdToInfo(?int $id = 0, ?array $field = [])
    {
        if (empty($id) === true) {
            return null;
        }
        $object = $this->model->where('id', '=', $id ?? 0)->field($field)->find();
        return $object ?: null;
    }

    /**
     * 检查子分类
     * @param int|null   $id
     * @param array|null $field
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author staitc7 <static7@qq.com>
     */
    public function subset(?int $id=0, ?array $field = [])
    {
        if (empty($id) === true) {
            return null;
        }
        $object = $this->model->where('pid', '=', $id ?? 0)->field($field)->find();
        return $object ?: null;
    }

    /**
     * 删除分类
     * @author staitc7 <static7@qq.com>
     * @param array|null $param
     * @return mixed
     * @throws Exception
     */
    public function remove(?array $param=[])
    {
        return $this->model->where('id','=', $param['id'])->delete();
    }

    /**
     * 移动分类
     * @author staitc7 <static7@qq.com>
     * @param array|null $map
     * @param array|null $data
     * @return mixed
     */
    public function moveRenew(?array $map,?array $data=[])
    {
        return $this->model->where(array_values($map))->update($data);
    }
}