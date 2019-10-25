<?php
/**
 * Description of AuthRule.php.
 * User: static7 <static7@qq.com>
 * Date: 2017-08-09 14:33
 */

namespace app\admin\model;


use app\admin\traits\Models;
use think\Model;
use think\facade\{
    Request
};

class AuthRule extends Model
{
    use Models;
    protected $insert = ['status' => 1];

}