<?php
require_once dirname(__FILE__) . '/WeChatCallBack.php';
/**
 * 核心类，用来加载其他的由关键字触发的功能类。
 * @author homker
 * @version 1.0.0
 */

class WeChatCallBackKeyWord extends WeChatCallBack{
	public function init($postObj) {
		// 获取参数
		$this->_postObject = $postObj;
		if ($this->_postObject == false) {
			return false;
		}
		$this->_fromUserName = ( string ) trim ( $this->_postObject->FromUserName );//openID
		$this->_toUserName = ( string ) trim ( $this->_postObject->ToUserName );//公众号名字
		$this->_msgType = ( string ) trim ( $this->_postObject->MsgType );
		$this->_createTime = ( int ) trim ( $this->_postObject->CreateTime );
		$this->_msgId = ( int ) trim ( $this->_postObject->MsgId );
		$this->_time = time ();
		if(!($this->_fromUserName && $this->_toUserName && $this->_msgType)) {
			return false;
		}
		return true;
	}
	
	public function process(){
		try {
			$db = DbFactory::getInstance('DB');
			$sql = "SELECT * FROM keyword";
			//$sql = "insert into userinput (userId, input) values(\"" . $this->_fromUserName . "\", \"" . $this->_postObject->Content . "\")";
			interface_log(DEBUG, 0, "sql:" . $sql);			
			$rs = $db->query($sql);
			
		} catch (DB_Exception $e) {
			interface_log(ERROR, EC_DB_OP_EXCEPTION, "query db error" . $e->getMessage());
		}
			while($row = $db->fetch($rs)){
				if(strstr($this->_postObject->Content, $row['key']))
				{
					$model = $row['content'];
					interface_log(DEBUG, 0, "所属关键字".$rowp['key']."匹配模式".$row['content']);
					break;
				}
			}
			if (!isset($model)) {
				interface_log(lDEBUG, 0, "内容不属于关键字，内容是：".$this->_postObject->Content);
				return $this->makeHint("");
			}
			if(strstr($model, 'model')){
				$queryObj = $this->modelMatch($model);
				if(!is_object($queryobj)){
					if($StudentID = $this->getSID($this->_fromUserName)){
						$queryObj = $this ->modelMatch_xh($model);
					}else{
						interface_log('ERROR', 0,"未获取到学号。");
					}
				}

			}
			$queryObj->init($StudentID);
			$out = $queryObj->progress();
		return $this->makeHint ($out);
	}
	/*private function modelMatch($model){

		if($model == 'model_xhchenck'){
			require_once dirname(__FILE__) . '/model/xhcheck.php';
			return new xhchenck();
		}
		else if($model == 'model_sfchenck'){
			require_once dirname(__FILE__) . '/model/sfcheck.php';
			return new sfchenck();
		}
		else{
			return $model;
		}
	}*/
	private function modelMatch_xh($model)//需要匹配学号的模式在这个函数下匹配
	{
		if($model == 'model_KEBIAO'){
			require_once dirname(__FILE__) . '/model/classquery.php';
			return new classquery();
		}
		elseif($model == 'model_CJ') {
			require_once dirname(__FILE__) . '/model/cjquery.php';
			return new cjquery();
		}
		else{
			return $model;
		}
	}
	private function getSID($openID){
		try {
			$sql = "SELECT bd,xh FROM user WHERE wxh ='$openID'";
			//$sql = "insert into userinput (userId, input) values(\"" . $this->_fromUserName . "\", \"" . $this->_postObject->Content . "\")";
			interface_log(DEBUG, 0, "sql:" . $sql);			
			$rs = $db->query($sql);
			if($row = $db->fetch($rs)){
				if($row['bd'] == 1){
					return $StudentID = $row['xh'];
				}else{
					interface_log('DEBUG', 0 ,"学号未绑定。");
					$this->makeHint(NO_BD);
				}
			}else{
				interface_log('ERROR', EC_DB_OP_EXCEPTION ,"资源句柄解析出错。");
				return false;
			}
		} catch (DB_Exception $e) {
			interface_log(ERROR, EC_DB_OP_EXCEPTION, "query db error" . $e->getMessage());
		}
	}	
}
