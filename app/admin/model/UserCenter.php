<?php

namespace app\admin\model;

use app\admin\traits\Models;
use think\Model;
use think\facade\{Log, Request, App};

/**
 * Description of UcenterMember
 * 会员模型
 * @property  error
 * @author static7
 */
class UserCenter extends Model
{
    use Models;
    protected $autoWriteTimestamp = true;
    protected $insert = [
        'status' => 1,
        'reg_ip' => '',
        'reg_time' => '',
    ];


    /*===============获取器===============*/

    /**
     * 注册ip
     * @param $value
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getLastLoginIpAttr($value)
    {
        return empty($value) === false ? long2ip($value) : '';
    }

    /**
     * 最后登录时间
     * @param $value
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getLastLoginTimeAttr($value)
    {
        return empty($value) === false ? date('Y-m-d H:i:s', $value) : '';
    }

    /**
     * 注册时间
     * @param $value
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getRegTimeAttr($value)
    {
        return empty($value) === false ? date('Y-m-d H:i:s', $value) : '';
    }

    /**
     * 注册ip
     * @param $value
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getRegIpAttr($value)
    {
        return empty($value) === false ? long2ip($value) : '';
    }

    /*===============修改器===============*/

    /**
     * 设置ip
     * @param $value
     * @return int
     * @author staitc7 <static7@qq.com>
     */
    public function setRegIpAttr($value)
    {
        if (empty($value) === true) {
            return ip2long(Request::ip());
        }
        if (is_int($value) === false) {
            return ip2long($value);
        }
        return $value;
    }

    /**
     * 设置注册时间
     * @param $value
     * @return int
     * @author staitc7 <static7@qq.com>
     */
    public function setRegTimeAttr($value)
    {
        return empty($value) === true ? Request::time():$value;
    }


    /**
     * 最后登录ip
     * @param $value
     * @return int
     * @author staitc7 <static7@qq.com>
     */
    public function setLastLoginIpAttr($value)
    {
        if (empty($value) === true) {
            return ip2long(Request::ip());
        }
        if (is_int($value) === false) {
            return ip2long($value);
        }
        return $value;
    }

    /**
     * 最后登录时间
     * @author staitc7 <static7@qq.com>
     */
    public function setLastLoginTimeAttr()
    {
        return Request::time();
    }

    /**
     * 添加密码
     * @param $value
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function setPasswordAttr($value)
    {
        return ucenter_md5($value);
    }

}
