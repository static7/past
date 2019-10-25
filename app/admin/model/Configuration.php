<?php
/**
 * Description of Deploy.php.
 * User: static7 <static7@qq.com>
 * Date: 2017-08-03 14:31
 */

namespace app\admin\model;

use app\admin\traits\Models;
use think\Model;
use think\facade\{Config};

class Configuration extends Model
{
    use Models;
    protected $autoWriteTimestamp = true; //自动写入创建和更新的时间戳字段
    protected $insert             = ['status' => 1];
    protected $update             = [];


    /*====================获取器====================*/

    /**
     * 获取配置的分组
     * @param  $value 配置分组
     * @return string
     */
    function getGroupAttr($value)
    {
        $list = Config::get('app.config_group_list');
        if (isset($list[ (int)$value ]) && empty($list[ (int)$value ]) === false) {
            return $list[ (int)$value ];
        }
        return '系统默认';
    }

    /**
     * 配置区域
     * @author staitc7 <static7@qq.com>
     * @param $value
     * @return mixed
     */
    public function getAreaAttr($value)
    {
        return is_numeric($value) ? change_status($value, ['前后台', '前台', '后台']) : null;
    }

    /**
     * 配置类型
     * @author staitc7 <static7@qq.com>
     * @param $value
     * @return mixed
     */
    public function getTypeAttr($value)
    {
        $list = Config::get('app.config_type_list');
        if (isset($list[ (int)$value ]) && empty($list[ (int)$value ]) === false) {
            return $list[ (int)$value ];
        }
        return '默认';
    }


}