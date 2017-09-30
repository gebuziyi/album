<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/11
 * Time: 15:45
 */
namespace app\index\validate;
use think\Validate;
header("content-type:text/html;charset=utf-8");
class User extends Validate{
    protected $rule=[
        'name|姓名' => 'require',
        'sex|密码'=>'require',
        'email|邮箱'=>'require|email',
        'url|图片地址'=>'require',
    ];
    protected $message=[
        'name.require'=>'姓名不能为空!',
        'sex.require'=>'性别不能为空！',
        'email.require'=>'邮箱不能为空！',
        'email.email'=>'邮箱格式不正确！',
        'url.require'=>'图片地址不能为空！',
    ];
}