<?php
/**
 * 核心基类，所有的回复的都必须加载的类。
 * @version 1.0.0
 * @author homker
 *
 */
//require_once dirname(dirname(__FILE__)) .'/common/Common.php';
require_once dirname(dirname(__FILE__)) .'/common/Define.php';
require_once dirname(dirname(__FILE__)) .'/common/GlobalFunctions.php';
require_once dirname(dirname(__FILE__)) .'/common/DbFactory.php';
require_once dirname(__FILE__) .'/PicArc.php';
require_once dirname(__FILE__) .'/model/rxl.php';
require_once dirname(__FILE__) .'/model/predis-0.8/autoload.php';

class WeChatCallBack {
	protected $_postObject;
	protected $_fromUserName;
	protected $_toUserName;
	protected $_createTime;
	protected $_msgType;
	protected $_msgId;
	protected $_event;
	protected $_EventKey;
	protected $_Recognition;
	protected $_time;
	
    public function getToUserName() {
    	return $this->_toUserName;
    }
    
    protected  function makeHint($hint) {
    	$resultStr = sprintf ( HINT_TPL, $this->_fromUserName, $this->_toUserName, $this->_time, 'text', $hint );
		return $resultStr;
    }
    /**
    *@desc 用来封装图文信息
    *@param (int) $hint (array) $hint = array( '1' =>array('title'=>$title ,'describle'=>$desc ,'picurl'=>$picurl ,'url'=>$url),'2' =>arry(……) );
    *@return (string) $resultStr
    */
    protected function makePic($hint){
    	$resultHead = sprintf( SUCC_TPL_head, $this->_fromUserName, $this->_toUserName, $this->_time,'news', count($hint) );
    	foreach($hint as $key => $value){
    		$resultBody = $resultBody . sprintf( SUCC_TPL_body, $value['title'], $value['describle'] , $value['picurl'], $value['url'] );
    	}
    	return $resultStr = $resultHead.$resultBody.SUCC_TPL_footer;
    }
     /**
    *@desc 用来封装音乐信息
    *@param 
    *@return (string) $resultStr
    */
    protected function makeMus($hint){
    	$resultStr = sprintf(MUSIC_TPL, $this->_fromUserName, $this->_toUserName, $this->_time, $hint['title'], $hint['describle'], $hint['musicurl'], $hint['HQurl'] );
    	return $resultStr;
    }
	
	public function init($postObj) {
		// 获取参数
		$this->_postObject = $postObj;
		if ($this->_postObject == false) {
			return false;
		}
		$this->_fromUserName = ( string ) trim ( $this->_postObject->FromUserName );//openID
		$this->_toUserName = ( string ) trim ( $this->_postObject->ToUserName );//公众号名字
		$this->_msgType = ( string ) trim ( $this->_postObject->MsgType );
		$this->_event = ( string ) trim ( $this->_postObject->Event);
		$this->_EventKey = ( string ) trim ( $this->_postObject->EventKey);
		$this->_createTime = ( int ) trim ( $this->_postObject->CreateTime );
		$this->_msgId = ( int ) trim ( $this->_postObject->MsgId );
		$this->_Recognition = ( string )trim( $this->_postObject->Recognition );
		$this->_time = time ();
		if(!($this->_fromUserName && $this->_toUserName && $this->_msgType)) {
			return false;
		}
		return true;
	}
	/*
	public function process($content) {
		if($content!=""){
			return $this->makeHint($content);
		}
		return $this->makeHint("没有匹配信息");
	}*/
	
	public function process(){

		$redis = new Predis\Client();

		if($this->_msgType == 'event'){
			interface_log(DEBUG, 0, "进入事件，事件是".$this->_event);
			if($this->_event == 'subscribe'){
				return $this->makeHint("啦啦啦，啦啦啦，我是缺爱的小新啊！看到你来了，小新才不哭了呢。日小新微信公众平台，微信号：rixinwx\n日小新“花椒助手”助你将最新成绩，课表等校园信息统统收入掌内！小新愿意成为大家萌萌哒小白，为你们打造贴心的“交大之家”。\nOK！亲爱的们，你可以回复一下关键词来获得你想要得内容！\n\n[微店]：有需求随意购哦~亲..........mua!!/亲亲\n\n[交大微电台]：聆听交大的另一个声音.........pia！！/害羞\n\n[新生指南]：只有你想不到的，没有我们不知道的..xu！让小新吹一下牛逼/阴险)\n\n[博约课堂]：汪立夏浅谈大学那一二三点事.........../鼓掌\n\n[新生专题]：小花椒，点它，你就赢了半个交大！/坏笑\n\n[小新快递]：交大最新消息尽在你的掌握之中....../可爱\n\n[跳蚤市场]：居家好男人女人的首选，你值得拥有！/酷\n\n[一卡通]：查询余额~食堂虽好，可不要贪吃哦！/憨笑\n\n[查课表]：绑定学号即可查询~做个爱学习的好孩子/奋斗\n\n[查成绩]：绑定学号即可查询~\n\n[日新社区]：畅言人生，爱情，梦想........\n（本条信息自动发送，如果小新有时间就会在线回复大家的留言哟~）");
			}elseif($this->_event == 'CLICK'){
				if($picObj = new PicArc()){
					interface_log(DEBUG , 0, "class works");
				}
				if($this->_EventKey == 'QGS'){
					$out = "投票结束，谢谢：）";
					return $this->makeHint($out);
				}

				if($this->_EventKey != 'KAOSHI'&&$this->_EventKey != 'CHENGJI'&&$this->_EventKey != 'YKT'&&$this->_EventKey != 'DIANTAI'&&$this->_EventKey != 'KEBIAO'){
					//$count = ($this->_EventKey=='KUAIDI')? 4 : 1;
					$count = 5;
					$arr = $picObj->getContent($this->_EventKey,$count);
					interface_log(DEBUG , 0, "数据库提取数据正常。");
					if(is_array($arr)){
						return $this->makePic($arr);
					}elseif(is_string($arr)){
						return $this->makeHint($arr);
					}
				}else{
					switch($this->_EventKey)
					{

						case 'DIANTAI': $model = "model_MUSIC" ; break;
						case 'KAOSHI' : $model = "model_KS"    ; break;
						case 'CHENGJI': $model = "model_CJ"    ; break;
						case 'YKT'    : $model = "model_YKT"   ; break;
						case 'KEBIAO' : $model = "model_KEBIAO"; break;
					}
					interface_log(DEBUG, 0, $model);
					$queryObj = $this->modelMatch($model);
					interface_log(DEBUG, 0, "class1:".var_export($queryObj,TRUE));
					if(!$queryObj){
						if($StudentID = $this->getSID($this->_fromUserName)){
							$queryObj = $this->modelMatch_xh($model);
						}else{
							interface_log('ERROR', 0,"未获取到学号。");
							return $this->makeHint(NO_BD);
						}
						interface_log(DEBUG,0,"学号是：".$StudentID);
					}
					interface_log(DEBUG, 0, "class2:".var_export($queryObj,TRUE));
					$queryObj->init($StudentID);
					$out = $queryObj->progress();
					interface_log(DEBUG, 0, "content:".var_export($out,TRUE));
					if(is_array($out)){
						return $this->makeMus($out);
					}else{	
						return $this->makeHint($out);
					}
				}
			}
		}if($this->_msgType == 'text'||$this->_msgType == 'voice'){
			if($this->_msgType == 'voice') $this->_postObject->Content = $this->_postObject->Recognition;
			interface_log(lDEBUG, 0, "messagetype：".$this->_msgType.",#绑定检查的回复是：".$this->_postObject->Recognition);
			$rb = new rxl();
			$rb->init($this->_postObject->Content);
			$model = $rb->progress($this->FromUserName);
			interface_log(lDEBUG, 0, "内容不属于关键字，内容是：".$this->_postObject->Content.",#绑定检查的回复是：".$model);
				if (!isset($model)) {
					$out = $this->bdcheck($this->_postObject->Content);
					interface_log(lDEBUG, 0, "内容不属于关键字，内容是：".$this->_postObject->Content.",#绑定检查的回复是：".$out);
					if($out != null ) return $this->makeHint($out);
					else return $this->makeHint($this->tuLing($this->_postObject->Content));
				}
				if(strstr($model, 'model')){
					$queryObj = $this->modelMatch($model);

					interface_log(DEBUG, 0, var_export($queryObj,TRUE));
					if(!$queryObj){
						if($StudentID = $this->getSID($this->_fromUserName)){
							interface_log(DEBUG, 0,"所属模块".$model);
							$queryObj = $this->modelMatch_xh($model);
							interface_log(DEBUG, 0,var_export($queryObj,TRUE));
						}
					}
					if(!is_object($queryobj)){
					interface_log(DEBUG, 0,"所属模块".$model);
						if($StudentID = $this->getSID($this->_fromUserName)){
							//interface_log(DEBUG, 0,"所属模块".$model);
							$queryObj = $this->modelMatch_xh($model);
							interface_log(DEBUG, 0,"所属模块".var_export($queryObj,TRUE));
						}else{
							interface_log('ERROR', 0,"未获取到学号。".NO_BD);
							return $this->makeHint(NO_BD);
						}
					}
					$queryObj->init($StudentID);
					$out = $queryObj->progress();
					if(is_array($out)){
						return $this->makeMus($out);
					}else{
						return $this->makeHint($out);
					}
				}else{
					return $this->makeHint($model);
				}
		}
	}
	
	private function modelMatch($model)
	{

		if($model == 'model_MUSIC'){
			require_once dirname(__FILE__) . '/model/musicquery.php';
			return new musicquery();
		}
		return false;
		//return $model;
	}
	
	private function modelMatch_xh($model)//需要匹配学号的模式在这个函数下匹配
	{
		if($model == 'model_KEBIAO'){
			require_once dirname(__FILE__) . '/model/classquery.php';
			return new classquery();
		}
		elseif($model == 'model_CJ') {
			require_once dirname(__FILE__) . '/model/scorequery.php';
			return new scorequery();
		}
		elseif($model == 'model_BUKAO'){
			require_once dirname(__FILE__) . '/model/bukaoquery.php';
			return new bukaoquery();
		}
		elseif($model == 'model_KS'){
			require_once dirname(__FILE__) . '/model/kaoshiquery.php';
			return new kaoshiquery();
		}
		elseif($model == 'model_MUSIC'){
			require_once dirname(__FILE__) .'/model/musicquery.php';
			return new musicquery();
		}
		elseif($model == 'model_YKT'){
            require_once dirname(__FILE__) .'/model/yktquery.php';
            return new yktquery();
        }
		elseif($model == 'model_GETUP'){
			require_once dirname(__FILE__) .'/model/getup.php';
            return new getup();
		}
		else{
			return $model;
		}
	}
	private function getSID($openID){
		try {
			$db = DbFactory::getInstance('WX');
			$sql = "SELECT bd,xh FROM user WHERE wxh ='$openID'";
			//$sql = "insert into userinput (userId, input) values(\"" . $this->_fromUserName . "\", \"" . $this->_postObject->Content . "\")";
			interface_log(DEBUG, 0, "sql:" . $sql);			
			$rs = $db->query($sql);	
			if($row = $db->fetch($rs)){
				interface_log(DEBUG, 0,"数据库读取正常，读取数据为：".var_export($row,TRUE));
				if($row['bd'] == 1){
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
	public function datacheck($keyword){
		$keyword = intval($keyword);
        if(($keyword < 20000000000000)||($keyword > 30000000000000)){
			return FALSE;
        }else{
			return TRUE;
		}                
     }
	private function tuLing($content)
	{
		$url = "http://www.tuling123.com/openapi/api";
		$array = array('key'=>'9e110e9087c945a8c6ccc31e802ac039','info'=>( string )$this->_postObject->Content);
		( array ) $tuling = json_decode(doCurlGetRequest($url,$array),true);
		interface_log(LDEBUG, EC_OK, "get:".var_export($array,true)."tuling 返回值：".var_export($tuling,true));
		if($tuling['code']==100000){
			return $tuling['text'];
		}
		
	}
	private function bdcheck($content){

	        if($this->_postObject->Content == '投票'||strstr($this->_postObject->Content,'电台')||strstr($this->_postObject->Content,'青歌赛')){
                        $out = "投票结束，谢谢：）";
                	return $out;
                }
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
