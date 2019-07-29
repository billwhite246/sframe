<?php
/**
 * Created by PhpStorm.
 * User: find35.com
 * Date: 15/12/24
 * Time: 下午5:44
 */
class Test{
    public function index($param){
        echo "这里是test控制器的index方法";
        echo '<br/>token=' . $param[0];
        echo '<br/>number=' . $param[1];
    }
}
