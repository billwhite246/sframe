<?php

// ============================ //
//                              //
//         自定义参数配置        //
//                              //
// ============================ //                                    // 令牌有效期 259200s 3天


// =======================================================================

// ============================ //
//                              //
//          基础参数配置         //
//                              //
// ============================ //
define("RUN_TIME_LOG", "./runtime/log");                                  // runtime的日志log目录
define("Domain", "http://127.0.0.1/vsCode/rtbServer-mini/");               // 网站域名
define("UPLOAD", "upload/");                                              // 文件上传根目录


// ============================ //
//                              //
//         公众号参数配置        //
//                              //
// ============================ //
define("APPID", "APPID");                    // mini-program APPID
define("APPSECRET", "APPSECRET");  // mini-program APPSECRET


// ============================ //
//                              //
//        数据库参数配置         //
//                              //
// ============================ //
define("DB_HOST", "127.0.0.1");   // 数据库地址
define("DB_USER", "root");        // 数据库用户名
define("DB_PASSWD", "root");      // 数据库密码
define("DB_NAME", "sframe");         // 数据库名


// ============================ //
//                              //
//         数据库表配置          //
//                              //
// ============================ //
define("T_TOKEN", "demo_wxmini_wx_autologin"); // 存 token 的表 表结构固定
define("T_USER", "demo_wxmini_user");          // 存 user 的表 几个字段需要保持
define("T_VISIT", "demo_wxmini_visit");        // 存 webservice 访问记录的表


// ============================ //
//                              //
//          默认时区配置         //
//                              //
// ============================ //
date_default_timezone_set('PRC');

