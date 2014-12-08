<?php
require_once dirname(dirname(dirname(__FILE__))) . '/wechatphp/common/Define.php';
require_once dirname(dirname(dirname(__FILE__))) . '/wechatphp/class/PicArc.php'; 
/**
*@desc 发布处理
*@version 0.0.1
*/
//var_dump($_POST);
//var_dump($_FILES['files']);
session_start();
if(isset($_POST['submit'])){
	//$uname = $_SESSION['access']['uName'];
	if(isset($_POST['title'])&&isset($_POST['desc'])&&isset($_POST['url'])&&isset($_POST['colum'])){
	$arr = array('title'=>$_POST['title'],'descr'=>$_POST['desc'],'piurl'=>$_POST['picurl'],'arurl'=>$_POST['url'],'colum'=>$_POST['colum'],'publishman'=>$uname);
	}
	
	$picarc = new PicArc();
//	var_dump($arr);
	$picarc->addContent($arr);
	$res = array("status"=>"200","message"=>"ok");
	echo json_encode($res);
	//echo "success:".$piurl;
	//include("./submit_gbz.html");
}
?>
