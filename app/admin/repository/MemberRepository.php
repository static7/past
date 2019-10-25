<?php
/**
 * Description of Member.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/6/22 13:54
 */

namespace app\admin\repository;


use app\admin\model\Member;
use app\admin\traits\BaseRepository;
use think\facade\{App, Hook, Request};

class MemberRepository
{
    use BaseRepository;

    private $model;

    public function __construct()
    {
        $this->model= new Member();
    }

    /**
     * 登录用户信息
     * @author staitc7 <static7@qq.com>
     * @param int|null $userId
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function memberLogin(?int $userId)
    {
        $object = $this->model->where('user_id','=', $userId)->find();
        if (!$object || (int)$object->status !== 1) {
            $this->error = '用户不存在或已被禁用！';
            return false;
        }
        //更新登录信息
        $object->login++;
        $object->last_login_ip=ip2long(Request::ip());
        $object->save();
        return $object;
    }

    /**
     * 用户注册
     * @param array|null $param
     * @return mixed
     * @throws \think\Exception
     * @author staitc7 <static7@qq.com>
     */
    public function register(?array $param=[])
    {
        $object=$this->model->register($param);
        if ($object === false) {
            $this->error = $this->model->getError();
            return false;
        }
        return $object ?: null;
    }

    /**
     * 更新用户昵称
     * @author staitc7 <static7@qq.com>
     * @param int|null    $userId
     * @param null|string $nikename
     * @return mixed
     */
    public function updateNickname(?int $userId,?string $nikename=null)
    {
        $Member = $this->model;
        $object = $Member->update(['user_id' => $userId, 'nickname' => $nikename]);
        if ($object === false) {
            $this->error = $Member->getError();
            return false;
        }
        return $object ?: null;
    }

    /**
     * 更新头像
     * @author staitc7 <static7@qq.com>
     * @param int|null $userId
     * @param          $avatarId
     * @return mixed
     */
    public function saveAvatar(?int $userId,$avatarId)
    {
        return $this->model->where('user_id','=',$userId)->save(['avatar'=>$avatarId]);

    }
}