<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
Use app\admin\User;

class Article extends Admin
{

    public function index()
    {
        $data = Db::name('article')->select();
        return view('Article/index',['data'=>$data]);
    }

    //执行删除角色的操作
    public function delete($id)
    {
        $result =Db::name('article')->delete($id);

        if ($result) {
            return  $this->success('删除成功','/admin/Article/index');
        }else{
            return  $this->error('删除失败');
        }
    }

}
