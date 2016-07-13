<?php
/**
 * @date 20160629
 * @package core\Init
 * @link http://open.12xue.com/
 * @author Eass September<15522280@qq.com>
 * @version 1.0
 * @copyright © 2016, 12XUE. All rights reserved.
 * @todo 核心类库
 *
 */
require_once(dirname(__FILE__)."/OpenApi.php");
class Sdk{

    private     $clientId;     //客户端ID

    private     $clientSecret; //客户端秘钥

    private     $redirectUrl;  //回调地址

    private     $openId;       //OPEN ID

    private     $accessToken;  //ACCESS TOKEN

    private     $refreshToken; //Refresh Token

    private     $Service;      //服务层

    private     $apiList;      //API LIST

    /**
     * Sdk constructor.
     * @param String  $clientId
     * @param String  $clientSecret
     * @param String  $redirectUrl
     * @param null $accessToken
     * @param null $refreshToken
     * @todo 12XUE PHP SDK Construct
     */
    public function __construct($clientId, $clientSecret, $redirectUrl ,$apiList,$accessToken = null, $refreshToken = null){

        $this->clientId     = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUrl  = $redirectUrl;
        $this->apiList      = $apiList;
        $this->Service      = new Service();
        $this->errorMsg     = new ErrorInfo(true);


    }

    /**
     * @return string oAuthXueLoginURI
     * @todo 获取登录地址
     */
    function oAuthXueLoginURI()         { return "http://open.".DOMINURI."/oauth2/grant"; }
    /**
     * @return string oAuthXueAccessTokenURI
     * @todo 获取access TOKEN 的地址
     */
    function oAuthXueAccessTokenURI()   { return "http://open.".DOMINURI."/oauth2/token"; }
    /**
     * @return string oAuthXueOpenIdURI
     * @todo 获取open ID的地址
     */
    function oAuthXueOpenIdURI()        { return "http://open.".DOMINURI."/oauth2/me?access_token="; }

    /**
     * @param String $oAuthXueLoginURI 此地址为12XUE登录处理地址 一般不需要自行修改
     * @param string $response_type    授权类型 为 ”TOKEN" 和 ”code” 本系统固定为 Code
     * @param String $state              client端的状态值。用于第三方应用防止CSRF攻击，成功授权后回调时会原样带回。请务必严格按照流程检查用户与state参数状态的绑定。
     * @param String $scope            请求用户授权时向用户显示的可进行授权的列表。可填写的值是API文档中列出的接口，以及一些动作型的授权，如果要填写多个接口名称，请用逗号隔开。该配置暂未生效
     * @return  string Login Url       生成登录地址
     * @todo 构建12XUE 登录地址
      */

    public function login($oAuthXueLoginURI = "", $response_type = "code", $state = "", $scope = NULL){
        //String parameterArray[] = {};
        $parameterArray = array();
        $parameterArray["client_id"]     = $this->clientId;
        $parameterArray["redirect_uri"]  = $this->redirectUrl;
        $parameterArray["response_type"] = $response_type;
        $parameterArray["state"]         = $state;
        $baseUrl    = !empty($oAuthXueLoginURI) ? $oAuthXueLoginURI : $this->oAuthXueLoginURI();
        $loginUrl   = $this->Service->urlBuilder($baseUrl,$parameterArray);
        return  $loginUrl;
        
    }

    /**
     * @param array $values
     * @param string $type
     * @return mixed
     * @todo 获取ACCESS TOKEN
     */
    public function getAccessToken($values = array(),$type = "code"){

        $parameterArray = array();
        switch ( $type ){
            case   "code" :
                $parameterArray["grant_type"]   = "authorization_code";
                $parameterArray["redirect_uri"] = $this->redirectUrl;
                $parameterArray["client_id"]    = $this->clientId;
                $parameterArray["client_secret"]= $this->clientSecret;
                $parameterArray["code"]         = $values["code"];

                break;
            case   "token":
                $parameterArray["grant_type"]    = "refresh_token";
                $parameterArray["refresh_token"] = $values["refresh_token"];
                break;
            case   "password":
                $parameterArray["grant_type"] = "password";
                $parameterArray["username"]   = $values["username"];
                $parameterArray["password"]   = $values["password"];
                break;
            default :
                $this->errorMsg->errorMsg(10061);

        }

        $accessToken = $this->Service->post($this->oAuthXueAccessTokenURI(),$parameterArray);

        return $accessToken["access_token"];
    }

    /**
     * @param String $accessToken
     * @return mixed
     * @todo 获取OPEN ID
     */
    public function getOpenId($accessToken){

        $getOpenIdUrl = $this->oAuthXueOpenIdURI().$accessToken;
        $content      = file_get_contents($getOpenIdUrl);
        $content      = str_replace('callback(','',$content);
        $content      = str_replace(');','',$content);
        $returns      = json_decode($content);
        $openId       = $returns->openid;
        return $openId;
    }

    /**
     * @return bool
     * @todo 检测是否已经登录 本SDK准则
     */
    public function isLogin(){
        if($_SESSION["OAUTHOPENAPI"]["token"]===NULL && $_SESSION["OAUTHOPENAPI"]["open"]===NULL){
            return false;
        }else{
            return true;
        }
    }


    /**
     * @return string
     * @todo  登出方法
     */
    public function logOut(){
        $_SESSION['OAUTHOPENAPI']['token']  =   NULL;
        $_SESSION['OAUTHOPENAPI']['open']   =   NULL;
        return  "../Login/index.php";
    }

    /**
     * @param string $apiName  API名称
     * @param array $params    参数
     * @return array $info     返回获取信息
     * @todo  构建API 方法
     */
    public function api($apiName = "" , $params = array() ){
        $apiList  = $this->apiList;

        if($_SESSION["OAUTHOPENAPI"]["token"]===NULL && $_SESSION["OAUTHOPENAPI"]["open"]===NULL){
            $this->errorMsg->errorMsg(10063); return;
        }
        $header = array(

            "Token	      : {$_SESSION['OAUTHOPENAPI']['token']}",

            "OpenID	      : {$_SESSION['OAUTHOPENAPI']['open']}",

            "Content-Type : application/x-www-form-urlencoded",

        );
        if($apiName == "" || !is_array($apiList[$apiName])){
            $this->errorMsg->errorMsg(10064);return;
        }
        $method = strtolower ($apiList[$apiName]["method"]);

        $info   = $this->Service->$method($apiList[$apiName]["api"], $params, $header);

        return $info;
    }

    public function __destruct(){/* TODO: Implement __destruct() method.*/}
}