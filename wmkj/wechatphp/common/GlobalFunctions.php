<?php
include_once dirname(__FILE__).'/Define.php';
include_once dirname(__FILE__).'/ParamChecker.php';
include_once dirname(__FILE__).'/MiniLog.php';
/**
 * 
 * 参数检查方法
 * @param array $rules
 * @param array $args
 *示例1：
 *$rules = array(
 *   'appId' => 'int',  //int类型
 *	'owners' => 'array',  //array类型
 *   'instanceIds' => 'intArr',  //array类型，元素为int类型
 *   'instanceTypes' => 'strArr',  //array类型，元素为string类型
 *   'ips' => 'ipArr',//array类型，元素为ip
 *   'deviceId' => 'int/array',  //int类型或者array类型，最后转化为元素为idArr类型
 *   'deviceClass' => 'string/array',  //string类型或者array类型，最后转化为strArr类型
 *   'blocks' => array('type' => 'int', 'range' => '(5,10)'), //int类型，> 5，< 10
 *   'blocks2' => array('type' => 'int', 'range' => '[5,10]'), //int类型，>= 5，<= 10
 *	'percent' => array('type' => 'float', 'range' => '[5.1,10.9]'), //int类型，>= 5，<= 10
 *	'appName' => array('type' => 'string'),  //string类型
 *   'appName2' => array('type' => 'string', 'reg' => '[^0-9A-Za-z]', 'maxLen' => 10, 'minLen' => 1, 'nullable' => true),  //string类型，支持正则表达式
 *);
 *示例2：
 *$rules = array(
 *           'appId' => 'int',
 *           'appName' => array('type' => 'string', 'maxLen' => 255, 'minLen' => 1, 'nullable' => true),
 *           'isDistribute' => array('type' => 'int', 'enum' => array(0, 1), 'nullable' => true, 'emptyable' => true),
 *           'ownerUin' => array('type' => 'string', 'reg' => "^[0-9]{4,}$", 'nullable' => true),
 *           'owner' => array('type' => 'string', 'nullable' => true),
 *           'sets' => array('type' => 'strArr', 'nullable' => true)
 *       );
 *示例3：
 *参数格式：
 *“wsName”:” /cloudsns/app/app123456/app123456_cee/app123456_rs1”
 *"blocks":[{"ipName":"10.135.130.35","blockCnt ":20, alterBlockCnt:2},
 *{"ipName":"10.135.130.36","blockCnt ":20, alterBlockCnt:2}]
 *
 *对应的rules：
 *$rules = array( 'wsName' => 'string',
 *           'blocks' => array( 'type' => 'array', 
 *               'elem' => array( 'type' => 'object',
 *                   'items' => array(
 *                       'ipName' => 'ip', 
 *                       'blockCnt' => array('int', 'range' => '[1,+)'), 
 *                       'alterBlockCnt' => 'int',
 *                    ) 
 *               )
 *            )
 *       ); 
 *
 */
function checkParam($rules = array(), &$args) {
	return ParamChecker::getInstance()->checkParam($rules, $args);
}

define("DEBUG", "DEBUG");
define("INFO", "INFO");
define("ERROR", "ERROR");
define("STAT", "STAT");



/*
 * 默认打开所有的日志文件文件
 * ERROR,INFO,DEBUG日志级别分别对应的关闭标记文件为：NO_ERROR, NO_INFO, NO_DEBUG
 */
function isLogLevelOff($logLevel)
{
	$swithFile = ROOT_PATH . '/log/' . 'NO_' . $logLevel;
	if (file_exists($swithFile)){
		return true;
	}else {
		return false;
	}
}


/**
 * @author pacozhong
 * 日志函数的入口
 * @param string $confName 日志配置名
 * @param string $logLevel 级别
 * @param int $errorCode 错误码
 * @param string $logMessage 日志内容
 */
function ccdb_log($confName ,$logLevel, $errorCode, $logMessage = "no error msg")
{
	if (isLogLevelOff($logLevel)){
		return;
	}
	
	$st = debug_backtrace();

	$function = ''; //调用interface_log的函数名
	$file = '';     //调用interface_log的文件名
	$line = '';     //调用interface_log的行号
	foreach($st as $item) {
		if($file) {
			$function = $item['function'];
			break;
		}
		if($item['function'] == 'interface_log') {
			$file = $item['file'];
			$line = $item['line'];
		}
	}
	
	$function = $function ? $function : 'main';
	
	//为了缩短日志的输出，file只取最后一截文件名
	$file = explode("/", rtrim($file, '/'));
	$file = $file[count($file)-1];
	$prefix = "[$file][$function][$line][$logLevel][$errorCode] ";
	if($logLevel == INFO || $logLevel == STAT) {
		$prefix = "[$logLevel]" ;
	}
	$logMessage = genErrMsg($errorCode , $logMessage);
	$logFileName = $confName . "_" . strtolower($logLevel);
	MiniLog::instance(ROOT_PATH . "/log/")->log($logFileName, $prefix . $logMessage);
	if (isLogLevelOff("DEBUG") || $logLevel == "DEBUG"){
		return ;
	}else {
		MiniLog::instance(ROOT_PATH . "/log/")->log($confName . "_" . "debug", $prefix . $logMessage);
	}
}

/**
 * @author pacozhong
 * 接口层日志函数
 */
function interface_log($logLevel, $errorCode, $logMessage = "no error msg")
{
	ccdb_log('interface', $logLevel, $errorCode, $logMessage);
}

/**
 * @author pacozhong
 * matcher log
 */
function matcher_log($logLevel, $errorCode, $logMessage = "no error msg")
{
	ccdb_log('matcher', $logLevel, $errorCode, $logMessage);
}

function getIp()
{
	if (isset($_SERVER)){
		if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
			$realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		} else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
			$realip = $_SERVER["HTTP_CLIENT_IP"];
		} else {
			$realip = $_SERVER["REMOTE_ADDR"];
		}
	} else {
		if (getenv("HTTP_X_FORWARDED_FOR")){
			$realip = getenv("HTTP_X_FORWARDED_FOR");
		} else if (getenv("HTTP_CLIENT_IP")) {
			$realip = getenv("HTTP_CLIENT_IP");
		} else {
			$realip = getenv("REMOTE_ADDR");
		}
	}

	return $realip;
}



/**
 * @desc 封装curl的调用接口，post的请求方式
 */
function doCurlPostRequest($url, $requestString, $timeout = 5) {
	if($url == "" || $requestString == "" || $timeout <= 0){
		return false;
	}

    $con = curl_init((string)$url);
    curl_setopt($con, CURLOPT_HEADER, false);
    curl_setopt($con, CURLOPT_POSTFIELDS, $requestString);
    curl_setopt($con, CURLOPT_POST, true);
    curl_setopt($con, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($con, CURLOPT_TIMEOUT, (int)$timeout);

    return curl_exec($con);
}  

/**
 * @desc 封装curl的调用接口，get的请求方式
 */
function doCurlGetRequest($url, $data = array(), $timeout = 10) {
	if($url == "" || $timeout <= 0){
		return false;
	}
	if($data != array()) {
		$url = $url . '?' . http_build_query($data);	
	}
	
	$con = curl_init((string)$url);
	curl_setopt($con, CURLOPT_HEADER, false);
	curl_setopt($con, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($con, CURLOPT_TIMEOUT, (int)$timeout);

	return curl_exec($con);
}

function getCurrentTime() {
	date_default_timezone_set('PRC');
	$secondTime = time();
	return date('Y-m-d H:i:s', $secondTime);	
}



//获取当前时间，毫秒级别,如果startTime传入，则计算当前时间与startTime的时间差
function getMillisecond($startTime = false) {
	$endTime = microtime(true) * 1000;
		
	if($startTime !== false) {
		$consumed = $endTime - $startTime;
		return round($consumed);
	}
		
	return $endTime;
}


function rSortByTimeStamp($a, $b){
	$ret = strnatcmp($a['addTimeStamp'], $b['addTimeStamp']);
	if ($ret > 0){
		return -1;
	}
	if ($ret < 0){
		return 1;
	}
	return 0;
}

function rSortByName($domainListA, $domainListB){
	$ret = strcmp($domainListA['domainName'], $domainListB['domainName']);
	if ($ret > 0){
		return 1;
	}
	if ($ret < 0){
		return -1;
	}
	return 0;
}

//the end of php for wmkj
//author homker
