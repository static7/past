<?php
/**
 * Description of AuthGroupAccess.php.
 * User: static7 <static7@qq.com>
 * Date: 2017-08-09 16:26
 */

namespace app\admin\model;

use app\admin\traits\Models;
use think\facade\Config;
use think\Model;

class AuthGroupAccess extends Model
{
    use Models;
    protected $autoWriteTimestamp = false;

    /**
     * 一对一关联 用户表
     * @author staitc7 <static7@qq.com>
     * @return mixed
     */
    public function user()
    {
        $config=Config::get('app.auth_config.auth_user',[]);
        return $this->hasOne($config['table'],$config['primaryKey'],'user_id')
            ->where('status','=',1)
            ->field(['user_id','nickname','last_login_time','last_login_ip','status']);
    }



    /**
     * 一对一关联 权限组表
     * @author staitc7 <static7@qq.com>
     * @return mixed
     */
    public function authGroup()
    {
        $config=Config::get('app.auth_config.auth_group');
        return $this->hasOne($config,'id','group_id')
            ->where('status','=',1)
            ->field(['id','rules']);
    }
    
}