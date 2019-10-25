<?php
/**
 * Description of Picture.php.
 * User: static7 <static7@qq.com>
 * Date: 2017/9/18 23:05
 */

namespace app\admin\model;

use Exception;
use think\facade\{Log, Request};
use think\Model;

class File extends Model
{
    protected $autoWriteTimestamp = true; //自动写入创建时间戳字段
    protected $updateTime = false;// 关闭自动写入update_time字段
    protected $insert = ['status' => 1];

    /**
     * 图片添加或者更新
     * @param array|null $data
     * @return mixed
     * @throws Exception
     * @author staitc7 <static7@qq.com>
     */
    public function renew(?array $data = [])
    {
        if (empty($data) === true) {
            $this->error = '参数错误!';
            return false;
        }
        $this->startTrans();
        try {
            if (isset($data[ $this->pk ]) === true && (int)$data[ $this->pk ] > 0) {
                $object = $this->update($data);
            } else {
                //合并数组
                if (property_exists($this, 'insert') === true && empty($this->insert) === false && is_array($this->insert) === true) {
                    $data = array_merge($this->insert, $data);
                }
                $object = $this->create($data);
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw new Exception('数据库操作异常:' . $e, 10006);
        }
        return $object->visible(['id', 'md5', 'url', 'path', 'file_name', 'sha1', 'original_name'])->toArray();
    }
}