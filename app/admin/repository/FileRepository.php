<?php
/**
 * Description of FlashRepository.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/8/4 17:35
 */

namespace app\admin\repository;


use app\admin\model\File;
use app\admin\traits\BaseRepository;

class FileRepository
{
    use BaseRepository;

    private $model;

    public function __construct()
    {
        $this->model=new File();
    }


    /**
     * 检测文件hash散列值
     * @param null|string $md5
     * @param null|string $sha1
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function checkFileHash(?string $md5,?string $sha1)
    {
        $object= $this->model->where('status','=', 1)
            ->where('md5', '=',$md5)
            ->where('sha1','=', $sha1)
            ->field(['id', 'md5', 'url', 'path', 'sha1', 'file_name', 'mime','original_name'])
            ->allowEmpty(true)
            ->find();
        if ($object->isEmpty() === false){
            return $object->toArray();
        }
        return [];
    }

    /**
     * 更新远程链接
     * @author staitc7 <static7@qq.com>
     * @param int|null    $id
     * @param null|string $url
     * @return mixed
     */
    public function updateUrl(?int $id=0,?string $url='')
    {
        return $this->model->update(['url'=>$url],['id','=',$id],['url']);
    }
}