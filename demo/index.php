<?php
/**
* @date 20160629
* @package INDEX
* @link http://open.12xue.com/
* @author Eass September<15522280@qq.com>
* @version 1.0
* @copyright © 2016, 12XUE. All rights reserved.
* @todo 演示DEMO入口文件*
*/
require_once(dirname(dirname(__FILE__))."/core/OpenApi.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>12XUE PHP SDK</title>
    <style type="text/css">
        *{margin: 0; padding: 0; font-size:14px; font-family: "微软雅黑"; line-height:25px;}
        font{font-size:20px; font-weight: ;}
        .main{width: 1200px; height: auto;  background: #f9f7f7; margin: 0 auto;}
        .h1_title{color:#2c8bec; width: 1000px;text-align: center; font-size:25px;}
        .clear-10{clear:both; height: 10px;}
        p{padding: 10px;}
    </style>
</head>

<body>
<div class="main">
    <div class="clear-10"></div>
    <h1 class="h1_title" style="color:#2c8bec;">12XUE PHP SDK DEMO演示文件入口----Powered By 12Xue.com</h1><br>
    <p><font color="red">♠ </font>12XUE-PHP-SDK旨在为开发者快速接入12XUE网站而服务，本SDK由12XUE OPEN模块团队负责维护和更新，FAQ : <a target="_blank" href="http://open.12xue.com/faq/index.html">http://open.12xue.com/faq/index.html</a></p>
    <p><font color="red">♠ </font>本文件为SDK 演示入口文件，可用于初始化配置文件，开发者可以肯据情况只保留核心文件core即可。</a></p>
    <br>

    <p><font color="red">♠ </font>（1）.用户登录演示【<a  href="javascript:demoIndexUrl('./Login/index.php')"><font color="red">入口</font></a>】</p>
    <p><font color="red">♠ </font>（2）.获取用户信息【<a  href="javascript:demoIndexUrl('./UserInfo/index.php')"><font color="red">入口</font></a>】</p>
    <div id="showMessage"></div>
</div>

</body>
</html>
<script type="text/javascript">
    var $ = function(tags){return document.getElementById(tags);}
    function demoIndexUrl(JUMPURL){

        <?php if(XUE_CLIENT_ID === "" || XUE_CLIENT_SECRET === "" || XUE_REDIRECT_URL === ""  ){ ?>
        $("showMessage").innerHTML = "<?php $errorMsg->errorMsg(10060);  ?>";
        setTimeout("$('showMessage').innerHTML =''",4000);
        <?php }else{ ?>
        window.location.href=  JUMPURL;
        <?php } ?>

    }
</script>