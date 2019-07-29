<?php

// ============================ //
//                              //
//         自定义参数配置        //
//                              //
// ============================ //


// =======================================================================

// ============================ //
//                              //
//          基础参数配置         //
//                              //
// ============================ //
define("RUN_TIME_LOG", "./runtime/log");                                  // runtime的日志log目录
define("Domain", "https://xxx.xxx.cn/");                               // 网站域名
define("UPLOAD", "upload/");                                              // 文件上传根目录


// ============================ //
//                              //
//         小程序参数配置        //
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
define("DB_USER", "aat1");        // 数据库用户名
define("DB_PASSWD", "aat1");      // 数据库密码
define("DB_NAME", "aat1");   // 数据库名


// ============================ //
//                              //
//         数据库表配置          //
//                              //
// ============================ //
define("T_TOKEN", "aat_wx_autologin");   // 存 token 的表 表结构固定
define("T_USER", "aat_user");            // 存 user 的表 几个字段需要保持
define("T_VISIT", "aat_visit");          // 存 webservice 访问记录的表
define("T_SWZL", "aat_swzl_record");     // 存 失物招领记录的表
define("T_LIKE", "aat_like");            // 存 收藏记录的表


// ============================ //
//                              //
//          默认时区配置         //
//                              //
// ============================ //
date_default_timezone_set('PRC');

