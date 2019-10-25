<?php
/**
 * Description of Menu.php.
 * User: static7 <static7@qq.com>
 * Date: 2017-08-04 15:43
 */

namespace app\admin\model;


use app\admin\traits\Models;
use think\Container;
use think\facade\{Request, Config};
use think\Model;

class Menu extends Model
{
    use Models;
    protected $insert = ['status' => 1,'module'=>''];
    protected $update = ['module'=>''];


    /* =================自动完成===================== */

    /**
     * 设置module类型
     * @param $value
     * @return null|string
     */
    public function setModuleAttr($value)
    {
        if (empty($value) === false) {
            return $value;
        }
        return Container::getInstance()->make('http')->getName();
    }
}