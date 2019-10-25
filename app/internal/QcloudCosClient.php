<?php
/**
 * Description of UploadCos.php.
 * User: static7 <static7@qq.com>
 * Date: 2017/10/2 13:50
 */

namespace app\internal;

use Exception;
use Qcloud\Cos\Client;
use Qcloud\Cos\Exception\ServiceResponseException;
use think\facade\{Filesystem, Log, Request, Config, App};

class QcloudCosClient
{
    private $error;

    private $cos;

    private $config;


    //初始化
    public function __construct()
    {
        $this->config = Config::get('tencent.tencent_cos');
        $this->cos    = new Client(Config::get('tencent.tencent_cos'));
    }

    /**
     * 上传
     * @param string $path 上传的文件的路径 (相对路径)
     * @return mixed
     * @throws Exception
     * @author staitc7 <static7@qq.com>
     */
    public function upload(?string $path = '')
    {
        //获取文件信息
        $fileInfo = Filesystem::get($path);
        //检测文件 是否存在
        if ($fileInfo->exists() === false) {
            $this->error = '文件不存在';
            return false;
        }
        try {
            $result = $this->cos->Upload($this->config['bucket'], $path, $fileInfo->read());
        } catch (ServiceResponseException $e) {
            throw new Exception("腾讯云COS上传异常! 信息:{$e}");
        }
        if (empty($this->config['domain'])) {
            return $result['Location'];
        }
        return $this->config['domain'] .'/'. $path;
    }


    /**
     * 返回模型的错误信息
     * @access public
     * @return string|array
     */
    public function getError()
    {
        return $this->error;
    }
}