<?php
/**
 * Description of Banner.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/11/24 12:07
 */

namespace app\admin\controller;


use app\admin\service\BannerService;
use app\admin\traits\{
    Admin,Jump
};

class Banner
{
    use Admin,Jump;

    /**
     * banner
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function index()
    {
        return $this->setView(['mateTitle'=>'Banner']);
    }

    /**
     * banner接口
     * @author staitc7 <static7@qq.com>
     * @param BannerService $bannerService
     * @return mixed
     * @throws DbException
     */
    public function bannerInterface(BannerService $bannerService)
    {
        $param=$this->app->request->params([
            'title'=>['title','like'],
            'position'=>['position','=']
        ]);
        $data=$bannerService->getListPage($param);
        return $this->layuiJson($data->toArray());
    }

    /**
     * 编辑或者新增
     * @param BannerService $bannerService
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function edit(BannerService $bannerService)
    {
        $param=$this->app->request->only(['id'=>0]);
        $data=$bannerService->edit($param);
        return $this->setView(['info'=>$data ?? '']);
    }

    /**
     * 用户更新或者添加导航
     * @param BannerService $bannerService
     * @return \think\Response
     * @author staitc7 <static7@qq.com>
     */
    public function renew(BannerService $bannerService)
    {
        $param = $this->app->request->param();
        $info  = $bannerService->renew($param);
        if ($info === false) {
            return $this->error($bannerService->getError());
        }
        $this->app->cache->delete('banner_list');
        return $this->success('操作成功', 'Banner/index');
    }

    /**
     * 设置状态
     * @param BannerService $bannerService
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function setStatus(BannerService $bannerService)
    {
        $param = $this->app->request->param();
        $info  = $bannerService->setStatus($param);
        if ($info === false) {
            return $this->error($bannerService->getError());
        }
        $this->app->cache->delete('banner_list');
        return $this->success('更新成功');
    }
}