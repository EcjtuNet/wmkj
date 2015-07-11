<?php
require_once dirname(dirname(dirname(__FILE__))) . '/common/Common.php';
require_once dirname(__FILE__) . '/AbstractQuery.php';

//一卡通余额查询类
class ksap extends AbstractQuery
{
	public function init($StudentID){
	 	$this->_StudentID = $StudentID;
                         
	}
	
	public function progress(){	
			$xuehao = substr($this->_StudentID,12)
                        $a = exec("python /home/data/www/api_ecjtu_net/ksap.py '$xuehao'");
                        $arr = json_decode($a);
                       // $redis = new Predis\Client();
                        //$redis->zadd('ykt:top', $arr[0], $this->_StudentID);
                        //$rank = $redis->zrank('ykt:top', $this->_StudentID);
                        //$count = $redis->zcount('ykt:top', '-inf', '+inf');
                       // $percentage = intval((($rank+1)/$count)*100);
			if($a!='nothing'){
				return $out = $a;	
			}else
			{	
				return $out ="抱歉，暂时还没有查到你的考试安排哦~";
			}
	}
}
?>
