<?php
/**
 * Description of UserInfo.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/6/22 14:37
 */

namespace app\internal;

use app\admin\model\UserCenter;
use think\facade\{Config, Log, Request, Session, App, Cache};

class UserInfo
{
    /**
     * 自动登录用户
     * @param array $data
     * @return void
     */
    public function autoLogin(?array $data = []): void
    {
        //缓存用户信息
        Session::set('user_auth', $data);
        Session::set('user_auth_sign', data_auth_sign((array)$data));
        return;
    }

    /**
     * 模块名称
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    protected function getPrefix(): ?string
    {
        return Config::get('app.user_prefix', 'app') . '_';
    }

    /**
     * 获取用户信息
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getUserInfo(): ?array
    {
        $userId = $this->getUserId();
        if (empty($userId) === true) {
            return [];
        }
        $data = Session::get('user_auth',null);
        return $data ?: [];
    }

    /**
     * 检测用户是否登录
     * @return integer 0-未登录，大于0-当前登录用户ID
     * @author staitc7 <static7@qq.com>
     */
    public function getUserId(): ?int
    {
        $user = Session::get('user_auth', null);
        if (empty($user) === true) {
            return 0;
        }
        return Session::get('user_auth_sign', null) == data_auth_sign((array)$user) ? (int)$user['user_id'] : null;
    }

    /**
     * 检测是否是超级管理员
     * @param int|null $user_id
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function checkAdministrator(?int $user_id = 0): bool
    {
        $userId = (int)$user_id ?: $this->getUserId();
        if ((int)$userId === (int)Config::get('app.user_administrator',1)) {
            return true;
        }
        return false;
    }

    /**
     * 检测用户是否在线
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function online(): ?bool
    {
        return $this->getUserId() > 0 ? true : false;
    }

    /**
     * 注销当前用户
     * @return bool
     */
    public function logout(): ?bool
    {
        Session::clear();
        return true;
    }


    /**
     * 根据用户ID获取用户昵称
     * @return string       用户昵称
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function getNickName(): ?string
    {
        $info = $this->getMemberInfo($this->getUserId());
        return $info['nickname'] ?? '';
    }

    /**
     * 设置会员信息
     * @param int|null $userId
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function getMemberInfo(?int $userId = null)
    {
        $userAuth = Session::get("user_auth", null);
        if (isset($userAuth['username']) === true) {
            return $userAuth;
        }
        $userId = $userId ?: $this->getUserId();
        $data   = UserCenter::where('id', '=', $userId)->hidden(['password'])->find();
        if (empty($data) === true) {
            return null;
        }
        $userAuth['avatar'] = get_files($userAuth['avatar']);
        $userInfo           = array_merge($data->toArray(), $userAuth ?? []);
        $this->autoLogin($userInfo);
        return $userInfo;
    }

    /**
     * 获取用户字段
     * @param string|null $field 字段
     * @param string|null $default 默认值
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function getUserInfoField(?string $field = null, ?string $default = null)
    {
        $data = $this->getMemberInfo();
        if (empty($data) === true) {
            return null;
        }
        if (empty($field) === true) {
            return $data;
        }
        return isset($data[ $field ]) ? $data[ $field ] : $default;
    }

    /**
     * 刷新用户session信息
     * @param array|null $data
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function userInfoRefresh(?array $data = [])
    {
        if ((int)$this->getUserId() <= 0) {
            return false;
        }
        $userInfo = Session::get('user_auth', null);
        if (empty($userInfo) === true) {
            return false;
        }
        $newInfo = array_merge($userInfo, $data);
        $this->autoLogin($newInfo);
        unset($newInfo, $userInfo);
        return true;
    }
}

