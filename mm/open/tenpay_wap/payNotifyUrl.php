<?php
require_once(__DIR__.'/../../venus09/includes/setup.php');
//---------------------------------------------------------
//财付通即时到帐支付后台回调示例，商户按照此文档进行开发即可
//---------------------------------------------------------

require_once (MAIN_."lib/tenpay_wap/classes/ResponseHandler.class.php");
require_once (MAIN_."lib/tenpay_wap/classes/WapNotifyResponseHandler.class.php");

/* 商户号 */
$partner = "1215370601";

/* 密钥 */
$key = "5503adb92e3c722cfdef73766321bcf5";


/* 创建支付应答对象 */
$resHandler = new WapNotifyResponseHandler();
$resHandler->setKey($key);

$verify_result = $resHandler->isTenpaySign();
//判断签名
if($verify_result) {
	
	//商户订单号
	$sp_billno = $resHandler->getParameter("sp_billno");
	$bargainor_id = $resHandler->getParameter("bargainor_id");
	
	//财付通交易单号
	$transaction_id = $resHandler->getParameter("transaction_id");
	//金额,以分为单位
	$total_fee = $resHandler->getParameter("total_fee");
	
	//支付结果
	$pay_result = $resHandler->getParameter("pay_result");

	if( "0" == $pay_result  ) {
		echo 'success';
	}
	else
	{
		echo 'fail';
	} 
	
	$details = $_SERVER['QUERY_STRING'];
	
	$data = array('verifyresult' => $verify_result,'tradestatus' => $pay_result, 'orderno' => $sp_billno, 'outtradeno' => $transaction_id, 'tradedetails'=> $details,'totalfee'=>$total_fee);
	
	Payment::tenpay_callback($data);
} else {
	//回调签名错误
	echo "fail";
}


?>