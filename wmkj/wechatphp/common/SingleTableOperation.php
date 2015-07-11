<?php
include_once dirname(__FILE__).'/DbFactory.php';
/**
 * 对数据库单表的操作类 
 * @desc: 封装了对单表的增，删，改，查，取列等多种操作
 *
 * @author pacozhong
 * @version v1.0.0 
 * @package common
 */
class SingleTableOperation {
    private $_tableName;
    private $_db;
    
    /**
     * 基本类，提供增删改查
     * @author benzhan
     * @param string $tableName 表名
     * @param string/object $dbKey $dbKey，默认为'DB'
     */
    function __construct($tableName = '', $dbKey = 'DB') {
        $this->_tableName = strtolower($tableName);
        $this->_db = DbFactory::getInstance($dbKey);
    }
    /**
     * 设置tableName
     */
    function setTableName($tableName) {
    	$this->_tableName = strtolower($tableName);
    }
    /**
     * 读取数据
     * @author benzhan
     * @param array $args 参数列表，特殊参数前缀_
     */
    function getObject(array $args = array(), $or = 0) {
        $fetch = $args['_fetch'];
        $fetch || $fetch = 'getAll';
        
        $field = $args['_field'];
        $field || $field = "*";
        
        $tableName = $this->getTableName($args);
        if($or) {
       		$where = $args['_where'] ? $args['_where'] : '0'; 	
        } else {
        	$where = $args['_where'] ? $args['_where'] : '1';	
        }
        $sql = "SELECT $field FROM {$tableName} WHERE {$where} ";
        //构造条件部分
        $args = $this->_db->escape($args);
        foreach ($args as $key => $value) {
            if ($key[0] == '_') { continue; }
            
            if (is_array($value)) {
            	if($or) {
            		$sql .= "OR `{$key}` IN ('" . implode("','", $value) . "') ";	
            	}else {
            		$sql .= "AND `{$key}` IN ('" . implode("','", $value) . "') ";	
            	}
            } else {
               // $value && $sql .= "AND `{$key}` = '{$value}' ";
               if($or) {
               		$sql .= "OR `{$key}` = '{$value}' ";
               } else {
               		$sql .= "AND `{$key}` = '{$value}' ";	
               }
               
            }
        }
        
        //排序
        if ($args['_sortExpress']) {
            $sql .= "ORDER BY {$args['_sortExpress']} ";
            $sql .= $args['_sortDirection'] . ' ';
        }
        //标识是否锁行，注意的是也有可能锁表
        $args['_lockRow'] && $sql .= "FOR UPDATE ";
        
        return $this->_db->$fetch($sql, $args['_limit']);
    }
    
    /**
     * 读取数据
     * @author benzhan
     * @param array $args 参数列表，特殊参数前缀_
     */
    function getAll(array $args = array()) {      
        return $this->getObject($args);
    }
    
    
    /** 
     * 获取数据的行数
     * @author benzhan
     * @param array $args 参数列表，特殊参数前缀_
     */
    function getCount(array $args = array()) {
        $args['_field'] = 'COUNT(*)';
        return $this->getOneField($args);
    }

    /** 
     * 【兼容函数】获取一行一列，等同于getOne
     * @author benzhan
     * @param array $args 参数列表，特殊参数前缀_
     */
    function getOneField(array $args = array()) {
        return $this->getOne($args);
    }
    
    /** 
     * 获取一行一列
     * @author benzhan
     * @param array $args 参数列表，特殊参数前缀_
     */
    function getOne(array $args = array()) {
        $args['_fetch'] = 'getOne';
        $args['_limit'] = 1;
        return $this->getObject($args);
    }
    
    /** 
     * 获取一列
     * @author benzhan
     * @param array $args 参数列表，特殊参数前缀_
     */
    function getCol(array $args = array()) {
        $args['_fetch'] = 'getCol';
        return $this->getObject($args);
    }
    
    /**
     * 【兼容函数】读取一行数据，等同于getRow
     * @author benzhan
     * @param array $args 参数列表，特殊参数前缀_
     */
    function getOneObject(array $args = array()) {      
        return $this->getRow($args);
    }
    
    /**
     * 读取一行数据
     * @author benzhan
     * @param array $args 参数列表，特殊参数前缀_
     */
    function getRow(array $args = array()) {      
        $args['_limit'] = 1;
        $datas = $this->getObject($args);
        if (count($datas) == 1) {
        	return current($datas);
        }
        else{
        	//return false;
        	return array();
        }
    }
    
    function safeGetRow(array $args = array()){
    	try {
    		return $this->getRow($args);
    	}catch (DB_Exception $e){
    		return array();
    	}
    	
    	
    }
    
    /**
     * 如果不存在，则插入一行数据
     * @author benzhan
     * @param array $args 参数列表
     */
    function addObjectIfNoExist(array $args, array $where) {
        if ($this->getCount($where)) { return true; }
        return $this->addObject($args);
    }
    
    /**
     * INSERT一行数据
     * @author benzhan
     * @param array $args 参数列表
     */
    function addObject(array $args) {
        return $this->_addObject($args, 'add');
    }
   
    /**
     * REPLACE一行数据
     * @author benzhan
     * @param array $args 参数列表
     */
    function replaceObject(array $args) {
        return $this->_addObject($args, 'replace');
    }
    
    private function _addObject(array $args, $type = 'add') {
        $sql = ($type == 'add' ? 'INSERT INTO ' : 'REPLACE INTO ');
        $tableName = $this->getTableName($args);
        $args = $this->_db->escape($args);
        $sql .= "{$tableName} SET " . $this->genBackSql($args, ', ');
        //echo $sql . '<BR>';
        return $this->_db->update($sql);
    }
    
    /**
     * INSERT多行数据
     * @author benzhan
     * @param array $cols 列名 
     * @param array $args 参数列表
     */
    function addObjects(array $cols, array $args) {
        return $this->_addObjects($cols, $args, 'add');
    }
    
    /**
     * REPLACE多行数据
     * @author benzhan
     * @param array $cols 列名 
     * @param array $args 参数列表
     */
    function replaceObjects(array $cols, array $args) {
        return $this->_addObjects($cols, $args, 'replace');
    }
    
    /**
     * REPLACE多行数据
     * @author benzhan
     * @param array $args array(array(key => $value, ...))
     */
    function replaceObjects2(array $args) {
        if (!$args) { return; }
        $value = current($args);
        $cols = array_keys($value);
        if (!$cols) { return; }
    
        return $this->_addObjects($cols, $args, 'replace');
    }
    
    private function _addObjects(array $cols, array $args, $type = 'add') {
        $sql = ($type == 'add' ? 'INSERT ' : 'REPLACE ');
        $tableName = $this->getTableName($args);
        $args = $this->_db->escape($args);
        
        $sql .= "`{$tableName}` (`" . join("`,`", $cols) . "`) VALUES ";
        foreach ($args as $value) {
            $sql .= "('" . join("', '", $value) . "'),";
        }
        $sql = substr($sql, 0, -1);
        return $this->_db->update($sql);
    }
    
    /**
     * 获取最后自增的id
     * @author benzhan
     */
    function getInsertId() {
        return $this->_db->lastID();
    }
    
    
    /**
     * 修改一条数据
     * @author benzhan
     * @param array $args 更新的内容
     * @param array $where 更新的条件
     */
    function updateObject(array $args, array $where) {
        $args = $this->_db->escape($args);
        $where = $this->_db->escape($where);
        $tableName = $this->getTableName($args);
        
        $sql = "UPDATE `{$tableName}` SET " . $this->genBackSql($args, ', ') . ' WHERE 1 '. $this->genFrontSql($where, 'AND ');
        return $this->_db->update($sql);
    }
    
    /**
     * 删除数据
     * @author benzhan
     * @param array $where 更新的条件
     */
    function delObject(array $where) {
        $where = $this->_db->escape($where);
        $tableName = $this->getTableName($where);
        
        $sql = "DELETE FROM `{$tableName}` WHERE 1 " . $this->genFrontSql($where, 'AND ');
        return $this->_db->update($sql);
    }
    
    /**
     * 把key => value的数组转化为后置连接字符串 
     * @author benzhan
     * @param array $args
     * @param string $connect
     */
    function genBackSql(array $args, $connect = ', ') {
        $str = '';
        foreach ($args as $key => $value) {
        	if ($key[0] == '_') {
        		continue;
        	}
            if (is_array($value)) {
                $str .= "`$key` IN ('" . join("','", $value) . "') " . $connect; 
            } else {
            	$str .= "`$key` = '$value'" . $connect;	
            }
        }
        return substr($str, 0, -strlen($connect));
    }
    
    /**
     * 把key => value的数组转化为前置连接字符串 
     * @author benzhan
     * @param array $args
     * @param string $connect
     */
    function genFrontSql(array $args, $connect = 'AND ') {
        $str = '';
        foreach ($args as $key => $value) {
        	if ($key[0] == '_') {
        		continue;
        	}
            if (is_array($value)) {
                $str .= "$connect `$key` IN ('" . join("','", $value) . "') "; 
            } else {
                $str .= "$connect `$key` = '$value' "; 
            }
        }
        return $str;
    }
        
    private function getTableName(&$args) {
        if (isset($args['_tableName'])) {
            $tableName = strtolower($args['_tableName']);
        } else {
            $tableName = $this->_tableName;
        }
        
        return $tableName;
    }
    
	function affectedRowsCnt(){
        return $this->_db->affectedRows();
	}
	
	//返回最近一次查询的错误码
	function getErrorNum(){
        return $this->_db->getErrorNum();
	}
	
	function getErrorInfo(){
        return $this->_db->getErrorInfo();
	}
        
}

//end of script
