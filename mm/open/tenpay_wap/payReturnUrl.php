<?php
require_once(__DIR__.'/../../venus09/includes/setup.php');
//---------------------------------------------------------
//财付通即时到帐支付页面回调示例，商户按照此文档进行开发即可
//---------------------------------------------------------
require_once (MAIN_."lib/tenpay_wap/classes/ResponseHandler.class.php");
require_once (MAIN_."lib/tenpay_wap/classes/WapResponseHandler.class.php");

define('RETURN_URL_REDIRECT', "http://dev.hoodinn.com/venus09/web/donate/ask_wish_m");

/* 密钥 */
$key = "5503adb92e3c722cfdef73766321bcf5";

/* 创建支付应答对象 */
$resHandler = new WapResponseHandler();
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
		
		$string = "<br/>" . "支付成功" . "<br/>";
	
	} else {
		//当做不成功处理
		$string =  "<br/>" . "支付失败" . "<br/>";
	}
	$details = $_SERVER['QUERY_STRING'];
	
	$data = array('verifyresult' => $verify_result,'tradestatus' => $pay_result, 'orderno' => $sp_billno, 'outtradeno' => $transaction_id, 'tradedetails'=> $details,'totalfee'=>$total_fee);
	
	Payment::tenpay_callback($data);
} else {
	$string =  "<br/>" . "认证签名失败" . "<br/>";
}

?>
 <!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN"
 "http://www.wapforum.org/DTD/wml_1.1.xml">
    <wml>
     <head>
       <meta http-equiv="Cache-Control" content="max-age=0" forua="true"/>
       <meta http-equiv="Cache-control" content="must-revalidate" />
       <meta http-equiv="Cache-control" content="private" />
       <meta http-equiv="Cache-control" content="no-cache" />
     </head>
     <card id="wappay" title="财付通wap手机支付示例——前台结果">
     <p>
     	<?php 
     		echo $string;

     		$url = RETURN_URL_REDIRECT;
     		// 判断交易是否成功
     		if (isset($pay_result) && $pay_result == 0) {
     			echo "<br><br>";
     			echo "<div><b style='color: red; text-align: center; font-size:28px;'>购买成功，正在进入祝福文字提交页面，请稍候...</b></div>";
     			echo "<script type='text/javascript'>
     			setTimeout(function(){ location.href = '$url';},1000);
     			</script>";
     		} else {     		
     			echo "您已提交捐助，请等待入账，感谢您的爱心行动！";
			}
     	?>
     </p>
	</card>
	</wml>     