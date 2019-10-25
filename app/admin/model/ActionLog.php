<?php
/**
 * Description of Action.php.
 * User: static7 <static7@qq.com>
 * Date: 2017-07-25 15:42
 */

namespace app\admin\model;


use app\admin\traits\Models;
use app\facade\UserInfo;
use think\{
    facade\Request, Model
};


class ActionLog extends Model
{
    use Models;
    protected $autoWriteTimestamp = true;
    protected $updateTime =false;
    protected $insert = ['status' => 1,'ip'=>'','user_id'=>''];
    // 设置json类型字段
    protected $json = ['data','header'];
    // 设置JSON数据返回数组
    protected $jsonAssoc = true;

    /**
     * 行为标题
     * @author staitc7 <static7@qq.com>
     * @return mixed
     */
    public function actionTitle()
    {
        return $this->hasOne(Action::class,'id','action_id')
            ->where('status','=',1)
            ->field(['title'])
            ->bind(['title']);
    }

    /**
     * nickname
     * @author staitc7 <static7@qq.com>
     * @return mixed
     */
    public function userCenter()
    {
        return $this->hasOne(UserCenter::class,'id','user_id')
            ->where('status','=',1)
            ->field(['username'])
            ->bind(['username']);
    }

    /*====================获取器====================*/

    /**
     * IP地址转换
     * @author staitc7 <static7@qq.com>
     * @param $value 值
     * @return mixed
     */
    public function getIpAttr($value)
    {
        return $value ? long2ip($value) : '';
    }

    /*====================修改器====================*/

    /**
     * 设置ip
     * @author staitc7 <static7@qq.com>
     * @param $value
     * @return mixed
     */
    public function setIpAttr($value)
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
     * 设置用户id
     * @author staitc7 <static7@qq.com>
     * @param $value
     * @return mixed
     */
    public function setUserIdAttr($value)
    {
     return $value ?: UserInfo::getUserId();
    }

}