<?php
namespace app\index\controller;

use think\Controller;
use think\Session;
use think\Db;
use think\Image;
use app\index\model\Job as jobModel;
use app\index\model\User;
class About extends Controller
{
	/* protected $beforeActionList=[
	 'check',//字段后无参数 表示该控制器下所有方法执行之前都要执行check方法
	];

	public function check()
	{
	if(!Session::has('name'))
	{
	$this->success('非法操作','User/login');
	}
	} */

	public function index()
	{
		$id = Session::get('id');
		$data['user']=Db::name('user')->where('id',$id)->find();
		$data['user']['birthday'] = date('Y-m-d',$data['user']['birthday']);
		/* $path="./static/upload/".$data[0]['url'];
		 $newpath="./static/upload/small".$data[0]['url'];
		 $image=Image::open($path);
		 $image->thumb(120,120,Image::THUMB_CENTER);
		 $image->save($newpath); */
		$jcount=Db::name('job')->where('user_id',$id)->count();
		$province=Db::name('province')->select();
		$pcount=Db::name('photo')->where('user_id',$id)->count();
		$photos=Db::name('photo')->where('user_id',$id)->order('time','desc')->limit(5)->select();
		$apcount=sizeof($photos);
		for($i=0;$i<$apcount;$i++)
		{
			$url=explode('/', $photos[$i]['url']);
			$photos[$i]['url']=$url[1];
		}
		$data['num']=['jcount'=>$jcount,'pcount'=>$pcount,'apcount'=>$apcount];
		$data['photos']=$photos;
		$data['today']=date('Y-m-d',time());
		$this->assign('data',$data);
		$this->assign('province',$province);
		return $this->fetch();
	}
	
	public function edit($id)
	{
		$photos=Db::name('photo')->where('user_id',$id)->order('time','desc')->limit(50)->select();
		$apcount=sizeof($photos);
		for($i=0;$i<$apcount;$i++)
		{
		$url=explode('/', $photos[$i]['url']);
		$photos[$i]['url']=$url[1];
		}
		
		$data['num']=['apcount'=>$apcount,'id'=>$id];
		
		$job=Db::name('job')->where('user_id',$id)->select();
		$this->assign('job',$job);
		$this->assign('data',$data);
		$this->assign('photos',$photos);
		return view();
	}
	public function up($id)
	{
		$job_id = input('post.job_id');
	    if($job_id=="new")
	    {
	    	$job = new jobModel();
	    	$fjob=Db::name('job')->where('user_id',$id)->select();
	    	$sizeofjob = sizeof($fjob);
	    	if($fjob){
	    	   for($i=0;$i<$sizeofjob;$i++)
	    	    {
	    	   	   if($fjob[$i]['title']=="新建相册")
	    	   	     {
	    	   	     	$job_id = $fjob[$i]['id'];	    	   	    	
	    	   	     	break;
	    	   	     }
	    	    }
              if($i==$sizeofjob){              	
			    	    $data['title'] ='新建相册';
			    	    $data['sort_name'] ='其他';
			    	    $data['user_id'] = $id;
			    	    $job->save($data);
			    	    $job_id = $job->id;			    	     
	    	}
	    	}else{
			    	    $data['title'] = "新建相册";
			    	    $data['sort_name'] = "其他";
			    	    $data['user_id'] = $id;
			    	    $job->save($data);
			    	    $job_id = $job->id; 
	    	}
	    	    	
	    	if($job_id){	
	    	$file = request()->file('file');
		    if(empty($file)){
			    $this->error('没有选择文件!');
		     }
		    $info =	$file->move(ROOT_PATH."public/upload");
		    if($info){
			  $date = date("Ymd");
			  $data['url']=$date."/".$info->getFilename();//存储到数据库		
			  $data['job_id'] = $job_id;
			  $data['user_id'] = $id;
			  $data['time'] = time();
			   if(Db::name('photo')->insert($data)){
			     $path="./upload/".$data['url'];
			     $u = explode('/', $data['url']);
			     $new ="./newpath/".$u[1];
			     $newpath = Image::open($path);
			     $newpath->thumb(120,120,Image::THUMB_CENTER)->save($new);
			     $img['url'] = $u[1]; 
			     if(Db::name('user')->where('id',$id)->update($img))
			     {
			     	$this->success('头像修改成功','about/index');
			     }else{
			     	$this->error('头像修改失败');
			     }
		     }else{
			   $this->error('图片上传失败');
		      }						
	    	}else{
	    		$this->error($file->getError());
	    	}
	    }
		
	    }else{
	    	
	    	$file = request()->file('file');
	    	if(empty($file)){
	    		$this->error('没有选择文件!');
	    	}
	    	
	    	$info =	$file->move(ROOT_PATH."public/upload");
	    	if($info){
	    		$date = date("Ymd");
	    		$data['url']=$date."/".$info->getFilename();//存储到数据库
	    		$data['job_id'] = $job_id;
	    		$data['user_id'] = $id;
	    		$data['time'] = time();
	    		if(Db::name('photo')->insert($data)){
	    			$path="./upload/".$data['url'];
	    			$u = explode('/', $data['url']);
	    			$new ="./newpath/".$u[1];
	    			$newpath = Image::open($path);
	    			$newpath->thumb(120,120,Image::THUMB_CENTER)->save($new);
	    			$img['url'] = $u[1];
	    			if(Db::name('user')->where('id',$id)->update($img))
	    			{
	    				$this->success('头像修改成功','about/index');
	    			}else{
	    				$this->error('头像修改失败');
	    			}
	    		}else{
	    			$this->error('图片上传失败');
	    		}
	    	}else{
	    		$this->error($file->getError());
	    	}
	    }	
	}
	public function flush($id,$u)
	{
		$data['url']=$u;
		if(Db::name('user')->where('id',$id)->update($data))
		{
			$this->success('头像修改成功','about/index');
		}else{
			$this->error('头像修改失败');
		}
	}
	public function update($id)
	{
		$data = input('post.');
		$data['birthday'] = strtotime($data['birthday']);		 
		$y = date("y",$data['birthday']);
		$ny = date("y",time());
		$data['age'] = $ny-$y+1;
		$ping[] = mktime(0,0,0,1,20,$y);
		$ping[] = mktime(23,59,59,2,18,$y);
		$yu[] = mktime(0,0,0,2,19,$y);
		$yu[] = mktime(23,59,59,3,20,$y);
		$bai[] = mktime(0,0,0,3,21,$y);
		$bai[] = mktime(23,59,59,4,19,$y);
		$niu[] = mktime(0,0,0,4,20,$y);
		$niu[] = mktime(23,59,59,5,20,$y);
		$zi[] = mktime(0,0,0,5,21,$y);
		$zi[] = mktime(23,59,59,6,21,$y);
		$ju[] = mktime(0,0,0,6,22,$y);
		$ju[] = mktime(23,59,59,7,22,$y);
		$shi[] = mktime(0,0,0,7,23,$y);
		$shi[] = mktime(23,59,59,8,22,$y);
		$chu[] = mktime(0,0,0,8,23,$y);
		$chu[] = mktime(23,59,59,9,22,$y);
		$cheng[] = mktime(0,0,0,9,23,$y);
		$cheng[] = mktime(23,59,59,10,23,$y);
		$xie[] = mktime(0,0,0,10,24,$y);
		$xie[] = mktime(23,59,59,11,22,$y);
		$she[] = mktime(0,0,0,11,23,$y);
		$she[] = mktime(23,59,59,12,21,$y);
		$mo[] = mktime(0,0,0,12,22,$y);
		$mo[] = mktime(23,59,59,1,19,$y+1);
		
		if( $data['birthday'] >= $ping['0'] && $data['birthday'] <=$ping['1'])
		{
			$data['xz']="水瓶座";
		}elseif($data['birthday'] >= $yu['0'] && $data['birthday'] <=$yu['1'])
		{
			$data['xz']="双鱼座";
		}elseif($data['birthday'] >= $bai['0'] && $data['birthday'] <=$bai['1'])
		{
			$data['xz']="白羊座";
		}elseif($data['birthday'] >= $niu['0'] && $data['birthday'] <=$niu['1'])
		{
			$data['xz']="金牛座";
		}elseif($data['birthday'] >= $zi['0'] && $data['birthday'] <=$zi['1'])
		{
			$data['xz']="双子座";
		}elseif($data['birthday'] >= $shi['0'] && $data['birthday'] <=$shi['1'])
		{
			$data['xz']="狮子座";
		}elseif($data['birthday'] >= $chu['0'] && $data['birthday'] <=$chu['1'])
		{
			$data['xz']="处女座";
		}elseif($data['birthday'] >= $cheng['0'] && $data['birthday'] <=$cheng['1'])
		{
			$data['xz']="天秤座";
		}elseif($data['birthday'] >= $xie['0'] && $data['birthday'] <=$xie['1'])
		{
			$data['xz']="天蝎座";
		}elseif($data['birthday'] >= $she['0'] && $data['birthday'] <=$she['1'])
		{
			$data['xz']="射手座";
		}elseif($data['birthday'] >= $mo['0'] && $data['birthday'] <=$mo['1'])
		{
			$data['xz']="摩羯座";
		}	
		unset($data['submit']);
		if(Db::name('user')->where('id',$id)->update($data))
		{
			$this->success('信息修改成功','about/index');
		}else{
			$this->error('信息修改失败');
		}
	}
	

}
