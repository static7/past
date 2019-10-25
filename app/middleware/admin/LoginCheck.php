<?php

namespace app\middleware\admin;

use app\facade\UserInfo;
use think\{facade\Log, facade\Request, facade\Route, facade\Session, Response};

class LoginCheck
{
    //需要排除的控制器
    protected $exclude = [
        'Login',
    ];

    public function handle($request, \Closure $next)
    {
        $exclude = array_map('strtolower', $this->exclude);
        if (in_the_array($request->controller(true), $exclude)) {
            return $next($request);
        }
        //检测用户登录
        if ((bool)UserInfo::online() === true) {
            return $next($request);
        }
        //判断是否ajax
        if ($request->isAjax()) {
            return $this->respond('登录失效,请刷新页面重新登录~');
        }
        $url=Route::buildUrl('Login/index')->domain(true);
        return Response::create((string)$url, 'redirect', 302);
    }

    /**
     * 结束调度
     * @param Response $response
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function end(Response $response)
    {
        // 回调行为
        Log::record($response);
    }

    /**
     * ajax返回数据
     * @access protected
     * @param mixed   $data 要返回的数据
     * @param integer $code 返回的code
     * @param mixed   $msg 提示信息
     * @param array   $header 发送的Header信息
     * @return \think\response
     */
    protected function respond(?string $msg = '', ?int $code = 0, ?array $data = [], array $header = [])
    {
        $result = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
        ];
        return Response::create($result, 'json')->code(200)->header($header);
    }
}
