<?php
/**
 * Description of Login.php.
 * User: static7 <static7@qq.com>
 * Date: 2019/5/22 11:50
 */

namespace app\admin\controller;


use app\admin\service\{
    MemberService,UserCenterService
};
use app\admin\traits\{Entrust, Jump};
use app\facade\UserInfo;
use static7\Captcha;
use think\Exception;
use think\facade\View;

class Login
{
    use Entrust,Jump;

    /**
     * 设置视图并输出
     * @param array  $value 赋值
     * @param string $template 模板名称
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    protected function setView(?array $value = [], ?string $template = '')
    {
        //检测模板初始化
        View::config($this->app->config->get('app.template',[]));
        return View::fetch($template ?: '',$value ?: []);
    }

    /**
     * 首页
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function index()
    {
        if (UserInfo::online() === true) {
            return $this->redirect('/');
        }
        $remember = $this->app->cookie->get('remember');
        return $this->setView(['remember' => $remember],'index');
    }

    /**
     * 登录
     * @param UserCenterService $userCenterService
     * @param MemberService     $memberService
     * @return mixed
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author staitc7 <static7@qq.com>
     */
    public function login(UserCenterService $userCenterService, MemberService $memberService)
    {
        //判断是否ajax登录
        if ($this->app->request->isAjax() === false) {
            return $this->error('非法请求');
        }
        //腾讯验证码
        $this->app->config->get('tencent.captcha.status') && $this->tencentCaptcha();
        $userId = $userCenterService->login($this->app->request->param());
        //更新用户信息
        $memberService->memberlogin($userId);
        return $this->success('登录成功,页面马上跳转~', 'Index/index');
    }


    /**
     * 退出
     * @author staitc7 <static7@qq.com>
     * @return mixed
     */
    public function logout()
    {
        if (UserInfo::online() === false) {
            return $this->redirect('Login/index',302);
        }
        UserInfo::logout();
        return $this->redirect('Login/index', 302);
    }

    /**
     * 腾讯验证码
     * @author staitc7 <static7@qq.com>
     * @return mixed
     * @throws Exception
     */
    private function tencentCaptcha()
    {
        $param   = $this->app->request->param();
        $config=$this->app->config->get('tencent.captcha');
        $Captcha = new Captcha([
            'aid' => $config['appid'],
            'AppSecretKey' => $config['appkey']
        ]);
        $result  = $Captcha->verify($param['ticket'], $param['randstr']);
        if ((int)$result['response'] === 0) {
            return $this->result($param, 0, $result['err_msg']);
        }
        return true;
    }

}