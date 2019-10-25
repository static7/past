<?php
namespace app\admin\controller;

use app\admin\service\{
    FileService,MemberService,UserCenterService
};
use app\facade\{
    UserInfo
};

use app\admin\traits\{
    Admin,Jump
};
use think\facade\View;

class Member
{
    use Jump,Admin;

    /**
     * 用户
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function index()
    {
        return $this->setView();
    }

    /**
     * 会员接口
     * @author staitc7 <static7@qq.com>
     * @param MemberService $memberService
     * @return mixed
     * @throws DbException
     */
    public function memberInterface(MemberService $memberService)
    {
        $param=$this->app->request->params([
            'status'=>['status','='],
            'nickname'=>['nickname','like'],
            'last_login_time'=>['last_login_time','between time','~']
        ]);
        $data=$memberService->getMemberListPage($param);
        return $this->layuiJson($data->toArray());
    }

    /**
     * 设置状态
     * @author staitc7 <static7@qq.com>
     * @param MemberService $memberService
     * @return mixed
     */
    public function setStatus(MemberService $memberService)
    {
        $param=$this->app->request->param();
        $info=$memberService->setStatus($param,'user_id');
        if ($info === false) {
            return $this->error($memberService->getError());
        }
        return $this->success('更新成功');
    }

    /**
     * 添加用户
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function add()
    {
        return $this->setView();
    }

    /**
     * 新增用户
     * @param MemberService $memberService
     * @return \think\Response
     * @throws \think\Exception
     * @author staitc7 <static7@qq.com>
     */
    public function renew(MemberService $memberService) {
        $param=$this->app->request->param();
        $info= $memberService->userRegister($param);
        if ($info===false) {
            return $this->error($memberService->getError());
        }
        return $this->success('新增成功','Member/index');
    }

    /**
     * 用户详情
     * @param MemberService $memberService
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function detail(MemberService $memberService)
    {
        $info=$memberService->first($this->app->request->param());
        if ($info===false) {
            return $this->error($memberService->getError());
        }
        return $this->setView(['info'=>$info]);
    }


    /**
     * 修改昵称
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function nicknameModify()
    {
        $this->initView();
        $view=View::fetch('nickname');
        View::assign(['nickname' => UserInfo::getNickname()]);
        return $this->template($view,1,'修改昵称');
    }

    /**
     * 更新昵称
     * @param MemberService     $memberService
     * @param UserCenterService $userCenterService
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author staitc7 <static7@qq.com>
     */
    public function submitNickname(MemberService $memberService,UserCenterService $userCenterService)
    {
        $param=$this->app->request->param();
        //验证用户密码
        $userCenterService->verifyPassword($param['password']);
        //更改昵称
        if ($memberService->updateNickname($param['nickname']) === false){
            return $this->error($memberService->getError());
        }
        UserInfo::userInfoRefresh(['nickname'=>$param['nickname']]);
        return $this->success('修改成功');
    }

    /**
     * 修改头像
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function avatarModify()
    {
        $this->initView();
        $view=View::fetch('avatar');
        return $this->template($view,1,'更新头像');
    }

    /**
     * 头像上传接口
     * @param FileService   $fileService
     * @param MemberService $memberService
     * @return mixed
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function avatarUploadInterface(FileService $fileService,MemberService $memberService)
    {
        $param=$this->app->request->only(['field'=>'avatar']);
        $data=$fileService->avatarUpload($param);
        if ($data ===false){
            return $this->error($fileService->getError());
        }
        //存储头像ID
        $memberService->saveAvatar($data['id']);
        //刷新用户信息
        UserInfo::userInfoRefresh(['avatar'=>$data['url']]);
        return $this->result($data,1,'上传成功');
    }
}