<?php
/**
	*功能：设置帐户有关信息及返回路径（基础配置页面）
	*版本：2.0
	*日期：2011-09-06
	*说明：
	*以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
	*该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
*/

define('DOMAIN', 'http://dev.hoodinn.com');

//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
	
	$partner		= "2088801545301962";			//合作身份者ID，以2088开头的16位纯数字
	$seller_email	= "dev@hoodinn.com";			//签约支付宝账号或卖家支付宝帐户
	
// 	$subject		= "献爱心捐款";			//产品名称
// 	$out_trade_no	= "123";			//请与贵网站订单系统中的唯一订单号匹配
// 	$total_fee		= "20";			//订单总金额
// 	$out_user		= "abc";			//商户系统中用户唯一标识、例如UID、NickName
	
	//以下是三个返回URL
// 	$notify_url		= DOMAIN."/venus09/donates/alipay_notify";			//服务端获取通知地址，用户交易完成异步返回地址
// 	$call_back_url	= DOMAIN."/venus09/donates/alipay_callback";			//用户交易完成同步返回地址
// 	$merchant_url	= DOMAIN."/venus09/donates/alipay_merchant";			//用户付款中途退出返回地址

	$notify_url		= DOMAIN."/callback/alipay_wap_rsa/notify_url.php";
	$call_back_url	= DOMAIN."/callback/alipay_wap_rsa/callback_url.php";
	$merchant_url	= DOMAIN."/callback/alipay_wap_rsa/merchant_url.php";
	

//↓↓↓↓↓↓↓↓↓↓以下参数为支付宝默认参数，禁止修改其参数值↓↓↓↓↓↓↓↓↓↓

	$Service_create				= "alipay.wap.trade.create.direct";
	$Service_authAndExecute		= "alipay.wap.auth.authAndExecute";
	$format						= "xml";
	$sec_id						= "0001";
	$_input_charset				= "utf-8";
	$v							= "2.0";

?>