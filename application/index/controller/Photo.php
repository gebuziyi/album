<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use app\index\model\Photo as PhotoModel;
use think\Session;
use think\File;
header("content-type:text/html;charset=utf-8");
class Photo extends Controller
{
	public function index()
	{
		
	    
		return view();
	}
	
	public function show()
	{			
		$id = Session::get('id');
		$job = Db::name('job')->where('user_id',$id)->select();
		$this->assign('job',$job);
		$user_id = $id;
		$this->assign('id',$user_id);
		return view();
	}
	
	//上传照片
	public function upload($id)
	{
		$photo = new PhotoModel;
		$data['user_id'] = Session::get('id');
		$data['name'] = input('post.name');
		$data['job_id'] = input('post.job_id');
		$data['url'] = input('post.url');
		$data['time'] = time();
		$job_id=$data['job_id'];
		$file = request()->file('image');
		if(empty($file)){
			$this->error('没有选择文件！');
		}

		$info =	$file->move(ROOT_PATH."public/upload");
		if($info){
			$date = date("Ymd");
			$data['url']=$date."/".$info->getFilename();//存储到数据库		
			$photo = new PhotoModel();
			$photo->save($data);
			$this->success('文件上传成功！','job/browse/'.$job_id);
		}else{
			$this->error($file->getError());
		}						
	}
	
	//删除照片
	public function delete($id)
	{
		$photo = PhotoModel::get($id);//获取当前照片的 id值
		
		if($photo){
			if($photo->delete()){
				$this->success("相片删除成功！");
			}else{
				$this->error("相片删除失败！");
			}
		}else{
			$this->error("删除的相片不存在！");
		}
	}
	
}		
		
	
