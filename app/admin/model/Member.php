<?php
/**
 * Description of Member.php.
 * User: static7 <static7@qq.com>
 * Date: 2017-04-29 18:28
 */

namespace app\admin\model;

use think\{
    Model,Exception
};
use app\admin\traits\Models;
use think\facade\{
    Request,Config,Log,App
};

class Member extends Model
{

    use Models;
    protected $pk = 'user_id';
    protected $autoWriteTimestamp = true;
    // 定义时间戳字段名
    protected $update = [];
    protected $insert = ['reg_ip'=>'','reg_time'=>''];

    /**
     * 会员注册
     * @param array|null  $data
     * @param null|string $scene
     * @param string      $validateName
     * @return mixed
     * @throws Exception
     * @author staitc7 <static7@qq.com>
     */
    public function register(?array $data = null, ?string $scene = null, $validateName = '')
    {
        //获取验证器
        if (empty($validateName)) {
            $className    = explode('\\', get_class());
            $validateName = end($className);
        }
        $namespace = App::getNamespace() . '\\validate\\' . $validateName;
        $validate  = new $namespace;
        // 验证失败 输出错误信息
        if ($validate->scene($scene ?: '')->check($data) === false) {
            $this->error = $validate->getError();
            return false;
        }
        $this->startTrans();
        try {
            //合并数组
            if (property_exists($this, 'insert') === true && empty($this->insert) === false && is_array($this->insert) === true) {
                $data = array_merge($this->insert, $data);
            }
            $object = $this->create($data);
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new Exception('数据库操作异常:' . $e, 10006);
        }
        return $object ?: null;
    }

    /**
     * 定义相对关联  日志表
     * @author staitc7 <static7@qq.com>
     * @return mixed
     */
    public function actionLog()
    {
        return $this->belongsTo(ActionLog::class,'user_id','user_id');
    }

    /**
     * 定义相对关联  日志表
     * @author staitc7 <static7@qq.com>
     * @return mixed
     */
    public function documentUserId()
    {
        return $this->belongsTo(Document::class,'user_id','user_id');
    }

    /*================获取器================*/

    /**
     * 最后登录时间转换
     * @author staitc7 <static7@qq.com>
     * @param $value 修改的值
     * @return string
     */
    public function getLastLoginTimeAttr($value) {
        return $value ? date('Y-m-d H:i:s', $value) : '';
    }

    /**
     * 注册时间
     * @param $value
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getRegTimeAttr($value)
    {
        return empty($value) === false ? date('Y-m-d H:i:s', $value) : '';
    }

    /**
     * 最后登录IP转换
     * @author staitc7 <static7@qq.com>
     * @param $value 修改的值
     * @return string
     */
    public function getLastLoginIpAttr($value) {
        return $value ? long2ip($value) : '';
    }

    /**
     * 出日期转换
     * @author staitc7 <static7@qq.com>
     * @param $value
     * @return mixed
     */
    public function getBirthdayAttr($value)
    {
        return (int)$value > 0 ? date('Y-m-d',$value) : '';
    }

    /*================设置器================*/


    /**
     * 设置注册时间
     * @author staitc7 <static7@qq.com>
     * @param $value
     * @return mixed
     */
    public function setRegTimeAttr($value)
    {
        if (empty($value) === true){
            return Request::time();
        }
        if (is_int($value) === true){
            return $value;
        }
        return strtotime($value);
    }

    /**
     * 获取ip
     * @param $value
     * @return int
     * @author staitc7 <static7@qq.com>
     */
    public function setRegIpAttr($value)
    {
        if (empty($value) === true){
            return ip2long(Request::ip());
        }
        if (is_int($value) === false){
            return ip2long($value);
        }
        return $value;
    }
}