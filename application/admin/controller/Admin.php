<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Session;

class Admin extends Controller
{
    //初始化操作 判断是否存在session
    public function _initialize()
    {
        $data = Session::get('admin_user');
        if (empty(Session::get('admin_user'))){
            //跳转到登录页面
            $this->redirect('login/index');
        }

        $adminname = Session::get('admin_user.adminname');
        $this->assign('adminname',$adminname);

        //权限过滤
        $request= \think\Request::instance();
        $module = $request->module();
        $mname = $request->controller();
        $aname = $request->action();

        // echo $mname.'/'.$aname;die;

        //获取权限列表
        $nodelist = Session::get('admin_user.nodelist');
        // var_dump($nodelist);
        // var_dump($nodelist[$mname]);die;

        // 超级管理员 所有权限
        if(Session::get('admin_user.adminname') != 'admin'){
            //验证操作权限
            if(empty($nodelist[$mname]) || !in_array($aname,$nodelist[$mname])){

                $this->error("抱歉！没有操作权限！");
                exit;
            }

        }
    }


    public function _empty()
    {
        $this->redirect('admin/lndex/index');
    }


}
