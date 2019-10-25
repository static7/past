<?php
/**
 * Description of Channel.php.
 * User: static7 <static7@qq.com>
 * Date: 2017-08-04 15:24
 */

namespace app\admin\model;

use app\admin\traits\Models;
use think\{
    Container,Model
};

class Navigation extends Model
{
    use Models;
    protected $autoWriteTimestamp = true;
    protected $insert = ['status' => 1];

    /* =================自动完成================== */

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