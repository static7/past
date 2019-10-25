<?php
/**
 * Description of ConfigRepository.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/7/16 20:27
 */

namespace app\admin\repository;


use app\admin\model\Configuration;
use app\admin\traits\BaseRepository;

class ConfigRepository
{
    use BaseRepository;

    private $model;

    public function __construct()
    {
        $this->model=new Configuration();
    }

    /**
     * 批量保存配置
     * @author staitc7 <static7@qq.com>
     * @param array $data 配置数据
     * @return bool
     */

    public function batchSave(?array $data = []) {
        foreach ($data as $name => $value) {
            $status = $this->model->update(['value'=>$value],[['name','=',$name]]);
            if ($status === false) {
                $this->error = '系统错误，请稍候再试';
                return false;
            }
        }
        return true;
    }
}