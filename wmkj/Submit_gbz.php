<?php
require_once dirname(__FILE__) . '/common/Common.php';
require_once dirname(__FILE__) . '/class/PicArc.php';
/**
*@desc 发布处理
*@version 0.0.1
*/
<<<<<<< HEAD
//var_dump($_POST);
if(@$_POST['submit']){
	if($_FILES['file']['error']=='0'){
		$filename = $_FILES['file']['name'];
		$name = explode('.',$filename);
		$filetype = $_FILES['file']['type'];
		$filesize = $_FILES['file']['size'];
		if($filetype=="audio/mpeg"||$filetype == "audio/x-ms-wma"||$filetype == "audio/wav"||$filetype== "audio/mp3"){
			if($filesize > 8388608){
				echo "The file is too large, please upload the audio smaller than 8M~!";
				include("./submit_gbz.html");
				exit(0);
			}
			$Time = time();
			$audioname = $Time.".".$name['1']; //!!!!!!!!!
			$uplodefile = "./mp3/".$audioname;
			if(move_uploaded_file($_FILES['file']['tmp_name'],$uplodefile)){
				$piurl = $arurl = "http://wx.ecjtu.net/wmkj/mp3/".$audioname;	
			}else{
				echo "wrong,sorry";
				var_dump($_FILES['file']['tmp_name']);
				var_dump($uplodefile);
				include("./submit_gbz.html");
				exit(0);
			}
		}else{
			echo "File format error, please use .mp3/.wma/.wav";
			
			exit(0);
		}
	}
	//var_dump($_FILES);
	//echo $_FILES['file']['error'];
	if(isset($_POST['title'])&&isset($_POST['title'])&&isset($piurl)&&isset($arurl)&&isset($_POST['colum'])){
	$arr = array('title'=>$_POST['title'],'descr'=>$_POST['descr'],'piurl'=>$piurl,'arurl'=>$arurl,'colum'=>$_POST['colum']);
	}
	
	$picarc = new PicArc();
//	var_dump($arr);
	$picarc->addContent($arr);
	echo "success:".$piurl;
	include("./submit_gbz.html");
}
=======
if(@$_POST['submit']){
	if(isset($_POST['title'])&&isset($_POST['title'])&&isset($_POST['piurl'])&&isset($_POST['arurl'])&&isset($_POST['colum'])){
	$arr = array('title'=>$_POST['title'],'descr'=>$_POST['descr'],'piurl'=>$_POST['piurl'],'arurl'=>$_POST['arurl'],'colum'=>$_POST['colum']);
	}
	
	$picarc = new PicArc();
	$picarc->addContent($arr);
	echo "success~!";
	include("./submit_gbz.html");
}


>>>>>>> 9da44a0e8cc1991dfb4b2b2b5e497102a2704fa8
?>
