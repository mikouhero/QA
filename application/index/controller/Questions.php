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
        //  var_dump($list);
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

        // 一旦进入 问题的浏览量加一
        Db::name('question')->where(['id'=>$id])->setInc('read_count');

        // 问题详情

        $list = Db::name('question')->find($id);
        $category = Db::name('category')->field('name')->find($list['category_id']);
        $name = Db::name('user')->field('nickname')->find($list['uid']);
        $list['nickname'] = $name['nickname'];
        // 关于评论  ask_question_comment
        // 头像
        // 用户名  uid =>nickname
        // 评价内容  content
        // 用户的答案被点赞的数量  agree_count
        //问题id  question_id

        $msg = Db::name('question_answer')->where(['question_id'=>$id])->select();
            // var_dump($msg);
        foreach ($msg as $k=>$v) {
            // var_dump($v['uid']);
             $user = Db::name('user')->field(['nickname'])->find($v['uid']);
             // var_dump($user);
            $msg[$k]['username']= $user['nickname'];
            // var_dump($user['nickname']);
            // uid =>answer_comment
            $some = Db::name('question_answer_comment')->where(['uid'=>$v['uid']])->select();
        }
        // var_dump($some);
        // var_dump($msg);die;
        // var_dump($category);
        // var_dump($list);
        // var_dump($msg);die;
        return view('index@questions/read',[
            'list'=>$list,
            'category'=>$category,
            'msg'=>$msg
        ]);
    }

    // 问答答案的评论页面
    public function answerComment($id)
    {
        //$id 为question_answer 的ID

        //查询 question_answer_comment  question_answer_id = $id
        // 姓名  时间 内容
        $comment = Db::name('question_answer_comment')->where(['question_answer_id'=>$id])->select();
        // var_dump($comment);
        foreach ($comment as $k=>$v)
        {
            $user = Db::name('user')->field('nickname')->where(['id'=>$v['uid']])->find();
            $comment[$k]['nickname'] = $user['nickname'];
        }
        var_dump($comment);
        return view('index/question/read',[
            'comment'=>$comment
        ]);
    }

}
