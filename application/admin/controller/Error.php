<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Error extends Controller
{

    public function index()
    {
        $this->redirect('admin/index/index');
    }

    public function _empty()
    {
        $this->redirect('admin/lndex/index');
    }


}
