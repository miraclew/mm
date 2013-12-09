<?php
require_once __DIR__.'/../include/init.inc';
$user = auth();

$model = new Friend($user['uid']);
$friends = $model->friends();
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $user['name']?>,welcome</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
<style type="text/css">
body {
	padding-top: 40px;
	padding-bottom: 40px;
	background-color: #f5f5f5;
}
div.user {
	padding: 10px;
}
img.avatar {
	width:32px; 
	height:32px;
}
ul.friends {
	margin: 0 5px;	
}

ul.friends li {
	margin: 10px 0;
	border: white solid 1px;
}

ul.friends li:hover {
	border: gray solid 1px;
}

.chat_input {
	width: 400px;
}

.messages {
/* 	height: 200px; */
/*  	max-height: 200px; */
}

.navbar .brand {
	float: right;
}

.navbar .btn-group {
	float: right;
	margin-right: 20px;
}

</style>
<script type="text/javascript">
var user = <?php echo json_encode($user); ?>;
var ws_url = '<?php echo WS_CONNECT_URL.'/'.$user['uid']; ?>';
</script>
</head>
<body>
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<div class="nav-collapse collapse">
				    <ul class="nav">
				      <li class="active"><a href="#">首页</a></li>
				      <li><a href="#">Link</a></li>
				      <li><a href="#">Link</a></li>
				    </ul>
				    <a class="brand" href="#">Title</a>
				    <div class="btn-group">
					  <button class="btn btn-inverse">Action</button>
					  <button class="btn btn-inverse dropdown-toggle" data-toggle="dropdown">
					    <span class="caret"></span>
					  </button>
					  <ul class="dropdown-menu">
				      <li><a href="#">Link</a></li>
				      <li><a href="#">Link</a></li>
					  </ul>
					</div>
				</div>
		  </div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="span1">
				<img class="avatar" alt="" src="<?php echo $user['avatar'];?>">
			</div>
			<div class="span11">
				<?php echo $user['name'];?>
				<a id='logout'class="btn">退出</a>
			</div>
		</div>
		<div class="row" style="height:10px;"></div>
		<div class="row">
			<div class="span2">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#my-friends">好友</a></li>
					<li><a href="#recent-chat">对话</a></li>
				</ul>
				
				<div class="tab-content">
					<div class="tab-pane active" id="my-friends">
						<ul class="friends">
						<?php foreach ($friends as $v) {
							echo "<li data-uid='{$v['uid']}'><img class='avatar' src='{$v['avatar']}'> {$v['name']}</li>";
						} ?>
						</ul>
						<div class="btn-toolbar">
							<div class="btn-group">
							  <a class="btn" href="#myModal" data-toggle="modal">+</a>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="recent-chat">recent-chat</div>
				</div>
			</div>
			<div class="span10">
				<!--Body content-->
				<ul class="nav nav-tabs" id="chat-tab">
<!-- 					<li class="active"><a href="#home">Home</a></li> -->
				</ul>

				<div class="tab-content" id='chat'>
					<div class="tab-pane active" id="home" style="display: none;">
						<div class="messages">
							<p>hello</p>
						</div>
						<input id="" class="chat_input">
						<a class="btn">发送</a>
					</div>
				</div>
				
				<div class="btn-toolbar">
					<div class="btn-group">
					  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
					    表情
					    <span class="caret"></span>
					  </a>
					  <ul class="dropdown-menu">
					    <!-- dropdown menu links -->
					    <li><a href="#">a</a></li>
					    <li><a href="#">b</a></li>
					    <li><a href="#">c</a></li>
					    <li><a href="#">d</a></li>
					  </ul>
					</div>
					<div class="btn-group">
						<form id="form_0" name="form_0" action="/mm/api/chats.php?a=message_send" target="csr" enctype="multipart/form-data" method="post" style="margin:px; padding:0px">
							<div id="loader_0"></div>
							<input type="hidden" name="chatid" value="13">
							<input type="hidden" name="text" value="1">
							<input type="hidden" name="media_type" value="image/*">							
							<input type="file" name="media" class="" />
							<a class="btn" type="button" onclick="upload(document.form_0, document.loader_0)" >发送</a>
						</form>
					</div>
				</div>
				
			</div>
		</div>
	</div>
	
	<iframe id="csr" name="csr" height="1" width="1" style="border:0px none"></iframe>
	<div id="myModal" class="modal hide fade" data-keyboard="true">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	    <h3>找好友</h3>
	  </div>
	  <div class="modal-body">
	  	<form id="search_user" class="form-search">
		  <input type="text" class="input-medium search-query" name="kw">
		  <button type="submit" class="btn">搜索</button>
		</form>
		<div id="search_result">
		</div>	  	
	  </div>
	  <div class="modal-footer">
	    <a href="#" class="btn" data-dismiss="modal" aria-hidden="true">关闭</a>
	    <a href="#" class="btn btn-primary" onclick="add_friend()" data-dismiss="modal" aria-hidden="true">加为好友</a>
	  </div>
	</div>
	<script src="js/jquery-1.9.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/app.js"></script>
	<script>
	</script>
</body>
</html>
