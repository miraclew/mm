<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
/*
 * 功能：付完款后跳转的页面（页面跳转同步通知页面） 版本：2.0 日期：2011-08-06 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
 */

// /////////页面功能说明////////////
// 该页面可在本机电脑测试
// 该页面称作“页面跳转同步通知页面”，是由支付宝服务器同步调用，可当作是支付完成后的提示信息页，如“您的某某某订单，多少金额已支付成功”。
// 可放入HTML等美化页面的代码和订单交易完成后的数据库更新程序代码
// 该页面可以使用PHP开发工具调试，也可以使用写文本函数log_result进行调试，该函数已被默认关闭，见alipay_notify.php中的函数return_verify
// TRADE_FINISHED(表示交易已经成功结束);
// /////////////////////////////////

require_once ("class/alipay_notify.php");
require_once ("alipay_config.php");

// 构造通知函数信息
$alipay = new alipay_notify ( $partner, $sec_id, $_input_charset );

// 计算得出通知验证结果
$verify_result = $alipay->return_verify ();

// 验签成功
if ($verify_result) {
	
	// 获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
	$mydingdan = $_GET ['out_trade_no']; // 外部交易号
	$myresult = $_GET ['result']; // 订单状态，是否成功
	$mytrade_no = $_GET ['trade_no']; // 交易号
	                                 
	// 判断交易是否成功
	if ($_GET ['result'] == 'success') {
		//
		echo "trade_status=" . $_GET ['result'];
	} else {
		echo "trade_status=" . $_GET ['result'];
	}
} else {
	
	// 验签失败
	echo "fail";
}
?>