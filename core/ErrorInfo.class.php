<?php
/**
 * @date 20160629
 * @package INDEX
 * @link http://open.12xue.com/
 * @author Eass September<15522280@qq.com>
 * @version 1.0
 * @copyright © 2016, 12XUE. All rights reserved.
 * @todo 异常错误信息处理类
 *
 */

class ErrorInfo{

    private $errorMessageOut ; //是否开启错误展示



    public function __construct($errorMessageOut){

        $this->errorMessageOut = $errorMessageOut;

    }
    public function errorMsg($errorStateValue,$Extramessage = ""){
        $config = $this->config();
        $errorMsgString = "<span>【我们很遗憾的通知您发生了以下错误信息】<span><br>";
        $errorMsgString.= "<span>【CODE:{$errorStateValue}】<span><br>";
        $errorMsgString.= "<span>【Info:<font style='color: red'> {$config[$errorStateValue]}</font>】<span><br>";
        if($Extramessage != ""){
            $errorMsgString.= "<span>【Extramessage:<font style='color: red'> {$Extramessage}</font>】<span><br>";
        }
        $show = true === $this->errorMessageOut?$errorMsgString:"您关闭了错误信息提示!";
        echo $show;
    }
    public function config(){
        $errorConfig  =  array(
            "10058"   =>  "配置文件损坏或无法读取" ,
            "10059"   =>  "您的PHP版本过低，请升级PHP" ,
            "10060"   =>  "请您填写完整配置文件" ,
            "10061"   =>  "请求类型错误" ,
            "10062"   =>  "地址格式错误，以Http或https开头" ,
            "10063"   =>  "请重新获取access token 和 openid的值" ,
            "10064"   =>  "无效的接口名称或接口名称为空" ,
            "10065"   =>  "code 获取异常" ,
            "10066"   =>  "access token 获取异常" ,
            "10067"   =>  "openid 获取异常" ,
        );
        return $errorConfig;
     }

    /**
     * @todo 析构函数
     */
    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }
}