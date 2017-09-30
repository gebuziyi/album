<?php
namespace app\index\controller;
use think\Db;
use think\Controller;
class Index extends Controller
{
    public function index()
    {  	
    	$photo= Db::name('photo')->order('time','desc')->limit(15)->select();
    	$size = sizeof($photo);
    	if($size>0){
    	$sang =floor( $size/5);
    	$yu = $size%5;

    	if($yu!=0)
    	{
    		$sang=$sang+1;   	
    	    for($i=0; $i<$size; $i++){
           	$userinfo = Db::name('user')->where('id',$photo[$i]['user_id'])->find();
           	$user[$i] = $userinfo['url'];
           	$photo[$i]['username'] = $userinfo['username'];
    	}
    	
    	for($i=0; $i<$sang; $i++){
    		if($sang==1){
    			for($k=0;$k<$yu;$k++)
    			{
    				$u[$i][$k] =['id'=>$photo[$k]['id'], 'user_name'=>$photo[$k]['username'],'url'=>$photo[$k]['url']];
    				$user_url[$i][$k] = $user[$k];
    			}
    		}elseif ($sang == 2){
    			if($i == 0){
    				for($k=0;$k<5;$k++){
    				$u[$i][$k] =['id'=>$photo[$k]['id'], 'user_name'=>$photo[$k]['username'],'url'=>$photo[$k]['url']];
    				$user_url[$i][$k] = $user[$k];
    			   }
    			}
    			if($i == 1){
    				for($k=5;$k<$yu+5;$k++){
    					$u[$i][$k] =['id'=>$photo[$k]['id'],'user_name'=>$photo[$k]['username'], 'url'=>$photo[$k]['url']];
    					$user_url[$i][$k] = $user[$k];
    				}	
    			}      			
    		}elseif ($sang == 3){
    			if($i == 0){
    				for($k=0;$k<5;$k++){
    					$u[$i][$k] =['id'=>$photo[$k]['id'], 'user_name'=>$photo[$k]['username'],'url'=>$photo[$k]['url']];
    					$user_url[$i][$k] = $user[$k];
    				}
    			}
    			if($i == 1){
    				for($k=5;$k<10;$k++){
    					$u[$i][$k] =['id'=>$photo[$k]['id'],'user_name'=>$photo[$k]['username'], 'url'=>$photo[$k]['url']];
    					$user_url[$i][$k] = $user[$k];
    				}
    			}if($i == 2){
    				for($k=10;$k<$yu+10;$k++){
    					$u[$i][$k] =['id'=>$photo[$k]['id'],'user_name'=>$photo[$k]['username'], 'url'=>$photo[$k]['url']];
    					$user_url[$i][$k] = $user[$k];
    				}
    		}
    		
    	  }
    	}
    }elseif ($yu == 0)
    {
    	for($i=0; $i<$size; $i++){
    		$userinfo = Db::name('user')->where('id',$photo[$i]['user_id'])->find();
    		$photo[$i]['username'] = $userinfo['username'];}
    		
    	for($i=0; $i<$sang; $i++){
    		$j=0+$i*5;
    		$k=0+$i*5;
    		for($k;$k<$j+5;$k++){
    			$u[$i][$k] =['id'=>$photo[$k]['id'], 'user_name'=>$photo[$k]['username'],'url'=>$photo[$k]['url']];
    		}
    	}
    }
    	
    	
    	  $this->assign('u',$u);
    	  $this->assign('sang',$sang);
    	  $this->assign('yu',$yu);
    	  return view(); 
    	
 	
    }
    
    }
}
