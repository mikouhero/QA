<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Session;

class Index extends Home
{
    public function _initialize()
    {
        // 实例化model
        // var_dump(Session::get('vip_info'));die;


    }

    public function index()
    {
        // var_dump(Session::get('vip_info'));die;
        $info = array();
        $info = Session::get('vip_info');
        $this->assign('info',$info);
        return view('index@index/index');
        // return 'TP5前台主页';
    }

    public function ip(Request $request)
    {
        var_dump($request->ip());
    }

}
