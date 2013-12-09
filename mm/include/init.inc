<?php
error_reporting(E_ALL);
@ini_set('display_errors', true);
date_default_timezone_set('Asia/Shanghai');
session_start();

# defines
define('TMP', __DIR__.'/../tmp');
define('LIB', __DIR__.'/../lib');

$config = require_once __DIR__.'/../config/config.php';

require_once __DIR__.'/helper.php';

# db
require_once __DIR__.'/../lib/php-activerecord/ActiveRecord.php';

$connectstring = "mysql://{$config['db.user']}:{$config['db.password']}@{$config['db.host']}/{$config['db.database']};charset=utf8";
$cfg = ActiveRecord\Config::instance();
$cfg->set_model_directory(__DIR__.'/../model');
$cfg->set_connections(
		array(
				'development' => $connectstring,
				'test' => $connectstring,
				'production' => $connectstring
		)
);
ActiveRecord\DateTime::$DEFAULT_FORMAT = 'Y-m-d H:i:s';

// auth check
class HttpError extends Exception {
	const BadRequest 		= 400;
	const Unauthorized 		= 401;
	const PaymentRequried 	= 402;
	const Forbidden			= 403;
	const NotFound			= 404;
	const MethodNotAllowed	= 405;

	public function __construct ($message = null, $code = null, $previous = null) {
		parent::__construct($message, $code, $previous);
	}
}

function auth($name='', $check_only=false) {
	if (empty($_SESSION['user'])) {
		if ($check_only) return null;
		throw new HttpError('please login', HttpError::Forbidden);
	}
	else {
		if ($name == '') {
			return $_SESSION['user'];
		}
		else {
			return $_SESSION['user'][$name];
		}
	}
}
