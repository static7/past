<?php
/**
 * Description of LinksRepository.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/11/9 11:26
 */

namespace app\admin\repository;


use app\admin\model\Links;
use app\admin\traits\BaseRepository;

class LinksRepository
{
    use BaseRepository;

    private $model;

    public function __construct()
    {
        $this->model=new Links();
    }

}