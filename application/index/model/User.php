<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/25
 * Time: 9:44
 */
namespace app\index\model;
use think\Model;
header("content-type:text/html;charset=utf-8");
class User extends Model
{
    protected function getStatusAttr($value){
        $key=['0'=>'游客','1'=>'用户'];
        return $key[$value];
    }

    public function talk(){
        return $this->hasMany('Talk');
    }

    public function job(){
        return $this->hasMany('Job');
    }

    public function photo(){
        return $this->hasMany('Photo');
    }

    public function reply(){
        return $this->hasMany('Reply');
    }
}