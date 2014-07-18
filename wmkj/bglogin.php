<?php
/*
登入，注册页面处理模块
*/
require_once dirname(dirname(__FILE__)) . '/wechatphp/common/Define.php';
require_once dirname(dirname(__FILE__)) . '/wechatphp/class/PicArc.php';
//var_dump($_POST);
if (isset($_POST['sign']))
	{
		$usename = $_POST['usename'];
		$password = $_POST['password'];
		$login = new PicArc();
		$access = $login->loginChenck($usename,$password);
		$status = "wrong";
		switch($access){
			case '10': {
				$keyW = $login->getKeyW();
				$content = $login->getContent("*","10");
				$bdcount = $login->getCount();
				//var_dump($bdcount);
				//$out = array('keyword'=>$keyW,'content'=>$content);
				include("wx.html");
				break;
				}
			case '5' : include("submit_gbz.html"); break;
			default : echo "<message style='display:none'>$status</message>"; include("sign_new.htm"); break;
		}
	}elseif(@$_POST['apply'])
	{
		//echo "this is a test";
		if (isset($_POST['applyname'])&&isset($_POST['applyword'])) 
		{
			$applyname=$_POST['applyname'];
			$applyword=$_POST['applyword'];
			$applycheck= new check($conn,$applyname,$applyword);
			switch ($applycheck->cc) 
			{
				case '1':
					{
					//echo "this is a test";
						$dbname="login";
						$applyin=new db($dbname);
						$value=array('LoginName'=>$applyname,'PassWord'=>md5($applyword),'Access'=>2);
						if($result=$applyin->insert($value)) echo "注册成功"."<a href='index.htm'>前往登入</a>";
						else echo "you are fail~!";
					}
				break;
				default:
					echo "<script language=\"javascript\">alert('用户名被占用');location.href='apply.htm';</script>";break;
				break;
			}
		}

	}else{
	echo "you haven't login, please login again~! <a href='http://wx.ecjtu.net/wmkj/index.php'>click me~!</a>";
	}
?>
