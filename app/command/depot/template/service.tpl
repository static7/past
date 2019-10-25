<?php

namespace {%namespace%};

use app\{%app%}\traits\BaseService;

class {%className%}
{
    use BaseService;

    public function __construct()
    {
        $this->repository=new {%newClass%};
    }
}