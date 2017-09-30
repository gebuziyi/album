<?php
/**
 * Created by PhpStorm.
 * User: whdn1
 * Date: 2017/9/12
 * Time: 20:47
 */

namespace app\index\controller;
use think\Controller;
use think\Paginator;
use app\index\model\Sort as SortModel;


class Sort extends Controller{
    public function index(){
        return view();
    }

    public function show(){
        //$data = SortModel::all();
        $data = SortModel::paginate(3);
        $this->assign('data',$data);
        return view();
    }

    public function create(){
        return view();
    }

    public function save(){
        $sort = new SortModel();
        $data['name'] = input('post.name');
        if($sort->validate(true)->save($data)){
            $this->success('分类信息添加成功！','sort/show');
        }else{
            $this->error($sort->getError());
        }
    }

    public function edit($id){
        $sort = SortModel::find($id);
        $this->assign('sort',$sort);
        return view();
    }

    public function update($id){
        $sort = new SortModel();
        $data['id'] = $id;
        $data['name'] = input('post.name');
        $this->validate($data,'sort.edit');
        if($sort->isUpdate(true)->validate(true)->save($data)){
            $this->success('信息修改成功！','sort/show');
        }else{
            $this->error($sort->getError());
        }
    }

    public function delete($id){
        $sort =SortModel::get($id);//获得当前id值
        if($sort){
            if($sort->delete()){
                $this->success('信息删除完成！','sort/show');
            }else{
                $this->error('信息删除失败！');
            }
        }else{
            $this->error('数据删除的记录不存在！');
        }
    }
}

