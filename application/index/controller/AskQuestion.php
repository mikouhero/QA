<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Session;
use think\Db;

class AskQuestion extends Controller
{

    public function index()
    {
        $info = array();
        $info = Session::get('vip_info');
        $this->assign('info',$info);
        // 查询问题分类
        $list = Db::name('category')->select();
        // var_dump($list);die;
        return view('index@askquestion/index',[
            'list'=>$list
        ]);
    }
    public function save(Request $request)
    {
        $info = array();
        $info = Session::get('vip_info');
        $this->assign('info',$info);
        $data = $request->post();
        // var_dump($data);die;
        // if(!captcha_check($data['code']))
        // {
        //     $this->error("验证码错误");
        // };
        var_dump($info);
        var_dump($data);die;
        // 判断是否有足够的额gold 作为条件
        $gold= Db::name('user')->field(['gold'])->where(['id'=>$info['id']])->find();
        // var_dump($data['gold']);
        // var_dump($gold['gold']);
        // var_dump($data['gold'] < $gold['gold']);die;
        // 所需 大于 已有
        if($data['gold'] > $gold['gold']){
            $this->error('金币不足');
            exit;
        }
        // die;
        // var_dump($gold);die;
        // 动用数据user表  questionn_count +1 score +5 gold - $data['gold']
        // setInc（字段，值）不加值 默认1 setDec ()
        // 开启事务操作
        Db::startTrans();
        try {
            $result1 = Db::name('user')->where(['id'=>$info['id']])->setInc('question_count',1);
            $result2 = Db::name('user')->where(['id'=>$info['id']])->setInc('score',5);
            $result3 = Db::name('user')->where(['id'=>$info['id']])->setDec('gold',$data['gold']);

            // 将问题的相关信息导入 question数据表
            unset($data['code']);
            $data['uid'] = $info['id'];
            $data['create_time']=time();
            $result4 = Db::name('question')->insert($data);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $this->error('发布失败');
            exit;
        }
        $this->success('发布成功','/index/questions/index');

        // if($result4){
        //     $this->success('发布成功','/index/questions/index');
        // }else{
        //     $this->error('发布失败');
        // }
    }
}
