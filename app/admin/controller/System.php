<?php
/**
 * Description of System.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/6/27 21:58
 */

namespace app\admin\controller;


use app\admin\traits\{
    Admin,Jump
};

class System
{
    use Jump,Admin;

    /**
     * 系统首页
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function index()
    {
        return $this->setView();
    }
}