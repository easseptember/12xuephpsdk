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
session_start();
require_once(dirname(__FILE__)."/ErrorInfo.class.php");
require_once(dirname(__FILE__)."/Service.class.php");
require_once(dirname(__FILE__)."/Sdk.class.php");
require_once(dirname(__FILE__)."/ApiList.php");
require_once(dirname(dirname(__FILE__))."/demo/Config.php");

//初始化SDK类   此配置用户可以根据自身需求改为适用于自己的初始化方式
$SDK = new Sdk(XUE_CLIENT_ID,  XUE_CLIENT_SECRET,  XUE_REDIRECT_URL,$APILIST);

if($_SESSION["OAUTHOPENAPI"]["token"]===NULL && $_SESSION["OAUTHOPENAPI"]["open"]===NULL){
    $accessToken = $SDK->getAccessToken(array("code"=>$_GET['code']));
    $openId      = $SDK->getOpenId($accessToken);
    $_SESSION["OAUTHOPENAPI"]["token"]= $accessToken;
    $_SESSION["OAUTHOPENAPI"]["open"] = $openId;
}