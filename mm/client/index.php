<!DOCTYPE html>
<html>
  <head>
    <title>MM登陆</title>
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

      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }

    </style>
  </head>
  <body>
		<h3 style="text-align: center;">The world needs your voice</h3>
		<br>
     <div class="container">
      <form id="login" class="form-signin">
        <h2 class="form-signin-heading">MM登陆</h2>
        <input type="text" class="input-block-level" placeholder="MM号码" name="uid">
        <input type="password" class="input-block-level" placeholder="密码" name="password">
        <label class="checkbox">
          <input type="checkbox" value="remember-me"> 记住我
        </label>
        <button class="btn btn-large btn-primary" type="submit">登陆</button>
        没有账号? <a class="btn" onclick="sw('login','register')">注册</a>
      </form>
      <form id="register" class="form-signin" style="display: none;">
        <h2 class="form-signin-heading">新用户注册</h2>
        <input type="text" class="input-block-level" placeholder="昵称" name="name">
        <input type="password" class="input-block-level" placeholder="密码" name="rpassword">
        <button class="btn btn-large btn-primary" type="submit">注册</button>
        已有账号, 直接 <a class="btn" onclick="sw('register', 'login')">登陆</a>
      </form>
      <div id="register_ok" class="form-signin" style="display: none;">
      	<h2 class="form-signin-heading">MM号注册成功！</h2>
      	<p>MM号: <span id="ro_uid"></span></p>
      	<p>昵称: <span id="ro_name"></span></p>
      	<button class="btn btn-block" onclick="start()">开始使用</button>
      </div>
    </div> <!-- /container -->
    <script src="js/jquery-1.9.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/login.js"></script>
    <script type="text/javascript">
    </script>
  </body>
</html>
