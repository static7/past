<?php
/**
 * Description of Tree.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/6/26 14:52
 */

namespace app\facade;

use think\Facade;
/**
 * @see \app\internal\Tree
 * @mixin \app\internal\Tree
 */

class Tree extends Facade
{

    protected static function getFacadeClass()
    {
        return 'app\internal\Tree';
    }
}