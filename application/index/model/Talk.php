<?php
namespace app\index\model;
use think\Model;
header("content-type:text/html;charset=utf-8");

class Talk extends Model
{


    protected function getTimeAttr($time){//读取器
        return date('Y-m-d',$time);
    }



    public function photo(){
        return $this->belongsTo('Photo');
    }


}