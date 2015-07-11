<?php

define('EC_OK', 0);
define('EC_INVALID_INPUT', 1000);
define('EC_RECORD_NOT_EXIST', 1001);
define('EC_RECORD_EXIST', 1002);
define('EC_REFERENCE_EXIST', 1003);
define('EC_REFERENCE_NOT_EXIST', 1004);
define('EC_INVALID_PASSWORD', 1005);

define('EC_DB_OP_EXCEPTION', 1100);
define('EC_ALLOC_RESOURCE', 1101);


define('EC_ALREADY_FIGHT', 1201);
define('EC_MULTIPLE_FIGHT', 1202);
define('EC_NOT_THIS_USR_ORDER', 1203);
define('EC_NOT_ENOUGH_MONEY', 1204);
define('EC_STEP_ERROR', 1205);
define('EC_ERROR_MAGIC', 1206);
define('EC_ERROR_START_USR', 1207);
define('EC_CHIP_MONEY_NOT_IN_RANGE', 1208);
define('EC_NOT_ENOUGH_MAGIC', 1209);
define('EC_NOT_ENOUGH_BULLET', 1210);
define('EC_STEP_OPERATION_NOT_MATCH', 1211);
define('EC_COUNT_ERROR', 1212);
define('EC_USER_NOT_EXIST', 1213);
define('EC_FIGHT_NOT_EXIST', 1214);
define('EC_OTHER', 1500);


function genErrMsg($errCode, $errorMsg = "no error msg") {
	//错误信息
	$errMsg = array(
		EC_OK=>"return successfully!",
		EC_INVALID_INPUT=>"invalid input!",
		EC_RECORD_NOT_EXIST=>'record do not exist!',
		EC_RECORD_EXIST=>'record already exist!',
		EC_REFERENCE_EXIST=>'reference record exist!',
		EC_REFERENCE_NOT_EXIST=>'reference record do not exist!',
		EC_INVALID_PASSWORD=>'invalid password, no Permissions!',
	
		EC_DB_OP_EXCEPTION=>'DB operation exception!',
		EC_ALLOC_RESOURCE=>'alloc resource fail!',
	
		EC_ALREADY_FIGHT => 'user is already in fight',
		EC_MULTIPLE_FIGHT => 'user is in mutiple fight',
		EC_NOT_THIS_USR_ORDER => "not this user's order",
		EC_NOT_ENOUGH_MONEY => "not enough money",
		EC_STEP_ERROR => "step error!",
		EC_ERROR_MAGIC => 'error magic',
		EC_ERROR_START_USR => 'not this user to start game',
		EC_CHIP_MONEY_NOT_IN_RANGE => 'chip money not in range',
		EC_NOT_ENOUGH_MAGIC => 'not enough magic',
		EC_NOT_ENOUGH_BULLET => 'not enough bullet',
		EC_STEP_OPERATION_NOT_MATCH => 'step and operation not match',
		EC_COUNT_ERROR => 'count error, equal 0 or below 0',
		EC_OTHER=>'other error!',
	);
	
	if($errorMsg == "") {
		return $errMsg[$errCode];
	}
	
	return $errMsg[$errCode] . " | " . $errorMsg;
}

function genRetStr($errCode) {
	//错误信息
	$retStr = array(
			
			EC_ALREADY_FIGHT => '用户已经在游戏中',
			EC_MULTIPLE_FIGHT => '开启了多个游戏',
			EC_NOT_THIS_USR_ORDER => "not this user's order",
			EC_NOT_ENOUGH_MONEY => "not enough money",
			EC_STEP_ERROR => "step error!",
			EC_ERROR_MAGIC => 'error magic',
			EC_ERROR_START_USR => 'not this user to start game',
			EC_CHIP_MONEY_NOT_IN_RANGE => 'chip money not in range',
			EC_NOT_ENOUGH_MAGIC => 'not enough magic',
			EC_NOT_ENOUGH_BULLET => 'not enough bullet',
			EC_STEP_OPERATION_NOT_MATCH => 'step and operation not match',
			EC_COUNT_ERROR => 'count error, equal 0 or below 0',
			EC_OTHER=>'other error!',
	);

	
	$ret = $retStr[$errCode] || "内部错误";
	return $ret;
}


function genRetCode($mysqlRetCode)
{
	/*if ( $mysqlRetCode == 1062 ){
		return EC_RECORD_EXIST;
	}else if ( $mysqlRetCode == 1452 ){
		return EC_REFERENCE_NOT_EXIST;
	}else if ($mysqlRetCode == 1451){
		return EC_REFERENCE_EXIST;
	}else {*/
		return EC_DB_OP_EXCEPTION;
	//}
}

?>
