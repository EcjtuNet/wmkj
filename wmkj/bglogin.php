<?php
header("Content-type: text/html; charset=utf-8"); 
/*
登入，注册页面处理模块
*/
session_start();
require_once dirname(dirname(__FILE__)) . '/wechatphp/common/Define.php';
require_once dirname(dirname(__FILE__)) . '/wechatphp/class/PicArc.php';
if (isset($_POST['sign'])){
		$usename  = $_POST['usename'];
		$password = md5($_POST['password']);
		$login 	  = new PicArc();
		if(isset($usename)&&isset($password)){
			$access   = $login->loginChenck($usename,$password);
			$keyW     = $login->getKeyW();
			$cloum    = $login->getAccess($usename, $password);
			$bdcount  = $login->getCount();
			$_SESSION['access']['uName'] = $usename;
		}
		$status   = "wrong";
		switch($access){
			case '10': {
				$content = getContent($login, "10", $cloum['cloum']);
				$_SESSION['access']['access']="10";
				include("wx.php");
				break;
				}
			case '7':{
				$content = getContent($login, "10", $cloum['cloum']);
				$_SESSION['access']['access']="7";
				include("wx.php");
				break;
			}
			case '5': {
				if($cloum['cloum'] == '8'){
					include("submit_gbz.html");
				}else{
					$content = getContent($login, "10", $cloum['cloum']);
					$_SESSION['access']['access']="5";
					include("wx.php");
				}
				break;
			}
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
				if(isset($applyword)&&isset($department)&&isset($access)&&isset($cloum)){

					if($applycheck->addUser($applyname,$applyword,$access,$department,$cloum)){
						echo "<message style='display:none'>success</message>";
						include("login_new.php");
					}
				}
			}
		}else echo "<message style='display:none'>wrong:uname</message>";

	}elseif(isset($_GET['quit'])){
		session_destroy();
		header("Location: http://wx.ecjtu.net/wmkj/index.php");
		exit();
	}elseif(isset($_GET['dele'])){
		if($_SESSION['access']['access']<5){
			echo "";
		}
	}else{
		echo "you haven't login, please login again~! <a href='http://wx.ecjtu.net/wmkj/index.php'>click me~!</a>";
	}
function getContent($obj, $access, $cloum)
{
	switch($access){
		case '10': $cloum = "*";
		case '7' : $cloum = $cloum;
		case '5' : $cloum = $cloum;
	}
	$res = $obj->getContent($cloum, "10");
	return $res;
}
	
/////////////////////////////////////////the end of php
/////////////////////////////////////////power by homker
	
