<?php
abstract class AbstractInterface {
  
  // 所有消息都有的字段
  protected $_toUserName;
  protected $_fromUserName;
  protected $_createTime;
  protected $_msgType;
  protected $_msgId;
  
  //文本消息字段
  protected $_content;
  
  //图片消息字段
  protected $_picUrl;
  
  //地理位置消息字段
  protected $_location_x;
  protected $_location_y;
  protected $_scale;
  protected $_label;
  
  //链接消息字段
  protected $_title;
  protected $_description;
  protected $_url;
  
  //推送消息字段，包括关注、取消关注和自定义菜单消息
  protected $_event;
  protected $_eventKey;
  
  //整个消息对应的XML对象
  protected $_xmlObj;
  
  // 返回消息字段
  protected  $_retValue = 0;
  protected  $_retMsg = "";
  protected  $_data = array();
  //需要给到用户的提示文本
  protected  $_responseText = "";
  
  public function getResponseText(){ 
    return $this->_responseText;
  }
    
  public function verifyCommonInput(&$xmlObj) {
    //在此时解析用户消息XML，并初始化类成员变量
    interface_log(DEBUG, 0, "verifyCommonInput!");
    if(null == $xmlObj) {
      $this->_retValue = EC_INVALID_INPUT;
      $this->_retMsg = "xml obj error!";
      return false;
    }
    $this->_xmlObj = $xmlObj;
    $this->_toUserName = (string)$xmlObj->ToUserName;
    $this->_fromUserName = (string)$xmlObj->FromUserName;
    $this->_createTime = (int)$xmlObj->CreateTime;
    $this->_msgType = (string)$xmlObj->MsgType;
    $this->_msgId = (string)$xmlObj->MsgId;
    
    
    if($this->_msgType == 'event') {
      $this->_event = (string)$xmlObj->Event;
      $this->_eventKey = (string)$xmlObj->EventKey;
    }else if($this->_msgType == 'text'){
      $this->_content = (string)$xmlObj->Content;
    }else if($this->_msgType == 'image') {
      $this->_picUrl = (string)$xmlObj->PicUrl;
    }else if($this->_msgType == 'location') {
      $this->_location_x = (float)$xmlObj->Location_X;
      $this->_location_y = (float)$xmlObj->Location_Y;
      $this->_label = (string)$xmlObj->Label;
      $this->_scale = (float)$xmlObj->Scale;
    }else if($this->_msgType == 'link') {
      $this->_title = (string)$xmlObj->Title;
      $this->_description = (string)$xmlObj->Description;
      $this->_url = (string)$xmlObj->Url;
    }
    //调用子类的函数
    return $this->verifyInput($xmlObj);
    
  }
  
  
  /**
   * 
   * 加载需要用到的对象
   */
  abstract public function initialize();
  /**
   * 
   * 输入校验
   * @param array $args 输入参数
   */
  abstract public function verifyInput(&$args);
  
  /**
   * 设置数据 
   */
  abstract public  function prepareData();
  
  /**
   * 
   * 请求处理
   */
  abstract public function process();
  
  //封装返回信息
  public function renderOutput() {
    $ret = array(
        "timestamp" => time(), 
        "retVal" => $this->_retValue, 
        "retMsg" => genErrMsg($this->_retValue , $this->_retMsg),
        "retStr" => genRetStr($this->_retValue),
        "retData" => $this->_data
      );
    interface_log(DEBUG, 0, "ret:" . json_encode($ret));
    return $ret;
  }
}

?>