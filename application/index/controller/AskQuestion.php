<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Session;

class AskQuestion extends Controller
{

    public function index()
    {
        $info = array();
        $info = Session::get('vip_info');
        $this->assign('info',$info);
        return view('index@askquestion/index');
    }
    public function save(Request $request)
    {
        $info = array();
        $info = Session::get('vip_info');
        $this->assign('info',$info);
        $data = $request->post();
        var_dump($info);
        var_dump($data);
        die;
    }
}
