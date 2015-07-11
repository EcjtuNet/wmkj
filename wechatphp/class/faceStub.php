<?php
require_once dirname(__FILE__) . '/../common/Common.php';

class faceStub {
	
	private static function requestToFace($interface, $data) {
		$data = array_merge($data, array('api_key' => API_KEY, 'api_secret' => API_SECRET));
		interface_log(DEBUG, 0, "url: " . FACE_URL . $interface . "\ndata:" . var_export($data, true) );
		$json = doCurlGetRequest(FACE_URL . $interface, $data, FACE_TIMEOUT);
		interface_log(DEBUG, 0, 'response:' . $json);
		$data = json_decode($json, true);
		if(!$data || $data['error_code']) {
			return false;
		} else {
			return $data;
		}
	}
	
	public static function createGroup($groupName) {
		$interface = 'group/create';
		$data = array(
				'group_name' => $groupName
				);
		return faceStub::requestToFace($interface, $data);
	}
	
	public static function detect($imageUrl) {
		$interface = 'detection/detect';
		$data = array(
				'url' => $imageUrl
				);
		return faceStub::requestToFace($interface, $data);
	}
	
	public static function search($faceId, $groupName, $count) {
		$interface = 'recognition/search';
		$data = array(
				'key_face_id' => $faceId,
				'group_name' => $groupName,
				'count' => $count
				);
		return faceStub::requestToFace($interface, $data);
	}
	
	public static function createPerson($personName, $faceId, $groupName) {
		$interface = 'person/create';
		$data = array(
				'person_name' => $personName,
				'face_id' => $faceId,
				'group_name' => $groupName 
				);
		return faceStub::requestToFace($interface, $data);
	}
	
	public static function addFaceToPerson($personName, $faceId) {
		$interface = 'person/add_face';
		$data = array(
				'person_name' => $personName,
				'face_id' => $faceId
				);
		return faceStub::requestToFace($interface, $data);
	}
	public static function removeFaceFromPerson($personName, $faceId) {
		$interface = 'person/remove_face';
		$data = array(
				'person_name' => $personName,
				'face_id' => $faceId
		);
		return faceStub::requestToFace($interface, $data);
	}
	public static function train($groupName, $type) {
		$interface = 'recognition/train';
		$data = array(
				'group_name' => $groupName,
				'type' => $type
				);
		return faceStub::requestToFace($interface, $data);
	}
	public static function getSession($sessionId){
		$interface = 'info/get_session';
		$data = array(
				'session_id' => $sessionId
				);
		return faceStub::requestToFace($interface, $data);
	}
	
	public static function getPersonInfo($personName) {
		$interface = 'person/get_info';
		$data = array(
				'person_name' => $personName
				);
		return faceStub::requestToFace($interface, $data);
	}
	
	public static function deletePerson($personName) {
		$interface = '/person/delete';
		$data = array(
				'person_name' => $personName
				);
		return faceStub::requestToFace($interface, $data);
	}
	
	public static function getPersonList(){
		$interface = '/info/get_person_list';
		$data = array();
		return faceStub::requestToFace($interface, $data);
	}
	
}
//$ret = faceStub::getSession('5be1b5c52220c1e18014e478506ed48a');

/*$ret = faceStub::train(GROUP_NAME, 'search');
$ret = faceStub::createGroup('test1');
var_dump($ret);*/
//echo phpinfo();
?>