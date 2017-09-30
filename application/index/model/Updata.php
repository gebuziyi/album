<?php
/**
 * Created by PhpStorm.
 * User: 28374
 * Date: 2017/9/18
 * Time: 11:22
 */
$user = new User();
// post数组中只有name和email字段会写入
$user->allowField(['username','password','email'])->save($_POST, ['id' => 1]);
