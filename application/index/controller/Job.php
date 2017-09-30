<?php
/*
谢雅龙PHP
*/

namespace app\index\controller;
use think\Db;
use think\Image;
use think\Session;
use think\Controller;
use app\index\controller\User;
use app\index\model\Job as JobModel;
use app\index\model\Photo as PhotoModel;
header("content-type:text/html;charset=utf-8");
class Job extends Controller
{
	public function index()
	{
		$id = Session::get('id');
		$data = Db::name('job')->where('user_id',$id)->select();
		$job_count = Db::name('job')->where('user_id',$id)->count();
		foreach ($data as $v)
		{
			$job_id[] = $v['id'];
		}
		for($i=1;$i<=$job_count;$i++){
			$count= Db::name('photo')->where('job_id',$job_id[$i-1])->count();
			 if($count){
			 	$photo= Db::name('photo')->where('job_id',$job_id[$i-1])->order('time','desc')->limit(1)->find();
			 	$url=explode('/', $photo['url']);
			    $data[$i-1]['url'] = $url[1];
				
			 }
		}
		$this->assign('data',$data);
		//var_dump($data);
		return view();
	}
	
	public function create()
	{ 
		
		$sort = Db::name('sort')->select();
		$this->assign('sort',$sort);
		$user_id = Session::get('id');
		$this->assign('id',$user_id);
		return view();			
	}
	
	public function save($id)
	{
		$list = new JobModel();
		$data['title'] = input('post.title');
		$data['article'] = input('post.article');
		$data['sort_name'] = input('post.sort_name');
		$data['user_id'] = Session::get('id');
		if($list->save($data)){
			$this->success('添加成功！','job/index');
		}else{
			$this->error($list->getError());
		}
				
	}
	
	//浏览相册
	public function browse($id)
	{
		$user = new User();
		$image = Db::name('photo')->where('job_id',$id)->select();
		
		foreach ($image as $v){
			$path="./upload/".$v['url'];
			$u = explode('/', $v['url']);
			$new ="./newpath/".$u[1];
			$url[]=['url'=>$u[1],'id'=>$v['id']];
			$newpath = Image::open($path);
			$newpath->thumb(120,120,Image::THUMB_CENTER)->save($new);
			
		
		}	
		$this->assign('w',$v);	
		$this->assign('data',$image);
		$this->assign('u',$url);
		return view();
	}
	
	//删除相册
	public function delete($id)
	{
		$job = JobModel::get($id);//获取当前相册的 id值
		
		if($job){
			if($job->delete()){
				$this->success("相册删除成功！",'job/index');
			}else{
				$this->error("相册删除失败！");
			}
		}else{
			$this->error("删除的相册不存在！");
		}
	}
	
	
	//修改相册
	public function edit($id){
		$job = JobModel::find($id);
		$this->assign('job',$job);
		$sort = Db::name('sort')->select();
		$this->assign('sort',$sort);
		return view();
	}
	
	public function update($id){
		$job = new JobModel;
		$data['id'] = $id;
		$data['title'] = input('post.title');
		$data['article'] = input('post.article');
		$data['sort_name'] = input('post.sort_name');
	
		if($job->isUpdate(true)->save($data)){
			$this->success('数据修改成功！','job/index');
		}else{
			$this->error($job->getError());
		}
	}
	
	
}