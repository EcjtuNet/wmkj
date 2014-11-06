<?php
	error_reporting(0);
	function doCurlGetRequest($url) {
			$ch = curl_init();
        	curl_setopt($ch, CURLOPT_URL, $url);
        	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        	$json_str = curl_exec($ch);
        	curl_close($ch);
        	return $json_str;
}
	function GetAccessToken(){
		$AppID = 'wx7e8ff56227c59aa6';
		$AppSecret = '4c99eb5a094f1041d423d66207549f89';
		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$AppID.'&secret='.$AppSecret;
		$obj = doCurlGetRequest($url);
		$info = json_decode($obj,TURE);
    	$access_token = $info['access_token'];
   		return $access_token;
}
	function GetNickname($open_id){
	$access_token = GetAccessToken();
    $req = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$open_id.'&lang=zh_CN';
    $get_obj = doCurlGetRequest($req);
    $json_data = json_decode($get_obj,TURE);
    $name = $json_data['nickname'];
    return $name;
}
?>