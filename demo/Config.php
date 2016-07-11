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
//XUE_CLIENT_ID为申请接入12xue登录时的获取的APP_ID;
define( "XUE_CLIENT_ID" , "web_stu" );
//XUE_CLIENT_SECRET为申请接入12xue登录时的获取的APP_SECRET;
define( "XUE_CLIENT_SECRET" , "28d39c6fd4a50094f72a693eaf53bfc5" );
//XUE_REDIRECT_URL为申请接入12xue登录时输入的回调地址
define( "XUE_REDIRECT_URL" , "http://12xuephpsdk.do/demo/Api/back.php" );
