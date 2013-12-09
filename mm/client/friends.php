<form>
<?php
require_once __DIR__.'/../include/init.inc';
$user = auth();
$kw = get_argument('kw','');

if ($kw) {
	$users = User::find('all', array('conditions'=>array("(uid=? or name like '%$kw%') and uid !=?", $kw, $user['uid']), 'order' => 'uid desc'));
}
else {
	$users = User::all();
}

if (count($users) > 0) {
	echo '<ul class="friends">';
	foreach ($users as $v) {
		echo "<li data-uid='{$v->uid}'><input type='radio' name='uid' value='{$v->uid}'><img class='avatar' src='{$v->avatar}'> {$v->name}</li>";
	}
	echo '</ul>';	
}
else {
	echo "未找到";
}
?>
</form>