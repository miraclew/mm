<?php
require_once '../include/init.inc';

$logs = TMP.'/logs'; 
if (!file_exists($logs)) {
	mkdir($logs);
}

$logfile = $logs.'/callback.log';

file_put_contents($logfile, print_r($_REQUEST, true), FILE_APPEND);
