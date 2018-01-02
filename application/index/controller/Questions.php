<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Session;
use think\Db;

class Questions extends Controller
{
    // 问题首页
    public function index()
    {
        $info = array();
        $info = Session::get('vip_info');
        $this->assign('info',$info);

        //查询question 表中的数据
        //  回答数 answer_count
        // 是否解决 status
        //浏览数  read_count
        // 时间  create_time
        // 用户  uid
        //标题 title
        // gold
         $list = Db::name('question')->field(['id','answer_count','status','read_count','create_time','uid','title','gold'])->order('id desc')->paginate(10);
         // $username = Db::name('user')->field('usename')->where(['id'=>$list])->find();
        foreach ($list as $k=>$v) {
            $username= Db::name('user')->field('nickname')->find($v['uid']);
            $v['username'] = $username['nickname'];
            // var_dump($v);
            $list[$k] = $v;
        }
        // $list = Db::name('question')->fetchsql('true')->order('id desc')->select();
         var_dump($list);
        return view('index@questions/index',[
            'list'=>$list
        ]);
    }

    // 问题详情页
    public function read(Request $request,$id)
    {
        $info = array();
        $info = Session::get('vip_info');
        $this->assign('info',$info);
        // 问题详情
        $list = Db::name('question')->find($id);
        $category = Db::name('category')->field('name')->find($list['category_id']);

        // 关于评论


        // var_dump($category);
        // var_dump($list);
        return view('index@questions/read',[
            'list'=>$list,
            'category'=>$category

        ]);
    }


}
