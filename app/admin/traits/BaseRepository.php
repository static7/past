<?php
/**
 * Description of BaseRepository.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/6/26 11:15
 */

namespace app\admin\traits;

use think\facade\{Request, Config};

trait BaseRepository
{
    protected $error;

    /**
     * 捕获错误
     * @author staitc7 <static7@qq.com>
     * @return mixed
     */
    public function getError()
    {
        if (empty($this->error) === false) {
            return $this->error;
        }
        return $this->model->getError();
    }


    /**
     * 分页列表(一般形式)
     * @param array|null $map
     * @param array|null $field
     * @param array|null $order
     * @param array|null $preload 预载入
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getListPage(?array $map = [], ?array $field = null, ?array $order = null, ?array $preload = [])
    {
        $object = $this->model->with($preload)
            ->where(array_values($map))
            ->field($field)
            ->order($order)
            ->paginate([
                'page' => Request::param('page',1,'strip_tags,intval'),
                'list_rows' =>Request::param('limit',Config::get('paginate.list_rows',10),'strip_tags,intval')
            ]);
        return $object ?: null;
    }

    /**
     * 分页列表(链表)
     * @param array|null $map
     * @param array|null $field
     * @param array|null $order
     * @param array|null $preload 预载入
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getJoinListPage(?array $map = [], ?array $field = null, ?array $order = null, ?array $preload = [])
    {
        $object = $this->model->withJoin($preload)
            ->where(array_values($map))
            ->field($field)
            ->order($order)
            ->paginate([
                'page' => Request::param('page',1,'strip_tags,intval'),
                'list_rows' =>Request::param('limit',Config::get('paginate.list_rows'),'strip_tags,intval')
            ]);
        return $object ?: null;
    }

    /**
     * 获取数据列表(一般形式)
     * @author staitc7 <static7@qq.com>
     * @param array|null $map
     * @param array|null $field
     * @param array|null $order
     * @param array|null $preload
     * @return mixed
     */
    public function getJoinList(?array $map = [], ?array $field = null, ?array $order = null,?array $preload = [])
    {
        $object = $this->model
            ->withJoin($preload)
            ->where(array_values($map))
            ->field($field)
            ->order($order)
            ->select();
        return $object ?: null;
    }

    /**
     * 获取数据列表(链表形式)
     * @author staitc7 <static7@qq.com>
     * @param array|null $map
     * @param array|null $field
     * @param array|null $order
     * @param array|null $preload
     * @return mixed
     */
    public function getList(?array $map = [], ?array $field = null, ?array $order = null,?array $preload = [])
    {
        $object = $this->model->with($preload)
            ->where(array_values($map))
            ->field($field)
            ->order($order)
            ->select();
        return $object ?: null;
    }


    /**
     * 单条数据详情
     * @author staitc7 <static7@qq.com>
     * @param int         $id 数据ID
     * @param array       $field 字段
     * @param array       $affixation 附加条件
     * @param null|string $statusField 状态
     * @return mixed
     */
    public function first(int $id=0,?array $field = [],?array $affixation =[],?string $statusField='status')
    {
        $Model= $this->model;
        $map = [
            $Model->getPk() => [$Model->getPk(), '=', (int)$id],
            $statusField => [$statusField, '>=', 0]
        ];
        if (empty($affixation) === false){
            $map=array_merge($map,$affixation);
        }
        $object= $Model->where(array_values($map))->field($field ?: null)->find();
        return $object ?: null;
    }


    /**
     * 更新或者添加
     * @param array|null  $param
     * @param string|null $scene 验证场景
     * @param string|null $primary 主键
     * @param string|null $validateName 验证器类名
     * @return bool
     * @author staitc7 <static7@qq.com>
     */

    public function renew(?array $param = [],?string $scene = null,?string $primary=null, ?string $validateName = null)
    {
        $object = $this->model->renew($param,$scene,$primary,$validateName);
        if ($object === false) {
            $this->error = $this->model->getError();
            return false;
        }
        return $object ?: null;
    }

    /**
     * 设置更新状态
     * @author staitc7 <static7@qq.com>
     * @param array|null $map
     * @param array|null $data
     * @return mixed
     */
    public function setStatus(?array $map=[],?array $data=[])
    {
        $Model= $this->model;
        $object =$Model->setStatus($map,$data);
        if ($object===false){
            $this->error=$Model->getError();
            return false;
        }
        return $object ?: null;
    }

    /**
     * 字段更新
     * @author staitc7 <static7@qq.com>
     * @param array|null $map 条件
     * @param array|null $field 更新的字段
     * @return mixed
     */
    public function updateField(?array $map=[],?array $field=[])
    {
        return $this->model->where(array_values($map))->save($field);
    }

}