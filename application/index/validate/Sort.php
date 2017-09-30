<?php
/**
 * Created by PhpStorm.
 * User: whdn1
 * Date: 2017/9/13
 * Time: 10:17
 */


namespace app\index\validate;
use think\Validate;

class Sort extends Validate{
    protected $rule=[
        'name'=>'require'
    ];

    protected $message=[
        'name.require'=>'分类名不能为空！',
    ];

    protected $scene=[
        'create'=>['name'],
        'edit'=>['name']
    ];
}