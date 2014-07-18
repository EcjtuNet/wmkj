<?php
require_once dirname(dirname(dirname(__FILE__))) .'/common/Define.php';
require_once dirname(dirname(dirname(__FILE__))) .'/common/GlobalFunctions.php';
require_once dirname(dirname(dirname(__FILE__))) .'/common/DbFactory.php';
require_once dirname(__FILE__) . '/AbstractQuery.php';
/**
*@desc 考试查询类
*@version 1.0.2
*@author homker
*/

class kaoshiquery extends AbstractQuery
{
	public function init($StudentID){
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
		$url ='http://172.16.47.252:8080/jwcmis/examarrange.jsp';
		$param = array('classid'=>$this->_ClassID,'submit'=>"%B2%E9%D1%AF");
		$out = doCurlGetRequest($url,$param);
		$data = iconv('GB2312', 'UTF-8', $out);
		//interface_log(DEBUG, 0, var_export($data,TRUE));
		$pattern="/<td><font size=-1>(.*?)<\/font><\/td>/";
		preg_match_all($pattern, $data, $matches);
		if(preg_last_error() == PREG_NO_ERROR) 
		  	{
		  		//var_dump($matches);
		  		if(!isset($matches[1][0])) return $content = "对不起，暂时未查询到相关考试信息。\n".'提示：部分考试安排未出，请花椒们密切关注<a href="http://202.101.209.252:8080/jwcmis/examarrange.jsp?classid='.$this->_ClassID.'">校教务处</a>的官方信息。';
		  		$s=0;
		  		while(isset($matches[1][$s]))
		  		{
		  			if($matches[1][$s]=="") $matches[1][$s]="[暂无数据]";
		  			$s++;
		  		}
		  		if($this->_StudentID=='20110610040101') $content = "趴趴，逢考必过哦～！\n";
		  		else $content = "同学，你的考试信息查询如下：\n";
		  		$k=1;
		  		for($i=1;;$i+=10)
		  		{
		  			$content = $content."第".$k."门:\n科目:".$matches[1][$i]."\n考试周：".$matches[1][$i+2]."\n时间:".$matches[1][$i+3]."\n地点:".$matches[1][$i+4]."\n主监考:".$matches[1][$i+5]."(".$matches[1][$i+6].")"."\n副监考:".$matches[1][$i+7]."(".$matches[1][$i+8].")"."\n***********************\n";
		  			$k+=1;
		  			if(isset($matches[1][$i+9])) continue;
		  			else break;
		  		}
		  		$out = $content.'信息来源：<a href="http://202.101.209.252:8080/jwcmis/examarrange.jsp?classid='.$this->_ClassID.'">校教务处</a>'." \n更新时间：". date('Y-m-d H:i:s',time());
		  		return $out;
		  	}
	}
}
?>
