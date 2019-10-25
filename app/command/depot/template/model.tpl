<?php

namespace {%namespace%};

use think\Model;
use app\{%app%}\traits\Models;

class {%className%} extends Model
{
    use Models;
    protected $autoWriteTimestamp = true; //自动写入创建和更新的时间戳字段
    protected $insert             = ['status' => 1];
    protected $auto               = [];
    protected $update             = [];
    // 设置json类型字段
    protected $json = [];
}
