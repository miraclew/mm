<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<body>
<?php
	require_once(__DIR__.'/../../venus09/includes/setup.php');
	
	$details = $_SERVER['QUERY_STRING'];
	Log::write("alipay_callback", 'donate');
	Log::write("callback querystring: $details", 'donate');
	require_once (MAIN_."lib/alipay_wap_rsa/class/alipay_notify.php");
	require_once (MAIN_."lib/alipay_wap_rsa/alipay_config.php");
	
	// 构造通知函数信息
	$alipay = new alipay_notify ( $partner, $sec_id, $_input_charset );
	// 计算得出通知验证结果
	$verify_result = $alipay->return_verify ();
	
	$mydingdan = isset($_GET ['out_trade_no'])?$_GET ['out_trade_no']:''; // 外部交易号
	$myresult = isset($_GET ['result'])?$_GET ['result']:''; // 订单状态，是否成功
	$mytrade_no = isset($_GET ['trade_no'])?$_GET ['trade_no']:''; // 交易号
	$total_fee = isset($_GET['total_fee']) ? $_GET['total_fee'] : '';
	
	// 验签成功
	if ($verify_result) {
		// 获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
		
		$url = 'http://dev.hoodinn.com/venus09/web/donate/ask_wish_m';
		// 判断交易是否成功
		if ($_GET ['result'] == 'success') {
			echo "<b style='color: red; text-align: center;'>您已成功进行捐助，感谢您的爱心行动！</b>";
			echo "<script type='text/javascript'>
					setTimeout(function(){ location.href = '$url';},2000);
				  </script>";
		} else {
// 			echo "trade_status=" . $_GET ['result'];
			echo "您已提交捐助，请等待入账，感谢您的爱心行动！";
		}
		
	} else {
		// 验签失败
		//echo "fail";
		echo "交易出现一点小问题， 验证失败了。";		
		echo "<script type='text/javascript'>
				setTimeout(function(){ location.href = 'venus://donate/result=2';},2000);
			</script>";		
	}
	
	$data = array('verifyresult' => $verify_result,'tradestatus' => $myresult, 'orderno' => $mydingdan, 'outtradeno' => $mytrade_no, 'tradedetails'=> $details,'totalfee'=>$total_fee);
	
	Payment::alipay_callback($data);
?>

</body>
</html>