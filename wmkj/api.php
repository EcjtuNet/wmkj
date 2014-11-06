<?php
require_once dirname(__FILE__) .'../wechatphp/common/Define.php';
require_once dirname(__FILE__) .'../wechatphp/common/GlobalFunctions.php';
require_once dirname(__FILE__) .'../wechatphp/common/DbFactory.php';

$type = $_GET['type'];
		function display(){
			$sql = "SELECT * FROM `luck` WHERE 1";
			$query = mysql_query($sql);
			while ($rows = mysql_fetch_array($query)) {
				$id = $rows['id'];
				$wechat_id= $rows['Wechat_name'];
				$time = $rows['time'];
				$array["$id"] = array("NickName"=>"$wechat_name","time" => "$time");
		}
		echo json_encode($array);
	}
		function luck(){
			$sql = "SELECT * FROM `luck` order by rand() limit 3";
			$query = mysql_query($sql);
			while($rows = mysql_fetch_array($query))
			{
			$id = $rows['id'];
			$wechat_id= $rows['Wechat_id'];
			$time = $rows['time'];
			$array["$id"] = array("NickName"=>"$wechat_id","time"=>"$time");
			}
			echo json_encode($array);
		}
		switch ($type) {
			case 'display':
				display();
				break;
			case 'luck':
				luck();
				break;
		}
		
?>