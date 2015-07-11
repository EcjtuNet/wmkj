<?php
require_once dirname(__FILE__) . '/../common/Common.php';

class tokenStub {
	public static function getToken($force = false) {
		try {
			$STO = new SingleTableOperation();
			$STO->setTableName("ctoken");
			if($force == false) {
				$ret = $STO->getObject();
				interface_log(DEBUG, 0, "token data get from ctoken: " . json_encode($ret));
				if(count($ret) == 1) {
					$token = $ret[0]['token'];
					$expire = $ret[0]['expire'];
					$addTimestamp = $ret[0]['addTimestamp'];
					$current = time();
					if($addTimestamp + $expire + 30 < $current) {
						return $token;
					}	
				}
			}
			
			$para = array(
				"grant_type" => "client_credential",
				"appid" => WX_API_APPID,
				"secret" => WX_API_APPSECRET
			);
			
			$url = WX_API_URL . "token";
			interface_log(DEBUG, 0, "url:" . $url . "  req data:" . json_encode($para));
			$ret = doCurlGetRequest($url, $para);
			interface_log(DEBUG, 0, "response data:" . $ret);
			
			$retData = json_decode($ret, true);
			if(!$retData || $retData['errcode']) {
				interface_log(ERROR, EC_OTHER, "requst wx to get token error");
				return false;
			}
			$token = $retData['access_token'];
			$expire = $retData['expires_in'];
			$STO->delObject();
			$STO->addObject(array('token' => $token, "expire" => $expire, "addTimestamp" => time()));
			
			return $token;
			
		} catch (DB_Exception $e) {
			interface_log(ERROR, EC_DB_OP_EXCEPTION, "operate ctoken error! msg:" . $e->getMessage());
			return false;
		}
		
		
	}
}