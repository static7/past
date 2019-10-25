<?php
/**
 * Description of AuthRuleRepository.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/7/21 22:40
 */

namespace app\admin\repository;


use app\admin\model\AuthRule;
use app\admin\traits\BaseRepository;

class AuthRuleRepository
{
    use BaseRepository;

    private $model;

    public function __construct()
    {
        $this->model = new AuthRule();
    }

    /**
     * 批量操作
     * @param array|null $data
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function batch(?array $data = [])
    {
        return $this->model->saveAll($data);
    }

    /**
     * 获取权限节点列表
     * @param array|null $param
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getAuthRuleColumn(?array $param = [])
    {
        $object = $this->model->where('status', '=', 1)
            ->where('module', '=', $param['module'] ?? '')
            ->column('name', 'menu_id');
        return $object ?: null;
    }

    /**
     * 清理失效的节点
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function cleanInvalidCompetence()
    {
        return $this->model->where('status', '=', 0)->delete();
    }

}