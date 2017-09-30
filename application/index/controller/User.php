<?php
/**
 * Created by PhpStorm.
 * User: 28374
 * Date: 2017/8/29
 * Time: 19:51
 */
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;
use think\captcha\Captcha;
use app\index\model\User as UserModel;
use app\index\model\Admin;
class User extends Controller
{
    public function login()
    {

        return view();
    }

    public function register()
    {
        return view();
    }

    public function save($code = '')
    {//验证

        $captcha = new Captcha();
        $result = $captcha->check($code);

        if ($result) {

            $data['username'] = input('post.username');

            $data['password'] = input('post.password');
            // $result = $this->validate($data,'Admin.index');//Admin验证类

            /* $result = true;*/
            $data['identify'] = input('post.identify');

            if (!empty($data['identify']) && $data['identify'] == 'use') {
                $user = UserModel::where(['username' => $data['username'], 'password' => $data['password']])->find();
                if ($user) {
                    $user = $user->toArray();
                    Session::set('name', $user['username']);
                    Session::set('id', $user['id']);
                    $this->success('欢迎使用相册用户系统！', 'user/index');

                } else {
                    $this->error('用户名或密码错误！');
                }

            } else {
                $admin = Admin::where(['username' => $data['username'], 'password' => $data['password']])->find();
                if ($admin) {
                    $admin = $admin->toArray();
                    Session::set('name', $admin['username']);
                    $this->success('欢迎使用相册后台管理系统！', 'backend/index');
                } else {
                    $this->error('用户名或密码错误！');
                }

            }
        } else {
            $this->error('验证码错误！');
        }
    }
    public function logout(){//注销
    	if(Session::has('name') && Session::has('id')){
    		Session::delete('name');
    		Session::delete('id');
    		Session::clear();
    		$this->success('退出成功！','user/login');
    	}else{
    		$this->error('非法操作！','user/login');
    	}
    }
    public function register_post()
    {
        $data = [
            'username' => input('post.username'),
            'password' => input('post.password'),
            'email' => input('post.email'),
            'sex' => input('post.sex'),
            'birthday' => strtotime(input('post.birthday')),
            'job' => input('post.job'),
            'hobby' => input('post.hobby')
        ];
        $result = Db::name('user')->insert($data);
        if ($data && $result) {
            $this->success('注册成功！', 'user/login');
        } else {
            $this->error('注册失败！', 'user/register');
        }
    }

    public function forget(){
        return view();
    }
    public function forget_post()
    {
        $username=$_POST['username'];
        $data['password']=$_POST['password'];
        $email=$_POST['email'];
        $dataUsername=UserModel::where(array('username'=>$username))->find();
        if($dataUsername){
            if($email===$dataUsername['email']){
                UserModel::where(array('username'=>$username))->update($data);
                $this->success('密码修改成功','user/login');

            }else{
                $this->error('邮箱不匹配，请重新填写！');
                exit();
            }
        }
        else{
            $this->error('用户名不匹配','user/register');
        }
    }
    
    public function index()
    {
    	
    	return view();
    }

}