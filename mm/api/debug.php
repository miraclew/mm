<?php
require_once 'api.inc';

function a_index() {
	require_once LIB.'/nsqphp/bootstrap.php';
	$nsq = new nsqphp\nsqphp();
	$nsq->publishTo('localhost');
	
	$msg = new nsqphp\Message\Message('hello');
	$nsq->publish('mytopic', $msg);
	
	echo "debug";
}