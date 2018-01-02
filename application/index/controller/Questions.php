<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Session;

class Questions extends Controller
{

    public function index()
    {
        $info = array();
        $info = Session::get('vip_info');
        $this->assign('info',$info);
        return view('index@questions/index');
    }

    public function read()
    {
        $info = array();
        $info = Session::get('vip_info');
        $this->assign('info',$info);
        return view('index@questions/read');
    }


}
