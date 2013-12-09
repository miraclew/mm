<?php
/*
 * 功能：支付宝主动通知调用的页面（服务器异步通知页面） 版本：2.0 日期：2011-09-06 '说明：
 * '以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * '该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
 */
// /////////页面功能说明///////////////
// 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
// 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
// 该页面调试工具请使用写文本函数log_result，该函数已被默认关闭，见alipay_notify.php中的函数notify_verify
// TRADE_FINISHED(表示交易已经成功结束);
// 该服务器异步通知页面面主要功能是：对于返回页面（return_url.php）做补单处理。如果没有收到该页面返回的 success
// 信息，支付宝会在24小时内按一定的时间策略重发通知
// ///////////////////////////////////

require_once(__DIR__.'/../../venus09/includes/setup.php');
require_once (MAIN_."lib/alipay_wap_rsa/class/alipay_notify.php");
require_once (MAIN_."lib/alipay_wap_rsa/alipay_config.php");

echo "success";
die;

// 构造通知函数信息
$alipay = new alipay_notify ( $partner, $sec_id, $_input_charset );

// 计算得出通知验证结果
$verify_result = $alipay->notify_verify ();

// 判断验签是否成功
if ($verify_result) {
	// 解密notify_data数据，并获得该xml节点的状态
	$notify_data = decrypt ( $_POST ['notify_data'] );
	$details = print_r($notify_data, true);
	$status = getDataForXML ( $notify_data, '/notify/trade_status' );
	$orderno = getDataForXML ( $notify_data, '/notify/out_trade_no' );
	$trade_no = getDataForXML ( $notify_data, '/notify/trade_no' );
	$total_fee = getDataForXML ( $notify_data, '/notify/total_fee' );
	
	// 判断交易是否完成
	if ($status == 'TRADE_FINISHED') {
		// 在判断交易完成后，必须在页面输出success
		echo "success";
		// 记录日志
		// log_result("success");
		Log::write("alipay_notify success: orderno=$orderno, trade_no=$trade_no", 'donate_notify');
	/**
	 * ********************************这里配置商户的业务逻辑************************************
	 */
	} else {
		// 交易未完成
		echo "fail";
		// 记录日志
		log_result ( "" );
		Log::write("alipay_notify fail status=$status", 'donate_notify');
	}
	
	$data = array('verifyresult' => $verify_result,'tradestatus' => $status, 'orderno' => $orderno, 'outtradeno' => $trade_no, 'tradedetails'=> $details,'totalfee'=>$total_fee);
	Payment::alipay_notify($data);
} else {
	// 验签失败，输出fail，支付宝会24小时根据策略重发总共7次
	echo "fail";
	// 记录日志
	//log_result ( "" );
	Log::write("alipay_notify fail: verify error, post=".print_r($_POST, true), 'donate_notify');
}

?>