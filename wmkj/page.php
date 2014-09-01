<?php
/**
*@desc 页面呈现
*@version 1.0.2
*@author homker
*/
require_once dirname(dirname(__FILE__)) . '/wechatphp/common/Define.php';
require_once dirname(dirname(__FILE__)) . '/wechatphp/class/PicArc.php';
session_start();
if(isset($_SESSION['access'])){
	$page = new PicArc();
	$bdcount  = $page->getCount();
	$keyW     = $page->getKeyW();
	$access   = $_SESSION['access']['access'];
	$cloum    = $_SESSION['access']['cloum'];
	$usename  = $_SESSION['access']['uName'];
	$content  = getContent($page, $access, $cloum);
	//var_dump($cloum);
	if(isset($_SESSION['error'])) echo "<message style='display:none'>".$_SESSION['error']."</message>";
	include_once("wx.php");
}else{
	header("Location: http://wx.ecjtu.net/wmkj/index.php");
	exit(0);
}
function getContent($obj, $access, $cloum)
{
	switch($access){
		case '10': $cloum = "*";
		case '7' : $cloum = $cloum;
		case '5' : $cloum = $cloum;
	}
	$res = $obj->getContent($cloum, "10");
	return $res;
}




//////////////the end of php
//////////////power by homker
