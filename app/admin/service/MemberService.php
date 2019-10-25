<?php
/**
 * Description of Member.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/6/20 18:09
 */

namespace app\admin\service;

use app\admin\repository\{MemberRepository};
use app\admin\traits\BaseService;
use app\facade\UserInfo;
use think\facade\Event;
use think\facade\Hook;

class MemberService
{
    use BaseService;

    //字段映射
    protected $mapper = [
        'id'       => 'user_id',
        'status'   => 'status',
        'username' => 'nickname',
    ];

    /**
     * Member constructor.
     */
    public function __construct()
    {
        $this->repository = new MemberRepository();
    }

    /**
     * 获取登录信息
     * @param array|null $param
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getMemberListPage(?array $param = [])
    {
        $map = array_merge([
            'status' => ['status', '>=', 0]
        ], $param ?? []);
        return $this->repository->getListPage($map, null, ['user_id' => 'desc']);
    }

    /**
     * 用户登录
     * @param int|null $userId
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function memberlogin(?int $userId = null)
    {
        $memberInfo = $this->repository->memberlogin($userId);
        if ($memberInfo === false) {
            return $this->error($this->repository->getError());
        }
        //处理用户登录信息
        UserInfo::autoLogin($memberInfo->toArray());
        //等户登录日志
        Event::trigger('UserLoginRecord',$memberInfo->toArray());
        return $memberInfo;
    }

    /**
     * 获取用户信息
     * @param array|null $param
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getMemberList(?array $param = [])
    {
        $map = array_merge([
            'status' => ['status', '>=', 0]
        ], $param);
        return $result = $this->repository->getList($map, null, ['user_id' => 'desc']);
    }

    /**
     * 设置状态
     * @param array|null $param
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function setStatus(?array $param = [])
    {
        if (!isset($param['value']) || (string)$param['value'] === '') {
            return $this->error('更新数据值错误');
        }
        $data = ['status' => (int)$param['value']];
        if (!isset($param['user_id']) || empty($param['user_id'])) {
            return $this->error('用户ID不能为空');
        }
        $this->checkAdministrator($param['user_id']);
        $map = $this->primaryKey($param['user_id'] ?? null, 'user_id');
        return $this->repository->setStatus($map, $data);
    }

    /**
     * 注册用户
     * @param array|null $param
     * @return mixed
     * @throws \think\Exception
     * @author staitc7 <static7@qq.com>
     */
    public function userRegister(?array $param = [])
    {
        $UcenterService = new UserCenterService();
        $data           = $UcenterService->register($param);
        if ($data === false) {
            return $this->error($UcenterService->getError());
        }
        $memberData = $this->mapField($data->toArray());
        return $this->repository->register($memberData);
    }

    /**
     * 获取用户信息
     * @param array|null $param
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function first(?array $param = [])
    {
        if (!isset($param['user_id']) && empty($param['user_id'])) {
            return $this->error('用户ID错误');
        }
        return $this->repository->first($param['user_id']);
    }

    /**
     * 更新用户昵称
     * @param null|string $nickname
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function updateNickname(?string $nickname = null)
    {
        if (empty($nickname)) {
            return $this->error('昵称不能为空');
        }
        return $this->repository->updateNickname(UserInfo::getUserId(), $nickname);
    }

    /**
     * 字段映射
     * @param array|null $array
     * @param array|null $field 字段集
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function mapField(?array $array = [], ?array $field = [])
    {
        if (empty($field) === false) {
            $this->mapper = $field;
        }
        if (empty($this->mapper)) {
            return $array;
        }
        $data = [];
        foreach ($this->mapper as $key => $value) {
            if (array_key_exists($key, $array) === true) {
                $data[ $value ] = $array[ $key ];
            }
        }
        unset($array);
        return $data;
    }


    /**
     * 保存头像
     * @param int|null $avatarId
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function saveAvatar(?int $avatarId = 0)
    {
        if (empty($avatarId)) {
            return $this->error('头像ID不能为空');
        }
        return $this->repository->saveAvatar(UserInfo::getUserId(), $avatarId);
    }

}