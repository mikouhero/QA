<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Session;

class Home extends Controller
{
   public function _initialize()
   {
       // 判读session是否存在
       if(empty(Session::get('vip_info'))){
           // $this->redirect('index/Login');die;
           $this->error('请登录','index/Login/index');
           exit;
       }
       $info =array();
       $info = Session::get('vip_info');
       $this->assign('info',$info);
   }
}
