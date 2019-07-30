<?php

/**
 * @version Beat 2.0
 * work with php-pdo-extension and php(version>=5.6)
 */

/**
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * @author Bill E-mail scompany@vip.qq.com
 * @date 2019-07-19 18:46
 * the interface to controll the MySql DataBase
 * 轻量级 PHP 持久层接口类
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 支持两种工作模式: CUSTOMER/AUTO
 * ---------------------------------------------------------------------
 * CUSTOMER
 * 手动模式: 即每次适用对象需传入DB_HOST, DB_USER, DB_PASSWD, DB_NAME这四个
 *          参数.
 * ---------------------------------------------------------------------
 * AUTO
 * 自动模式: 通过读取全局变量的DB_HOST, DB_USER, DB_PASSWD, DB_NAME自动连接,
 *          全局变量需要提前在引用页面中定义.
 * ---------------------------------------------------------------------
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 若以独立文件的形式获取到 该 Bean.class.php, 注意 __construct方法中的
 * DB_HOST, DB_USER, DB_PASSWD, DB_NAME
 * 需要定义全局变量 或者 替换成 自己引入的变量
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 本持久层 基于 PHP的PDO扩展,因此需要开启PDO扩展
 * PHP PDO & PHP version >= 5.6
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 传参需要备注类型,即INT整数,BOOL布尔,NULL空,STR字符及其他类型
 * 引用常量:
 * Bean::PARAM_INT
 * Bean::PARAM_BOOL
 * Bean::PARAM_NULL
 * Bean::PARAM_STR
 * 其实上面的静态变量等价于PDO类中对应名称的静态变量的值
 * Bean::PARAM_INT <=> PDO::PARAM_INT
 * Bean::PARAM_BOOL <=> PDO::PARAM_BOOL
 * Bean::PARAM_NULL <=> PDO::PARAM_NULL
 * Bean::PARAM_STR <=> PDO::PARAM_STR
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * # 接口列表
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * use e.g
 * ---------------------------------------------------------------------
 * ## 插入新纪录
    save(
        'table_name',
        [
            ['columnA', 'valA', Bean::PARAM_INT],
            ['columnB', 'valB', Bean::PARAM_BOOL],
            ['columnC', 'valC', Bean::PARAM_NULL],
            ['columnD', 'valD', Bean::PARAM_STR],
        ]
    );
 * ---------------------------------------------------------------------
 * ## 更新一条记录
    save(
        'table_name',
        [
            ['columnA', 'valA', Bean::PARAM_INT],
            ['columnB', 'valB', Bean::PARAM_BOOL],
        ],
        [
            ['columnC', 'valC', Bean::PARAM_STR, Bean::RELATION_MORE],
            ['columnD', 'valD', Bean::PARAM_STR, Bean::RELATION_LESS],
            ['columnE', 'valE', Bean::PARAM_STR, Bean::RELATION_MORE_OR_EQUAL],
            ['columnF', 'valF', Bean::PARAM_STR, Bean::RELATION_LESS_OR_EQUAL],
            ['columnG', 'valG', Bean::PARAM_STR, Bean::RELATION_EQUAL],
            ['columnH', 'valH', Bean::PARAM_STR, Bean::RELATION_NOT_EQUAL],
            ['columnI', 'valI', Bean::PARAM_STR, Bean::RELATION_LIKE],
        ]
    );
 * ---------------------------------------------------------------------
 * ## 批量插入新纪录
    save_many(
        'table_name',
        [
            [
                ['columnA', 'valA', Bean::PARAM_STR],
                ['columnB', 'valB', Bean::PARAM_BOOL]
            ],
            [
                ['columnC', 'valC', Bean::PARAM_STR],
                ['columnD', 'valD', Bean::PARAM_BOOL]
            ],
            [
                ['columnE', 'valE', Bean::PARAM_STR],
                ['columnF', 'valF', Bean::PARAM_BOOL]
            ]
        ]
    );
 * ---------------------------------------------------------------------
 * ## 批量更新记录(每一个更新指令均可指定不同的更新条件)
    save_many(
        'table_name',
        [
            [ 语句1
                ['columnA', 'valA', Bean::PARAM_STR],
                ['columnB', 'valB', Bean::PARAM_BOOL]
            ],
            [ 语句2
                ['columnC', 'valC', Bean::PARAM_STR],
                ['columnD', 'valD', Bean::PARAM_BOOL]
            ]
        ],
        [
            [ 语句1 对应的条件
                ['columnE', 'valE', Bean::PARAM_STR, Bean::RELATION_MORE],
                ['columnF', 'valF', Bean::PARAM_STR, Bean::RELATION_LESS_OR_EQUAL],
                ['columnG', 'valG', Bean::PARAM_STR, Bean::RELATION_EQUAL],
            ],
            [ 语句2 对应的条件
                ['columnH', 'valH', Bean::PARAM_STR, Bean::RELATION_NOT_EQUAL],
                ['columnI', 'valI', Bean::PARAM_STR, Bean::RELATION_LIKE],
            ]
        ]
    );
 * ---------------------------------------------------------------------
 * ## 查询一条记录
    限定字段查询:
    find_one(
        'table_name',
        ['column1', 'column2', 'column3'],
        [
            ['cloumnA', 'valA', Bean::PARAM_STR, Bean::RELATION_EQUAL],
            ['cloumnB', 'valB', Bean::PARAM_STR, Bean::RELATION_NOT_EQUAL]
        ]
    )
    通配符查询:
    find_one(
        'table_name',
        [Bean::SIGN_FINDALL],
        [
            ['cloumnA', 'valA', Bean::PARAM_STR, Bean::RELATION_EQUAL],
            ['cloumnB', 'valB', Bean::PARAM_STR, Bean::RELATION_NOT_EQUAL]
        ]
    )
 * ---------------------------------------------------------------------
 * ## 查询 指定字段 / 按条件查询 / 支持模糊查询 / 仅支持AND关系式 / 按单个or多个字段升序or降序排序 / 分页查询
    find_all(
        'table_name',
        [Bean::SIGN_FINDALL],
        [
            ['cloumnA', 'valA', Bean::PARAM_STR, Bean::RELATION_MORE],
            ['cloumnB', 'valB', Bean::PARAM_STR, Bean::RELATION_LIKE]
        ],
        [
            ['cloumnC', Bean::ORDER_BY_DESC],
            ['cloumnD', Bean::ORDER_BY_ASC],
        ],
        [
            0 (第几页, 必传), 10 (每页多少个, 不传默认为30)
        ]
    );
 * ---------------------------------------------------------------------
 * ## 按条件删除
    remove(
        'table_name',
        [
            ['cloumnA', 'valA', Bean::PARAM_STR, Bean::RELATION_EQUAL],
            ['cloumnB', 'valB', Bean::PARAM_STR, Bean::RELATION_NOT_EQUAL]
        ]
    );
 * ---------------------------------------------------------------------
 * ## 自定义select
    select_native(
        'SELECT * FROM `table_name` WHERE `id` > :id',
        [
            [':id', '2', Bean::PARAM_STR]
        ]
    );
 * ---------------------------------------------------------------------
 * ## 自定义update
    update_native(
        'UPDATE `table_name` SET money = money + 1 WHERE `id` = :id',
        [
            [':id', '2', Bean::PARAM_STR]
        ]
    );
 * ---------------------------------------------------------------------
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 */



class Bean{


    /** 数据库句柄 - 依赖PHP的PDO扩展 */
    private $_pdo;

    /** 存储 用户传入的 mode */
    private $mode;
    /**存储用户传入的$db_host, $db_user, $db_passwd, $db_name (当且仅当用户传入的mode为MODE_CUSTOMER时有效) */
    private $db_host;
    private $db_user;
    private $db_passwd;
    private $db_name;


    /**配置数据库端口 */
    const PORT                   = 3306;

    /** basic param type */
    const PARAM_INT               = 1;  // PDO::PARAM_INT
    const PARAM_BOOL              = 5;  // PDO::PARAM_BOOL
    const PARAM_NULL              = 0;  // PDO::PARAM_NULL
    const PARAM_STR               = 2;  // PDO::PARAM_STR

    /** 本类中的INT型常量从20开始排, 0-19留给PDO内置的常量 */
    /** Bean mode */
    const MODE_CUSTOMER           = 20;  // 自定义传参
    const MODE_AUTO               = 21;  // 自动寻找参数

    /** 关系限定符 */
    const RELATION_MORE           = '>';   // 大于
    const RELATION_LESS           = '<';   // 小于
    const RELATION_MORE_OR_EQUAL  = '>=';  // 大于等于
    const RELATION_LESS_OR_EQUAL  = '<=';  // 小于等于
    const RELATION_EQUAL          = '=';   // 等于 => 也是算数运算符
    const RELATION_NOT_EQUAL      = '!=';  // 不等于
    const RELATION_LIKE           = '%';   // 模糊查询用Like

    /** 算数运算符 */
    const COMPUTED_PLUS           = '+';  // plus
    const COMPUTED_MINUS          = '-';  // minus
    const COMPUTED_MULTIPLY       = '*';  // multiply
    const COMPUTED_DIVISION       = '/';  // division

    /** 符号 */
    const SIGN_FINDALL            = '*';

    /** 自定义外SQL <=> 内 SQL */
    const ORDER_BY_DESC           = 22;  // 仅限查询时,定义排序规则所用
    const ORDER_BY_ASC            = 23;  // 仅限查询时,定义排序规则所用



    /**
     * 状态编码及语句
     * @param int status code
     * @return array(status_code, status)
     */
    public function BeanStatus($status_code=-1, $custom_info=''){

        // 严谨
        $status_code = (string)$status_code;

        // 状态列表
        $statusArr = [

            /* 全局通用 */
            ['10000' => 'success'],
            ['10001' => 'fail'],
            ['10002' => 'BeanStatus can not understand which status you want to get'],
            ['10003' => 'Throw PDOException, Connection failed: '],

            /* PDO驱动加载失败 或 PHP版本小于5.6 */
            ['10004' => 'Your php is not support PDO extension, please set it'],
            ['10005' => 'Your php version under 5.6, please change your php version'],

            /* 连接时 */
            ['11000' => 'you are using CUSTOMER mode, please set the parameters for DB_HOST, DB_USER, DB_PASSWD and DB_NAME'],

            /* SQL执行时 */
            ['-12000' => 'PDO->errInfo: '],
            ['12000' => 'exec save(add new record) fail: '],
            ['12001' => 'exec save(update a exist record) fail: '],
            ['12002' => 'exec select fail: '],



        ];

        $returnMsg = ['status' => '', 'data' => ''];

        // 循环查找
        foreach($statusArr as $key => $item){
            if(isset($item[$status_code])){
                $returnMsg['status'] = $status_code;
                $returnMsg['info']   = $item[$status_code];
                $returnMsg['data']   = $custom_info;
                return $returnMsg;
            }
        }

        // 没有对应的码返回 通用 fail
        // return $statusArr[1] . $custom_info;
        $returnMsg['status'] = '10001';
        $returnMsg['info']   = 'fail: ' . $custom_info;
        $returnMsg['data'] = [];
        // 返回 ['status' => 'No', 'info' => 'xxx' 'data' => 'xxx']
        return $returnMsg;
    }



    /**
     * 初始化对象
     * 构造函数
     * @access public
     * @param 
     */
    function __construct($mode=Bean::MODE_AUTO, $db_host='', $db_user='', $db_passwd='', $db_name=''){
        // check pdo extension
        if(!class_exists('pdo')){
            exit(json_encode($this->BeanStatus(10004)));
        }
        // check php version if it >= 5.6
        $version = substr(PHP_VERSION,0,3);
        if($version < 5.6){
            exit(json_encode($this->BeanStatus(10005)));
        }
        $this->mode = $mode;
        $this->db_host = $db_host;
        $this->db_user = $mode;
        $this->db_passwd = $db_passwd;
        $this->db_name = $db_name;

        // test connect db
        $this->keep_db_connected();
    }



    /**
    * 检查连接是否可用
    * @param Link $dbconn 数据库连接
    * @return Boolean
    */
    private function pdo_ping(){
        if($this->_pdo == null){
            return false;
        }else{
            try{
                $this->_pdo->getAttribute(PDO::ATTR_SERVER_INFO);
            } catch (PDOException $e) {
                if(strpos($e->getMessage(), 'MySQL server has gone away')!==false){
                    return false;
                }
            }
            return true;
        }
    }



    private function keep_db_connected(){
        if(!$this->pdo_ping()){
            // 连接数据库
            try{
                // 判断连接模式
                if($this->mode == 21){
                    // AUTO 1
                    // 自动
                    // $dsn = "mysql:dbname=" . DB_NAME . ";host=" . DB_HOST;
                    $dsn = "mysql:host=" . DB_HOST . ";port=" . Bean::PORT . ";dbname=" . DB_NAME . ";charset=utf8";
                    $this->_pdo = new PDO($dsn, DB_USER, DB_PASSWD);
                }else{
                    // CUSTOMER 0
                    // 手动
                    // $dsn = "mysql:dbname={$db_name};host={$db_host}";
                    $dsn = "mysql:host={$this->db_host};port=" . Bean::PORT . ";dbname={$this->db_name};charset=utf8";
                    if($this->db_host == '' || $this->db_user == '' || $this->db_passwd == '' || $this->db_name == ''){
                        exit(json_encode($this->BeanStatus(11000)));
                    }
                    $this->_pdo = new PDO($dsn, $this->db_user, $this->db_passwd);
                }
            }catch(PDOException $e){
                exit(json_encode($this->BeanStatus(10003, $e->getMessage())));
            }

            /* disabled prepared statements的仿真效果 (anti sqlin? 实测无这条语句也可以防注入) */
            $this->_pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); //
            /* set autocommit to off */
            // $this->_pdo->beginTransaction();
        }
    }



    // =========================================================================================================
    // =========================================================================================================
    // =========================================================================================================



    /**
     * [INSERT]/[UPDATE]
     * @access public
     * 当为 INSERT 时，参数有 2 个
     * @param array table_name 表名
     * @param string arr_save 字段名运算符号值
     * How To use
        save(
            'table_name',
            [
                ['username', 'test', Bean::BasicType],
                ['phone', '15010988888', Bean::BasicType]
            ]
        );
     *
     * 当为 UPDATE 时，参数有 3 个
     * @access public
     * @param array  table_name 表名
     * @param string arr_save 字段名运算符号值
     * @param array arr_condition 查询条件 字段名运算符号值 列表
     * How To use
        save(
            'table_name',
            [
                ['username', 'test', Bean::BasicType],
                ['phone', '15010988888', Bean::BasicType]
            ],
            [
                ['userid', '16', Bean::BasicType, Bean::Relation]
            ]
        );
     * 
     */
    public function save(){
        // overloadFun可以随便定义，但为了命名规范，建议宝贝为与此函数名一样，
        // 后面的尾随数值为参数个数，以方便管理
        $name = "save".func_num_args(); 
        return call_user_func_array(array($this,$name), func_get_args());
    }



    /**
     * [INSERT]插入一条记录
     * @access private
     * @param array table_name 表名
     * @param string arr_save 字段名运算符号值
     * @return
     * INSERT INTO `pb_user` (`username` ,`phone`) VALUES ('test', '15010988888')
     */
    private function save2(){
        $this->keep_db_connected();
        $this->_pdo->beginTransaction();
        // 动态获取参数
        $arg_list = func_get_args();
        $table_name = $arg_list[0];
        $arr_save = $arg_list[1];
        // $auto_commit = isset($arg_list[2]) ? $arg_list[2] : true;

        // begin to deal

        // separate column_names, column_vals and column_val_types
        $column_names = [];
        $column_vals = [];
        $column_val_types = [];

        $_prepare_sql_column_name_combine = "(";
        $_prepare_sql_column_val_combine = "(";
        foreach($arr_save as $idx => $column_name_value_arr){
            $column_name = $column_name_value_arr[0];
            $column_val = $column_name_value_arr[1];
            $column_names[] = ':' . $column_name;
            $column_vals[] = $column_val;
            $column_val_types[] = $column_name_value_arr[2];
            if($idx == 0){
                $_prepare_sql_column_name_combine .= '`';
                $_prepare_sql_column_val_combine .= ':' . $column_name;
            }else{
                $_prepare_sql_column_name_combine .= ', `';
                $_prepare_sql_column_val_combine .= ', :' . $column_name;
            }
            $_prepare_sql_column_name_combine .= $column_name;
            $_prepare_sql_column_name_combine .= '`';
        }
        $_prepare_sql_column_name_combine .= ")";
        $_prepare_sql_column_val_combine .= ")";

        // combine prepare sql and prepare it
        $_prepare_sql = "INSERT INTO `{$table_name}` ";
        $_prepare_sql .= $_prepare_sql_column_name_combine;
        $_prepare_sql .= ' VALUES ';
        $_prepare_sql .= $_prepare_sql_column_val_combine;
        $_stmt = $this->_pdo->prepare($_prepare_sql);

        // 捕捉 prepare 方法的错误并返回
        if($this->_pdo->errorCode() != '000000'){
            // print_r($this->_pdo->errorInfo());
            $errInfo = $this->_pdo->errorInfo();
            return $this->BeanStatus(-12000, $errInfo[0] . ', ' . $errInfo[1] . ', ' . $errInfo[2]);
        }

        // echo $_prepare_sql . '<br/>';

        // bindValue
        foreach($column_names as $idx => $column_name){
            $_stmt->bindValue($column_name, $column_vals[$idx], $column_val_types[$idx]);
            // echo $column_name . ' / ' . $column_vals[$idx] . ' / ' . $column_val_types[$idx] . '<br/>';
        }

        // exit();

        // 测试执行
        try{
            $_stmt->execute();
            $this->_pdo->commit();
        }catch(PDOException $e){
            echo $e->getMessage();
            if($this->pdo_ping()){
                $this->_pdo->rollback();
            }
            // return save fail
            return $this->BeanStatus(12000, $e->getMessage());
        }
        // return success
        return $this->BeanStatus(10000);
    }



    /**
     * [UPDATE]更新一条记录
     * @access private
     * @param array table_name 表名
     * @param string arr_save 字段名运算符号值
     * @param array arr_condition 查询条件 字段名运算符号值 列表
     * @return Array ( [status] => 10000 [data] => effect row count [info] => success )
     */
    private function save3(){
        $this->keep_db_connected();
        $this->_pdo->beginTransaction();
        // 动态获取参数
        $arg_list = func_get_args();
        $table_name = $arg_list[0];
        $arr_save = $arg_list[1];
        $arr_condition = $arg_list[2];

        // separate column_names, column_vals and column_val_types
        $column_names = [];
        $column_vals = [];
        $column_val_types = [];

        $_prepare_sql_set_column_val = ' SET ';
        foreach($arr_save as $idx => $column_val){
            $column_names[]     = ':' . $column_val[0];
            $column_vals[]      = $column_val[1];
            $column_val_types[] = $column_val[2];
            if($idx > 0){
                $_prepare_sql_set_column_val .= ', ';
            }
            $_prepare_sql_set_column_val .= '`';
            $_prepare_sql_set_column_val .= $column_val[0];
            $_prepare_sql_set_column_val .= '`';
            $_prepare_sql_set_column_val .= '=';
            $_prepare_sql_set_column_val .= ':' . $column_val[0];
        }

        // 解析 condition
        $translate_res = $this->translateWhere($arr_condition);

        // 拼接
        $_prepare_sql = "UPDATE `{$table_name}`";
        $_prepare_sql .= $_prepare_sql_set_column_val;
        $_prepare_sql .= $translate_res->prepare_sql_condition;
        $_stmt = $this->_pdo->prepare($_prepare_sql);

        // debug
        // echo $_prepare_sql . '<br/>';

        // exit();

        // 捕捉 prepare 方法的错误并返回
        if($this->_pdo->errorCode() != '000000'){
            // print_r($this->_pdo->errorInfo());
            $errInfo = $this->_pdo->errorInfo();
            return $this->BeanStatus(-12000, $errInfo[0] . ', ' . $errInfo[1] . ', ' . $errInfo[2]);
        }

        // exit();
        
        // 合并 column_names,column_vals,column_val_types
        $column_names = array_merge($column_names, $translate_res->column_names);
        $column_vals = array_merge($column_vals, $translate_res->column_vals);
        $column_val_types = array_merge($column_val_types, $translate_res->column_val_types);

        // bindValue
        foreach($column_names as $idx => $column_name){
            $_stmt->bindValue($column_name, $column_vals[$idx], $column_val_types[$idx]);
            // debug
            // echo $column_name . ' / ' . $column_vals[$idx] . ' / ' . $column_val_types[$idx] . '<br/>';
        }

        // 测试执行
        try{
            $_stmt->execute();
            $this->_pdo->commit();
            // return success
            return $this->BeanStatus(10000, $_stmt->rowCount());
        }catch(PDOException $e){
            $this->_pdo->rollback();
            // return save fail
            return $this->BeanStatus(12001, $e->getMessage());
        }

    }



    // =========================================================================================================
    // =========================================================================================================
    // =========================================================================================================



    /**
     * 批量[INSERT]/[UPDATE]
     * @access public
     * 当为 INSERT 时，参数有 2 个
     * @param array table_name 表名
     * @param string arr_save 字段名运算符号值
     * How To use
        save_many(
            'table_name',
            [
                [
                    ['group_id', 'test', Bean::PARAM_STR],
                    ['create_cno', '\'DELETE FROM `chat_group` WHERE `id` < 2;--', Bean::PARAM_STR]
                ],
                [
                    ['group_id', 'test', Bean::PARAM_STR],
                    ['create_cno', '?????????????', Bean::PARAM_STR]
                ],
                [
                    ['group_id', 'test', Bean::PARAM_STR],
                    ['create_cno', '?????????????', Bean::PARAM_STR]
                ]
            ]
        );
     *
     * 当为 UPDATE 时，参数有 3 个
     * @access public
     * @param array  table_name 表名
     * @param string arr_save 字段名运算符号值
     * @param array arr_condition 查询条件 字段名运算符号值 列表
     * How To use
        save_many(
            'chat_group',
            [
                [ 语句1
                    ['group_id', 'test', Bean::PARAM_STR],
                    ['create_cno', '\'DELETE FROM `chat_group` WHERE `id` < 2;--', Bean::PARAM_STR]
                ],
                [ 语句2
                    ['group_id', 'test', Bean::PARAM_STR],
                    ['create_cno', '?????????????', Bean::PARAM_STR]
                ]
            ],
            [
                [ 语句1 对应的条件
                    ['id', '1', Bean::PARAM_STR, Bean::RELATION_EQUAL]
                ],
                [ 语句2 对应的条件
                    ['id', '2', Bean::PARAM_STR, Bean::RELATION_EQUAL]
                ]
            ]
        );
     * 
     */
    public function save_many(){
        // overloadFun可以随便定义，但为了命名规范，建议宝贝为与此函数名一样，
        // 后面的尾随数值为参数个数，以方便管理
        $name = "save_many".func_num_args(); 
        return call_user_func_array(array($this,$name), func_get_args());
    }



    /**
     * 批量插入
     */
    private function save_many2(){
        $this->_pdo->beginTransaction();
        // 动态获取参数
        $arg_list = func_get_args();
        $table_name = $arg_list[0];
        $arr_saves = $arg_list[1];

        foreach($arr_saves as $outter_idx => $arr_save){
            // $res[$idx] = $this->save2($table_name, $arr_save);
            // $res[$idx] = call_user_func_array(array($this,'save2'), [
            //     $table_name,
            //     $arr_save
            // ]);
            // begin to deal

            // separate column_names, column_vals and column_val_types
            $column_names = [];
            $column_vals = [];
            $column_val_types = [];

            $_prepare_sql_column_name_combine = "(";
            $_prepare_sql_column_val_combine = "(";
            foreach($arr_save as $idx => $column_name_value_arr){
                $column_name = $column_name_value_arr[0];
                $column_val = $column_name_value_arr[1];
                $column_names[] = ':' . $column_name;
                $column_vals[] = $column_val;
                $column_val_types[] = $column_name_value_arr[2];
                if($idx == 0){
                    $_prepare_sql_column_name_combine .= '`';
                    $_prepare_sql_column_val_combine .= ':' . $column_name;
                }else{
                    $_prepare_sql_column_name_combine .= ', `';
                    $_prepare_sql_column_val_combine .= ', :' . $column_name;
                }
                $_prepare_sql_column_name_combine .= $column_name;
                $_prepare_sql_column_name_combine .= '`';
            }
            $_prepare_sql_column_name_combine .= ")";
            $_prepare_sql_column_val_combine .= ")";

            // combine prepare sql and prepare it
            $_prepare_sql = "INSERT INTO `{$table_name}` ";
            $_prepare_sql .= $_prepare_sql_column_name_combine;
            $_prepare_sql .= ' VALUES ';
            $_prepare_sql .= $_prepare_sql_column_val_combine;
            $_stmt = $this->_pdo->prepare($_prepare_sql);

            // 捕捉 prepare 方法的错误并返回
            if($this->_pdo->errorCode() != '000000'){
                // print_r($this->_pdo->errorInfo());
                $errInfo = $this->_pdo->errorInfo();
                // $res[1][$outter_idx] = $this->BeanStatus(-12000, $errInfo[0] . ', ' . $errInfo[1] . ', ' . $errInfo[2]);
                print_r($this->BeanStatus(-12000, $errInfo[0] . ', ' . $errInfo[1] . ', ' . $errInfo[2]));
            }

            // echo $_prepare_sql . '<br/>';

            // bindValue
            foreach($column_names as $idx => $column_name){
                $_stmt->bindValue($column_name, $column_vals[$idx], $column_val_types[$idx]);
                // echo $column_name . ' / ' . $column_vals[$idx] . ' / ' . $column_val_types[$idx] . '<br/>';
            }
            $_stmt->execute();
        }

        // 测试执行
        try{
            // $_stmt->execute();
            $this->_pdo->commit();
            $res = $this->BeanStatus(10000);
        }catch(PDOException $e){
            $this->_pdo->rollback();
            // return save fail
            $res = $this->BeanStatus(12000, $e->getMessage());
        }
        return $res;
    }



    /**
     * 批量修改
     * save_many3
     */
    private function save_many3(){
        $this->_pdo->beginTransaction();
        // 动态获取参数
        $arg_list = func_get_args();
        $table_name = $arg_list[0];
        $arr_saves = $arg_list[1];
        $arr_conditions = $arg_list[2];

        foreach($arr_saves as $outter_idx => $arr_save){
            // separate column_names, column_vals and column_val_types
            $column_names = [];
            $column_vals = [];
            $column_val_types = [];

            $_prepare_sql_set_column_val = ' SET ';
            foreach($arr_save as $idx => $column_val){
                $column_names[]     = ':' . $column_val[0];
                $column_vals[]      = $column_val[1];
                $column_val_types[] = $column_val[2];
                if($idx > 0){
                    $_prepare_sql_set_column_val .= ', ';
                }
                $_prepare_sql_set_column_val .= '`';
                $_prepare_sql_set_column_val .= $column_val[0];
                $_prepare_sql_set_column_val .= '`';
                $_prepare_sql_set_column_val .= '=';
                $_prepare_sql_set_column_val .= ':' . $column_val[0];
            }

            // 解析 condition
            $translate_res = $this->translateWhere($arr_conditions[$outter_idx]);

            // 拼接
            $_prepare_sql = "UPDATE `{$table_name}`";
            $_prepare_sql .= $_prepare_sql_set_column_val;
            $_prepare_sql .= $translate_res->prepare_sql_condition;
            $_stmt = $this->_pdo->prepare($_prepare_sql);

            // debug
            // echo $_prepare_sql . '<br/>';

            // exit();

            // 捕捉 prepare 方法的错误并返回
            if($this->_pdo->errorCode() != '000000'){
                // print_r($this->_pdo->errorInfo());
                $errInfo = $this->_pdo->errorInfo();
                print_r($this->BeanStatus(-12000, $errInfo[0] . ', ' . $errInfo[1] . ', ' . $errInfo[2]));
            }

            // exit();
            
            // 合并 column_names,column_vals,column_val_types
            $column_names = array_merge($column_names, $translate_res->column_names);
            $column_vals = array_merge($column_vals, $translate_res->column_vals);
            $column_val_types = array_merge($column_val_types, $translate_res->column_val_types);

            // bindValue
            foreach($column_names as $idx => $column_name){
                $_stmt->bindValue($column_name, $column_vals[$idx], $column_val_types[$idx]);
                // debug
                // echo $column_name . ' / ' . $column_vals[$idx] . ' / ' . $column_val_types[$idx] . '<br/>';
            }
            $_stmt->execute();
        }

        // 测试执行
        try{
            $this->_pdo->commit();
            $res = $this->BeanStatus(10000);
        }catch(PDOException $e){
            $this->_pdo->rollback();
            // return save fail
            $res = $this->BeanStatus(12001, $e->getMessage());
        }
        return $res;
    }



    // =========================================================================================================
    // =========================================================================================================
    // =========================================================================================================



    /**
     * 查询一条
     * @param string table_name
     * @param array find_cloumns
     * @param array find_condition
     * @return Array ( [status] => 10000 [data] => Array ( [group_id] => 2 ) [info] => success )
        find_one(
            'table_name',
            ['column1', 'column2', 'column3'] / [Bean::SIGN_FINDALL],
            [
                ['cloumnA', 'valA', Bean::PARAM_STR, Bean::RELATION],
                ['cloumnB', 'valB', Bean::PARAM_STR, Bean::RELATION]
            ]
        )
     */
    public function find_one($table_name, $find_cloumns, $find_condition){
        $_prepare_sql = 'SELECT ';
        foreach($find_cloumns as $idx => $column_name){
            if($idx > 0){
                $_prepare_sql .= ', ';
            }
            $_prepare_sql .= '`' . $column_name . '`';
        }
        $_prepare_sql .= ' FROM `' . $table_name . '`';
        // 解析 condition
        $translate_res = $this->translateWhere($find_condition);
        // 拼接解析结果
        $_prepare_sql .= $translate_res->prepare_sql_condition;
        // LIMIT 1
        $_prepare_sql .= ' LIMIT 1';
        $_stmt = $this->_pdo->prepare($_prepare_sql);

        // debug
        // echo $_prepare_sql . '<br/>';
        // print_r($translate_res);
        // print_r($translate_res->column_vals);
        // exit();

        // 捕捉 prepare 方法的错误并返回
        if($this->_pdo->errorCode() != '000000'){
            // print_r($this->_pdo->errorInfo());
            $errInfo = $this->_pdo->errorInfo();
            print_r($this->BeanStatus(-12000, $errInfo[0] . ', ' . $errInfo[1] . ', ' . $errInfo[2]));
        }

        // bindValue
        foreach($translate_res->column_names as $idx => $column_name){
            $_stmt->bindValue($column_name, $translate_res->column_vals[$idx], $translate_res->column_val_types[$idx]);
            // debug
            // echo $column_name . ' / ' . $column_vals[$idx] . ' / ' . $column_val_types[$idx] . '<br/>';
        }

        $_stmt->execute();

        if($_assoc = $_stmt->fetch(PDO::FETCH_ASSOC)){
            return $this->BeanStatus(10000, $_assoc);
        }else{
            return $this->BeanStatus(10000, []);
        }
    }



    /**
     * 查询所有 / 可以分页 / 可以模糊查询
    find_all(
        'table_name',
        ['column1', 'column2', 'column3'] / [Bean::SIGN_FINDALL],
        [
            ['cloumnA', 'valA', Bean::PARAM_STR, Bean::RELATION_LESS],
            ['cloumnB', 'valB', Bean::PARAM_STR, Bean::RELATION_LIKE]
        ],
        [
            ['column_name1', Bean::ORDER_BY_DESC <=> (22)],
            ['column_name2', Bean::ORDER_BY_ASC  <=> (23)],
        ],
        [
            page(begin with 0), count_per_page
        ]
    )
     * @param string table_name         [必选参数] 表明
     * @param array find_cloumns        [必选参数] 查询的列名, 支持通配符查询, 通配符查询传入 array(Bean::SIGN_FINDALL)
     * @param array find_condition      [必选参数] 查询条件
     * @param array order_rules         [必选参数] 如果不进行排序则传入 array(-1) 即可
     * @param array page_separate_rules [必选参数] 如果不进行分页则传入 array(-1) 即可
     *                                  对于page_separate_rules,如果不传入 count_per_page,那么默认为30个数据/页
     *                                  count_per_page 务必大于0, 小于等于0的数值视为无效,将自动转为30
     * @return
     */
    public function find_all($table_name, $find_cloumns, $find_condition, $order_rules, $page_separate_rules){
        $_prepare_sql = 'SELECT ';
        foreach($find_cloumns as $idx => $column_name){
            if($idx > 0){
                $_prepare_sql .= ', ';
            }
            $_prepare_sql .= $column_name;
        }
        $_prepare_sql .= " FROM `{$table_name}`";
        // 解析 find_condition
        $translate_res = $this->translateWhere($find_condition);
        // 拼接 sql_find_condition
        $_prepare_sql .= $translate_res->prepare_sql_condition;

        // 解析 order_rule
        if($order_rules[0] != -1){
            foreach($order_rules as $idx => $order_rule){
                $temp_column_name = $order_rule[0];
                $temp_rule_sign   = $order_rule[1] == 22 ? 'DESC' : 'ASC';
                if($idx > 0){
                    $_prepare_sql .= ',';
                }
                $_prepare_sql .= ' ORDER BY `' . $temp_column_name . '` ' . $temp_rule_sign;
            }
        }

        // 解析 page_separate_rules
        if($page_separate_rules[0] != -1){
            $page           = $page_separate_rules[0];
            $count_per_page = isset($page_separate_rules[1]) ? (int)($page_separate_rules[1]) : 30;
            if($count_per_page <= 0){
                $count_per_page = 30;
            }
            $start = $page * $count_per_page;
            $_prepare_sql .= " LIMIT {$start}, {$count_per_page}";
        }

        $_stmt = $this->_pdo->prepare($_prepare_sql);

        // debug
        // echo $_prepare_sql . '<br/>';

        // exit();

        // 捕捉 prepare 方法的错误并返回
        if($this->_pdo->errorCode() != '000000'){
            // print_r($this->_pdo->errorInfo());
            $errInfo = $this->_pdo->errorInfo();
            print_r($this->BeanStatus(-12000, $errInfo[0] . ', ' . $errInfo[1] . ', ' . $errInfo[2]));
        }

        // bindValue
        foreach($translate_res->column_names as $idx => $column_name){
            $_stmt->bindValue($column_name, $translate_res->column_vals[$idx], $translate_res->column_val_types[$idx]);
            // debug
            // echo $column_name . ' / ' . $column_vals[$idx] . ' / ' . $column_val_types[$idx] . '<br/>';
        }

        // 执行查询
        $_stmt->execute();

        $data = [];

        while($_assoc = $_stmt->fetch(PDO::FETCH_ASSOC)){
            $data[] = $_assoc;
        }

        return $this->BeanStatus(10000, $data);
    }



    // =========================================================================================================
    // =========================================================================================================
    // =========================================================================================================



    /**
     * [DELETE] 通过条件 删除
     * 建议：谨慎使用删除方法，一般采用 update 数据记录状态 代替 delete 方法
     * @access public
     * @param string table_name
     * @param array del_condition
     * @return Array ( [status] => 10000 [data] => effect row count [info] => success )
     * How to use
        remove(
            'table_name',
            [
                ['username', 'test', Bean::PARAM_STR, Bean::RELATION_EQUAL],
                ['phone', '15010988888', Bean::PARAM_STR, Bean::RELATION_EQUAL],
            ]
        );
     */
    public function remove($table_name, $del_condition){
        $this->_pdo->beginTransaction();
        $_prepare_sql = "DELETE FROM `{$table_name}`" ;

        // 解析 del_condition
        $translate_res = $this->translateWhere($del_condition);
        // 拼接 sql_del_condition
        $_prepare_sql .= $translate_res->prepare_sql_condition;

        $_stmt = $this->_pdo->prepare($_prepare_sql);

        // debug
        // echo $_prepare_sql . '<br/>';

        // exit();

        // 捕捉 prepare 方法的错误并返回
        if($this->_pdo->errorCode() != '000000'){
            $errInfo = $this->_pdo->errorInfo();
            print_r($this->BeanStatus(-12000, $errInfo[0] . ', ' . $errInfo[1] . ', ' . $errInfo[2]));
        }

        // bindValue
        foreach($translate_res->column_names as $idx => $column_name){
            $_stmt->bindValue($column_name, $translate_res->column_vals[$idx], $translate_res->column_val_types[$idx]);
            // debug
            // echo $column_name . ' / ' . $translate_res->column_vals[$idx] . ' / ' . $translate_res->column_val_types[$idx] . '<br/>';
        }

        $_stmt->execute();

        // 测试执行
        try{
            $this->_pdo->commit();
            $res = $this->BeanStatus(10000, $_stmt->rowCount());
        }catch(PDOException $e){
            $this->_pdo->rollback();
            // return save fail
            $res = $this->BeanStatus(12001, $e->getMessage());
        }
        return $res;
    }



    // =========================================================================================================
    // =========================================================================================================
    // =========================================================================================================



    /**
     * 自定义SQL语句查询[SELECT]
     * 注意防sql注入
     * @param string _prepare_sql
     * @param array column_names_vals
     * @return Array ( [status] => 10000 [data] => Array ( [0] => Array ( [id] => 3 ) [1] => Array ( [id] => 4 )  [info] => success )
        How to use
        select_native(
            'SELECT * FROM `table_name` WHERE `id` > :id',
            [
                [':id', '2', Bean::PARAM_STR]
            ]
        )
     */
    public function select_native($_prepare_sql, $column_names_vals){
        $_stmt = $this->_pdo->prepare($_prepare_sql);

        // debug
        // echo $_prepare_sql . '<br/>';

        // exit();

        // 捕捉 prepare 方法的错误并返回
        if($this->_pdo->errorCode() != '000000'){
            $errInfo = $this->_pdo->errorInfo();
            print_r($this->BeanStatus(-12000, $errInfo[0] . ', ' . $errInfo[1] . ', ' . $errInfo[2]));
        }

        // bindValue
        foreach($column_names_vals as $idx => $column_name_val){
            $_stmt->bindValue($column_name_val[0], $column_name_val[1], $column_name_val[2]);
            // debug
            // echo $column_name_val[0] . ' / ' . $column_name_val[1] . ' / ' . $column_name_val[2] . '<br/>';
        }

        // 执行查询
        $_stmt->execute();

        $data = [];

        while($_assoc = $_stmt->fetch(PDO::FETCH_ASSOC)){
            $data[] = $_assoc;
        }

        return $this->BeanStatus(10000, $data);
    }



    /**
     * 自定义SQL语句更新[UPDATE]
     * 注意防sql注入
     * @param string _prepare_sql
     * @param array column_names_vals
     * @return Array ( [status] => 10000 [data] => effect row count [info] => success )
        How to use
        update_native(
            'UPDATE `table_name` SET create_cno = create_cno + 1 WHERE `id` = :id',
            [
                [':id', '2', Bean::PARAM_STR]
            ]
        )
     */
    public function update_native($_prepare_sql, $column_names_vals){
        $this->_pdo->beginTransaction();
        $_stmt = $this->_pdo->prepare($_prepare_sql);

        // debug
        // echo $_prepare_sql . '<br/>';

        // exit();

        // 捕捉 prepare 方法的错误并返回
        if($this->_pdo->errorCode() != '000000'){
            $errInfo = $this->_pdo->errorInfo();
            print_r($this->BeanStatus(-12000, $errInfo[0] . ', ' . $errInfo[1] . ', ' . $errInfo[2]));
        }

        // bindValue
        try{
            foreach($column_names_vals as $idx => $column_name_val){
                $_stmt->bindValue($column_name_val[0], $column_name_val[1], $column_name_val[2]);
                // debug
                // echo $column_name_val[0] . ' / ' . $column_name_val[1] . ' / ' . $column_name_val[2] . '<br/>';
            }
        }catch(PDOException $e){
            echo $e;
        }

        // 执行查询
        $_stmt->execute();

        // 测试执行
        try{
            $this->_pdo->commit();
            $res = $this->BeanStatus(10000, $_stmt->rowCount());
        }catch(PDOException $e){
            $this->_pdo->rollback();
            // return save fail
            $res = $this->BeanStatus(12001, $e->getMessage());
        }
        return $res;
    }





    // =========================================================================================================
    // =========================================================================================================
    // =========================================================================================================




    /**
     * 解析 WHERE 后面的条件
     * @param array arr_condition
       arr_condition[index] = ['cloumnA', 'valA', Bean::PARAM_STR, Bean::RELATION_LESS]
     * @return
     */
    private function translateWhere($arr_condition){
        $_prepare_sql_condition = ' WHERE ';
        $column_names = [];
        $column_vals = [];
        $column_val_types = [];
        foreach($arr_condition as $idx => $condition){
            // 对于 Like 模糊查询 在这里将会将数据写入绑定,因此仅like语句附近可能会发生SQL注入(虽然已经防御,但是仍不可预测未知的风险)
            if($condition[3] != Bean::RELATION_LIKE){
                $column_names[]     = ':' . $condition[0];
                $column_vals[]      = $condition[1];
                $column_val_types[] = $condition[2];
                if($idx > 0){
                    $_prepare_sql_condition .= ' AND ';
                }
                $_prepare_sql_condition .= '`';
                $_prepare_sql_condition .= $condition[0];
                $_prepare_sql_condition .= '`';
                $_prepare_sql_condition .= $condition[3];
                $_prepare_sql_condition .= ' :' . $condition[0];
            }else{
                // Like模糊查询
                if($idx > 0){
                    $_prepare_sql_condition .= ' AND ';
                }
                $_prepare_sql_condition .= '`';
                $_prepare_sql_condition .= $condition[0];
                $_prepare_sql_condition .= '`';
                $_prepare_sql_condition .= ' LIKE ';
                $_prepare_sql_condition .= '\'%';
                // -----------------------------------
                // 为了确保安全 只支持中英文、数字及下划线
                // 正则匹配中英文、数字及下划线
                $preg='/[\w\x{4e00}-\x{9fa5}]+/u';
                preg_match($preg, $condition[1], $filter);
                // -----------------------------------
                $_prepare_sql_condition .= $filter[0];
                $_prepare_sql_condition .= '%\'';
            }
        }
        // 返回对象
        return json_decode(json_encode([
            'prepare_sql_condition' => $_prepare_sql_condition,
            'column_names' => $column_names,
            'column_vals' => $column_vals,
            'column_val_types' => $column_val_types
        ]));
    }


    // the end of class
}

// End on 2019-07-27 20:29:19
