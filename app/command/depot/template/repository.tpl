<?php

namespace {%namespace%};

use think\facade\{
    App,Config,Request
};
use app\{%app%}\traits\BaseRepository;
use app\{%app%}\model\{%model%};

class {%className%}
{
    use BaseRepository;

    private $model;

    public function __construct()
    {
        $this->model=new {%model%}();
    }
}