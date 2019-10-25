<?php
/**
 * Description of Config.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/6/29 11:04
 */

namespace app\admin\controller;


use app\admin\service\ConfigService;
use app\admin\traits\{Admin, Jump};


class Config
{
    use Jump, Admin;

    /**
     * 配置首页
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function index()
    {
        return $this->setView();
    }

    /**
     * 配置接口
     * @param ConfigService $configService
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function configInterface(ConfigService $configService)
    {
        $param = $this->app->request->params([
            'name'   => ['name', 'like'],
            'status' => ['status', '='],
            'group'  => ['group', '='],
            'area'   => ['area', '='],
            'type'   => ['type', '=']
        ]);
        $data  = $configService->getListPage($param);
        return $this->layuiJson($data->toArray());
    }

    /**
     * 设置状态
     * @param ConfigService $configService
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function setStatus(ConfigService $configService)
    {
        $info = $configService->setStatus($this->app->request->param());
        if ($info === false) {
            return $this->error($configService->getError());
        }
        return $this->success('更新成功');
    }

    /**
     * 网站配置
     * @param ConfigService $configService
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function deploy(ConfigService $configService)
    {
        $param = $this->app->request->only(['id' => 1]);
        $data  = $configService->getConfigList($param);
        $type  = $this->app->config->get('app.config_group_list') ?? null;
        return $this->setView([
            'list'      => $data,
            'group_id'  => $param['id'],
            'type'      => $type,
            'metaTitle' => '系统配置'
        ]);
    }


    /**
     * 配置详情
     * @param ConfigService $configService
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function edit(ConfigService $configService)
    {
        $param = $this->app->request->only(['id' => 0]);
        if ((int)$param['id'] > 0) {
            $info = $configService->edit((int)$param['id']);
        }
        return $this->setView(['info' => $info ?? null, 'metaTitle' => '配置详情']);
    }

    /**
     * 网站设置保存
     * @param ConfigService $configService
     * @return \think\Response
     * @author staitc7 <static7@qq.com>
     */
    public function setConfig(ConfigService $configService)
    {
        $param = $this->app->request->param();
        $info  = $configService->batchSave($param);
        if ($info === false) {
            return $this->error($configService->getError());
        }
        $this->app->cache->tag(['system_config'])->clear();
        return $this->success('操作成功,请清除缓存');
    }


    /**
     * 用户更新或者添加菜单
     * @param ConfigService $configService
     * @return \think\Response
     * @author staitc7 <static7@qq.com>
     */
    public function renew(ConfigService $configService)
    {
        $info = $configService->renew($this->app->request->param());
        if ($info === false) {
            return $this->error($configService->getError());
        }
        $this->app->cache->tag(['system_config'])->clear();
        return $this->success('操作成功,请清除缓存', 'Config/index');
    }
}