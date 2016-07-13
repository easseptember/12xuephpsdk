<?php
/**
 * @date 20160711
 * @package core\Init
 * @link http://open.12xue.com/
 * @author Eass September<15522280@qq.com>
 * @version 1.0
 * @copyright © 2016, 12XUE. All rights reserved.
 * @todo 入口初始化文件
 *
 *
 */
define("DOMINURI","12xuedev.com");
error_reporting(E_ALL^E_NOTICE);
session_start();
require_once(dirname(__FILE__)."/ErrorInfo.class.php");
require_once(dirname(__FILE__)."/Service.class.php");
require_once(dirname(__FILE__)."/Sdk.class.php");
require_once(dirname(__FILE__)."/ApiList.php");
require_once(dirname(dirname(__FILE__))."/demo/Config.php");
$errorMsg = new ErrorInfo(true);
//初始化SDK类   此配置项为config配置  可根据情况自定义获取
$SDK = new Sdk(XUE_CLIENT_ID,  XUE_CLIENT_SECRET,  XUE_REDIRECT_URL, $APILIST);
$code = !empty($_GET['code'])?$_GET['code']:"";
if($_SESSION["OAUTHOPENAPI"]["token"]===NULL && $_SESSION["OAUTHOPENAPI"]["open"]===NULL && !empty($code)){

    if(empty($_GET['code'])){
        $errorMsg->errorMsg(10065);return;
    }

    $accessToken = $SDK->getAccessToken(array("code"=>$_GET['code']));
    if(!$accessToken){
        $errorMsg->errorMsg(10066);return;
    }
    $openId      = $SDK->getOpenId($accessToken);
    if(!$openId){
        $errorMsg->errorMsg(10067);return;
    }
    $_SESSION["OAUTHOPENAPI"]["token"]= $accessToken;
    $_SESSION["OAUTHOPENAPI"]["open"] = $openId;
}