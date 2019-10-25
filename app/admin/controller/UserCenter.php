<?php
/**
 * Description of User.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/7/10 20:51
 */

namespace app\admin\controller;


use app\admin\service\UserCenterService;
use app\admin\traits\{
    Admin,Jump
};
use app\facade\Parameter;
use app\facade\UserInfo;
use think\facade\View;

class UserCenter
{
    use Jump,Admin;

    /**
     * 用户中心
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function index()
    {
        return $this->setView();
    }

    /**
     * 用户中心接口
     * @param UserCenterService $ucenterService
     * @return mixed
     * @throws \think\exception\DbException
     * @author staitc7 <static7@qq.com>
     */
    public function UserCenterInterface(UserCenterService $ucenterService)
    {
        $param=$this->app->request->params([
            'status'=>['status','='],
            'username'=>['username','like'],
            'last_login_time'=>['last_login_time','between time','~'],
            'reg_time'=>['reg_time','between time','~'],
        ]);
        $data=$ucenterService->getListPage($param);
        return $this->layuiJson($data->toArray());
    }

    /**
     * 更新密码
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function passwordModify()
    {
        $this->initView();
        $view=View::fetch('password');
        return $this->template($view,1,'更新密码');
    }


    /**
     * 更改密码
     * @param UserCenterService $userCenterService
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author staitc7 <static7@qq.com>
     */
    public function submitPassword(UserCenterService $userCenterService)
    {
        $param=$this->app->request->param();
        $userCenterService->updatePassword($param);
        UserInfo::logout();
        return $this->success('修改成功,请您重新登录!', 'Login/index');
    }

    /**
     * 设置状态
     * @author staitc7 <static7@qq.com>
     * @param UserCenterService $ucenterService
     * @return mixed
     */
    public function setstatus(UserCenterService $ucenterService)
    {
        $param=$this->app->request->param();
        $info=$ucenterService->setstatus($param);
        if ($info === false) {
            return $this->error($ucenterService->getError());
        }
        return $this->success('更新成功');
    }
}