<?php
/**
*@desc 电台文件加载
*@version 0.0.1
*@author homker
*/
header('Content-type:text/html;charset=utf-8');
require_once dirname(dirname(dirname(__FILE__))) . '/wechatphp/common/Define.php';
require_once dirname(dirname(dirname(__FILE__))) . '/wechatphp/class/PicArc.php';
require_once dirname(dirname(dirname(__FILE__))) . '/wechatphp/class/model/predis-0.8/autoload.php';

$redis = new Predis\Client();
$resback = array();
if(isset($_GET['wechatID'])){
	$wechat = $_GET['wechatID'];
	if(strlen($wechat)==28){
		$content = getMP3("18");
		foreach($content as $value){
			if($redis->exists($value['ID'])){
				$zan = $redis->get($value['ID']);
			}else {
				$zan = 0;
			}
			$add = array("ID"=>$value['ID'],"title"=>$value['title'],"picurl"=>$value['picurl'],"arcurl"=>$value['url'],"zan"=>$zan);
			array_push($resback,$add);
		}
		//$res = array("content"=>$content,"zan"=>$zan);
		include("./song.php");
	}else{
		echo "青歌赛期间，小新微电台暂停服务。";
		exit(0);
	}
}

if(strlen($_POST['wechatID']) == 31){
		$wechats = $_POST['wechatID'];
		if($redis->exists($wechat)){
			echo $callback = json_encode(array("error"=>"exists"));
		}else{
			$wechat = substr($wechats, 0,28);
			$id = substr($wechats,28,31);
			//var_dump($id);
			if($id<200||$id>400){
				echo "青歌赛期间，小新微电台暂停服务。";
				exit(0);
			}else{
				$redis->set($wechat,$id);
				$redis->incr($id);
				$num = $redis->get($id);
				$ok = array("status"=>"ok","zan"=>$num);
				echo $ok = json_encode($ok);
				exit(0);
			}
		}
}else{
	echo "青歌赛期间，小新微电台暂停服务。";
	exit(0);
}
	

if(isset($_POST['ID'])){
	$ID = $_POST['ID'];
	$res = getMP3();
	foreach($res as $value ){
		if($value['ID'] == $ID ){
			$re = array(url => $value['picurl']);
			echo json_encode($re);
		}
	}
	
}else{
	$content = getMP3();
	echo "青歌赛期间，小新微电台暂停服务。";
	//include("player.php");
}
function getMP3($count){

	$audio = new PicArc();
	$result = $audio->getContent('DIANTAI', $count);
	//var_dump($result);
	return $result;
}





/////////////////////the end of php script
