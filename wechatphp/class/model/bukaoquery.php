<?php
require_once dirname(dirname(dirname(__FILE__))) .'/common/Define.php';
require_once dirname(dirname(dirname(__FILE__))) .'/common/GlobalFunctions.php';
require_once dirname(dirname(dirname(__FILE__))) .'/common/DbFactory.php';
require_once dirname(__FILE__) . '/AbstractQuery.php';
/**
*@desc 补考查询类
*@version 1.0.2
*@author homker
*/

class bukaoquery extends AbstractQuery
{
	public function init($StudentID){
	 	$this->_StudentID = $StudentID; 
	}
	
	public function progress(){
		$url ='http://172.16.47.252:8080/jwcmis/bk/';
		$param = array('xuehao'=>$this->_StudentID,'submit'=>"%B2%E9%D1%AF");
		$out = doCurlGetRequest($url,$param);
		$data = iconv('GB2312', 'UTF-8', $out);
		$pattern="/<td><span class=\"STYLE5\">(.*?)<\/span><\/td>/";
		preg_match_all($pattern, $data, $matches);
		if(preg_last_error() == PREG_NO_ERROR) 
		  	{
		  		//var_dump($matches);
		  		if(!isset($matches[1][0])) return $content = "别逗了，你以为我不知道你都过了~？";
		  		if($matches[1][0]==20122110090238) $content = "三叔，加油，大216永远在你的身后，逢考必过。\n";
		  		else $content = "同学，补考信息查询如下：\n";
		  		$k=1;
		  		for($i=3;;$i+=6)
		  		{
		  			$content = $content."第".$k."门:\n 科目:".$matches[1][$i]."\n时间:".$matches[1][$i+1]."\n地点:".$matches[1][$i+2]."\n***********************\n";
		  			$k+=1;
		  			if(isset($matches[1][$i+6])) continue;
		  			else break;
		  		}
		  		$out = $content;
		  		return $out;
		  	}
	}
}
?>
