<?php 
namespace app\index\model;
use think\Model;
class Photo extends Model
{
	public function job(){
		return $this->belongsTo('Job');
	}

	public function user(){
		return $this->belongsTo('User');
	}

	public function talk(){
		return $this->hasMany('Talk');
	}
}
