<?php
/**
 * Description of models.php.
 * User: static7 <static7@qq.com>
 * Date: 2017-04-29 18:25
 */

namespace app\admin\traits;

use think\Exception;
use think\facade\{App};

trait Models
{
    private $error;

    /**
     * 捕获错误
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getError()
    {
        if (empty($this->error) === false) {
            return $this->error;
        }
        return $this->model->getError();
    }

    /**
     * 更新或者添加
     * @param array       $data 数据
     * @param string|null $scene 验证场景
     * @param string|null $primary 主键
     * @param string      $validateName 验证器类名
     * @return bool
     * @throws Exception
     * @author staitc7 <static7@qq.com>
     */

    public function renew(?array $data = [], ?string $scene = null, ?string $primary = null, $validateName = '')
    {
        //获取验证器
        if (empty($validateName) === true) {
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
        $primary = $primary ?: $this->getPk();
        $this->startTrans();
        try {
            if (isset($data[ $primary ]) === true && empty($data[ $primary ]) === false) {
                $object = $this->update($this->automaticMerge($data));
            } else {
                $object = $this->create($this->automaticMerge($data));
            }
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new Exception('数据库操作异常:' . $e, 10086);
        }
        return $object ?: null;
    }


    /**
     * 修改状态
     * @param int|array $map 数据的ID或者ID组
     * @param array     $data 要修改的数据
     * @return bool|int
     * @author staitc7 <static7@qq.com>
     */

    public function setStatus(?array $map = [], ?array $data = [])
    {
        if (empty($map) || empty($data)) {
            $this->error = '参数或数据更新为空';
            return false;
        }
        return $this->update($data, array_values($map));
    }

    /**
     * 自动合并默认数据 根据主键来
     * @param array|null  $data
     * @param string|null $primary
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function automaticMerge(?array $data=[],?string $primary=null)
    {
        if (empty($data) === true){
            return $data;
        }
        if (isset($data[ $primary ]) === true && empty($data[ $primary ]) === false) {
            if (property_exists($this, 'update') === true && empty($this->update) === false && is_array($this->update) === true) {
                return array_merge($this->update,$data);
            }
        }else{
            if (property_exists($this, 'insert') === true && empty($this->insert) === false && is_array($this->insert) === true) {
                return array_merge($this->insert,$data);
            }
        }
        return $data;
    }
}