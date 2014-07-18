<?php
require_once dirname(dirname(dirname(__FILE__))) . '/common/Common.php';
require_once dirname(__FILE__) . '/AbstractQuery.php';
/**
*@desc 日小新音乐电台实验类
*@version 1.0.0
*@author homker
*/

class musicquery 
{	public function init(){
	}	
class musicquery extends AbstractQuery
{
	public function init($StudentID){
	 	$this->_StudentID = $StudentID; 
	}
	
	public function progress(){
		$colum = 8;
		try {
				$db = DbFactory::getInstance('DB');
				$sql = "SELECT * FROM picarc WHERE colum ="."'$colum'"." ORDER BY ID DESC LIMIT 0 , 1";
				interface_log(DEBUG, 0, "sql:" . $sql);			
				$rs = $db->query($sql);
				$arr = $db->fetch($rs);
			} catch (DB_Exception $e) {
				interface_log(ERROR, EC_DB_OP_EXCEPTION, "query db error" . $e->getMessage());
			}
			
		$title    = $arr['title'];
		$desc     = $arr['describle'];
		$musicurl = $arr['picurl'];
		$HQurl    = $arr['url']; 
		$out = array('title'=>$title ,'describle'=>$desc ,'musicurl'=>$musicurl ,'HQurl'=>$HQurl);
		return $out;
	}
}
?>
