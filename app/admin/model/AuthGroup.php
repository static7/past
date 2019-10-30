<?php
/**
 * Description of AuthGroup.php.
 * User: static7 <static7@qq.com>
 * Date: 2017-08-03 12:01
 */

namespace app\admin\model;

use app\admin\traits\Models;
use think\facade\{Config, Request};
use think\{
    Model,Container
};

class AuthGroup extends Model
{
    use Models;
    protected $insert = [
        'status'      => 1,
        'type'        => '',
        'module'      => '',
    ];
    protected $update = [
        'type' => ''
    ];


    /*==============数据自动完成==============*/

    /**
     * 自动获取模块
     * @param $value
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    protected function setModuleAttr($value)
    {
        return $value ?: Container::getInstance()->make('http')->getName();
    }


    /**
     * 过滤非法字符description
     * @param $value
     * @return string
     * @author staitc7 <static7@qq.com>
     */

    public function setDescriptionAttr($value)
    {
        return $value ? htmlspecialchars($value) : "";
    }

    /**
     * 过滤非法字符description
     * @param $value
     * @return string
     * @author staitc7 <static7@qq.com>
     */

    public function setTitleAttr($value)
    {
        return $value ? htmlspecialchars($value) : "";
    }

    /**
     * 组类型 type
     * @author staitc7 <static7@qq.com>
     */

    public function setTypeAttr()
    {
        return Config::get('app.auth_config.type_admin',0);
    }

}