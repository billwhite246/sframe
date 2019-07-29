<?php
/**
 * Created by PhpStorm.
 * User: find35.com
 * Date: 15/12/24
 * Time: 下午5:44
 */
class Home{
    public function index(){
        echo "这里是 home 控制器里的 index 方法（默认方法）";
    }

    public function test(){
        echo "这里是 home 控制器里的 test 方法";
    }

    public function page($param){
        $absPath = 'view/';
        $filename = '';
        for($i = 0; $i < count($param); $i++){
            if($i == count($param) - 1){
                // 最后一个是文件名
                $absPath .= $param[$i] . '.html';
                $filename = $param[$i];
            }else{
                // 其他都是路径
                $absPath .= $param[$i] . '/';
            }
        }
        if(file_exists($absPath)){
            // $method = 'page_' . $filename;
            // self::$method($absPath);
            require_once $absPath;
        }else{
            require_once "view/error/404.html";
        }

    }

}
