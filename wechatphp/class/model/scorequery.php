<?php
require_once dirname(dirname(dirname(__FILE__))) .'/common/Define.php';
require_once dirname(dirname(dirname(__FILE__))) .'/common/GlobalFunctions.php';
require_once dirname(dirname(dirname(__FILE__))) .'/common/DbFactory.php';
require_once dirname(__FILE__) . '/AbstractQuery.php';
/**
*@desc 成绩查询类
*@version 1.1.0
*@author homker
*/

class scorequery extends AbstractQuery
{
	protected $_output;
	public function init($studentID){
		$this->_StudentID = $studentID;
	}
	public function progress(){
		$curl_errno = $this->getScore();
		//$output = $this->_output;
		$output = $this->_output;//$this->getScoreFromPython();
		//return $out = $output;
		interface_log(DEBUG, 0, var_export($output,true));
		$total = 0;
		$tmp .= $bk;		
		$tmp .= $bk;		
		$tmp = '';
		$count = 0;
		if($curl_errno>0){
			return $out = "抱歉程序出了点故障\n但你可以回复 查成绩 再试一次\n或者回复 抽打 抽打一下程序员";
		}
		elseif(False){
			return $out = ("抱歉现在暂时没有你的成绩呢,可能是教务处还没有录入哦~\n但你可以回复 查成绩 再试一次\n或者回复 抽打 抽打一下程序员");
		}else{
				$tmp .="你本学期已公布的成绩如下：\n";
				$bk1 = '';
				$bk2 = '';
				foreach($output as $cj)
				{
					$cj = (array) $cj;
					interface_log(DEBUG, 0, var_export($cj,true));
					if($cj['Term']=='2015.2' || $cj['Term']=='2016.1') {								
						$tmp .= "☆ ".$cj['Course']."  ".$cj['Score']."\n";
						
						if($cj['Score']!='合格'&&$cj['Score']!='不合格')
						{
							$count++;
							$total += $this->changecj($cj['Score']); 
						}
						if(trim($cj['FirstScore'])!=''){
							$bk1 .= "☆ ".$cj['Course']."  ".$cj['FirstScore']."\n"; 
						}
						if(trim($cj['SecondScore'])!=''){
                            $bk2 .= "☆ ".$cj['Course']."  ".$cj['SecondScore']."\n";
                        }
					}
				}
				if($bk1!=''){
					$tmp .= "你的补考成绩是:\n";
                                        $tmp .= $bk1;
				}
				if($bk2!=''){
                                        $tmp .= "你的清考成绩是:\n";
                                        $tmp .= $bk2;
                                }

				if($count!=0)
				{	
					$tmp .= "你的平均分是: ".intval($total/$count)."(参考)" ;
				}
				return $out = $tmp;
		

			}
	}
	public function getScore()
	{
		interface_log(DEBUG, 0, "访问地址：api.ecjtu.net/score.php?view=wx&s=".$this->_StudentID);
		$url = "http://127.0.0.1/score.php?view=wx&s=".$this->_StudentID;
		$curl = curl_init(); 
		curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_URL, $url); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 		
		curl_setopt($curl, CURLOPT_TIMEOUT, 4);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Host:api.ecjtu.net"));
		$this->_output = json_decode(curl_exec($curl),True);
		$info = curl_getinfo($curl);
		interface_log(DEBUG,0,"所得内容：".$output."\n#总耗时：".$info['total_time']."#Dns查询耗时：".$info['namelookup_time']."#等待连接耗时：".$info['connect_time']."#传输前准备耗时：".$info['pretransfer_time']."#上下行速度：".$info['speed_upload']."/".$info['speed_download']."#重定向耗时：".$info['redirect_time']);
		$curl_errno = curl_errno($curl);
		curl_close($curl);
		return $curl_errno;
	}
	public function getScoreFromPython()
	{
		//$a = "python ./scoreforwx.py".$this->_StudentID."";
		$output = exec("python /home/data/www/wx_ecjtu_net/wechatphp/class/model/scoreforwx.py $this->_StudentID");
		interface_log(DEBUG, 0, var_export("python ./scoreforwx.py $this->_StudentID",true));
		return json_decode($output);
	}
	
	public function changecj($cj)
    {
		switch($cj)
		{
			case '优秀':
			return 90;break;
			case '良好':
	                return 80;break;
			case '中等':
                return 70;break;
			case '合格':
                return 60;break;
			case '及格':
                return 60;break;
			case '不合格':
                return 50;break;
			case '不及格':
                return 50;break;
			default : return $cj ;
		}
    }
}
?>
