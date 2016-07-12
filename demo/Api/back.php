<?php
/**
 * @date 20160629
 * @package INDEX
 * @link http://open.12xue.com/
 * @author Eass September<15522280@qq.com>
 * @version 1.0
 * @copyright © 2016, 12XUE. All rights reserved.
 * @todo 演示Userinfo 回调节面*
 */
require_once(dirname(dirname(dirname(__FILE__)))."/core/OpenApi.php");
/**
 * @description  本示例可用isLogin（）方法 检测是否已经登录
 * 在已经登录的情况下   可以调用 api的任何方法
 * $SDK -> api($apiName , $params)  调用规则  传入方法名和参数即可调用任何API /post/get/delete/put
 * API文档地址 ：
 */
if($SDK->isLogin()){


    $info = $SDK->api("get_user_me");

    echo "<img src={$info['face']}>你好 ,".$info['realname'];
    $params2 =  array('uid'=>54551212,'identitytype'=>'4');
    $params1 = array('account'=>'x12986781','password'=>'111111','client_id'=>'web_stu','client_secret'=>'28d39c6fd4a50094f72a693eaf53bfc5');
    $params = array('uid'=>1034145308280422923,'sex'=>'0');
    $api = $SDK->api("put_user_info",$params);
    var_dump($api);
}else{
    header( "Location:../Login/index.php");
}

