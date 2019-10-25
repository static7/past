<?php
/**
 * Description of Common.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/7/12 11:34
 */

namespace app\admin\controller;


use app\admin\service\{
    FileService
};
use app\admin\traits\{
    Admin,Jump
};

class Common
{
    use Jump,Admin;

    /**
     * 图片上传接口
     * @param FileService $fileService
     * @return mixed
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function pictureUploadInterface(FileService $fileService)
    {
        $param=$this->app->request->only(['field'=>'image']);
        $data=$fileService->pictureUpload($param);
        if ($data ===false){
            return $this->error($fileService->getError());
        }
        return $this->result($data,1,'上传成功');
    }

    /**
     * 文件上传接口
     * @param FileService $fileService
     * @return mixed
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function fileUploadInterface(FileService $fileService)
    {
        $param=$this->app->request->only(['field'=>'file']);
        $data=$fileService->fileUpload($param);
        if ($data ===false){
            return $this->error($fileService->getError());
        }
        return $this->result($data,1,'上传成功');
    }


    /**
     * layui富文本文件上传接口
     * @param FileService $fileService
     * @return mixed
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function layeditFileUploadInterface(FileService $fileService)
    {
        $param=$this->app->request->only(['field'=>'file']);
        $data=$fileService->fileUpload($param);
        if ($data ===false){
            return $this->result(null,1,$fileService->getError());
        }
        return $this->result([
            'src'=>$data['url'],
            'title'=>$data['original_name']
        ],0,'上传成功');
    }

    /**
     * 分类查询
     * @author staitc7 <static7@qq.com>
     * @return mixed
     */
    public function categoryInterface()
    {
        //TODO code....
    }



}