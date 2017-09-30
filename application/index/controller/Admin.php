<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/11
 * Time: 17:38
 */
namespace app\index\controller;
use app\index\model\User as UserModel;
use think\Controller;
use think\Db;

header("content-type:text/html;charset=utf-8");
class Admin extends Controller{
    public function index(){
        $data = Db::name('user')->paginate(3);
        $this->assign('data',$data);
        return view();
    }

    public function delete($id){
        $user = UserModel::get($id);
        if($user){
            if($user->delete($id)){
                $this->success('删除用户成功！','admin/index');
            }else{
                $this->error('用户删除失败！');
            }
        }else{
            $this->error('用户已删除，请勿重复操作！');
        }
    }

    public function edit($id){
        $user = UserModel::get($id);
        $this->assign('user',$user);
        return view();
    }

    public function update($id){
        $user = new UserModel();
        $data['id'] = $id;
        $data['username'] = input('post.username');
        $data['sex'] = input('post.sex');
        $data['email'] = input('post.email');
        $data['url'] = input('post.url');
        $this->validate($data,'admin.edit');
        if($user->isUpdate(true)->validate(true)->save($data)){
            $this->success('用户修改成功！','admin/index');
        }else{
            $this->error($user->getError());
        }
    }



}