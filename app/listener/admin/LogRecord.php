<?php

namespace app\listener\admin;


use app\event\admin\LogLabel;
use think\Container;
use think\facade\{Env, Log, Request};

class LogRecord
{

    /**
     * 日志句柄
     */
    public function handle()
    {
        Log::notice('[ application ]' . var_export($this->getName(), true));
        Log::notice('[ route ]' . var_export($this->routeAnalyze(Request::rule()), true));
        Log::notice('[ param ]' . var_export(Request::param(), true));
        if ((bool)Env::get('app_debug') === true) {
            Log::notice('[ session ]' . var_export(Request::session(), true));

        }
    }

    /**
     * 路由解析
     * @param $rule
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function routeAnalyze(?object $rule)
    {
        return [
            'name' => $rule->getName(),
            'rule' => $rule->getRule(),
            'route' => $rule->getRoute(),
        ];
    }

    /**
     * 获取当前应用名称
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getName()
    {
       return Container::getInstance()->make('http')->getName();
    }


}
