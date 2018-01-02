<?php
/**
 * Created by PhpStorm.
 * User: Mikou
 * Date: 2017/12/29 0029
 * Time: 11:31
 */

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;

class Login extends Controller
{
    public function index()
    {
        return view('index@Login/login');
    }

    public function save(Request $request)
    {
        $data = $request->post();
        // var_dump($data);
        $card = $data['vip'];
        $data['password'] = md5($data['password']);
        $preg = '/\d{11}$/';
        if(preg_match($preg,$card)){
            // 手机号作为字段查询
            $info = Db::name('user')->field('password',true)->where(['mobile'=>$card,'password'=>$data['password']])->find();
            if($info){
               Session::set('vip_info',$info);
                // var_dump(Session::get('vip_info'));
                $this->success('登录成功','index/index/index');
            }else{
                $this->error('手机号或密码不正确');
                exit;
            }
        }else{
            // 邮箱作为字段 查询
            var_dump($card);
            $info = Db::name('vip')->field('password',true)->where(['email'=>$card,'password'=>$data['password']])->find();
            // var_dump($info);
            if($info){
                Session::set('vip_info',$info);
                // var_dump(Session::set('vip_info',$info));
                $this->success('登录成功','index/index/index');
            }else{
                $this->error('邮箱或密码不正确');
                exit;
            }
        }

    }

    public function qqLogin($code)
    {
        $data = forQQ($code);
        // var_dump($data);
        //
        // 查询 token 是否存在于 vip 表中
        $result = Db::name('vip')->where(['token'=>$data['token']])->find();
        if(empty($result)){
            //插入数据
            $res = Db::name('vip')->insertGetId($data);
            if(!$res){
                $this->error('登录失败');
            }else{
                //存session  id
                $data['id'] = $res;
                Session::set('vip_info',$data);
                $this->success('登录成功','index/index/index');
            }
        }else{
            // 存Session
            Session::set('vip_info',$result);
            $this->success('登录成功','index/index/index');
        }

    }




}