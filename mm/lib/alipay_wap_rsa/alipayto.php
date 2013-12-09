<?php

/**
 *类名：alipay_to.php
 *功能：支付宝wap接口处理页面
 *详细：该页面是调用底层接口并返回处理结果页面
 *版本：2.0
 *修改日期：2011-09-06
 '说明：
 '以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 '该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
*/

require_once ("alipay_config.php");
require_once ("class/alipay_service.php");

/**
 * ****************************alipay_wap_trade_create_direct*************************************
 */

// 构造要请求的参数数组，无需改动
$pms1 = array (
		"req_data" => '<direct_trade_create_req><subject>' . $subject . '</subject><out_trade_no>' . $out_trade_no . '</out_trade_no><total_fee>' . $total_fee . "</total_fee><seller_account_name>" . $seller_email . "</seller_account_name><notify_url>" . $notify_url . "</notify_url><out_user>" . $_GET ["out_user"] . "</out_user><merchant_url>" . $merchant_url . "</merchant_url>" . "<call_back_url>" . $call_back_url . "</call_back_url></direct_trade_create_req>",
		"service" => $Service_create,
		"sec_id" => $sec_id,
		"partner" => $partner,
		"req_id" => date ( "Ymdhms" ),
		"format" => $format,
		"v" => $v 
);

// 构造请求函数
$alipay = new alipay_service ();

// 调用alipay_wap_trade_create_direct接口，并返回token返回参数
$token = $alipay->alipay_wap_trade_create_direct ( $pms1 );

/**
 * ************************************************************************************************
 */

/**
 * *******************************alipay_Wap_Auth_AuthAndExecute***********************************
 */

// 构造要请求的参数数组，无需改动
$pms2 = array (
		"req_data" => "<auth_and_execute_req><request_token>" . $token . "</request_token></auth_and_execute_req>",
		"service" => $Service_authAndExecute,
		"sec_id" => $sec_id,
		"partner" => $partner,
		"call_back_url" => $call_back_url,
		"format" => $format,
		"v" => $v 
);

// 调用alipay_Wap_Auth_AuthAndExecute接口方法，并重定向页面
$alipay->alipay_Wap_Auth_AuthAndExecute ( $pms2 );

/**
 * ************************************************************************************************
 */
?>
