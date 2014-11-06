<?php
require_once dirname(__FILE__) .'../wechatphp/common/Define.php';
require_once dirname(__FILE__) .'../wechatphp/common/GlobalFunctions.php';
require_once dirname(__FILE__) .'../wechatphp/common/DbFactory.php';

$type = $_GET['type'];
		function display(){
			$arrays = doSql();
			foreach ($arrays as $rows) {
				$id = $rows['id'];
				$wechat_id= $rows['Wechat_name'];
				$time = $rows['time'];
				$array["$id"] = array("NickName"=>"$wechat_name","time" => "$time");
		}
		echo json_encode($array);
	}
		function luck(){
			$arrays = doSql(true);
			foreach ($arrays as  $rows) {
			$id = $rows['id'];
			$wechat_id= $rows['Wechat_id'];
			$time = $rows['time'];
			$array["$id"] = array("NickName"=>"$wechat_id","time"=>"$time");
			}
			echo json_encode($array);
		}
		function doSql($rand = false){
			try {
				$db = DbFactory::getallheaders("DB");
				if(!$rand){
					$sql = "SELECT * FROM `lucky` WHERE 1";
				}else{
					$sql = $sql ." order by rand() limit 3";
				}
				$array = $db->getAll($sql);
				return $array;
			} catch (Exception $e) {
				interface_log(ERROR, EC_DB_OP_EXCEPTION, "query db error" . $e->getMessage());
			}
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
