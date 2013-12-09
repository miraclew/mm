<META http-equiv=Content-Type content="text/html; charset=utf-8">
<?php
require_once(__DIR__.'/../../venus09/includes/setup.php');
//---------------------------------------------------------
//财付通即时到帐支付页面回调示例，商户按照此文档进行开发即可
//---------------------------------------------------------
require_once (MAIN_."lib/tenpay_web/classes/ResponseHandler.class.php");
require_once (MAIN_."lib/tenpay_web/classes/function.php");
require_once ("./tenpay_config.php");

define('RETURN_URL_REDIRECT', "http://dev.hoodinn.com/venus09/web/donate/ask_wish");

//log_result("进入前台回调页面");


/* 创建支付应答对象 */
$resHandler = new ResponseHandler();
$resHandler->setKey($key);

//判断签名
if($resHandler->isTenpaySign()) {
	
	//通知id
	$notify_id = $resHandler->getParameter("notify_id");
	//商户订单号
	$out_trade_no = $resHandler->getParameter("out_trade_no");
	//财付通订单号
	$transaction_id = $resHandler->getParameter("transaction_id");
	//金额,以分为单位
	$total_fee = $resHandler->getParameter("total_fee");
	//如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
	$discount = $resHandler->getParameter("discount");
	//支付结果
	$trade_state = $resHandler->getParameter("trade_state");
	//交易模式,1即时到账
	$trade_mode = $resHandler->getParameter("trade_mode");
	
	
	if("1" == $trade_mode ) {
		if( "0" == $trade_state){ 
		
			
			echo "<br/>" . "即时到帐支付成功" . "<br/>";
	
		} else {
			//当做不成功处理
			echo "<br/>" . "即时到帐支付失败" . "<br/>";
		}
		
		$url = RETURN_URL_REDIRECT;
		// 判断交易是否成功
		if (isset($trade_state) && $trade_state == 0) {
			echo "<br><br>";
			echo "<div><b style='color: red; text-align: center; font-size:28px;'>购买成功，正在进入祝福文字提交页面，请稍候...</b></div>";
			echo "<script type='text/javascript'>
			setTimeout(function(){ location.href = '$url';},1000);
			</script>";
		} else {
			echo "您已提交捐助，请等待入账，感谢您的爱心行动！";
		}
	}elseif( "2" == $trade_mode  ) {
		if( "0" == $trade_state) {
		
		
			
			echo "<br/>" . "中介担保支付成功" . "<br/>";
		
		} else {
			//当做不成功处理
			echo "<br/>" . "中介担保支付失败" . "<br/>";
		}
	}
	
} else {
	echo "<br/>" . "认证签名失败" . "<br/>";
	echo $resHandler->getDebugInfo() . "<br>";
}

?>