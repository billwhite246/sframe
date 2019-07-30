<?php

/**
 * @date 2019年5月30日 16点41分
 */

class user{

    protected $bean;

    function __construct(){
        $this->bean = new Bean();
    }

    /**
     * Is Token Valid
     * @access public
     * @param string token
     * @return Default json
     */
    public function isTokenValid($param){
        $token = $param[0];
        if($this->token2userid($token)){
            Tools::printMsg(GlobalError::$SUCCESS, 'token is right');
        }else{
            Tools::printMsg(GlobalError::$InvalidToken, 'token is not right');
        }
    }

    /**
     * token转userid
     * @access protected
     * @param string token
     * @return int userid
     * if userid == 0 Then 
     *  invalid userToken
     * Else 
     *  userToken is OK
     * End if
     */
    protected function token2userid($token){
        if($token == ''){
            exit;
        }
        $res = $this->bean->find_one(
            T_TOKEN,
            ['userid'],
            [
                ['token',  $token,  Bean::PARAM_STR, Bean::RELATION_EQUAL],
                ['inuse',  '1',     Bean::PARAM_STR, Bean::RELATION_EQUAL],
            ]
        );
        // print_r($res);
        if(count($res['data']) > 0){
            return $res['data']['userid'];
        }else{
            // 没有对应的userid
            return 0;
        }
    }

}
