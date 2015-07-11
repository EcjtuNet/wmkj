<?php
include_once dirname(__FILE__) . '/MysqliDb.php';
/**
 * 
 * Db单件类
 * @author pacozhong
 *
 */
class DbFactory {
    private static $db = array();

    public static function getInstance($dbKey = 'DB') {
        if (array_key_exists($dbKey, self::$db)) {
            return self::$db[$dbKey];
        } else {
            $newdb = new MysqliDb($dbKey);
            if ($newdb->connect()) {
                self::$db[$dbKey] = $newdb;
                return $newdb;
            } else {
                return false;
            }
        }
    }
}
?>