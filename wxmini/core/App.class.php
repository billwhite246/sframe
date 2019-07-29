<?php

// 设置返回值编码为 UTF-8
header("Content-type:text/html;charset=utf-8");
header("Access-Control-Allow-Origin: *");

class App{

    protected static $controller = 'Home';//控制器名
    protected static $method = 'index';//方法名
    protected static $pams = array();//其他参数

    /**
     * url重写路由的url地址解析方法
     */
    protected static function paseUrl(){
        if(isset($_GET['url'])){
            // 写入日志
            Tools::writeLog('visit', Tools::getNowTime() . '|' . Tools::getRealIpAddr() . '|' . $_GET['url']);
            Tools::ipdb(Tools::getRealIpAddr(), $_GET['url']);
        }else{
            Tools::writeLog('visit', Tools::getNowTime() . '|' . Tools::getRealIpAddr() . '| none url');
            Tools::ipdb(Tools::getRealIpAddr(), 'none url');
        }
        if(isset($_GET['url']) && $_GET['url'] != ''){
            // print_r($_GET['url']);
            $url = explode('/',$_GET['url']);
            // 如果是 访问 runtime/config 文件夹，是禁止的 省略了 /控制器/模块
            if(isset($url[0]) && ($url[0] == 'runtime' || $url[0] == 'config' || $url[0] == 'page')){
                if($url[0] == 'page'){
                    self::$controller = 'Home';
                    self::$method = 'page';
                    unset($url[0]);
                    self::$pams = array_values($url);
                }else{
                    Tools::printForbbiden();
                    exit;
                }
            }else{
                // 得到控制器
                if(isset($url[0])){
                    self::$controller = $url[0];
                    unset($url[0]);
                }
                // 得到方法名
                if(isset($url[1])){
                    self::$method = $url[1];
                    unset($url[1]);
                }
                // 判断是否有其他参数
                if(isset($url)){
                    self::$pams = array_values($url);
                }
            }
        }
    }


    /**
     * 项目的入口方法
     * @throws Exception
     */
    public static function run(){
        self::paseUrl();

        // 得到控制器的路径
        $url = './app/controllers/'.self::$controller.'.class.php';

        //判断控制器文件是否存在 且 控制器必须注册过
        if(file_exists($url)){
            // 核查对应类中的方法，参数个数是否匹配
            $paramtersCount = count(self::$pams);
            // 使用 控制器注册表 检查请求是否合法
            if(regedit::checkRequsetControllerStrANDMethodStrAndParamCount(self::$controller, self::$method, $paramtersCount)){
                require_once($url);
                $c = new self::$controller;
            }else{
                // 非法、未注册方法
                throw new Exception('未注册的方法');
            }
        }else{
            throw new Exception('控制器不存在');
        }

        // 执行方法
        if(method_exists($c, self::$method)){
            $m = self::$method;
            $new_pams = array();
            $num = count(self::$pams);
            for($i = 0; $i < $num; $i++){
                // 参数过滤不合法字符
                self::$pams[$i] = htmlspecialchars(self::$pams[$i]);
                $new_pams[$i] = self::$pams[$i];
            }
            $c->$m($new_pams);
        }else{
            throw new Exception('方法不存在,但是被注册了');
        }
    }
}

?>