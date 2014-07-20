<?php
require_once dirname(__FILE__) . '/common/Common.php';
require_once dirname(__FILE__) . '/class/PicArc.php';
/**
*@desc 发布处理
*@version 0.0.1
*/
//var_dump($_POST);
if(@$_POST['name']=="关键词管理"){
	if(isset($_POST['content'])){
		//var_dump($_POST['content']);
		$keyW = substr($_POST['content']['0'],0,-1);
		$model = $_POST['content']['1'];
		$weight = $_POST['content']['2'];
		$insertarr = array('keyW'=>$keyW,'model'=>$model,'weight'=>$weight);
		$key = new PicArc();
		$key->addKeyW($insertarr);
		$arr = array('status'=>"success");
		echo json_encode($arr);
		$arr = array();
	}
}
if(@$_POST['name']=="栏目内容管理"){
if(isset($_POST['content'])){
		//var_dump($_POST['content']);
		$title = $_POST['content']['0'];
		$desc = $_POST['content']['1'];
		$picurl = $_POST['content']['2'];
		$url = $_POST['content']['3'];
		$colum = $_POST['content']['4'];
		$publishman = $_POST['content']['5'];
		$insertarr = array('title'=>$title,'descr'=>$desc,'piurl'=>$picurl,'arurl'=>$url,'colum'=>$colum,'publishman'=>$publishman);
		$key = new PicArc();
		$key->addContent($insertarr);
		$arr = array('status'=>"success");
		echo json_encode($arr);
		$arr = array();
	}
	
}
if(@$_POST['submit']){
	if(isset($_POST['title'])&&isset($_POST['title'])&&isset($_POST['piurl'])&&isset($_POST['arurl'])&&isset($_POST['colum'])){
	$arr = array('title'=>$_POST['title'],'descr'=>$_POST['descr'],'piurl'=>$_POST['piurl'],'arurl'=>$_POST['arurl'],'colum'=>$_POST['colum'],'publishman'=>"飞扬之声");
	}
	
	$picarc = new PicArc();
	$picarc->addContent($arr);
	echo "success~!";
	include("./submit.html");
}


?>
