<?php
//===========================
// 定义 load_config 方法的常量
define('TEST', 0);
define('PROD', 1);
//===========================


class Tools{

    // ===================================================================================================
    // ===================================================================================================
    // 系统类

    /**
     * 自动加载类函数
     * // 路由分发处理
     * ./core/App.class.php
     * // 持久层
     * ./app/controllers/Bean.class.php
     */
    public static function import_basic_class(){
        require_once './core/App.class.php';             // 引用路由分发处理
        require_once './core/regedit.php';               // 控制器注册表
        require_once './app/controllers/Bean.class.php'; // 引用持久层接口
        require_once './app/error/GlobalError.php';      // 引用全局错误码
        require_once './app/models/mytools.php';         // 引用自定义工具类
    }

    /**
     * 加载配置类
     * @param string env
     * TEST / PROD
     * 测试环境 / 生产环境
     */
    public static function load_config($env){
        if($env == 0){
            // test 环境
            require_once './config/sframe-test.php';
        }elseif($env == 1){
            // prod 环境
            require_once './config/sframe.php';
        }
    }


    // ===================================================================================================
    // ===================================================================================================
    
    // 非系统类

    /**
     * 数据转为字符串型 防止出现 null 等情况
     * @param int/string data
     * @return string
     */
    public static function toString($data){
        if($data == '' || $data == 'null' || $data == null){
            return '';
        }else{
            return $data;
        }
    }

    /**
     * 获取 访问者 真实IP 无视代理
     */
    public static function getRealIpAddr(){
        if (!empty($_SERVER['HTTP_CLIENT_IP'])){//check ip from share internet
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){//to check ip is pass from proxy
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * 写入 日志文件夹下
     * @param type 日志类型 目前有 visit.log 访问日志
     * @param content 内容
     */
    public static function writeLog($type, $content){
        $filename = "";
        switch($type){
            case 'visit':
                $filename= RUN_TIME_LOG . "/visit.txt";
                break;
        }
        if($filename == ''){
            return;
        }
        $handle = fopen($filename,"a+");
        $str = fwrite($handle, $content."\n");
        fclose($handle); 
    }

    /**
     * IP访问日志存储到数据库中
     * @param string IP
     * @param 
     */
    public static function ipdb($ip, $url){
        // 写入数据库
        $bean = new Bean();
        /** Bean v2.0 */
        $res = $bean->save(
            T_VISIT,
            [
                ['ip', $ip, Bean::PARAM_STR],
                ['url', $url, Bean::PARAM_STR],
                ['time', self::msectime(), Bean::PARAM_STR],
            ]
        );
        // print_r($res);
    }

    /**
     * 年月日、时分秒 + 3位毫秒数
     * @param string $format
     * @param null $utimestamp
     * @return false|string
     */
    public static function ts_time($utimestamp){
        $times = substr($utimestamp, 0, 10);
        $ms = substr($utimestamp, 11, 3);
        $res = date("Y-m-d H:i:s:") . $ms;
        // $timestamp = floor($utimestamp);
        // $milliseconds = round(($utimestamp - $timestamp) * 1000);
        // return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
        return $res;
    }

    /**
     * 返回当前的毫秒时间戳
     * 【已测试】
     * 例如 1556693497 097
     */
    public static function msectime() {
        list($msec, $sec) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    }

    /**
     * 获取毫秒级时间戳
     * echo udate('Y-m-d H:i:s u');
     */
    public static function udate($format = 'u', $utimestamp = null){
        date_default_timezone_set('PRC');
        if (is_null($utimestamp)){
            $utimestamp = microtime(true);
        }
        $timestamp = floor($utimestamp);
        $milliseconds = round(($utimestamp - $timestamp) * 1000000);//改这里的数值控制毫秒位数
        return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
    }

    /**
     * 获取当前PRC日期 example 2016-12-31 05:07:05
     */
    public static function getNowTime(){
        date_default_timezone_set('PRC');
        return date('Y-m-d H:i:s', time());
    }

    /**
     * 返回当前PRC时间戳
     * @return timestamp
     */
    public static function getTimestamp(){
        date_default_timezone_set('PRC');
        return time();
    }

    /**
     * 时间戳转时间Str 精确到日期
     * @param int timestamp
     * @return string time
     * such as 2019-05-27
     */
    public static function timestamp2date($timestamp){
        date_default_timezone_set('PRC');
        return date('Y-m-d', $timestamp);
    }

    /**
     * 时间戳转时间Str 精确到时分秒
     * @param int timestamp
     * @return string time
     * such as 2019-05-27 12:00:00
     */
    public static function timestamp2time($timestamp){
        date_default_timezone_set('PRC');
        return date('Y-m-d H:i:s', $timestamp);
    }

    /**
     * 防SQL注入攻击
     */
    public static function anti_injection($str){
        $str = htmlspecialchars($str);
        $str = str_replace("'","\'",$str);
        return $str;
    }

    /**
     * 持久层专用返回格式
     * @param status
     * @param data
     * @return php array('status'=> , 'data'=>)
     */
    public static function returnBeanData($status, $data=''){
        return array(
            'status' => $status,
            'data' => $data
        );
    }

    public static function printForbbiden(){
        $res = array(
            'err_code' => "0001",
            'err_msg' => "403 Forbidden",
            'info' => "Powered by webpro.ltd AND kidkid.top 2018-2019"
        );
        // echo "403 Forbidden||Powered by webpro.ltd AND kidkid.top 2018-2019";
        $res = json_encode($res,JSON_UNESCAPED_UNICODE);
        return $res;
    }

    /**
     * 直接输出返回值
     * @param err_code
     * @param err_msg
     * @param [info]
     * @return example: [{'err_code': xxxx, 'err_msg': xxxx, 'info': ''}]
     */
    public static function printMsg($err_code, $err_msg='', $info=''){
        $res = array(
            'err_code' => $err_code,
            'err_msg' => $err_msg,
            'info' => $info
        );
        $res = json_encode($res,JSON_UNESCAPED_UNICODE);

        //  $res = json_encode($res,JSON_UNESCAPED_SLASHES);
        // $res = str_replace("\\'", "'", $res);
        $res = str_replace("\\/", "/", $res);
        $res = str_replace("\\n", "", $res);
        $res = str_replace("\\t", "", $res);
        // return $res;
        echo $res;
    }

    /**
     * 返回值
     * @param err_code
     * @param err_msg
     * @param [info]
     * @return example: [{'err_code': xxxx, 'err_msg': xxxx, 'info': ''}]
     */
    public static function returnMsg($err_code, $err_msg='', $info=''){
        $res = array(
            'err_code' => $err_code,
            'err_msg' => $err_msg,
            'info' => $info
        );
        $res = json_encode($res,JSON_UNESCAPED_UNICODE);

        //  $res = json_encode($res,JSON_UNESCAPED_SLASHES);
         $res = str_replace("\\/", "/", $res);
         $res = str_replace("\\n", "", $res);
         $res = str_replace("\\t", "", $res);
        return $res;
    }

    /**
     * 不转码返回值
     * @param err_code
     * @param err_msg
     * @param [info]
     * @return example: [{'err_code': xxxx, 'err_msg': xxxx, 'info': ''}]
     */
    public static function returnMsg1($err_code, $err_msg='', $info=''){
        $res = array(
            'err_code' => $err_code,
            'err_msg' => $err_msg,
            'info' => $info
        );
        $res = json_encode($res,JSON_UNESCAPED_UNICODE);

        //  $res = json_encode($res,JSON_UNESCAPED_SLASHES);
        // $res = str_replace("\\n", "", $res);
        // $res = str_replace("\\t", "", $res);
        return $res;
    }

    /**
     * 生成随机字符串
     * @param int $length 生成随机字符串的长度
     * @param string $char 组成随机字符串的字符串
     * @return string $string 生成的随机字符串
     */
    public static function str_rand($length = 32, $char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
        if(!is_int($length) || $length < 0) {
            return false;
        }
        $string = '';
        for($i = $length; $i > 0; $i--) {
            $string .= $char[mt_rand(0, strlen($char) - 1)];
        }
        return $string;
    }

    /**
     * CURL 模拟 htpp get post 请求
     * @param string url
     * @param string method GET/POST
     * @param string data suchAs urlencode("username=test&token=qazwsxedc753951")
     * @param boolean showHeaders true/false
     * @return string HTML
     */
    public static function CURL($url,$headers,$method,$data,$showHeaders){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch,CURLOPT_HEADER, $showHeaders);
        curl_setopt($ch,CURLOPT_TIMEOUT_MS, 3000);      //  3.0 秒超时
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,10);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_NOBODY, false);
        curl_setopt($ch,CURLOPT_CUSTOMREQUEST, $method);
        if ($method == 'POST')
        {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }


}
