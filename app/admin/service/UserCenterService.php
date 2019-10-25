<?php
/**
 * Description of UcenterMember.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/6/20 18:23
 */

namespace app\admin\service;

use app\admin\traits\BaseService;
use app\facade\UserInfo;
use think\facade\{Config, Cookie, Request};
use app\admin\repository\{
    UserCenterRepository
};

class UserCenterService
{
    use BaseService;

    public function __construct()
    {
        $this->repository=new UserCenterRepository();
    }

    /**
     * 登录
     * @param array|null $param
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function login(?array $param = [])
    {
        $type=$this->analysisUsername($param['username']);
        $userId= $this->repository->userLogin($param['username'], $param['password'], $type);
        if ($userId === false) {
            return $this->error($this->repository->getError());
        }
        $this->remember($param['username']); //记住我
        return $userId;
    }


    /**
     * 用户中心列表
     * @param array|null $param
     * @return mixed
     * @throws \think\db\exception\DbException
     * @author staitc7 <static7@qq.com>
     */
    public function getListPage(?array $param=[])
    {
        $map=array_merge([
            'status'=>['status','>=',0],
        ],$param);
        return $this->repository->getListPage($map,null,['id'=>'deac']);
    }

    /**
     * 设置状态
     * @author staitc7 <static7@qq.com>
     * @param array|null $param
     * @return mixed
     */
    public function setStatus(?array $param)
    {
        if (!isset($param['value']) || (string)$param['value'] === '') {
            return $this->error('更新数据值错误');
        }
        $data = ['status' => (int)$param['value']];
        if (!isset($param['id']) || empty($param['id'])){
            return $this->error('参数不能为空');
        }
        $this->checkAdministrator($param['id']);
        $map  = $this->primaryKey($param['id']);
        return $this->repository->setStatus($map, $data);
    }

    /**
     * 检测管理员
     * @author staitc7 <static7@qq.com>
     * @param int|null $userId 用户ID
     * @return mixed
     */
    private function checkAdministrator($userId=null)
    {
        $adminId = (int)Config::get('app.user_administrator');
        if (preg_match('/\,+/', $userId)) {
            if (in_the_array($adminId, explode(',', $userId))) {
                return $this->error('对超级管理员无效');
            }
        }
        if ($adminId === (int)$userId) {
            return $this->error('对超级管理员无效');
        }
        return true;
    }

    /**
     * 分析用户名类型
     * @author staitc7 <static7@qq.com>
     * @param null|string $username
     * @return mixed
     */
    public function analysisUsername(?string $username=null)
    {
        //手机号
        if (preg_match('/0?(13|14|15|17|18|19)[0-9]{9}/', $username)){
            return 3;
        }
        //邮箱
        if (preg_match('/\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}/', $username)){
            return 2;
        }
        //用户名
        if (preg_match('/[A-Za-z0-9_]+/', $username)){
            return 1;
        }
        return null;
    }

    /**
     * 记住我
     * @author staitc7 <static7@qq.com>
     * @param null|string $username
     * @return mixed
     */
    public function remember(?string $username='')
    {
        $remember=Request::param('remember') ?? 0;
        if ((int)$remember ===1){
            Cookie::forever('remember',$username);
        }else{
            Cookie::delete('remember');
        }
        return true;
    }

    /**
     * 验证密码
     * @param null|string $password
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function verifyPassword(?string $password=null)
    {
        if (empty($password) === true) {
            return $this->error('密码不能为空');
        }
        if ($this->repository->verifyUserPassword(UserInfo::getUserId(), $password) === false){
            return $this->error('验证出错：密码不正确！');
        }
        return true;
    }

    /**
     * 更改用户密码
     * @param array|null $param
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function updatePassword(?array $param=[])
    {
        if (empty($param) === true){
            return $this->error('参数不能为空');
        }
        if ((string)$param['password'] !== (string)$param['repassword']){
            return $this->error('两次密码不一致');
        }
        if ($this->repository->verifyUserPassword(UserInfo::getUserId(),$param['old_password']) === false){
            return $this->error('原密码不正确!');
        }
        $result= $this->repository->updatePassword(UserInfo::getUserId(),$param['password']);
        if ($result === false){
            return $this->error($this->repository->getError());
        }
        return true;
    }

    /**
     * 用户注册
     * @param array|null $param
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function register(?array $param=[])
    {
        $data = $this->repository->renew($param);
        if ($data === false) {
            return $this->error($this->repository->getError());
        }
        return $data;
    }
}