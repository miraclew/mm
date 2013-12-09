<?php

/**
 *类名：alipay_service
 *功能：支付宝Wap服务接口控制
 *详细：该页面是请求参数核心处理文件
 *版本：2.0
 *修改日期：2011-09-06
 '说明：
 '以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 '该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
*/

require_once ("alipay_function.php");

class alipay_service {
	//线上
	var $gateway_order = "http://wappaygw.alipay.com/service/rest.htm?";
	
	var $mysign;        //签名结果
	var $parameter;		//需要签名的参数数组
	var $format;		//字符编码格式
	var $req_data='';	//post请求数据

	/**构造函数
	 */
	function alipay_service() {
	}

	/**
	 * 创建alipay.wap.trade.create.direct接口
	 */
	function alipay_wap_trade_create_direct($parameter) {

		//除去数组中的空值和签名参数
		$this->parameter = para_filter($parameter); 
		
		//参数数组
		$this->req_data = urlencode($parameter['req_data']);
		
		//编码格式，此处为utf-8
		$this->format = $this->parameter['format']; 
		
		//得到从字母a到z排序后的签名参数数组
		$sort_array = arg_sort($this->parameter);

		//生成签名
		$this->mysign = build_mysign($sort_array);

		//配置post请求数据，注意sign签名需要urlencode
		$this->req_data = create_linkstring($this->parameter) . '&sign=' . urlencode($this->mysign);
		
		//Post提交请求
		$result	= $this->post($this->gateway_order);

		//调用GetToken方法，并返回token
		return $this->getToken($result);
	}

	/**
	 * 调用alipay_Wap_Auth_AuthAndExecute接口
	 */
	function alipay_Wap_Auth_AuthAndExecute($parameter) {
		
		//参数数组
		$this->parameter = para_filter($parameter);
		
		//排好序的参数数组
		$sort_array	= arg_sort($this->parameter);
		
		//生成签名
		$this->mysign = build_mysign($sort_array);
		
		//生成跳转链接
		$RedirectUrl = $this->gateway_order . create_linkstring($this->parameter) . '&sign=' . urlencode($this->mysign);
	
		Log::write("alipay_wap_rsa: url= $RedirectUrl",'alipay_debug');
		//跳转至该地址
		Header("Location: $RedirectUrl");
	}

	/**
	 * 返回token参数
	 * 参数 result 需要先urldecode
	 */
	function getToken($result)
	{
		//URL转码
		$result	= urldecode($result);				
		
		//根据 & 符号拆分
		$Arr = explode('&', $result);				
		
		//临时存放拆分的数组
		$temp = array();

		//待签名的数组
		$myArray = array();

		//循环构造key、value数组
		for ($i = 0; $i < count($Arr); $i++) {
			$temp = explode( '=' , $Arr[$i] , 2 );
			$myArray[$temp[0]] = $temp[1];
		}
		
		//需要先解密res_data
		$myArray['res_data'] = decrypt($myArray['res_data']);
		
		//获取返回的RSA签名
		$sign = $myArray['sign'];

		//去sign，去空值参数
		$myArray = para_filter($myArray);	

		//排序数组
		$sort_array = arg_sort($myArray);	

		//拼凑参数链接 & 连接
		$prestr = create_linkstring($sort_array);	
		
		//返回布尔值，是否验签通过
		$isverify = verify($prestr, $sign);					

		//判断签名是否正确
		if($isverify)
		{
			//返回token
			return getDataForXML($myArray['res_data'],'/direct_trade_create_res/request_token');	
		}
		else
		{
			//当判断出签名不正确，请不要验签通过
			return '签名不正确';
		}
	}

	/**
	 * PHP Crul库 模拟Post提交至支付宝网关
	 * 如果使用Crul 你需要改一改你的php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
	 * 返回 $data
	 */
	function post($gateway) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $gateway);				//配置网关地址
		curl_setopt($ch, CURLOPT_HEADER, 0);						//过滤HTTP头
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);							//设置post提交
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->req_data);		//post传输数据
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
}

?>