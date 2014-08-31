<?php
	session_start();
	if($_SESSION['access'] == NULL){
		 //var_dump($_SESSION);
		 header("Location: http://wx.ecjtu.net/wmkj/index.php"); 
		 exit(0);
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>ForPP</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="http://cdn.bootcss.com/bootstrap/2.3.1/css/bootstrap.min.css" rel="stylesheet">
<link href="http://libs.baidu.com/bootstrap/2.3.1/css/bootstrap.min.css" rel="stylesheet">
<link href="http://cdn.bootcss.com/bootstrap/2.3.1/css/bootstrap-responsive.min.css" rel="stylesheet">
<link href="http://cdn.bootcss.com/bootstrap/3.1.1/css/bootstrap-theme.css" rel="stylesheet">
<link href="./css/style.css" rel="stylesheet">
<link rel="shortcut icon" href="./image/logo_icon.png" type="image/png"> 
</head>
<body>
<div class="container">
	<div class="row-fluid">
		<div class="span4 offset4">
			<form class="form-signin" name="apply" method="post" action="bglogin.php" onsubmit="return chenck()">
				<fieldset>
					<legend><strong>REGISTRATION</strong> <small>注册</small></legend>
					   <div class="control-group name">
						<input type="text" class="input-block-level" placeholder="用户名" name="usename"/>
						<span class="help-block"></span>
					   </div>
					   <div class="control-group password">
						<input type="password" class="input-block-level" placeholder="密码" name="password"/>
					   	<span class="help-block"></span>
					   </div>
					   <div class="control-group department">
						<input type="text" class="input-block-level" placeholder="部门" name="department"/>
						 <span class="help-block"></span>
					   </div>
					   <div class="control-group ace">
					   	<select class="access" name="access">
							<option value="0">权限</option>
							<option value="5">信息发布</option>
							<option value="7">栏目管理</option>
							<option value="10">admin</option>
						</select>
							<span class="help-block"></span>
					   </div>
					   <div class="control-group clo">
						<select class="cloum hidden" name="cloum">
								<option value="0">栏目</option>
                                <option value="1">小新快递</option>
                                <option value="2">微科技</option>
                                <option value="3">孔目胡讲坛</option>
                                <option value="4">微音乐</option>
                                <option value="5">微杂志</option>
                                <option value="6">微童话</option>
                                <option value="7">博约课堂</option>
                                <option value="8">微电台</option>
                            </select>
						<span class="help-block"></span>
					</div>
						<button class="btn btn-large btn-primary submit" type="submit" name="apply" value="apply">提交</button>
						<span class="help-block success hidden">注册成功，<a href="http://wx.ecjtu.net">点我登入</a></span>
				</fieldset>
			</form>
			<p class="support">技术支持 <a href="http://www.ecjtu.net"><img src="./image/logo_icon.png" alt="^_^">日新网技术研发中心</a></p>
		</div>
	</div>
</div>
<script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>
<script src="./js/check.js" type="text/javascript" ></script>
</body>
</html>
