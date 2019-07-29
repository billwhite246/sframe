<?php

class wxmn{

    
    /**
     * BEEN REGISTER
     * code 换取 openid、session_key 等数据
     * @param string code
     * @return string token
     */
    public function getUserInfo($param){
        $code = '';
        if(isset($param[0])){
            $code = $param[0];
        }else{
            exit;
        }

        // auth.code2Session
        // 本接口应在服务器端调用，详细说明参见服务端API。
        // 登录凭证校验。通过 wx.login() 接口获得临时登录凭证 code 后传到开发者服务器调用此接口完成登录流程。
        // 请求地址
        // GET https://api.weixin.qq.com/sns/jscode2session?appid=APPID&secret=SECRET&js_code=JSCODE&grant_type=authorization_code
        $res = Tools::CURL(
            'https://api.weixin.qq.com/sns/jscode2session?' . 'appid=' . APPID . '&secret=' . APPSECRET . '&js_code=' . $code . '&grant_type=authorization_code',
            array('User-Agent: CURL For WeChat mini program'),
            'GET',
            '',
            false
        );

        $obj = json_decode($res);
        // if((int)$obj->errcode == 0){echo "TRUE";}else{echo "FAIL + " . $obj->errcode;}$obj
        // print_r($obj);

        // 判断下 errcode ,从而判断请求是否成功
        // 值	说明	最低版本
        // -1	系统繁忙，此时请开发者稍候再试	
        // 0	请求成功	
        // 40029	code 无效	
        // 45011	频率限制，每个用户每分钟100次
        // stdClass Object ( [session_key] => dnAi7rIyxWnDviGE5VV+2w== [openid] => ooi6v5zwr8hd1ciAskKbM_mYod1c )
        // errcode == 0 的 判断方法不对...
        if(isset($obj->openid) && isset($obj->session_key)){
            // 返回的 JSON 数据包
            // 属性	类型	说明
            // openid	string	用户唯一标识
            // session_key	string	会话密钥
            // unionid	string	用户在开放平台的唯一标识符，在满足 UnionID 下发条件的情况下会返回，详见 UnionID 机制说明。
            // errcode	number	错误码
            // errmsg	string	错误信息
            $openid = $obj->openid;
            $session_key = $obj->session_key;
            $unionid = isset($obj->unionid) ? $unionid : ''; // 如果有就存起来

            // 查是否为新用户
            $bean = new Bean();

reLogin:

            $res = $bean->find_one(
                T_USER,
                ['userid'],
                [
                    ['openid', $openid, Bean::PARAM_STR, Bean::RELATION_EQUAL]
                ]
            );

            // print_r($res);

            // exit();

            if($res['status'] == 10000 && count($res['data']) < 1){
                // 新用户

                $activeTime = Tools::getTimestamp();

                $res1 = $bean->save(
                    T_USER,
                    [
                        ['openid',      $openid,      Bean::PARAM_STR],
                        ['unionid',     $unionid,     Bean::PARAM_STR],
                        ['activeTime',  $activeTime,  Bean::PARAM_STR],
                    ]
                );

                if($res1['status'] == 10000){
                    // 新用户静默注册成功
                    goto reLogin;
                }else{
                    // 新用户静默注册失败
                    Tools::printMsg(GlobalError::$NewUserRegisterFail, $res1['status'] . ', ' . $res1['info']);
                }

            }elseif($res['status'] == 10000 && count($res['data']) > 0){
                // 老用户
                $userid = $res['data']['userid'];
                // 签发令牌
                $create_time = Tools::getTimestamp();
                $token = md5(Tools::str_rand() . $openid);
                $inuse = 1;

                // 踢掉该 userid 的其他令牌
                $res1 = $bean->save(
                    T_TOKEN,
                    [
                        ['inuse', '0', Bean::PARAM_STR],
                    ],
                    [
                        ['userid', $userid, Bean::PARAM_STR, Bean::RELATION_EQUAL],
                    ]
                );

                // 写入新令牌
                $res2 = $bean->save(
                    T_TOKEN,
                    [
                        ['userid',       $userid,       Bean::PARAM_STR],
                        ['openid',       $openid,       Bean::PARAM_STR],
                        ['unionid',      $unionid,      Bean::PARAM_STR],
                        ['session_key',  $session_key,  Bean::PARAM_STR],
                        ['token',        $token,        Bean::PARAM_STR],
                        ['create_time',  $create_time,  Bean::PARAM_STR],
                        ['inuse',        $inuse,        Bean::PARAM_STR],
                    ]
                );

                if($res1['status'] == '10000' && $res2['status'] == '10000' ){
                    // 签发令牌成功
                    // 登陆成功
                    // 返回 token 和 使用的 code，前端用code比对一下，看下传过去的code和传回来的code是否相同，这样逻辑更严谨，杜绝令牌签发错误
                    Tools::printMsg(GlobalError::$SUCCESS, '', ["code" => $code, "token" => $token]);
                }else{
                    // 令牌签发失败
                    // 数据回滚
                    // ... 代码实现略 ...
                    Tools::printMsg(GlobalError::$IssuedTokenFail, 'res1=' . $res1['status'] . ',res2=' . $res2['status']);
                }
            }elseif($res['status'] != 10000){
                print_r($res);
            }
        }elseif((int)($obj->errcode) == 40029){
            // code 无效
            Tools::printMsg(GlobalError::$InvalidCode, 'wx:' . $obj->errmsg);
        }else{
            // 其他错误
            Tools::printMsg(GlobalError::$GetOpenIdFail, 'wx:' . $obj->errmsg);
        }
    }

}
