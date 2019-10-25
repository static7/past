<?php
/**
 * Description of FileService.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/8/4 17:35
 */

namespace app\admin\service;


use app\admin\repository\FileRepository;
use app\admin\traits\BaseService;
use think\facade\{Config, Request, Filesystem, Validate};

class FileService
{
    use BaseService;

    protected $uploadInfo;

    protected $remoteServer;

    public function __construct()
    {
        $this->repository = new FileRepository();
        $this->uploadInfo = Config::get('upload');

    }

    /**
     * 上传图片
     * @param array|null $param
     * @return mixed
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function pictureUpload(?array $param = [])
    {
        return $this->uploads($param['field'] ?? 'image','picture');
    }


    /**
     * 上传文件
     * @param array|null $param
     * @return mixed
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function fileUpload(?array $param = [])
    {
        return $this->uploads($param['field'] ?? 'file','file');
    }


    /**
     * 上传头像
     * @param array|null $param
     * @return mixed
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function avatarUpload(?array $param = [])
    {
        return $this->uploads($param['field'] ?? 'avatar','picture');
    }


    /**
     * 文件上传
     * @param null|string $field
     * @param string      $type 文件类型
     * @return mixed
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    private function uploads(?string $field = null, string $type = 'picture')
    {
        $file = Request::file($field);
        if (empty($file) === true) {
            return $this->error('上传文件为空');
        }
        $absolutePath = ($type === 'picture') ? $this->uploadInfo['picture_path'] : $this->uploadInfo['file_path'];
        $restrict= $this->uploadInfo['file_upload_restrict'];
        $message=[];$rule=[];
        if (is_array($restrict) === true && empty($restrict) === false){
            foreach ($restrict as $k=>$v){
                $rule[$k]=$v['rule'];
                $message[$field.'.'.$k]=$v['message'];
            }
        }
        $validate = Validate::rule($field,$rule)->message($message);
        if ($validate->check([$field => $file]) === false){
            return $this->error($validate->getError());
        }
        unset($message,$rule,$restrict,$this->uploadInfo['file_upload_restrict']);
        $md5  = $file->md5();
        $sha1 = $file->sha1();
        //检测文件是否存在
        $result = $this->repository->checkFileHash($md5, $sha1);
        if (empty($result) === false) {
            unset($md5, $sha1);
            return $result;
        }

        //保存文件
        $saveName = Filesystem::putFile($absolutePath, $file, 'uniqid');
        $data = [
            'path'          => $this->uploadInfo['access'].'/'.$saveName,
            'url'           => $this->uploadInfo['access'].'/'.$saveName,
            'md5'           => $md5,
            'sha1'          => $sha1,
            'mime'          => $file->getOriginalMime(),
            'size'          => $file->getSize(),
            'ext'           => $file->getOriginalExtension(),
            'create_time'   => $file->getATime(),
            'original_name' => $file->getOriginalName(),
            'file_name'     => $saveName
        ];

        //是否上传远程
        if ($this->uploadInfo['upload_remote_server']['status'] === true) {
            $data['url']      = $this->entrance($saveName);
            $data['location'] = 1;
        }
        //删除本地path
        if ($this->uploadInfo['upload_remote_server']['local_backup'] === true) {
            Filesystem::delete($saveName);
        }
        unset($file, $md5, $sha1,$saveName);
        return $this->repository->renew($data);
    }


    /**
     * 上传远程的静态类
     * @param string $path 路径相对路径
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function entrance(?string $path = '')
    {
        $config = $this->uploadInfo['upload_remote_server'];
        $class  = $config['class'];
        $method = $config['method'];
        if (class_exists($class) === false) {
            return $this->error($class . '此代理静态类不存在');
        }
        if (empty($method) === true) {
            $this->error('远程上传的入口方法 ' . $method . ' 不能为空');
            return false;
        }
        $info = $class::$method($path);
        if ($info === false) {
            return $this->error($class::getError());
        }
        return $info;
    }

    /**
     * 更新Url(远程链接)
     * @param array|null $param
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function updateUrl(?array $param = [])
    {
        if (!isset($param['id']) || empty($param['id'])) {
            return $this->error('图片ID不能为空');
        }
        if (!isset($param['url']) || empty($param['url'])) {
            return $this->error('远程链接获取失败');
        }
        return $this->repository->updateUrl($param['id'], $param['url']);
    }
}