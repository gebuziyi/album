<?php
/*
谢雅龙PHP
*/
namespace app\index\controller;
use think\Controller;

header("content-type:text/html;charset=utf-8");
class Backend extends Controller{
	public function index(){
		return view();
	}
}
