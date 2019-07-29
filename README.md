# sframe暂时分三个版本
> * 微信小程序版
> * 微信公众号版
> * 自定义通用版

# sframe PHP框架 ~ 微信小程序版
------
> * version: Beat 1.0
> * 发布时间: 2019-07-28 19:42
> * E-mail: scompany@vip.qq.com
> * @Copyright webpro.ltd

------
该框架内置了微信小程序登陆集成功能:
> * 仅需修改/config下的配置文件sframe-test.php / sframe.php中的微信小程序appid、appsecret即可使用。
> * 后端自带路由控制，支持Apache服务器。
> * 微信小程序登陆集成，仅需客户端将code传给框架接口，框架接口解析后会进行新用户自动注册、生成令牌token并返回给客户端，token将是客户端访问服务器资源的唯一合法凭据。此举可防一般性的XSRF攻击。
> * 框架内置MySQL数据库操作对象，即/app/controller/Bean.class.php，内置了增删改查、分页、排序、模糊搜索等常用操作的操作接口，防SQL注入，底层为PDO，PHP版本需要>=5.6，[sframe-Bean文档](https://www.webpro.ltd/blog/?id=34)。
> * 框架内置后端访问日志，所有的访问记录都会存储在数据表中和/runtime/log/visit.txt中，对日志进行分析可提高框架安全性。

## 框架配置及使用
### 1. 引入数据库
解压`基础数据表.zip`，你会得到三个MySQL数据表：`demo_wxmini_user.sql`、`demo_wxmini_visit.sql`、`demo_wxmini_wx_autologin.sql`，而后导入到你的测试数据库中。
### 2. 指定配置文件
sframe框架支持双环境配置，你可以通过自由切换`/config`目录下的配置文件，实现在不同运行环境下框架正常运行。`/config`目录下目前有两个配置文件即`sframe-test.php`和`sframe.php`，一般来说，`sframe-test`一般应用与测试开发环境，`sframe`用于生产环境，你可以在`/index.php`中指定不同的配置文件：
```php
// 加载配置文件 TEST/PROD
Tools::load_config(TEST);
```
传入`TEST`则使用`sframe-test`，传入`PROD`则使用`sframe`。
### 3. 微信小程序参数配置
打开`/config/sframe-test.php`配置文件，修改：
```php
// ============================ //
//                              //
//         公众号参数配置        //
//                              //
// ============================ //
define("APPID", "APPID");                    // mini-program APPID
define("APPSECRET", "APPSECRET");            // mini-program APPSECRET
```
### 4. 数据库参数配置
打开`/config/sframe-test.php`配置文件，修改：
```php
// ============================ //
//                              //
//        数据库参数配置         //
//                              //
// ============================ //
define("DB_HOST", "127.0.0.1");   // 数据库地址
define("DB_USER", "root");        // 数据库用户名
define("DB_PASSWD", "root");      // 数据库密码
define("DB_NAME", "sframe");      // 数据库名


// ============================ //
//                              //
//         数据库表配置          //
//                              //
// ============================ //
define("T_TOKEN", "demo_wxmini_wx_autologin"); // 存 token 的表 表结构固定
define("T_USER", "demo_wxmini_user");          // 存 user 的表 几个字段需要保持
define("T_VISIT", "demo_wxmini_visit");        // 存 webservice 访问记录的表
```
### 5. 访问测试
例如我的sframe放在了`网站根目录/vsCode/sframe/wxmini`文件夹下，改框架内置了两个功能性路由，即登陆和校验令牌。
登陆接口：
`domain/vsCode/sframe/wxmini/wxmn/getUserInfo/{code}`
`{code}即微信小程序前端所获取之code`
返回示例(JSON)
```
{
    err_code: 100000,
    err_msg: "",
    info:{
        code: "011a0HHR1JEky21heBJR1wIIHR1a0HH6",
        token: "a1c10ba2cb4ff0b627e680d64ac55e1f"
    }
}
```
令牌校验接口：
`domain/vsCode/sframe/wxmini/wxmn/user/isTokenValid/{token}`
`{token}即上一个接口所获取之token`
返回示例(JSON)
```
{"err_code":100000,"err_msg":"token is right","info":""}
```
