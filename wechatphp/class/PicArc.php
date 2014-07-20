<?php
/**
*@desc web端数据处理类
*@version 1.0.2
*@author homker
*/

require_once dirname(dirname(__FILE__)) .'/common/Define.php';
require_once dirname(dirname(__FILE__)) .'/common/GlobalFunctions.php';
require_once dirname(dirname(__FILE__)) .'/common/DbFactory.php';

class PicArc
{
	protected $_colum;
	protected $_count;
	
	public function loginChenck($loginN,$loginP)
	{
		try{
			$db  = DbFactory::getInstance('DB');
			$sql = "SELECT `access` FROM admin_user WHERE name="."'$loginN'";
			if(isset($loginP))
			{
				$sql = $sql."AND password="."'$loginP'";
			}
			$rs  = $db->query($sql);
			$row = $db->fetch($rs);
		}catch(DB_Exception $e){
			interface_log(ERROR, EC_DB_OP_EXCEPTION, "query db error in login" . $e->getMessage());
		}
		return $row['access'];
	}
	public function getAccess($loginN,$loginP)
	{
		try{
			$db  = DbFactory::getInstance('DB');
			$sql = "SELECT * FROM admin_user WHERE name="."'$loginN'"."AND password="."'$loginP'";
			$rs  = $db->query($sql);
			$row = $db->fetch($rs);
		}catch(DB_Exception $e){
			interface_log(ERROR, EC_DB_OP_EXCEPTION, "query db error for get ace". $e->getMessage());
		}
		return $row;
	}
	public function addUser($uName,$passW,$acc,$depart,$cloum)
	{
		try{
			$db  = DbFactory::getInstance('DB');
			$sql = "INSERT INTO admin_user VALUES('',"."'$uName'".","."'$passW'".","."'$acc'".","."'$cloum'".","."'$depart'".")";
			interface_log(DEBUG, 0, "sql: ".$sql);
			if($db->insert($sql)) return true;
		}catch(DB_Exception $e){
			interface_log(ERROR, EC_DB_OP_EXCEPTION, "db error in apply".$e->getMessage());
		}
	}
	public function addContent($arr)
	{
		$title      = $arr['title'];
		$descr      = $arr['descr'];
		$piurl      = $arr['piurl'];
		$arurl      = $arr['arurl'];
		$colum      = $arr['colum'];
		$publishman = $arr['publishman'];
		interface_log(pic, 0 , var_export($arr,TRUE));
		try {
			$db  = DbFactory::getInstance('DB');
			$sql = "INSERT INTO picarc VALUES('',"."'$title'".","."'$descr'".","."'$piurl'".","."'$arurl'".","."'$colum'".","."'$publishman'".")";
			interface_log(DEBUG, 0, "sql:" . $sql);	
			$db->insert($sql);
		}catch (DB_Exception $e) {
				interface_log(ERROR, EC_DB_OP_EXCEPTION, "query db error" . $e->getMessage());
			}
	}
	public function addKeyW($arr)
	{
		$keyW    = $arr['keyW'];
		$content = $arr['model'];
		$weight  = $arr['weight'];
		try {
			$db  = DbFactory::getInstance('DB');
			$sql = "INSERT INTO keyword VALUES('','$keyW'".","."'$content'".","."'$weight'".")";
			interface_log(DEBUG, 0, "sql:" . $sql);	
			$db->insert($sql);
		}catch (DB_Exception $e) {
				interface_log(ERROR, EC_DB_OP_EXCEPTION, "query db error" . $e->getMessage());
			}
	}
	public function getKeyW()
	{
		try {
				$db  = DbFactory::getInstance('DB');
				$sql = "SELECT * FROM keyword WHERE weight>0 ORDER BY weight";
				//$sql = "insert into userinput (userId, input) values(\"" . $this->_fromUserName . "\", \"" . $this->_postObject->Content . "\")";
				interface_log(DEBUG, 0, "sql:" . $sql);			
				$arr = $db->getAll($sql);
			
			} catch (DB_Exception $e) {
				interface_log(ERROR, EC_DB_OP_EXCEPTION, "query db error" . $e->getMessage());
			}
			interface_log(DEBUG, 0, "数据库结果：".$arr."条数：".count($arr));
			if(count($arr)!=0){
				return $arr;
			}else return $arr="未发现关键字";	
	}
	
	public function getContent($colum ,$count="-1")
	{
		if($colum != "*"){
			$colum = $this->changeColum($colum);
			$where = "WHERE colum ="."'$colum'";
		}
		$arr = "内容编辑中，请稍后尝试～！";
		try {
				$db  = DbFactory::getInstance('DB');
				$sql = "SELECT * FROM picarc ".$where." ORDER BY ID DESC";
				if($count != '-1'){
					$sql = $sql." LIMIT 0,".$count;
				}
				//$sql = "insert into userinput (userId, input) values(\"" . $this->_fromUserName . "\", \"" . $this->_postObject->Content . "\")";
				interface_log(DEBUG, 0, "sql:" . $sql);			
				$arr = $db->getAll($sql);
			
			} catch (DB_Exception $e) {
				interface_log(ERROR, EC_DB_OP_EXCEPTION, "query db error" . $e->getMessage()."数据库链接信息:".var_export($GLOBALS['DB'],TRUE));
			}
			interface_log(DEBUG, 0, "数据库结果：".$arr."条数：".count($arr));
			if(count($arr)!=0){
				return $arr;
			}else return $arr="小新还在编辑呢，请稍后尝试～！";	
	}

	public function getCount()
	{
		$db  = DbFactory::getInstance("WX");
		$sql = "select count(wxh) from user where bd = 1";
		$rs  = $db->query($sql);
		$result = $db->fetch($rs);
		return $result;
	}

	protected function changeColum($colum)
	{
		switch($colum)
		{   
			case 'KUAIDI'  : $flag = 1; break;
			case 'KEJI'    : $flag = 2; break;
			case 'JIANGTAN': $flag = 3; break;
			case 'YINYUE'  : $flag = 4; break;
			case 'ZAIZHI'  : $flag = 5; break;
			case 'TONGHUA' : $flag = 6; break;
			case 'DONGMAN' : $flag = 7; break;
		}
		return $flag;
	}
}

?>
