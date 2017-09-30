<?php
namespace app\index\controller;
use think\Controller;
use think\Session;
use think\Db;
use app\index\controller\User;
use app\index\model\User as UserModel;
use app\index\model\Photo;
use app\index\model\Talk;
header("content-type:text/html;charset=utf-8");

class Comment extends Controller
{
    public function addComment($id){
        $data = Db::name('talk')->where(['photo_id'=>$id])->paginate(3);
        //$data = Talk::get($id)->paginate(4);
        $this->assign('data',$data);
        $this->assign('photo_id',$id);
        $user = new User();
        $user = Db::name('user')->where(['username'=>Session::get('name')])->find();
        $this->assign('user',$user);
        return view();
    }

    public function saveComment(){

        $content = input('post.content');
        $author = input('post.author');
        $photo_id = input('post.photo_id');
        $time = time();
        $result = $this->validate(
            ['content'=>$content,
             'author'=>$author,
            ],
        	['content'=>'require',
        	 'author'=>'require'
        	],
            [
                'content.require'=>'评论内容不能为空',
                'author.require'=>'您还没有登录哦，无法发表评论'
            ]

        );
        if($result === true){
            if(Db::name('talk')->insert(['content'=>$content,'author'=>$author,'photo_id'=>$photo_id,'time'=>$time])){
                $this->success('评论成功');
                return view('comment/addComment');
            }else{
                $this->error('评论失败');
            }
        }else{
            $this->error($result);
        }
    }
}