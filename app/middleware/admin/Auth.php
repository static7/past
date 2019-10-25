<?php

declare(strict_types=1);

namespace app\middleware\admin;

use app\admin\traits\Jump;
use app\facade\UserInfo;
use think\Container;
use think\facade\{Config, Log, Request};

class Auth
{
    use Jump;

    //需要排除的控制器
    protected $exclude = [
        'Login',
    ];

    /**
     * 处理请求
     * @param Request  $request
     * @param \Closure $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        $controller = $request->controller(true);  // 获取控制器
        $exclude    = array_map('strtolower', $this->exclude);
        if (in_the_array($controller, $exclude)) {
            return $next($request);
        }
        if (UserInfo::checkAdministrator() === true) {
            return $next($request);
        }
        $rule   = $controller . '/' . $request->action(true);
        $access = $this->accessControl($rule);
        if ($access === false) {
            return $this->error('403:禁止访问');
        }
        $completeRule=$this->getCompleteRule($rule);
        if ($access === null && $this->checkRule($completeRule, [1, 2]) === false) { //检测访问权限
            if (preg_match('/\w+\/\w+\/\w+interface/', $rule)) {
                return $this->json(['code' => -1, 'msg' => '数据接口无权限']);
            }
            return $this->error('未授权访问!');
        }
        return $next($request);
    }

    /**
     * action访问控制,在 **登陆成功** 后执行的第一项权限检测任务
     * @param string|null $rule
     * @return boolean|null  返回值必须使用 `===` 进行判断
     *   返回 **false**, 不允许任何人访问(超管除外)
     *   返回 **true**, 允许任何管理员访问,无需执行节点权限检测
     *   返回 **null**, 需要继续执行节点权限检测决定是否允许访问
     * @author 朱亚杰  <xcoolcc@gmail.com>
     */
    protected function accessControl(?string $rule = ''): ?bool
    {
        //不受限控制器方法
        $allow = Config::get('admin_config.allow_visit', []);
        //超管专限控制器方法
        $deny = Config::get('admin_config.deny_visit', []);
        //非超管禁止访问deny中的方法
        if (empty($deny) === false && $this->in_array_case($rule, $deny)) {
            return false;
        }
        if (empty($allow) === false && $this->in_array_case($rule, $allow)) {
            return true;
        }
        return null; //需要检测节点权限
    }

    /**
     * 权限检测
     * @param string $rule 检测的规则
     * @param array  $type 类型
     * @param string $mode check模式
     * @return bool
     * @author 朱亚杰  <xcoolcc@gmail.com>
     */
    protected function checkRule(?string $rule, ?array $type = null, ?string $mode = 'url'): bool
    {
        $type = $type ?: [Config::get('app.auth_rule.rule_url', 1)];
        return Container::getInstance()->get('auth')->check($rule, UserInfo::getUserId(), $type, $mode);
    }

    /**
     *  不区分大小写的in_array实现
     * @param $value
     * @param $array
     * @return bool
     * @author staitc7 <static7@qq.com>
     */
    private function in_array_case(?string $value = null, ?array $array = [])
    {
        return in_array(strtolower($value), array_map('strtolower', $array ?? []));
    }

    /**
     * 获取当前应用名称
     * @param string $rule
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    private function getCompleteRule(string $rule = '')
    {
        return Container::getInstance()->make('http')->getName() . '/' . $rule;
    }
}
