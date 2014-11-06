<?php
require_once dirname(dirname(dirname(__FILE__))) .'/common/Define.php';
require_once dirname(dirname(dirname(__FILE__))) .'/common/GlobalFunctions.php';
require_once dirname(dirname(dirname(__FILE__))) .'/common/DbFactory.php';
require_once dirname(dirname(dirname(__FILE__))) .'/common/GetFunctions.php';
require_once dirname(__FILE__) . '/AbstractQuery.php';
date_default_timezone_set('PRC');
/*抽奖*/

class choujiang extends AbstractQuery
{
	public function init($openID){
		$this->_StudentID = $StudentID;
		$this->_WeChatID = $openID;//getWechatID($this->_StudentID); 
	}
	public function progress(){
				$wechat_name = GetNickName($this->_WeChatID);
				interface_log(DEBUG, 0, "名字：".$wechat_name);
				$day = date('d',time());
				$hour = date('h',time());
				$time = time();
				$hour = (int)$hour;
				if ($day == '06' && $hour>=13 ) {
					doSql($WeChatID,$wechat_name,$time);
			}
			return $out = null;
	}
	private function doSql($WeChatID,$wechat_name, $time){
		try{
			$db = DbFactory::getallheaders("DB");
			$select_sql = "INSERT INTO  `lucky` VALUES ('','$WeChatID','$wechat_name','$time')";
			$rs = $db->insert($select_sql);
			if(!$rs){
				return null;
			}
		}catch(DB_Exception $e){
			interface_log(ERROR, EC_DB_OP_EXCEPTION, "query db error" . $e->getMessage());
		}
	}
	private function getWechatID($StudentID){
		try{
                   	 	$db     = DbFactory::getInstance('WX');
                    		$sql    = $db->query("SELECT wxh FROM `user` WHERE wx = '$StudentID'");
			$sql    = $db->fetch($sql); 
			return $sql['wxh'];
		}catch(DB_Exception $e){
			interface_log(ERROR, EC_DB_OP_EXCEPTION, "query db error" . $e->getMessage());
		}
	}
}				
?>
