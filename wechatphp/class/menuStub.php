<?php
require_once dirname(dirname(__FILE__)) .'/common/GlobalDefine.php';
require_once dirname(dirname(__FILE__)) .'/common/GlobalFunctions.php';
require_once dirname(__FILE__) .'/tokenStub.php';

class menuStub {
	public static function reqMenu($interface, $data) {
		$token = tokenStub::getToken();
		//retry 3 times
		$retry = 3;
		while ($retry) {
			$retry --;
			if(false  === $token) {
				interface_log(DEBUG, EC_OTHER, "get token error!");
				return false;
			}
			
			$url = WX_API_URL . "$interface?access_token=" . $token;
			
			interface_log(DEBUG, 0, "req url:" . $url . "  req data:" . json_encode($data));
			$ret = doCurlPostRequest($url, $data);
			interface_log(DEBUG, 0, "response:" . $ret);
			
			$retData = json_decode($ret, true);
			if(!$retData || $retData['errcode']) {
				interface_log(DEBUG, EC_OTHER, "req create menu error");
				if($retData['errcode'] == 40014) {
					$token = tokenStub::getToken(true);
				}
			} else {
				return $retData;
			}
		}
		
		return false;
	}
	
	public static function create($data) {
		$ret = menuStub::reqMenu("menu/create", $data);
		if(false === $ret) {
			return false;
		}
		return true;
	}
	
	public static function get() {
		$ret = menuStub::reqMenu("menu/get", array());
		if(false === $ret) {
			return false;
		}
		return $ret;
	} 
	
	public static function delete(){
		$ret = menuStub::reqMenu("menu/delete", array());
		if(false === $ret) {
			return false;
		}
		return true;
	}
}