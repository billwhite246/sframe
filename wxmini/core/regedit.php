<?php

/**
 * 控制器注册表
 * @date 2019年5月30日 17点27分
 */

/**
 * 所有连接请求用到的 controller=>method
 * 需要在这里登记注册，并附带方法所对应的参数个数
 */

class regedit{

    protected static $registerList = [

        /* wxmn.class.php */
        'wxmn' => [
            'getUserInfo' => [1],
        ],

        /* Test.class.php */
        'Test' => [
            'index' => [2],
        ],

        /* Home.class.php */
        'Home' => [
            'index' => [0],
            'page' => [2,3,4,5],
        ],

        /* user.class.php */
        'user' => [
            'isTokenValid' => [1]
        ]


    ];

    /**
     * 查询 类名=>方法=>参数个数 是否一致
     * @param string controller name
     * @param string method name
     * @param int paramters count
     * @return Boolean true/false
     * true 核查通过
     * false 核查失败
     */
    public static function checkRequsetControllerStrANDMethodStrAndParamCount($controllerStr, $methodStr, $paramtersCount){
        if(isset(self::$registerList[$controllerStr][$methodStr])){
            $countArr = self::$registerList[$controllerStr][$methodStr];
            $res = false;
            foreach($countArr as $k => $count){
                if($count == $paramtersCount){
                    $res = true;
                    break;
                }
            }
            return $res;
        }else{
            return false;
        }
    }

}