<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;

class Node extends Admin
{
    public function index()
    {
        // $data = Db::name('node')->select();
        $data = Db::name('node')->paginate(5);

        // var_dump($data);die;
        return view('admin@node/index',[
            'list'=>$data
        ]);
    }

    public function search(Request $request)
    {
        $data = $request->get();
        // var_dump($data);die;
        $mname = $data['mname'];
        $aname = $data ['aname'];
        if(empty($mname)&&empty($aname)){
            $this->error('请输入查询条件');
        }
        $pageParam    = ['query' =>[]];
        if ($mname) {
          $data  = Db::name('node')->where('mname', 'like', "%{$mname}%")->paginate(5,false,['query'=>request()->param()]);
        }
        if ($aname) {
           $data =  Db::name('node')->where('aname', 'like', "%{$aname}%")->paginate(5,false,['query'=>request()->param()]);
        }
        if($mname && $aname){
            $data = Db::name('node')->where('mname', 'like', "%{$mname}%")->where('aname', 'like', "%{$aname}%")->paginate(5,false,['query'=>request()->param()]);
        }
        return view('admin@node/index',[
            'list'=>$data
        ]);

    }

    public function create()
    {
        return view('admin@node/create');
    }

    public function save(Request $request)
    {
       $data = $request->put();
       unset($data['_method']);
       // var_dump($data);die;
       $result = Db::name('node')->insert($data);
       if ($result >0) {
           $this->success('添加成功','admin/node/index');
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
        $data = Db::name('node')->find($id);
       return view('admin@node/edit',[
           'list'=>$data
       ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->put();
        unset($data['_method']);
        // var_dump($data);die;
        $result = Db::name('node')->where(['id'=>$id])->update($data);
       if ($result>0) {
           $this->success('修改成功','admin/node/index');
       } else {
           $this->error('修改失败');
       }
    }

    public function delete($id)
    {
        $result = Db::name('node')->where(['id'=>$id])->delete();
        // if ($result>0) {
        //     $this->success('删除成功','admin/Node/index');
        // } else {
        //     $this->error('删除失败');
        // }
        if ($result > 0) {
            $info['status'] = true;
            $info['id'] = $id;
            $info['info'] = '节点删除成功!';
        } else {
            $info['status'] = false;
            $info['id'] = $id;
            $info['info'] = '节点删除失败!';
        }
        return json($info);
    }
}
