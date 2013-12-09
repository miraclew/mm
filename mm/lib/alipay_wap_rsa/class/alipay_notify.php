<?php
/*
 *类名：alipay_notify
 *功能：付款过程中服务器通知类
 *详细：该页面是通知返回核心处理文件
 *版本：2.0
 *修改日期：2011-09-06
 '说明：
 '以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 '该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
*/

////////////////////注意/////////////////////////
//调试通知返回时，可查看或改写log日志的写入TXT里的数据，来检查通知返回是否正常
/////////////////////////////////////////////////

require_once("alipay_function.php");

class alipay_notify {
    var $gateway;           //网关地址
    var $partner;           //合作伙伴ID
    var $sign_type;         //签名方式 系统默认
    var $mysign;            //签名结果
    var $_input_charset;    //字符编码格式

    /**构造函数
	 * 从配置文件中初始化变量
	 * $partner 合作身份者ID
	 * $key 安全校验码
	 * $sign_type 签名类型
	 * $_input_charset 字符编码格式
     */
    function alipay_notify($partner,$sign_type,$_input_charset) {

		//默认网关
		$this->gateway = "http://wappaygw.alipay.com/service/rest.htm?";
		
		//合作伙伴ID
        $this->partner          = $partner;

		//声明本地生成的签名
        $this->mysign           = "";

		//签名类型
        $this->sign_type	    = $sign_type;

		//字符编码格式
        $this->_input_charset   = $_input_charset;
    }

    /********************************************************************************/

    /**对notify_url的认证
	 *返回的验证结果：true/false
     */
    function notify_verify() {
		//判断POST来的数组是否为空
		if(empty($_POST)) {
			return false;
		}
		else {
			//此处为固定顺序，支付宝Notify返回消息通知比较特殊，这里不需要升序排列
			$notifyarray = array(
				"service"		=> $_POST['service'],
				"v"				=> $_POST['v'],
				"sec_id"		=> $_POST['sec_id'],
				"notify_data"	=> $_POST['notify_data']
			);
			
			//解密notify_data
			$notifyarray['notify_data'] = decrypt($_POST['notify_data']);

			//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
			$prestr = create_linkstring($notifyarray);	
			
			//获取返回sign签名
			$sign=$_POST['sign'];

			//返回验签bool值
			return verify($prestr, $sign);
		}
		
    }

    /********************************************************************************/

    /**对return_url的认证
	 * return 验证结果：true/false
     */
    function return_verify() {
        //判断GET来的数组是否为空
		if(empty($_GET)) {
			return false;
		}
		else {

			//对所有GET反馈回来的数据去空
			$get = para_filter($_GET);
			
			//对所有GET反馈回来的数据排序
			$sort_get = arg_sort($get);

			//获取返回的sign
			$sign = $_GET["sign"];

			//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
			$prestr = create_linkstring($sort_get);

			//返回验签bool值
			return verify($prestr, $sign);
		}
    }
}
?>
