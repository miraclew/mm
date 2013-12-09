<?php
define('UPLOAD_DIR', __DIR__.'/../uploads');

define('HOST', '192.168.1.5');
//define('HOST', '121.199.26.162');

if (HOST == 'localhost') {
	define('DEBUG', 2);
}
else {
	define('DEBUG', 0);
}

define('UPLOAD_URL', 'http://'.HOST.'/mm/uploads');
define('AVATAR_DEFAULT', 'http://'.HOST.'/mm/avatar/1.png');
define('GROUP_AVATAR_DEFAULT', 'http://'.HOST.'/mm/avatar/group_avatar_default.png');

define('WS_API_URL', 'http://'.HOST.':9001/mq');
define('WS_CONNECT_URL', 'ws://'.HOST.':9001/ws');


return array(
		'salt'				=> '964Dr7S4VZ',
		'db.host'			=> '127.0.0.1',
		'db.user'			=> 'root',
		'db.password'		=> 'apple',
		'db.database'		=> 'mm'
		);

