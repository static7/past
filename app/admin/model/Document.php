<?php
/**
 * Description of Document.php.
 * User: static7 <static7@qq.com>
 * Date: 2017/10/6 18:56
 */

namespace app\admin\model;

use think\{
    Model,Exception
};
use app\facade\UserInfo;
use app\admin\traits\Models;
use think\facade\{Config, Cookie, Log, Request, App};

class Document extends Model
{
    use Models;
    protected $autoWriteTimestamp = true; //自动写入创建和更新的时间戳字段
    protected $createTime = false;
    protected $insert = ['status' => 1];
    protected $update = [];
    // 设置json类型字段
    protected $json = ['label'];

    // 设置JSON数据返回数组
    protected $jsonAssoc = true;

    /**
     * 更新或者添加
     * @param array       $data 数据
     * @param string|null $scene 验证场景
     * @param string|null $primary 主键
     * @return bool
     * @throws Exception
     * @author staitc7 <static7@qq.com>
     */

    public function renew(?array $data = [], ?string $scene = '', ?string $primary = null)
    {
        $validate =new \app\admin\validate\Document();
        // 验证失败 输出错误信息
        if ($validate->scene($scene)->check($data) === false) {
            $this->error = $validate->getError();
            return false;
        }
        $primary = $primary ?: $this->getPk();
        $this->startTrans();
        try {
            if (isset($data[ $primary ]) === true && empty($data[ $primary ]) === false) {
                //合并数组
                if (property_exists($this, 'update') === true
                    && empty($this->update) === false
                    && is_array($this->update) === true) {
                    $data = array_merge($this->update, $data);
                }
                $object = $this->update($data);
            } else {
                //合并数组
                if (property_exists($this, 'insert') === true
                    && empty($this->insert) === false
                    && is_array($this->insert) === true) {
                    $data = array_merge($this->insert, $data);
                }
                $object = $this->create($data);
            }
            $object->documentArticle()->save($data);
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new Exception('数据库操作异常:' . $e, 10086);
        }
        return $object ?: null;
    }

    /**
     * 关联文章类容
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function documentArticle()
    {
        return $this->hasOne(DocumentArticle::class, 'document_id', 'id')
            ->field(['content', 'file_id', 'keywords', 'bookmark', 'template', 'parse', 'document_id']);
    }

    /**
     * 文章分类名称
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function categoryTitle()
    {
        return $this->hasOne(Category::class , 'id', 'category_id')
            ->where('status', '=', 1)
            ->field(['id', 'title'])
            ->bind(['category' => 'title'])
            ;
    }

    /**
     * 获取用户昵称
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function memberNickname()
    {
        return $this->hasOne(Member::class, 'user_id', 'user_id')
            ->where('status', '=', 1)
            ->field(['user_id', 'nickname'])
            ->bind(['nickname']);
    }

    /* ===================获取器====================== */

    /**
     * 截止时间转化
     * @param $value
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getDeadlineAttr($value)
    {
        return $value ? time_format($value) : '';
    }

    /**
     * 创建时间转化
     * @param $value
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getCreateTimeAttr($value)
    {
        return $value ? time_format($value) : '';
    }

    /**
     * 设置标签
     * @param $value
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getLabelAttr($value)
    {
        if (empty($value) === true) {
            return '';
        }
        return implode(',', $value);
    }


    /* ===================自动完成====================== */
    /**
     * 设置用户ID
     * @param $value
     * @return \app\internal\UserInfo
     */
    public function setUserIdAttr($value)
    {
        return empty($value) === true ? UserInfo::getUserId() : $value;
    }

    /**
     * 过滤字符串
     * @param $value
     * @return string
     */
    public function setDescriptionAttr($value)
    {
        return $value ? htmlspecialchars($value) : '';
    }

    /**
     * 设置创建时间
     * @param $value
     * @return false|float|int
     */
    public function setCreateTimeAttr($value)
    {
        return $value ? strtotime($value) : Request::time();
    }

    /**
     * 设置过期时间
     * @param $value
     * @return false|int
     */
    public function setDeadlineAttr($value)
    {
        return $value ? strtotime($value) : 0;
    }

    /**
     * 设置标签
     * @param $value
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function setLabelAttr($value)
    {
        if (empty($value) === true) {
            return [];
        }
        return explode(',', $value);
    }

    /**
     * 设置位运算
     * @param $value
     * @return int
     */
    protected function setPositionAttr($value)
    {
        $pos = 0;
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $pos += (int)$v;  //将各个推荐位的值相加
            }
        }
        return $pos;
    }
}