<?php
require_once dirname(dirname(dirname(__FILE__))) .'/common/Define.php';
require_once dirname(dirname(dirname(__FILE__))) .'/common/GlobalFunctions.php';
require_once dirname(dirname(dirname(__FILE__))) .'/common/DbFactory.php';
require_once dirname(__FILE__) . '/AbstractQuery.php';
require_once dirname(__FILE__) . '/predis-0.8/autoload.php';
//一卡通余额查询类
class yktquery extends AbstractQuery
{
	public function init($StudentID){
	 	$this->_StudentID = $StudentID;
		$this->_yktpw = $this->getYkt($this->_StudentID);
                         
	}
	
	public function progress(){	
		$sid = $this->_StudentID;
		$pw = $this->_yktpw;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://api.ecjtu.net/ykt.php?num=$sid&pw=$pw");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$a = curl_exec($ch);
		curl_close($ch);
		if($a!='false'){
			$a = json_decode($a, True);
			if($a['money']!=''){
				$money = $a['money'];
				$rank = $a['rank'];
				$tmp.="您的余额是:".$money."元\n"."击败了全校".$rank."的同学";
				return $out = $tmp;
			}else{
			
				return $out = "抱歉，程序出错了，请稍后重试。";
			}
		}else
		{	
			return $out ="你还没有绑定一卡通，输入cd加你的一卡通密码绑定\n（一般是你的学号后六位或者身份证号后六位)";
		}
	}
	private function getYkt($StudentID){
                try {
                        $db = DbFactory::getInstance('WX');
                        $sql = "SELECT * FROM user WHERE xh ='$StudentID'";
                      // interface_log(DEBUG, 0, "sql:" . $sql);
                        $rs = $db->query($sql);
                        if($row = $db->fetch($rs)){
                                interface_log(DEBUG, 0,"数据库读取正常，读取数据为：".var_export($row,true));
                                if(($row['yktpw']!='NULL')){
                                        return $row['yktpw'];
                                }else{
                                       // interface_log('DEBUG', 0 ,"未绑定一卡通号");
                                      //  $this->makeHint(NO_BD);
					return FALSE;
                                }
                        }else{
                               // interface_log('ERROR', EC_DB_OP_EXCEPTION ,"学号未绑定。");
                                return false;
                        }
                } catch (DB_Exception $e) {
                        interface_log(ERROR, EC_DB_OP_EXCEPTION, "query db error" . $e->getMessage());
                }
        }
	
	
}
?>
