<?php
/**
* @date 20160629
* @package INDEX
* @link http://open.12xue.com/
* @author Eass September<15522280@qq.com>
* @version 1.0
* @copyright © 2016, 12XUE. All rights reserved.
* @todo 用户自定义配置文件*
*/
header('Content-Type: text/html; charset=UTF-8');
require_once(dirname(dirname(__FILE__))."/core/Sdk.class.php");
//XUE_CLIENT_ID为申请接入12xue登录时的获取的APP_ID;
define( "XUE_CLIENT_ID" , "web_stu" );
//XUE_CLIENT_SECRET为申请接入12xue登录时的获取的APP_SECRET;
define( "XUE_CLIENT_SECRET" , "28d39c6fd4a50094f72a69" );
//XUE_REDIRECT_URL为申请接入12xue登录时输入的回调地址
define( "XUE_REDIRECT_URL" , "http://12xuephpsdk.do/demo/UserInfo/back.php" );
//初始化SDK类   此配置用户可以根据自身需求改为适用于自己的初始化方式
$SDK = new Sdk(XUE_CLIENT_ID,  XUE_CLIENT_SECRET,  XUE_REDIRECT_URL);