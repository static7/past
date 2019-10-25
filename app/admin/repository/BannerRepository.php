<?php
/**
 * Description of BannerRepository.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/11/24 12:12
 */

namespace app\admin\repository;


use app\admin\model\Banner;
use app\admin\traits\BaseRepository;

class BannerRepository
{
    use BaseRepository;

    private $model;

    public function __construct()
    {
        $this->model=new Banner();
    }

}