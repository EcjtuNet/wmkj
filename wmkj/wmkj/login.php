<?php
	if (@$_POST['login'])
	{
		$usename = $_POST['usename'];
		$password = md5($_POST['password']);
		if($usename == "admin"&&$password == md5("admin"))
		{
			include("submit.html");
		}
		elseif($usename == "ecjtu_gbz" && $password ==md5("ecjtu_gbz"))
		{
			include("submit_gbz.html");
		}else echo "fail~! password wrong! please try again";
	}
	if(@$_POST['apply'])
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

	}
		 
//include 'index.htm';
?>


