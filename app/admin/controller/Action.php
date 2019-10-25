<?php
/**
 * Description of Action.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/7/15 14:17
 */

namespace app\admin\controller;

use app\admin\service\ActionLogService;
use app\admin\traits\Admin;
use app\admin\traits\Jump;
use app\facade\Parameter;

class Action
{
    use Jump,Admin;

    /**
     * 用户日志
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function log()
    {
        return $this->setView([],'action_log');
    }

    /**
     * 行为日志接口
     * @author staitc7 <static7@qq.com>
     * @param ActionLogService $actionLogService
     * @return mixed
     */
    public function actionLogInterface(ActionLogService $actionLogService)
    {
        $data=$actionLogService->getListPage();
        return $this->layuiJson($data->toArray());
    }

    /**
     * 日志状态设置
     * @author staitc7 <static7@qq.com>
     * @param ActionLogService $actionLogService
     * @return mixed
     */
    public function actionLogSetStatus(ActionLogService $actionLogService)
    {
        $info  = $actionLogService->setStatus($this->app->request->param());
        if ($info === false) {
            return $this->error($actionLogService->getError());
        }
        return $this->success('更新成功');
    }

    /**
     * 清空表日志
     * @param ActionLogService $actionLogService
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function clearAll(ActionLogService $actionLogService)
    {
        $result =$actionLogService->clearAll();
        return $result === false
            ? $this->error('日志清空失败！')
            : $this->success('日志清空成功！');
    }
}