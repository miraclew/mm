<?php
class Payment {
	
	/**
	 * 参数
	 * array('biztype','verifyresult','tradestatus', 'outtradeno','orderno','totalfee','tradedetails','tradestatus')
	 */
	public static function alipay_callback($data) {
		self::handle_alipay($data, false);		
	}
	
	public static function alipay_notify($data) {
		self::handle_alipay($data, true);
	}
	
	private static function handle_alipay($data, $notify=false) {
		$orderno = $data['orderno'];
		$update = array();
		
		$tradedetails = $data['tradedetails'];
		
		if($data['tradestatus'] == 'success' || $data['tradestatus'] == 'TRADE_FINISHED' || $data['tradestatus'] == 'TRADE_SUCCESS') {			
			$donate = DonateLog::instance()->findOne(array('conditions'=>"orderno=? and status != ?"), array($orderno, DonateLog::STATUS_PAY_SUCCESS));
			if ($donate) {
				$type = SystemMessage::MSG_TYPE_CHANNEL_STAR_PAY;
				$accountid = $donate['accountid'];
				$payaccountid = $donate['payaccountid'];
				if ($accountid != $payaccountid) {
					$type = SystemMessage::MSG_TYPE_CHANNEL_STAR_PAY_OTHER;
				}
				SystemMessage::instance()->createChannelStarPayMsg($accountid, $payaccountid,$donate['celebrityaccountid'], $type, $donate['id']);
			}
			
			$update['status'] = DonateLog::STATUS_PAY_SUCCESS;
		}
		else {
			//$update['status'] = DonateLog::STATUS_FAILED; // TODO 是否都是失败
		}
		
		if (!$data['verifyresult']) {
			$call = $notify? 'alipay_notify' : 'alipay_callback';
			Log::write("$call: Error: 签名验证失败, ", 'payment');
			$tradedetails = "$call: Error: 签名验证失败, ".$tradedetails;			
			$update['status'] = DonateLog::STATUS_PAY_VERIFY_ERROR;
		}
		
		$update['tradestatus'] = $data['tradestatus'];
		$update['outtradeno'] = $data['outtradeno'];
		if (isset($data['totalfee']) && floatval($data['totalfee']) > 0) {
			$update['totalfee'] = $data['totalfee'];
		}
		$update['tradedetails'] = $tradedetails;
		$update['callback_time'] = date('Y-m-d H:i:s');
		
		DonateLog::instance()->update($update, "orderno=?", array($orderno));
	}
	
	public static function tenpay_callback($data) {
		self::handle_tenpay($data, false);
	}
	
	public static function tenpay_notify($data) {
		self::handle_tenpay($data, true);
	}
	
	private static function handle_tenpay($data, $notify=false) {
		$orderno = $data['orderno'];
		$update = array();
	
		$tradedetails = $data['tradedetails'];		
		$tradestatus = intval($data['tradestatus']);
		
		if($tradestatus == 0) {
			$donate = DonateLog::instance()->findOne(array('conditions'=>"orderno=?"), array($orderno));
			if ($donate) {				
				if ($donate['status'] == DonateLog::STATUS_PAY_SUCCESS) { // 订单已经处理过
					return;
				}
				
				// send message
				$type = SystemMessage::MSG_TYPE_CHANNEL_STAR_PAY;
				$accountid = $donate['accountid'];
				$payaccountid = $donate['payaccountid'];
				if ($accountid != $payaccountid) {
					$type = SystemMessage::MSG_TYPE_CHANNEL_STAR_PAY_OTHER;
				}
				SystemMessage::instance()->createChannelStarPayMsg($accountid, $payaccountid,$donate['celebrityaccountid'], $type, $donate['id']);
				
				$update['status'] = DonateLog::STATUS_PAY_SUCCESS;
			} else {
				Log::write("order not exist:  $orderno", 'payment');
				return; // 不存在该订单
			}
			
			if ($donate && floatval($data['totalfee']) < floatval($donate['totalfee'])) {
				$update['status'] = DonateLog::STATUS_PAY_AMOUNT_INCORRECT;
			}
		}
		else {
			$update['status'] = DonateLog::STATUS_FAILED;
		}
	
		$update['tradestatus'] = $data['tradestatus'];
		$update['outtradeno'] = $data['outtradeno'];
		$update['tradedetails'] = $tradedetails;
		$update['callback_time'] = date('Y-m-d H:i:s');
	
		DonateLog::instance()->update($update, "orderno=?", array($orderno));
	}
}