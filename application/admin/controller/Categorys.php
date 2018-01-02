<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;

class Categorys extends Admin
{

    public function index()
    {
        // $list = Db::name('category')->field(['id','name','path'])->paginate(5);
        // foreach ($list as $k=>$v){
        //     $count = substr_count($v['path'],',');
        //     $list[$k]['level']=$count;
        // }
        $list = Db::name('category')->paginate(5);
        // var_dump($list);die;
        return view('admin@categorys/index',[
            'list'=>$list
        ]);
    }

    public function create()
    {
        return view('admin@categorys/create');
    }


    public function save(Request $request)
    {
        $data = $request->post();
        $result = Db::name('category')->insert($data);
        if ($result ) {
            $this->success('添加成功','admin/categorys/index');
        } else {
            $this->error('添加失败');
        }
    }


    public function read($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function delete($id)
    {
        //
    }
}
