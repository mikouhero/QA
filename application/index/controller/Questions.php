<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Session;
use think\Db;

class Questions extends Controller
{

    public function index()
    {
        $info = array();
        $info = Session::get('vip_info');
        $this->assign('info',$info);

        //查询question 表中的数据
         $list = Db::name('question')->select();
         var_dump($list);
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
