<?php

namespace app\listener\admin;

use app\admin\service\ActionLogService;
use think\facade\Request;

class UserLoginRecord
{
    /**
     * 执行日志句柄
     * @param $info
     */
    public function handle($info)
    {
        $data = [
            'type'    => 1, //登录日志
            'data'    => $info,
            'url'     => Request::url(),
            'header'  => Request::header(),
            'user_id' => $info['user_id'],
            'ip'      => $info['last_login_ip'],
            'remark'  => "{$info['nickname']} 在 {$info['last_login_time']} 登录了系统",
        ];
        $this->loginRecord($data);
    }

    /**
     * 保存的登录日志
     * @param array|null $param
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function loginRecord(?array $param = [])
    {
        if (empty($param) === true){
            return false;
        }
        $ActionLog=new ActionLogService();
        $ActionLog->renew($param);
        return true;
    }
}
