<?php
require_once dirname(dirname(dirname(__FILE__))) . '/common/Common.php';
require_once dirname(__FILE__) . '/AbstractQuery.php';
require_once dirname(__FILE__) . '/predis-0.8/autoload.php';
class getup extends AbstractQuery
{
	public function init($StudentID){
	 	$this->_StudentID = $StudentID;
	}
	
	public function progress(){
		//return 'ok';
		$redis = new Predis\Client();
		$key = 'getup:'.date('Ymd');	
		if(date('H')>=4&&date('H')<=14){
			$range = $redis->zrank($key, $this->_StudentID);
			if($range===NULL){
				$responses = $redis->transaction()->zadd($key, time(), $this->_StudentID)->zrank($key, $this->_StudentID)->execute();
				$rank = intval($responses[1]+1);
			}else{
				$rank = $range+1;
			}
			return '你是今天第'.$rank.'起床的';
		}else{
			return '只能在4点到14点起床签到哟';
		}
	}
	
}
?>
