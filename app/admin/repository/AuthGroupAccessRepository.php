<?php
/**
 * Description of AuthGroupAccessRepository.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/7/23 17:49
 */

namespace app\admin\repository;


use app\admin\model\AuthGroupAccess;
use app\admin\traits\BaseRepository;

class AuthGroupAccessRepository
{
    use BaseRepository;

    private $model;

    public function __construct()
    {
        $this->model=new AuthGroupAccess();
    }

    /**
     * 添加用户到用户组
     * @param array|null $user
     * @param array|null $group
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author staitc7 <static7@qq.com>
     */
    public function addToGroup(?array $user=null,?array $group=null)
    {
        $model=$this->model;
        $repeat = [];
        foreach ($user as $v) {
            //检查用户是否已经所在该用户组
            $object=$model->where('group_id','=',$group['id'])->where('user_id','=',$v)->field(['user_id'])->find();
            if (empty($object) === false) {
                $repeat[] = $object->user_id;
                continue;
            }
            $model->create(['group_id' => $group['id'], 'user_id' => $v,'main_id'=>$group['main_id']]);
        }

        if (empty($repeat) === false){
            $this->error='用户编号' . implode(',', $repeat) . '已经加入该组，不再重复添加';
            return false;
        }
        return true;
    }


    /**
     * 将用户添加到多个组
     * @param int|null   $userId
     * @param array|null $group
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function addUsersToMultipleGroups(?int $userId=null,?array $group=[])
    {
        $AuthGroupAccess=$this->model;
        //删除原来的用户组
        $AuthGroupAccess->where('user_id','=', $userId)->delete();
        //添加新的用户组
        if (empty($group) === false) {
            foreach ($group as $k=>&$v) {
                if (isset($v['group_id']) && empty($v['group_id']) === false){
                    $v['user_id']=$userId;
                }else{
                    unset($group[$k]);
                }
            }
        }
        return empty($group) ? true : $AuthGroupAccess->saveAll($group);
    }

    /**
     * 用户移除组
     * @author staitc7 <static7@qq.com>
     * @param int|null $user_id
     * @param int|null $group_id
     * @return mixed
     * @throws \Exception
     */
    public function removeUserFromGroup(?int $user_id=0,?int $group_id=0)
    {
        return $this->model->where('user_id','=',$user_id)
            ->where('group_id','=',$group_id)
            ->delete();
    }

    /**
     * 批量移除用户组
     * @param array|null $user
     * @param int        $group_id
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function removeToGroup(?array $user=[],int $group_id=0)
    {
        return $this->model->where('user_id','in',$user)
            ->where('group_id','=',$group_id)
            ->delete();
    }

}