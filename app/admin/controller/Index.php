<?php

namespace app\admin\controller;

use app\admin\traits\{
    Jump,Admin
};
use think\Response;

class Index
{
    use Admin,Jump;

    public function index()
    {
        return $this->setView();
    }


    /**
     * 清空文件缓存
     * @param string $path 缓存路径
     * @return Response
     */
    function clearRuntime(?string $path = null)
    {
        $path  = $path ?: $this->app->getRuntimePath();
        $files = scandir($path);
        if ($files) {
            foreach ($files as $file) {
                if ('.' != $file && '..' != $file && is_dir($path . $file)) {
                    array_map('unlink', glob($path . $file . '/*.*'));
                } elseif ('.gitignore' != $file && is_file($path . $file)) {
                    unlink($path . $file);
                }
            }
        }
        $this->app->cache->tag(['admin_menu','system_config'])->clear();
        return  $this->success('已经成功清理!','Index/index');
    }

    /**
     * 测试路由
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function test()
    {
        return '测试路由';
    }
}
