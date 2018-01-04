<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Db;

class Register extends Controller
{

    public function index()
    {
       return view('index@register/register');
    }


    public function mailIndex()
    {
        return view('index@register/MailRegister');
    }
    //邮箱验证码
    //     $result = sendMail("2713497141@qq.com");

    public function receivemail(Request $request)
    {
        $data['email'] = $request->post();
        if(empty($data['email'])){
            exit;
        }
        $data = $request->post();
        // 验证码可以做活
        $code = '335711';
        $result = sendMail($data['email'],$code);
        if ($result) {
            $info['status'] = true;
            $info['msg'] = '验证码发送成功';
        } else {
            $info['status'] = false;
            $info['msg'] = '验证码发送失败';
        }
        return json($info);
    }

    // 邮箱验证步骤
    public function saveEmail(Request $request)
    {
        $origincode = '335711';
        $data = $request->post();
        // var_dump($data);die;
        $code = $data['code'];
        // if($code1==$code){
        //     var_dump($code);
        // }
        // var_dump($data);die;

        // 判读验证码
        if($origincode != $code){
            $this->error('验证码错误');
            exit;
        }
        // var_dump($data);die;
        // 其他条件。。。。
        if($data['password'] != $data['repassword']){
            $this->error('两次密码不一致');
            exit;
        }
        unset($data['code']);
        unset($data['repassword']);
        //时间
        $data['regtime'] = time();
        // var_dump($data);die;

        $data['password'] = md5($data['password']);
        $result = Db::name('user')->insert($data);
        if($result>0){
            $this->success('注册成功','index/login/index');
        }else{
            $this->error('注册失败');
        }
    }

    // 手机验证步骤
    public function save(Request $request)
    {
        $origincode = '653819';
        $data = $request->post();
        // var_dump($data);die;
        $code = $data['code'];
        // if($code1==$code){
        //     var_dump($code);
        // }
        // var_dump($data);die;

        // 判读验证码
        if($origincode != $code){
            $this->error('短信验证码错误');
            exit;
        }
        // var_dump($data);die;
        // 其他条件。。。。
        if($data['password'] != $data['repassword']){
            $this->error('两次密码不一致');
            exit;
        }
        unset($data['code']);
        unset($data['repassword']);
        //时间
        $data['regtime'] = time();
        // var_dump($data);die;

        $data['password'] = md5($data['password']);
        $result = Db::name('user')->insert($data);
        if($result>0){
            $this->success('注册成功','index/login/index');
        }else{
            $this->error('注册失败');
        }
    }

    // 手机验证码
    public function receive(Request $request)
    {
        $data['mobile'] = $request->post();
        if(empty($data['mobile'])){
            exit;
        }
        $data = $request->post();
        // Db::name('user')->insert($data);
        // 手机验证码可以做或 随机数
       $result = sendTemplateSMS($data['mobile'],array('653819','3'),"1");//手机号码，替换内容数组，模板ID
        if ($result) {
            $info['status'] = true;
            $info['']=$data['mobile'];
            $info['msg'] = '短信发送成功';
        } else {
            $info['status'] = false;
            $info['msg'] = '短信发送失败';
        }
        return json($info);
    }
}
