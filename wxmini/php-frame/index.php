<?php

//========================================//
//                                        //
//        轻量级框架sframe商用开发版V1      //
//                                        //
//========================================//

/******************************************|
 * @version 1.0                            |
 * @author: Bill                           |
 * @date: 2019-2-2                         |
 * @time: 20:10                            |
 ******************************************/

//========================================//
//                                        //
//          @copyright webpro.ltd         //
//                                        //
//========================================//

//=//=//=//=//=//=//=//=//=//=//=//=//=//=//=//=//=//=//=//=//=/=//=//=
// 引用工具类                                                        //=
require_once dirname(__FILE__) .'/app/controllers/Tools.class.php'; //=
// auto load class                                                  //=
Tools::import_basic_class();                //=//=//=//=//=//=//=//=//=
// 加载配置文件 TEST/PROD                    //=
Tools::load_config(TEST);                   //=
//=//=//=//=//=//=//=//=//=//=//=//=//=//=//=//

//=//=//=//=//=//=//=//=//=//=//=//=//=//=//=
// start App                              //=
try{                                      //=
    App::run();                           //=
}catch(Exception $e){                     //=
    echo $e->getMessage();                //=
}                                         //=
//=//=//=//=//=//=//=//=//=//=//=//=//=//=//=

