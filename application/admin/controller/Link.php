<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;

class Link extends Admin
{
    public function index()
    {
        $res = Db::name('links')->paginate(3);
        $this->assign('list', $res);
        return view('link/LinkList');
    }

    /**
     * 显示创建友情链接表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        return view('link/Linkadd');
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $p = $request->post();

        // var_dump(post);die;
        
        $data['path'] = $p['path'];
        $data['name'] = $p['name'];
        $file = $request->file('icon');


        $data['time'] = time();
        //    移动到框架应用根目录/public/uploads/    目录下
        if($file){
        $info = $file->move(ROOT_PATH.'public'. DS. 'uploads');
        if($info){
            $icon =  $info->getFilename();
            }else{
            //    上传失败获取错误信息
            return $this->error('图片上传失败');
        }

        } 

        // var_dump($file);
        // die;

        if (empty($data['name']) || empty($p['path'])) {
            return $this->error('你好像漏填了什么?');
        }
        // if (empty($file)) {

        //     $pic = '1.jpg';
        // } else {
        //     // 移动到框架应用根目录/public/uploads/ 目录下
        //     $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        //     if ($info) {
        //         // 成功上传后 获取上传信息
        //         // 输出 jpg
        //         echo $info->getExtension();
        //         // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
        //         $pic = $info->getSaveName();
        //         // 输出 42a79759f284b767dfcb2a0197904287.jpg
        //     } else {
        //         // 上传失败获取错误信息
        //         return $this->error('图片上传失败');
        //     }
        // }

        $data = [
            'name' => $p['name'],
            'path' => $p['path'],
            'icon' => $p['icon']
        ];

        $result = Db::name('links')->data($data)->insert();
        if ($result > 0) {
            return $this->success('添加成功', 'admin/link/index');
        } else {
            return $this->error('添加失败');
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function read($id)
    {
        $res = Db::table('links')->select();
        $this->assign('list', $res);
        return view('link/linklist');
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int $id
     * @return \think\Response
     */
    public function edit($id)
    {
        // var_dump($id);die;
        $link = Db::name('links')->where('id', $id)->select();
        // var_dump($link);die;
        $link = $link['0'];
        $this->assign('link', $link);
        return view('link/LinkEdit');
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request $request
     * @param  int $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        $p = $request->put();
        // var_dump($p);die;
        // $data = [
        //     'name' => $p['name'],
        //     'path' => $p['path'],
        // ];

        $file = request()->file('img');

        if (!empty($file)) {

            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if ($info) {
                // 成功上传后 获取上传信息
                // 输出 jpg
                //            echo $info->getExtension();
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                $pic = $info->getSaveName();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg

            } else {
                // 上传失败获取错误信息
                return $this->error('logo上传失败');
            }

            $data = [
                'name' => $p['name'],
                'path' => $p['path'],
                'icon' => $pic
            ];
        } else {

            $data = [
                'name' => $p['name'],
                'path' => $p['path'],
            ];
        }


        $result = Db::name('links')->where('id', $id)->update($data);


        // $b_id = cache('b_id');
        // $list = Db::table('ml_hotel')->where('bus_id',$b_id)->select();
        // $pid = $list['0']['id'];

        if ($result) {
            return $this->success('修改成功', url('admin/link/index'));
        } else {
            return $this->error('修改失败');
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function delete($id)
    {

        $result = Db::name('links')->delete($id);

        if ($result) {
            $info['status'] = true;
            $info['id'] = $id;
            $info['info'] = 'ID为:' . $id . '的分类删除成功';
        } else {
            $info['status'] = false;
            $info['id'] = $id;
            $info['info'] = 'ID为:' . $id . '的分类删除失败,请重试!';
        }
        return json($info);

    }

}
