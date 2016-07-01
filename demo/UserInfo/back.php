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
require_once(dirname(dirname(__FILE__))."/Config.php");
$accessToken =$SDK->getAccessToken(array("code"=>$_GET['code']));
var_dump($SDK->getOpenId($accessToken));
