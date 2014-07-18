<?php
require_once dirname(dirname(dirname(__FILE__))) .'/common/Define.php';
require_once dirname(dirname(dirname(__FILE__))) .'/common/GlobalFunctions.php';
require_once dirname(dirname(dirname(__FILE__))) .'/common/DbFactory.php';
require_once dirname(__FILE__) . '/AbstractQuery.php';
/**
*@desc 课表查询类
*@version 1.0.2
*@author homker
*/

class classquery extends AbstractQuery
{
	public function init($StudentID){
		//使用Redis统计查询次数

		require 'predis-0.8/autoload.php';
		$redis = new Predis\Client();
		$redis->rpush('class:count',time());
		//by wtbhk
	 	$this->_StudentID = $StudentID; 
	 	try{
	 		$dbs = DbFactory::getInstance('SCORE');
	 		$sql = "SELECT ClassCode FROM `StudentInfo` WHERE `StudentID` LIKE"."'$this->_StudentID'";
	 		interface_log(DEBUG, 0, "sql:" . $sql);		
	 		$rs  = $dbs->query($sql);
	 		$res = $dbs->fetch($rs);
	 	} catch (DB_Exception $e){
	 		interface_log(ERROR, EC_DB_OP_EXCEPTION, "query db error" . $e->getMessage());
	 	}
	 	$this->_ClassID=substr($res['ClassCode'], 0,12);
	}
		
	public function progress(){
		interface_log(DEBUG,0,"班级：".$this->_ClassID);
		if($this->chenck($this->_ClassID)){
			try {
				$db = DbFactory::getInstance('CLASS');
				$sql = "SELECT course FROM class WHERE cid='$this->_ClassID' AND term="."'2013.2'";
				//$sql = "insert into userinput (userId, input) values(\"" . $this->_fromUserName . "\", \"" . $this->_postObject->Content . "\")";
				interface_log(DEBUG, 0, "sql:" . $sql);			
				$rs = $db->query($sql);
			
			} catch (DB_Exception $e) {
				interface_log(ERROR, EC_DB_OP_EXCEPTION, "query db error" . $e->getMessage());
			}
			$class=$db->fetch($rs);
			interface_log(DEBUG,0,"数据库读取正常。读取数据为：".$class);
			$class=$class['course'];
		}
		else{
			return $this->_content= "对不起，11级软工分流后的班级序号有变，暂时无法正常查询";
		}
	    if(date("w")==0) $week = 7;
	    else $week = date("w");
	    if(date("H")>21) {
	    	$week = $week + 1;
	    	if($week == 8) $week =1;
		interface_log(DEBUG, 0, "星期是：".$week);
	    }
	  	$pattern="/<td><div align=\"center\"><font size=\"2\">(.*?)<\/font><\/div><\/td>/";
	  	preg_match_all($pattern, $class, $matches);
	  	if(preg_last_error() == PREG_NO_ERROR) 
	  	{
			for ($i=0; $i <40 ; $i++) { 
				$matches[1][$i]=str_replace("<br>", "\n", str_replace("&nbsp;"," ",$matches[1][$i]));
			}
			$k=0;
			if(date("H")<21){
				$course = "您今天的课表如下：\n\n";
			}else{
				$course = "您明天的课表如下：\n\n";
			}
			for($i=$week;$i<40;$i+=8){
				if($matches[1][$i]==" ") $matches[1][$i]="该时段没有课哦，亲～";
				$course = $course."第".$matches[1][$k]."节课:\n".$matches[1][$i]."\n===============\n\n";
				$k=$k+8;
			}
	    		$this->_content=$course.'<a href="http://class.ecjtu.net/wClass.php?class='.$this->_ClassID.'">更多课表，请戳我</a>';
	    		interface_log(DEBUG,0,"正则匹配结果：".$course);
	    		interface_log(DEBUG,0,"回复内容：".$this->_content);
	  	}
	  	else 
	  	{
	  		if (preg_last_error() == PREG_NO_ERROR) {
			    $contentStr= 'There is no error.';
			}
			else if (preg_last_error() == PREG_INTERNAL_ERROR) {
			    $contentStr= 'There is an internal error!';
			}
			else if (preg_last_error() == PREG_BACKTRACK_LIMIT_ERROR) {
			    $contentStr= 'Backtrack limit was exhausted!';
			}
			else if (preg_last_error() == PREG_RECURSION_LIMIT_ERROR) {
			    $contentStr= 'Recursion limit was exhausted!';
			}
			else if (preg_last_error() == PREG_BAD_UTF8_ERROR) {
			    $contentStr= 'Bad UTF8 error!';
			}
			else if (preg_last_error() == PREG_BAD_UTF8_ERROR) {
			    $contentStr= 'Bad UTF8 offset error!';
			}
			
			interface_log(ERROR , 1, "正则表达式错误。错误信息：".$contentStr);	
	  	}
	  	return $this->_content;
	}
	private function chenck($ClassID){
		switch($ClassID){
			case '201121100101' : return false; break;
			case '201121100102' : return false; break;
			case '201121100103' : return false; break;
			case '201121100104' : return false; break;
			case '201121100105' : return false; break;
			case '201121100106' : return false; break;
			case '201121100107' : return false; break;
			case '201121100108' : return false; break;
			default : return true; break;
		}
	}
}



?>
