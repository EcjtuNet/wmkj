<?php
header("Content-type: text/html; charset=utf-8"); 
/*
登入，注册页面处理模块
*/
require_once dirname(dirname(__FILE__)) . '/wechatphp/common/Define.php';
require_once dirname(dirname(__FILE__)) . '/wechatphp/class/PicArc.php';
//var_dump($_POST);
if (isset($_POST['sign'])){
		$usename  = $_POST['usename'];
		$password = md5($_POST['password']);
		//var_dump($password);
		$login 	  = new PicArc();
		$access   = $login->loginChenck($usename,$password);
		$status   = "wrong";
		switch($access){
			case '10': {
				$keyW    = $login->getKeyW();
				$content = $login->getContent("*","10");
				$bdcount = $login->getCount();
				//var_dump($bdcount);
				//$out = array('keyword'=>$keyW,'content'=>$content);
				include("wx.html");
				break;
				}
			case '5': include("submit_gbz.html"); break;
			default : echo "<message style='display:none'>$status</message>"; include("sign_new.htm"); break;
		}
	}elseif(@$_POST['apply']){
		//echo "this is a test";
		if (isset($_POST['usename'])) {
			$applyname  = $_POST['usename'];
			$applyword  = md5($_POST['password']);
			$department = $_POST['department'];
			$access     = $_POST['access'];
			$cloum      = $_POST['cloum'];
			$applycheck= new PicArc();
			if($applycheck->loginChenck($applyname)!=null){
				$status = array('status'=>'wrongName');
				echo json_encode($status);
			}else{
				$status = array('status'=>'true');
				if(!isset($access)) echo json_encode($status);
				if(isset($access)&&$access == "10"){
					$cloum = "*";
				}
				//var_dump($department);
				if(isset($applyword)&&isset($department)&&isset($access)&&isset($cloum)){
				//echo "hello ha";
				//var_dump($applycheck);
					if($applycheck->addUser($applyname,$applyword,$access,$department,$cloum)){
						echo "<message style='display:none'>success</message>";
						include("login_new.htm");
					}
				}
			}
		}else echo "<message style='display:none'>wrong:uname</message>";

	}else{
	echo "you haven't login, please login again~! <a href='http://wx.ecjtu.net/wmkj/index.php'>click me~!</a>";
	}
?>
