<?php
namespace app\index\controller;
use app\index\model\Talk;
use think\Controller;
use think\Db;
use think\Session;
header("content-type:text/html;charset=utf-8");

class Reply extends Controller
{

    public function show($id){
        $data = Db::name('talk')->where(['photo_id'=>$id])->paginate(3);
        $this->assign('data',$data);
        return view();
    }

    public function reply($id){
       $data = Db::name('talk')->where(['id'=>$id])->find();
        $this->assign('data',$data);
        return view();
    }

    public function saveReply(){
        $id = input('post.id');
        $reply = input('post.reply');
        $result = $this->validate(
            ['reply'=>$reply,

            ],
            [
                'reply.require'=>'回复内容不能为空',

            ]

        );
        if($result === true){
            if(Db::name('talk')->where('id',$id)->update(['reply'=>$reply])){
                $this->success('回复成功');
                return view('reply/show');
            }else{
                $this->error('回复失败');
            }
        }else{
            $this->error($result);
        }
    }

    public function delete($id){
        if(Db::name('talk')->where('id',$id)->delete()){
            $this->success('评论删除成功');
            return view('reply/show');
        }else{
            $this->error('评论删除失败');
        }
    }
}