<?php
//echo "hello word";
$startTime = microtime(true);
require_once dirname(__FILE__) .'/common/Define.php';
require_once dirname(__FILE__) .'/common/GlobalFunctions.php';

function checkSignature()
{
	$signature = $_GET["signature"];
	$timestamp = $_GET["timestamp"];
	$nonce = $_GET["nonce"];

	//interface_log(DEBUG,EC_OK,"some of ".var_export($_GET,TRUE));
	$token = "weixin_token";
	$tmpArr = array($token, $timestamp, $nonce);
	sort($tmpArr,SORT_STRING);
	$tmpStr = implode( $tmpArr );
	$tmpStr = sha1( $tmpStr );
	//interface_log(DEBUG,EC_OK,$tmpStr);	


	$token = WEIXIN_TOKEN;
	$tmpArr = array($token, $timestamp, $nonce);
	sort($tmpArr);
	$tmpStr = implode( $tmpArr );
	$tmpStr = sha1( $tmpStr );

	if( $tmpStr == $signature ){
		return true;
	}else{
		return false;
	}
}

if(checkSignature()) {
	if($_GET["echostr"]) {
		echo $_GET["echostr"];

		interface_log(DEBUG,EC_OK,$_GET["echostr"]);
	exit;

		exit(0);

	}
}/*
else {
	//恶意请求：获取来来源ip，并写日志
	$ip = getIp();
	interface_log(ERROR, EC_OTHER, '攻击者: ' . $ip);
	exit(0);
	
}*/

function exitErrorInput(){//由于错误的信息输入导致程序强行退出。
	
	interface_log(INFO, EC_OK, "*接口回复结束(interface  response  end)*");
	interface_log(INFO, EC_OK, "***************************************");
	interface_log(INFO, EC_OK, "");
	exit ( 0 );
}

function getWeChatObj() {
	require_once dirname(__FILE__) . '/class/WeChatCallBack.php';
	return new WeChatCallBack();
}



$postStr = file_get_contents ( "php://input" );

interface_log(INFO, EC_OK, "");
interface_log(INFO, EC_OK, "***************************************");
interface_log(INFO, EC_OK, "*接口回复开始(interface response start)*");
interface_log(INFO, EC_OK, 'request:' . $postStr);
interface_log(INFO, EC_OK, 'get:' . var_export($_GET, true));

if (empty ( $postStr )) {
	interface_log( ERROR, EC_OK, "error input!" );
	exitErrorInput();
}
// 获取参数
$postObj = simplexml_load_string ( $postStr, 'SimpleXMLElement', LIBXML_NOCDATA );
if(NULL == $postObj) {
	interface_log(ERROR, 0, "错误信息无法解析～！");	
	exit(0);
}
interface_log( DEBUG, EC_OK, '所得类型:'.$postObj->MsgType);
if($wechatObj = getWeChatObj ())
{
	$useTime = microtime(true) - $startTime;
	interface_log( DEBUG, EC_OK ,"类正常加载,耗时：".$useTime);
}
$ret = $wechatObj->init ( $postObj );
if (! $ret) {
	interface_log ( ERROR, EC_OK, "error input!" );
	exitErrorInput();
}
$retStr = $wechatObj->process ();
interface_log ( INFO, EC_OK, "response:" . $retStr );
echo $retStr;


interface_log(INFO, EC_OK, "*接口回复结束(interface  response  end)*");
interface_log(INFO, EC_OK, "***************************************");
interface_log(INFO, EC_OK, "");
$useTime = microtime(true) - $startTime;
interface_log ( INFO, EC_OK, "cost time:" . $useTime . " " . ($useTime > 4 ? "warning" : "") );

?>
