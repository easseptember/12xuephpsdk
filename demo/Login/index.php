<?php
/**
 * @date 20160629
 * @package INDEX
 * @link http://open.12xue.com/
 * @author Eass September<15522280@qq.com>
 * @version 1.0
 * @copyright © 2016, 12XUE. All rights reserved.
 * @todo 演示DEMO 登录*
 */
require_once(dirname(dirname(dirname(__FILE__)))."/core/OpenApi.php");
$loginUrl = $SDK->login();
echo "<a href={$loginUrl}>点击进行12XUE登录</a>";
