<?php
/**
 * Description of Links.php.
 * User: static7 <static7@qq.com>
 * Date: 2017/8/3 21:32
 */

namespace app\admin\model;


use app\admin\traits\Models;
use think\Model;

class Links extends Model
{
    use Models;
    protected $autoWriteTimestamp = true;
    protected $insert = ['status' => 1];
}