<?php
/**
 *功能：支付宝接口公用函数
 *详细：该页面是请求、通知返回两个文件所调用的公用函数核心处理文件，不需要修改
 *版本：2.0
 *修改日期：2011-09-06
 '说明：
 '以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 '该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
 */


/**----------------------------RSA加密--------------------------------------**/
 
/**生成签名结果
 * $array要签名的数组
 * return 签名结果字符串
 */
function build_mysign($sort_array) {

	//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
    $prestr = create_linkstring($sort_array);
    
	//调用RSA签名方法
	$mysgin = sign($prestr);

    return $mysgin;
}

/********************************************************************************/

/**RSA签名
 * $data签名数据(需要先排序，然后拼接)
 * 签名用商户私钥，必须是没有经过pkcs8转换的私钥
 * 最后的签名，需要用base64编码
 * return Sign签名
 */
function sign($data) {
    //读取私钥文件
	$priKey = file_get_contents(__DIR__.'/../key/rsa_private_key.pem');

	//转换为openssl密钥，必须是没有经过pkcs8转换的私钥
    $res = openssl_get_privatekey($priKey);

	//调用openssl内置签名方法，生成签名$sign
    openssl_sign($data, $sign, $res);

	//释放资源
    openssl_free_key($res);
    
	//base64编码
	$sign = base64_encode($sign);
    return $sign;
}

/********************************************************************************/

/**RSA验签
 * $data待签名数据(需要先排序，然后拼接)
 * $sign需要验签的签名,需要base64_decode解码
 * 验签用支付宝公钥
 * return 验签是否通过 bool值
 */
function verify($data, $sign)  {
	//读取支付宝公钥文件
	$pubKey = file_get_contents(__DIR__.'/../key/alipay_public_key.pem');

	//转换为openssl格式密钥
    $res = openssl_get_publickey($pubKey);

	//调用openssl内置方法验签，返回bool值
    $result = (bool)openssl_verify($data, base64_decode($sign), $res);
	
	//释放资源
    openssl_free_key($res);

	//返回资源是否成功
    return $result;
}

/********************************************************************************/

/**解密
 * $content为需要解密的内容
 * 解密用商户私钥
 * 解密前，需要用base64将内容还原成二进制
 * 将需要解密的内容，按128位拆开解密
 * return 解密后内容，明文
 */
function decrypt($content) {

	//读取商户私钥
    $priKey = file_get_contents(__DIR__.'/../key/rsa_private_key.pem');
    
	//转换为openssl密钥，必须是没有经过pkcs8转换的私钥
	$res = openssl_get_privatekey($priKey);

	//密文经过base64解码
    $content = base64_decode($content);

	//声明明文字符串变量
    $result  = '';

	//循环按照128位解密
    for($i = 0; $i < strlen($content)/128; $i++  ) {
        $data = substr($content, $i * 128, 128);
		
		//拆分开长度为128的字符串片段通过私钥进行解密，返回$decrypt解析后的明文
        openssl_private_decrypt($data, $decrypt, $res);

		//明文片段拼接
        $result .= $decrypt;
    }

	//释放资源
    openssl_free_key($res);

	//返回明文
    return $result;
}

/********************************************************************************/

/**把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
 * $array 需要拼接的数组
 * return 拼接完成以后的字符串
 */
function create_linkstring($array) {
    $arg  = "";
    while (list ($key, $val) = each ($array)) {
        $arg.=$key."=".$val."&";
    }
	//去掉最后一个&字符
    $arg = substr($arg,0,count($arg)-2);
    return $arg;
}

/********************************************************************************/

/**除去数组中的空值和签名参数
 * $parameter 签名参数组
 * return 去掉空值与签名参数后的新签名参数组
 */
function para_filter($parameter) {
    $para = array();
    while (list ($key, $val) = each ($parameter)) {
        if($key == "sign" || $key == "sign_type" || $val == "") continue;
        else	$para[$key] = $parameter[$key];
    }
    return $para;
}

/********************************************************************************/

/**对数组排序
 * $array 排序前的数组
 * return 排序后的数组
 */
function arg_sort($array) {
    ksort($array);
    reset($array);
    return $array;
}

/********************************************************************************/

/**日志消息,把支付宝返回的参数记录下来
 * 请注意服务器是否开通fopen配置
 */
function  log_result($word) {
    $fp = fopen("log.txt","a");
    flock($fp, LOCK_EX) ;
    fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
    flock($fp, LOCK_UN);
    fclose($fp);
}	

/********************************************************************************/

/**实现多种字符编码方式
 * $input 需要编码的字符串
 * $_output_charset 输出的编码格式
 * $_input_charset 输入的编码格式
 * return 编码后的字符串
 */
function charset_encode($input,$_output_charset ,$_input_charset) {
    $output = "";
    if(!isset($_output_charset) )$_output_charset  = $_input_charset;
    if($_input_charset == $_output_charset || $input ==null ) {
        $output = $input;
    } elseif (function_exists("mb_convert_encoding")) {
        $output = mb_convert_encoding($input,$_output_charset,$_input_charset);
    } elseif(function_exists("iconv")) {
        $output = iconv($_input_charset,$_output_charset,$input);
    } else die("sorry, you have no libs support for charset change.");
    return $output;
}

/********************************************************************************/

/**实现多种字符解码方式
 * $input 需要解码的字符串
 * $_output_charset 输出的解码格式
 * $_input_charset 输入的解码格式
 * return 解码后的字符串
 */
function charset_decode($input,$_input_charset ,$_output_charset) {
    $output = "";
    if(!isset($_input_charset) )$_input_charset  = $_input_charset ;
    if($_input_charset == $_output_charset || $input ==null ) {
        $output = $input;
    } elseif (function_exists("mb_convert_encoding")) {
        $output = mb_convert_encoding($input,$_output_charset,$_input_charset);
    } elseif(function_exists("iconv")) {
        $output = iconv($_input_charset,$_output_charset,$input);
    } else die("sorry, you have no libs support for charset changes.");
    return $output;
}

/********************************************************************************/

/**通过节点路径返回字符串的某个节点值
 * $res_data——XML 格式字符串
 * 返回节点参数
 */
function getDataForXML($res_data,$node)
{
	$xml = simplexml_load_string($res_data);
	$result = $xml->xpath($node);

	while(list( , $node) = each($result)) 
	{
		return $node;
	}
}

?>