<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;

class User extends Admin
{

    public function index()
    {
        //查询user 表中的 用户名 和姓名
        // $list = Db::name('user')->field(['id','username','name'])->select();
        $list = Db::name('admin')->field(['id','adminname','name'])->paginate(5);
        // 查询对应用户的角色 role
        $arr = array(); //声明一个空数组
        //遍历用户信息
        foreach($list as $v) {
            // 查询对应用户的  角色id =>　rid　　　　uid = user.id
            $role_ids = Db::name('admin_role')->field('rid')->where(array('aid' => array('eq', $v['id'])))->select();
            // dump($role_ids);die;
            //定义空数组
            $roles = array();
            //遍历
            //查询角色表名称 _role 表  role表中的id  对应的是 用户角色表中的rid
            foreach ($role_ids as $value) {
                $roles[] = Db::name('role')->where(array('id' => array('eq', $value['rid']), 'status' => array('eq', 1)))->value('name');
            }
            $v['role'] = $roles; //将新得到角色信息放置到$v中
            // dump($v);die;
            $arr[] = $v;
        }
        // var_dump($arr);die;
        return view('admin@user/index',[
            'list'=>$arr,
            'data'=>$list

        ]);
    }

    public function create()
    {
        return view('admin@user/create');
    }

    public function save(Request $request)
    {
        $data = $request->put();
        $info['name'] = $data['name'];
        $info['adminname'] = $data['username'];
        $info['password'] = md5($data['userpass']);
        $result = Db::name('admin')->insert($info);
        if ($result>0) {
            $this->success('添加成功','admin/User/index');
        } else {
            $this->error('添加失败');
        }
    }

    public function read($id)
    {
    }

    public function edit($id)
    {
        $list = Db::name('admin')->field(['id','adminname','name'])->find($id);

        return view('admin@user/edit',[
            'list'=>$list
        ]);
    }

    public function update(Request $request, $id)
    {
        $info = $request->put();
        unset($info['_method']);
        $result = Db::name('admin')->where('id', $id)->update($info);
        if ($result > 0) {
            $this->success('修改成功','admin/user/index');
        } else {
            $this->success('修改失败');
        }

    }

    public function delete($id)
    {
        // 删除用户的同时  同时也删除 user_role 表中对应得角色 user id = role_user.uid
        //有bug 未分配角色 开启事务
        $deluser = Db::name('admin')->delete($id) ;
        $delrole= Db::name('admin_role')->where(array('aid'=>array('eq',$id)))->delete();
        // if ($deluser> 0  ) {
        //     $this->success('删除成功','admin/User/index');
        // } else {
        //     $this->error('删除失败');
        // }

        if ($deluser > 0) {
            $info['status'] = true;
            $info['id'] = $id;
            $info['info'] = 'ID为: ' . $id . '的用户删除成功!';
        } else {
            $info['status'] = false;
            $info['id'] = $id;
            $info['info'] = 'ID为: ' . $id . '的用户删除失败,请重试!';
        }
        return json($info);

    }

    public function rolelist($id)
    {
        // 获取用户id  查询user_role中uid = id
        // 遍历 role 中的角色
        $list = Db::name('role')->where(['status'=>1])->select();
        // var_dump($list);die;
        $admin = Db::name('admin')->where(['id'=>$id])->find($id);
        // 查询用户角色的相关信息
        $rolelist =Db::name('admin_role')->where(array('aid'=>array('eq',$id)))->select();
        // dump($rolelist);die;
        $myrole = array(); //定义空数组
        //对用户的角色进行重组
        foreach($rolelist as $v){
            $myrole[] = $v['rid'];
        }
        // var_dump($user);
        // var_dump($myrole);die;
        // var_dump($list)die;die;
        return view('admin@user/rolelist',[
            'list'=>$list,
            'admin'=>$admin,
            'role'=>$myrole
        ]);
    }

    public function saverole(Request $request,$id)
    {
        $data = $request->put();
        unset($data['_method']);
        // var_dump($data);die;
        if (empty($data['role'])) {
        $this->error('请选择一个角色');
    }
        //清除用户所有的角色信息，避免重复添加
        $del = Db::name('admin_role')->where(array('aid'=>array('eq',$id)))->delete();
        // dump($del);
        // dump($data['role']);
        foreach($data['role'] as $v){
            $info['aid'] = $id;
            $info['rid'] = $v;
            //执行添加
           $result =  Db::name('admin_role')->insert($info);
           // dump($result);
        }

        if ($result>0 ) {
            $this->success('分配成功','admin/user/index');
        } else {
            $this->error('分配失败');
        }
    }
}
