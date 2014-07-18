<?php

/**
* 查询功能类使用的抽象基类
*@version 1.0.0
*@author homker
*/
abstract class AbstractQuery
{
	//所有使用的变量
	protected $_StudentID;
  	protected $_ClassID;
  	protected $_WeChatID;
	protected $_yktnum;
  	
  	//返回消息的变量
  	protected  $_content;
  	protected  $_contents;
  	
	//使用的函数
	abstract public function init($StudentID);
	/**
	* 以字符串的形式返回查询结果。
	* @version 1.0.0
	* @return (string)$_content
	*/
	abstract public function progress();
}

?>
