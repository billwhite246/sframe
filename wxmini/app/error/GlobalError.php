<?php

/**
 * 
<pre>
全局错误码
<!--===-->
错误码规则，例如 110001
  10    0               001
  |     |                |
 类别  代码升级自增  自增错误编号
<!--===-->
类别注释,类别从 10 开始排序,10,11,12,13,14,...
<!--===-->
代码升级自增,新写的代码，该位+1,自增错误编号从0开始
<!--===-->
自增错误编号,从0开始
<!--===-->
注册的类别注释以及错误码说明
<!--===-->
10 | 与微信小程序登陆集成过程中产生的错误码
<!--===-->
</pre>
<!--===-->
<ul>
<!--全局成功-->
<li>100000: 全局执行成功码</li>
<li>100001: 全局执行失败码。后面接错误描述(不可预测的错误描述)</li>

<!--微信登陆集成-->
<li>110001: 调用微信的auth.code2Session接口，未得到正确返回信息(可能的情况：系统繁忙，此时请开发者稍候再试	/ 频率限制，每个用户每分钟100次)</li>
<li>110002: 调用微信的auth.code2Session接口，code 无效</li>
<li>110003: 新用户静默注册失败</li>
<li>110004: 签发令牌失败，不是删除令牌错了就是写入令牌错了</li>

<!--===-->
</ul>

 * 
*/

class GlobalError{

    public static $SUCCESS = 100000;
    public static $FAIL    = 100001;

    public static $GetOpenIdFail       = 110001;
    public static $InvalidCode         = 110002;
    public static $NewUserRegisterFail = 110003;
    public static $IssuedTokenFail     = 110004;

    public static $InvalidToken        = 110005;


}