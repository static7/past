<?php
/**
 * Description of Banner.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/11/24 12:23
 */

namespace app\admin\model;


use app\admin\traits\Models;
use think\Model;

class Banner extends Model
{
    use Models;
    protected $autoWriteTimestamp = true; //自动写入创建和更新的时间戳字段
    protected $insert             = ['status' => 1];
    protected $update             = [];
    // 设置json类型字段
    protected $json = ['parameter'];


    /*==============关联================*/
    /**
     * 获取url链接
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function pictureUrl()
    {
        return $this->hasOne(File::class,'id','picture')
            ->where('status','=',1)
            ->field(['url','id'])
            ->bind(['picture_url'=>'url']);
    }

    /*==============获取器================*/

    /**
     * 转化url格式
     * @author staitc7 <static7@qq.com>
     * @param $value
     * @return mixed
     */
    public function getParameterAttr($value)
    {
        if (empty($value) === true){
            return '';
        }
        if (is_array($value)){
            return $value;
        }
        return http_build_query((array)$value);
    }

    /*==============自动完成================*/
    /**
     * 链接解析成数组
     * @author staitc7 <static7@qq.com>
     * @param $value
     * @return mixed
     */
    protected function setParameterAttr($value)
    {
        if (empty($value) === true){
            return [];
        }

        parse_str($value,$array);
        return $array;
    }
}