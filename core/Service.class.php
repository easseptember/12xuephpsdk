<?php
/**
 * @date 20160629
 * @package INDEX
 * @link http://open.12xue.com/
 * @author Eass September<15522280@qq.com>
 * @version 1.0
 * @copyright © 2016, 12XUE. All rights reserved.
 * @todo FUNC AND TOOLS
 *
 */


require_once(dirname(__FILE__)."/ErrorInfo.class.php");
class Service{



    private $dataType = "json";   //数据格式

    private $decodeJson = TRUE;   //decode_json

    private $errorMsg;            //````错误信息

    private $boundary = "";       //boundary分界
    public function __construct($dataType = "json", $decodeJson =true  ){

        $this->dataType   = $dataType;
        $this->decodeJson = $decodeJson;
        $this->errorMsg     = new ErrorInfo(true);
    }

    /**
     * @param string $baseUrl       基础ULR地址
     * @param array $optionsArray   参数数组
     * @return string $login Url    生成URL
     * @todo URL 构造器
     */
    public function urlBuilder($baseUrl = "" , $optionsArray=array()){

        $centerTag  = strpos($baseUrl, "?")?"&":"?";
        $loginUrl   = $baseUrl.$centerTag.http_build_query($optionsArray);
        return $loginUrl;
    }

    /**
     * @param $url
     * @param array $parameters
     * @param array $header
     * @return mixed
     * @TODO HTTP GET
     */
    function get($url, $parameters = array(), $header) {

        $response = $this->oAuthRequest($url, 'GET', $parameters , $header);

        if ($this->dataType === 'json' && $this->decodeJson) {
            return json_decode($response, true);
        }
        return $response;
    }

    /**
     * @param $url
     * @param array $parameters
     * @param array $header
     * @param bool $multi
     * @return mixed
     * @TODO HTTP POST
     */
    function post($url, $parameters = array(), $header = array(), $multi = false) {
        $response = $this->oAuthRequest($url, 'POST', $parameters, $header, $multi );
        if ($this->dataType === 'json' && $this->decodeJson) {
            return json_decode($response, true);
        }
        return $response;
    }

    /**
     * @param $url
     * @param array $parameters
     * @param array $header
     * @return mixed
     * @TODO HTTP DELETE
     */
    function delete($url, $parameters = array() , $header = array()) {
        $response = $this->oAuthRequest($url, 'DELETE', $parameters , $header);
        if ($this->dataType === 'json' && $this->decodeJson) {
            return json_decode($response, true);
        }
        return $response;
    }
    //public final function put(){}
    /**
     * @param $url
     * @param $method
     * @param $parameters
     * @param bool $multi
     * @param array $header
     * @return mixed
     * @todo OAUTH 请求器
     * @date 20160701 15:10
     * @description [解释multi来源网络]
     * 根据http/1.1 rfc 2616的协议规定，我们的请求方式只有OPTIONS、GET、HEAD、POST、PUT、DELETE、TRACE等
     * 既然http协议本身的原始方法不支持multipart/form-data请求，那这个请求自然就是由这些原始的方法演变而来的，具体如何演变且看下文：
     * 1、multipart/form-data的基础方法是post，也就是说是由post方法来组合实现的
     * 2、multipart/form-data与post方法的不同之处：请求头，请求体。
     * 3、multipart/form-data的请求头必须包含一个特殊的头信息：Content-Type，且其值也必须规定为multipart/form-data
     * 同时还需要规定一个内容分割符用于分割请求体中的多个post的内容，如文件内容和文本内容自然需要分割开来，不然接收方就无法正常解析和还原这个文件了
     */
    function oAuthRequest($url, $method, $parameters, $header= array() , $multi = false) {

        if (strrpos($url, 'http://') !== 0 || strrpos($url, 'https://') !== 0) {
            $url = $this->urlBuilder($url, array("format"=>$this->dataType));
        }else{
            $this->errorMsg->errorMsg(10062);
        }

        switch ($method) {
            case 'GET':
                $url = $this->urlBuilder($url, $parameters);

                return $this->http($url, 'GET',$parameters, $header);
            default:

                if (!$multi && (is_array($parameters) || is_object($parameters)) ) {

                    $body = http_build_query($parameters);
                } else {
                    $body = self::build_http_query_multi($parameters);
                    $headers[] = "Content-Type: multipart/form-data; boundary=" . $this->boundary;
                }

                return $this->http($url, $method, $body, $header);
        }
    }

    /**
     * @param $url
     * @param $method
     * @param null $postfields
     * @param array $headers
     * @return mixed
     * @todo CURL 模拟请求器
     */

    function http($url, $method, $postfields = NULL, $headers = array()) {

        $http_info = array();
        $ci = curl_init();
        /* Curl settings */
        //(默认值，让 cURL 自己判断使用哪个版本)，CURL_HTTP_VERSION_1_0 (强制使用 HTTP/1.0)或CURL_HTTP_VERSION_1_1 (强制使用 HTTP/1.1)。
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        //add infomation 没啥卵用
        curl_setopt($ci, CURLOPT_USERAGENT, "12XUE PHP SDK--1.0");
        //在尝试连接时等待的秒数。设置为0，则无限等待。
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 40);
        //允许 cURL 函数执行的最长秒数
        curl_setopt($ci, CURLOPT_TIMEOUT,40);
        //TRUE 将curl_exec()获取的信息以字符串返回，而不是直接输出。
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
        //	HTTP请求头中"Accept-Encoding: "的值。 这使得能够解码响应的内容。 支持的编码有"identity"，"deflate"和"gzip"。如果为空字符串""，会发送所有支持的编码类型。
        curl_setopt($ci, CURLOPT_ENCODING, "");
        //FALSE 禁止 cURL 验证对等证书（peer's certificate）。要验证的交换证书可以在 CURLOPT_CAINFO 选项中设置，或在 CURLOPT_CAPATH中设置证书目录。
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false);
        if (version_compare(phpversion(), '5.4.0', '<')) {
            curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, 1);
        } else {
            curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, 2);
        }
        //设置一个回调函数，这个函数有两个参数，第一个是cURL的资源句柄，第二个是输出的 header 数据。header数据的输出必须依赖这个函数，返回已写入的数据大小。
        curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
        curl_setopt($ci, CURLOPT_HEADER, FALSE);

        switch ($method) {
            case 'POST':
                curl_setopt($ci, CURLOPT_POST, TRUE);

                if (!empty($postfields)) {
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);

                }
                break;
            case 'DELETE':
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
                if (!empty($postfields)) {
                    $url = "{$url}?{$postfields}";
                }
        }

        if ( isset($this->access_token) && $this->access_token )
            $headers[] = "Authorization: OAuth2 ".$this->access_token;


        curl_setopt($ci, CURLOPT_URL, $url );
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers );
        curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );

        $response = curl_exec($ci);
        //http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);



        curl_close ($ci);
        return $response;
    }

    /**
     * @param $ch
     * @param $header
     * @return int
     * @todo 获取头信息
     */
    function getHeader($ch, $header) {
        $i = strpos($header, ':');
        if (!empty($i)) {
            $key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
            $value = trim(substr($header, $i + 2));
            $http_header[$key] = $value;
        }
        return strlen($header);
    }
    /**
     * @param array $parameters
     * @return string
     * @todo multi 构造参数
     */
    public static function build_http_query_multi($parameters) {
        if (!$parameters) return '';

        uksort($params, 'strcmp');

        $pairs = array();

        $boundary = uniqid('------------------');//uniqid() 函数基于以微秒计的当前时间，生成一个唯一的 ID。
        $MPboundary = '--'.$boundary;
        $endMPboundary = $MPboundary. '--';
        $multipartbody = '';

        foreach ($params as $parameter => $value) {

            if( in_array($parameter, array('pic', 'image')) && $value{0} == '@' ) {
                $url = ltrim( $value, '@' );
                $content = file_get_contents( $url );
                $array = explode( '?', basename( $url ) );
                $filename = $array[0];

                $multipartbody .= $MPboundary . "\r\n";
                $multipartbody .= 'Content-Disposition: form-data; name="' . $parameter . '"; filename="' . $filename . '"'. "\r\n";
                $multipartbody .= "Content-Type: image/unknown\r\n\r\n";
                $multipartbody .= $content. "\r\n";
            } else {
                $multipartbody .= $MPboundary . "\r\n";
                $multipartbody .= 'content-disposition: form-data; name="' . $parameter . "\"\r\n\r\n";
                $multipartbody .= $value."\r\n";
            }

        }

        $multipartbody .= $endMPboundary;
        return $multipartbody;
    }


    /**
     * @todo 析构函数
     */
    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }
}