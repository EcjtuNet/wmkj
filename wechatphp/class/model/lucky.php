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
	public function init($WeChatID){
		$this->_WeChatID = $WeChatID; 
	}
	public function process(){
				$wechat_name = GetNickName($this->_WeChatID);
				$day = date('d',time());
				$hour = date('h',time());
				$hour = (int)$hour;
				if ($day == '06' && $hour>=13 ) {
					doSql($WeChatID,$wechat_name,$time);
			}
	}
	private function doSql($WeChatID,$wechat_name, $time){
		try{
			$db = DbFactory::getallheaders("DB");
			$select_sql = "INSERT INTO  `luck` VALUES ('','$WeChatID','$wechat_name','$time')";
			$rs = $db->insert($select_sql);
			if(!$rs){
				return null;
			}
		}catch(DB_Exception $e){
			interface_log(ERROR, EC_DB_OP_EXCEPTION, "query db error" . $e->getMessage());
		}
	} 
}				
?>
