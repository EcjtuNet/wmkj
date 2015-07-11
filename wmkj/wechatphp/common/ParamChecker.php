<?php

/**
 * 
 * 参数检查工厂类
 * @author pacozhong
 *
 */
class ParamChecker {
	private static $param;
	
	public static function getInstance () {
		if (null == self::$param) {
		include_once dirname(__FILE__) . '/Param.php';
			self::$param = new Param();
		}
		
		return self::$param;
	}
}

?>
