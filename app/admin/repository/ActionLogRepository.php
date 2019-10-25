<?php
/**
 * Description of ActionLogRepository.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/7/15 21:42
 */

namespace app\admin\repository;

use app\admin\model\ActionLog;
use app\admin\traits\BaseRepository;

class ActionLogRepository
{
    use BaseRepository;

    private $model;

    public function __construct()
    {
        $this->model=new ActionLog();
    }


    /**
     * 清空日志
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function clearAll()
    {
        return $this->model->whereRaw('1=1')->delete();
    }

}