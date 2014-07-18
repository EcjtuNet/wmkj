<?php
require_once dirname(dirname(dirname(__FILE__))) .'/common/Define.php';
require_once dirname(dirname(dirname(__FILE__))) .'/common/GlobalFunctions.php';
require_once dirname(dirname(dirname(__FILE__))) .'/common/DbFactory.php';

/**
*@desc 语言解析器，将会把rxl 解析为php
*@version 1.0.0
*@author homker
*/

class rxl
{
	protected $_rule;
	protected $_keyWord;
	protected $_param;
	
	protected $_backObj;

	protected function __autoload($classname)
	{
		$class_file = strtolower($classname).".php";
		if(file_exists($class_file)){
			require_once($class_file);
		}
	}
	
	private function getRule()
	{
			try{
				$db  = DbFactory::getInstance('DB');
				$sql = "SELECT * FROM keyword WHERE weight>0 ORDER BY weight";
				$arr = $db->getAll($sql);
			}catch (DB_Exception $e) {
				interface_log(ERROR, EC_DB_OP_EXCEPTION, "query db error" . $e->getMessage().var_export($GLOBALS['DB'],true));
			}
		return $arr;
	}
	
	public function init($keyWord)
	{
		$this->_keyWord = $keyWord;
		$this->_rule = $this->getRule();
		interface_log(DEBUG, 0, "rule:" . $this->_rule);	
	}
	
	public function progress($fromUserName)
	{
			$result = $this->analyze($this->_rule);
			interface_log(DEBUG, 0, "analyze:" . $result);
			return $result;	
	}
	
	private function analyze($rule)
	{
		foreach ($rule as $key => $value) {
			$rule = explode(',', $value['keyW']);
			foreach ($rule as $param) {
				if(strstr($param, "(")){
					$param = $this->getParam($param);
					if(strstr($this->_keyWord, $param)) 
						return $value['content'];
				}
				elseif(strstr($param, "[")){
					$param = $this->getParam($param);
					if($this->_keyWord == $param) 
						return $value['content'];
				}
				elseif(strstr($param, "{")){
					$param = $this->getParam($param);
					return $value['content'];
				}
			}
		}
		//return $this->_keyWord;
	}
	private function getParam($param)
	{
		$param = substr($param, 1, -1);
		return $param;
	}

	private function bdchecks($content)
	{
		$flag = substr($content, 0, 2);
		$content = substr($content, 2);
		foreach ($GLOBALS['rule'] as $key => $value) {
			if($flag == $key){
				$length = $value['bit'];
				$rules = $value['rule'];
				if (!isset($content{$length})) {
					if($rules&&is_numeric($content)){
						return $out = $this->bdcheck($this->_keyWord);
					}
				}
			}
		}
		return false;
	}
	private function matchModel($model)
	{
		if($model == 'model_MUSIC'){
			return new musicquery();
		}
		//return $model;
	}
	
	private function modelMatch_xh($model)//需要匹配学号的模式在这个函数下匹配
	{
		if($model == 'model_KEBIAO'){
			require_once dirname(__FILE__) . '/classquery.php';
			return new classquery();
		}
		elseif($model == 'model_CJ') {
			require_once dirname(__FILE__) . '/scorequery.php';
			return new scorequery();
		}
		elseif($model == 'model_BUKAO'){
			require_once dirname(__FILE__) . '/bukaoquery.php';
			return new bukaoquery();
		
		}
		elseif($model == 'model_MUSIC'){
			require_once dirname(__FILE__) .'/musicquery.php';
			return new musicquery();
		}
		elseif($model == 'model_YKT'){
            require_once dirname(__FILE__) .'/yktquery.php';
            return new yktquery();
               }
		elseif($model == 'model_GETUP'){
			require_once dirname(__FILE__) .'/getup.php';
            return new getup();
		}

		else{
			return $model;
		}
	}
	public function getSID($openID){
		try {
			$db = DbFactory::getInstance('WX');
			$sql = "SELECT bd,xh FROM user WHERE wxh ='$openID'";
			//$sql = "insert into userinput (userId, input) values(\"" . $this->_fromUserName . "\", \"" . $this->_postObject->Content . "\")";
			interface_log(DEBUG, 0, "sql:" . $sql);			
			$rs = $db->query($sql);	
			if($row = $db->fetch($rs)){
				interface_log(DEBUG, 0,"数据库读取正常，读取数据为：".var_export($row,TRUE));
				if($row['bd']){
					return $StudentID = $row['xh'];
				}else{
					interface_log('DEBUG', 0 ,"学号未绑定。");
					$this->makeHint(NO_BD);
				}
			}else{
				interface_log('ERROR', EC_DB_OP_EXCEPTION ,"学号未绑定。");
				return false;
			}
		} catch (DB_Exception $e) {
			interface_log(ERROR, EC_DB_OP_EXCEPTION, "query db error" . $e->getMessage());
		}
	}

	private function bdcheck($content)
	{
		$aword = substr($content,0,2);
		$zword = substr($content,2,14);
		$wxh   =$this->_fromUserName;
		if((!$this->datacheck($zword)) AND (strtolower($aword) == 'xh')){	
			return $out = NO_STYLE_XH;
		}
		elseif(($this->datacheck($zword)) AND  (strtolower($aword) == 'xh'))
		{
			$xh 	= $zword;
			try{
				$db 	= DbFactory::getInstance('WX');
				$sqlWxh = "SELECT * FROM `user` WHERE wxh = '$wxh'";
				$rsWxh 	= $db->query($sqlWxh);
				$sqlXh 	= "SELECT * FROM `user` WHERE xh = '$xh'";
				$rsXh 	= $db->query($sqlXh);
				$rstWxh = $db->fetch($rsWxh);
				$rstXh   = $db->fetch($rsXh);
				if($rstWxh['bd'] == 1){
					return $out = "你的微信已经绑定了学号，直接回复 查成绩 可以查询你本学期的成绩了";
				}
				elseif($rstXh['bd'] == 1){
					return $out = "这个学号已经被绑定了~";
				}else{
					$delxh  = "DELETE FROM `user` WHERE xh = '$xh'";
					$delwxh = "DELETE FROM `user` WHERE wxh = '$wxh'";
					$sql 	= "INSERT INTO `user`(`wxh`, `xh`, `bd`) VALUES ('$wxh','$xh',0)";
					$db->delete($delxh);
					$db->delete($delwxh);
					$db->insert($sql);
					return $out = "成功，请回复 sf你的身份证号码后4位数 继续绑定,例如:sf1234";
				}
			}catch (DB_Exception $e) {
				interface_log(ERROR, EC_DB_OP_EXCEPTION, "query db error" . $e->getMessage());
			}
		}
		elseif((strlen($content) == 6||(strlen($content)==20)) AND  (strtolower($aword) == 'sf'))
		{
			try{
				$db 	= DbFactory::getInstance('WX');	
				$sql  	= $db->query("SELECT * FROM `user` WHERE wxh = '$wxh'");
				$result = $db->fetch($sql);
				interface_log(DEBUG, 0, "数据库所得内容：".$result);
				if(($result['xh'] > 0) AND ($result['bd'] == 0)){
				 	$xh 	= intval($result['xh']);
				 	$dbs	= DbFactory::getInstance('SCORE');
				 	$sql	= $dbs->query("SELECT * FROM `StudentInfo` WHERE StudentID='$xh'");	
				 	$rs 	= $dbs->fetch($sql);
				 	$idcard = $rs['IDCard'];
			 	 	$idh4 	= substr("$idcard",-4); 
				 	$keywordh4 =  strtoupper(substr("$zword",-4));
				 	if($idh4 == $keywordh4){
						$sql = "UPDATE `user` SET `bd`= 1 WHERE wxh = '$wxh' ";
                		$db->update($sql);
						return $out = "绑定成功啦!现在可以直接回复 查成绩 查询了哟";
					}else{
						return $out = "验证失败，身份证号与学号不匹配，或你的微信号还没有绑定学号\n回复 xh你的学号 绑定，例如：xh20122010010000";
					}
				}
				elseif($result['bd'] == 1){
					return $out = "你的微信已经绑定了学号，不能重复绑定，回复 查成绩 可直接查询你本学期的成绩";
				}else{
					return $out = "你还没有绑定学号，请回复 xh你的学号 ，例如：xh20122010010000\n绑定后,你可以通过输入“查成绩”来查询你本学期的成绩";
				}
			}catch (DB_Exception $e) {
				interface_log(ERROR, EC_DB_OP_EXCEPTION, "query db error" . $e->getMessage());
			}
		}
	    elseif((strlen($content) == 8) AND (strtolower($aword) == 'cd')){
            $yword = substr($content,2,6);
            if(strlen($yword)==6){
            	try{
                    $db     = DbFactory::getInstance('WX');
                    $sql    = $db->query("SELECT * FROM `user` WHERE wxh = '$wxh'");
                    $result = $db->fetch($sql);
                    interface_log(DEBUG, 0, "数据库所得内容：".$result);
                    if(($result['bd'] == 1) AND ($result['yktpw']===NULL ))
		    {
			$a = exec("python /home/data/www/api_ecjtu_net/paykt.py '$result[xh]' '$yword'");
			if($a=='false'){
				return $out = "密码不对哦，请重试";
			}
			else
			{	
                    		$sql    = "UPDATE `user` SET `yktpw` = '$yword' WHERE wxh = '$wxh'";
                    		interface_log(DEBUG, 0,"sql语句为："."UPDATE `user` SET `yktpw` = '$yword' WHERE wxh = '$wxh'");
                    		$db->update($sql);
                    		return $out = "绑定成功啦!可以查一卡通余额了哦";
			}
                    }
		    elseif(($result['bd'] == 1) AND (strlen($result['yktpw'])==6))
   	       	    {
				return $out = "你已经绑定过一卡通了";
		    }
		    else{
                     	return $out = "绑定一卡通失败，密码不正确\n或者你的微信号还没有绑定学号\n回复 xh你的学号 绑定，例如：xh20122010010000";
                    }
                }catch (DB_Exception $e) {
                    interface_log(ERROR, EC_DB_OP_EXCEPTION, "query db error" . $e->getMessage());
                }
            }else{
              return $out = "小新觉得一卡通的密码应该是数字哦~！";
            }
        }
	}	
}
?>
