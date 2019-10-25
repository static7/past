<?php
/**
 * Description of UcenterMember.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/6/20 18:24
 */

namespace app\admin\repository;

use app\admin\model\UserCenter;
use app\admin\traits\BaseRepository;
use think\facade\{
    Request,Config
};

class UserCenterRepository
{
    use BaseRepository;

    private $model;

    public function __construct()
    {
        $this->model=new UserCenter();
    }

    /**
     * 用户登录
     * @param string|null $username
     * @param string|null $password
     * @param int|null    $type
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function userLogin(?string $username = null, ? string $password = null, ?int $type = null)
    {
        switch ($type) {
            case 1:
                $field = 'username';
                break;
            case 2:
                $field = 'email';
                break;
            case 3:
                $field = 'mobile';
                break;
            case 4:
                $field = 'id';
                break;
            default:
                $this->error='用户名不正确 -103';
                return false;
        }
        //获取用户数据
        $object=$this->model->where($field,'=', $username)->field(['id', 'status', 'password'])->find();
        if (!$object || (int)$object->status !== 1) {
            $this->error='用户不存在或被禁用';
            return false;
        }
        //验证用户密码
        if (ucenter_md5($password) !== $object->password) {
            $this->error='用户名或者密码错误';
            return false;
        }
        //更新用户登录信息
        $object->last_login_time=Request::time();
        $object->last_login_ip=ip2long(Request::ip());
        $object->save();
        return (int)$object->id;
    }


    /**
     * 验证用户密码
     * @author staitc7 <static7@qq.com>
     * @param int|null    $userId
     * @param null|string $password
     * @return mixed
     */
    public function verifyPassword(?int $userId=0,?string $password=null)
    {
        $UserCenter=$this->model;
        $data = $UserCenter->verifyUserPassword($userId,$password);
        if ($data===false){
            $this->error=$UserCenter->getError();
            return false;
        }
        return $data;
    }

    /**
     * 菜单列表
     * @param array $map 条件
     * @param array $field 字段
     * @param array $order 排序
     * @param array $query 额外参数
     * @return mixed
     * @throws \think\db\exception\DbException
     * @author staitc7 <static7@qq.com>
     */
    public function getListPage(?array $map = [], ?array $field = null, ?array $order = null, ?array $query = [])
    {
        $object = $this->model->where(array_values($map))
            ->field($field)
            ->order($order ?: ['id' => 'asc'])
            ->paginate([
                'query' => $query,
                'page' => Request::param('page',1,'strip_tags,intval'),
                'list_rows' =>Request::param('limit',Config::get('paginate.list_rows'),'strip_tags,intval')
            ]);
        if ($object->isEmpty()){
            return null;
        }
        return $object->hidden(['password']);
    }

    /**
     * 获得菜单全部数据
     * @param array $map 条件
     * @param array $field 字段
     * @param array $order 排序
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function lists(?array $map = [], ?array $field = null, ?array $order = null)
    {
        $object = $this->model->where(array_values($map))
            ->field($field ?: null)
            ->order($order ?: ['id' => 'asc'])
            ->select();
        return $object ?: null;
    }

    /**
     * 更新密码
     * @param int|null    $userId 用户ID
     * @param null|string $password 新密码
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function updatePassword(?int $userId = null, ?string $password = null)
    {
        $data=$this->model->update(['password'=>$password,'id'=>$userId]);
        if ($data === false) {
            $this->error = $this->model->getError();
            return false;
        }
        return $data;
    }

    /**
     * 验证用户密码
     * @param int         $userId 用户id
     * @param string|null $password
     * @return true 验证成功，false 验证失败
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author huajie <banhuajie@163.com>
     */
    public function verifyUserPassword(int $userId, ?string $password='')
    {
        $object=$this->model->where('id','=',  $userId)->field(['id','password'])->find();
        if (ucenter_md5($password) === $object->password) {
            return true;
        }
        return false;
    }
}