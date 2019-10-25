<?php
/**
 * Description of UserInfo.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/6/22 14:38
 */

namespace app\facade;


use think\Facade;

/**
 * @see \app\internal\UserInfo
 * @mixin \app\internal\UserInfo
 */

class UserInfo extends Facade
{

    protected static function getFacadeClass()
    {
        return 'app\internal\UserInfo';
    }
}