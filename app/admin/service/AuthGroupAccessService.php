<?php
/**
 * Description of AuthGroupAccessService.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/7/23 17:50
 */

namespace app\admin\service;

use app\admin\repository\{
    AuthGroupAccessRepository,MemberRepository
};
use app\admin\traits\BaseService;
use think\facade\Config;

class AuthGroupAccessService
{
    use BaseService;

    public function __construct()
    {
        $this->repository=new AuthGroupAccessRepository();
    }

    /**
     * 获取组权限列表
     * @author staitc7 <static7@qq.com>
     * @param array|null $param
     * @return mixed
     */
    public function getAuthGroupAccessList(?array $param=[])
    {
        if (!isset($param['group_id']) || empty($param['group_id'])){
            return $this->result([],1,'请选择权限组别');
        }
        $map=[
            'group_id'=>['group_id','=',(int)$param['group_id']]
        ];
        return $this->repository->getListPage($map,null,['user_id'=>'asc'],['user']);
    }

    /**
     * 用户所在组
     * @param array|null $param
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function getUserGroupList(?array $param=[])
    {
        if (!isset($param['user_id']) || empty($param['user_id'])){
            return $this->error('用户ID不能为空');
        }
        if ((int)Config::get('app.user_administrator') === (int)$param['user_id']) {
            return $this->error("对超级管理员授权无效");
        }
        $map=[
            'user_id'=>['user_id','=',(int)$param['user_id']]
        ];
        return $this->repository->getList($map);
    }


    /**
     * 添加用户到用户组
     * @author staitc7 <static7@qq.com>
     * @param array|null $param
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function addUserToGroup(?array $param=[])
    {
        if (!isset($param['user_id']) || empty($param['user_id'])){
            return $this->error('用户ID不能为空');
        }

        if (!isset($param['group_id']) || empty($param['group_id'])){
            return $this->error('用户组ID不能为空');
        }
        //检查用户组
        $group=$this->checkGroup($param['group_id']);
        $userArray = array_unique(array_filter(explode(',', $param['user_id'])));
        $this->checkUser($userArray);
        return $this->repository->addToGroup($userArray,$group);
    }


    /**
     * 添加用户到多个组
     * @param array|null $param
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function addUsersToMultipleGroups(?array $param=[])
    {
        if (!isset($param['user_id']) || empty($param['user_id'])){
            return $this->error('用户ID不能为空');
        }
        if ((int)Config::get('app.user_administrator') === (int)$param['user_id']) {
            return $this->error("对超级管理员授权无效");
        }
        if (!isset($param['group']) || empty($param['group'])){
            return $this->error('用户组不能为空');
        }
        return $this->repository->addUsersToMultipleGroups((int)$param['user_id'],$param['group']);

    }

    /**
     * 将用户从组中删除
     * @author staitc7 <static7@qq.com>
     * @param array|null $param
     * @return mixed
     * @throws \Exception
     */
    public function removeFromGroup(?array $param=[])
    {
        if (isset($param['user_id'])===false || empty($param['user_id'])){
            return $this->error('用户ID不能为空');
        }
        if (isset($param['group_id'])===false || empty($param['group_id'])){
            return $this->error('权限组不能为空');
        }
        return $this->repository->removeUserFromGroup($param['user_id'],$param['group_id']);
    }

    /**
     * 批量移除用户
     * @author staitc7 <static7@qq.com>
     * @param array|null $param
     * @return mixed
     * @throws \Exception
     */
    public function removeToGroup(?array $param=[])
    {
        if (isset($param['user_id']) ===false || empty($param['user_id'])){
            return $this->error('用户ID不能为空');
        }
        if (isset($param['group_id'])===false || empty($param['group_id'])){
            return $this->error('权限组不能为空');
        }
        $users=explode(',',$param['user_id']);
        return $this->repository->removeToGroup($users,$param['group_id']);
    }

    /**
     * 检查用户是否存在/是否为超级管理员
     * @param array|null $user
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function checkUser(?array $user=[])
    {
        if (in_the_array(Config::get('app.user_administrator'),$user)){
            return $this->error("添加用户中不能包含超级管理员");
        }
        $map=[
            'user_id'=>['user_id','in',$user],
            'status'=>['status','=',1]
        ];
        $members=(new MemberRepository())->getList($map,['user_id']);
        if (empty($members)){
            return $this->error('用户不存在');
        }
        $members=array_column($members->toArray(),'user_id');
        $difference=array_diff($user,$members);
        if (empty($difference) === false){
            return $this->error("用户编号 ".implode(',',$difference)." 用户不存在");
        }
        return true;
    }

    /**
     * 检测用户是否存在,并返回main_id
     * @author staitc7 <static7@qq.com>
     * @param int|null $group_id
     * @return mixed
     */
    public function checkGroup(?int $group_id=0)
    {
        $data=(new AuthGroupService())->first($group_id,['id','main_id']);
        if (empty($data)){
            return $this->error('用户组不存在');
        }
        return $data->toArray();
    }


}