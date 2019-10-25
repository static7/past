<?php
/**
 * Description of Entrust.php.
 * User: static7 <static7@qq.com>
 * Date: 2019/5/22 13:42
 */

namespace app\admin\traits;


use think\App;

trait Entrust
{

    protected $app;

    /**
     * Entrust constructor.
     * 初始化容器
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $this->app ?: $app;

        //应用开始事件监听
        $app->event->trigger('HttpRun');

        //记录日志
        $app->event->trigger('ActionBegin');
        //初始化
        $this->initialize();

        //绑定其他类到容器
        $this->bindContainer();
    }

    /**
     * 初始化
     * @author staitc7 <static7@qq.com>
     * @return mixed
     */
    protected function initialize()
    {

    }

    /**
     * 其他类绑定到容器
     * @author staitc7 <static7@qq.com>
     * @return mixed
     */
    protected function bindContainer()
    {

    }
}