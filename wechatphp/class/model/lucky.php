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
				if ($day == '06' && $hour>=18 ) {
					$select_sql = " SELECT * FROM luck WHERE Wechat_name=".$wechat_name;
					$select_query = mysql_query($select_sql);
					if (mysql_num_rows($select_query) == 0) {
						$time = time();
						$sql = "INSERT INTO  `luck` VALUES ('','$wechat_name','$time')";
						mysql_query($sql);
				}
			}
		}
	}				
?>
