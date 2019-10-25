<?php
/**
 * Description of QcloudCosClient.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/8/4 21:22
 */

namespace app\facade;


use think\Facade;
/**
 * @see \app\internal\QcloudCosClient
 * @mixin \app\internal\QcloudCosClient
 *
 */
class QcloudCosClient extends Facade
{
    protected static function getFacadeClass()
    {
        return 'app\internal\QcloudCosClient';
    }
}