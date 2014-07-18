<?php
require_once dirname(__FILE__).'/DbException.php';
require_once dirname(__FILE__).'/GlobalFunctions.php';
require_once dirname(__FILE__) .'/Define.php';
/**
 * mysql类型数据库的访问类 
 *
 * @author homker 
 * @version v1.0.1 
 * @package common
 */

class MysqliDb
{
	const DB_FETCH_ASSOC    = MYSQLI_ASSOC;					//以关联(key->value)的方式获取数据库表的结果
    const DB_FETCH_NUM	    = MYSQLI_NUM;					//以数组的方式获取数据库表的结果
    const DB_FETCH_BOTH     = MYSQLI_BOTH;					//包含两种方式
    const DB_FETCH_DEFAULT  = self::DB_FETCH_ASSOC;
    

    private $_autoCommitTime = 0;
    protected $_conn;											
    protected $_fecthMode;
    

    /**
     * MySQLi构造函数
     *
     * @param array $dbInfo 数据库配置信息
     * @param 返回的数据格式 $fetchMode
     */
     public function __construct($dbKey, $fetchMode = self::DB_FETCH_ASSOC) {
        $this->_dbKey = $GLOBALS['DB'][$dbKey];//getConf('ROUTE.'.$dbKey);
        $this->_fecthMode = $fetchMode;
    }
	
	/**
	 * 连接数据库
	 * @return boolean
	 */
	public function connect() {
		$dbHost = $this->_dbKey ["HOST"];
		$dbName = $this->_dbKey ["DBNAME"];
		$dbUser = $this->_dbKey ["USER"];
		$dbPass = $this->_dbKey ["PASSWD"];
		$dbPort = (int)$this->_dbKey ["PORT"];
		//$this->_conn = mysqli_connect ( $dbHost, $dbUser, $dbPass, $dbName, $dbPort );
		
		$this->_conn = mysqli_init();
		if (! $this->_conn) {
			//throw new DB_Exception ( 'connect to db fail: '.$dbHost.':'.$dbPort.'  '.$dbUser.':'.$dbPass.'  '.$dbName );
			throw new DB_Exception ( 'mysqli_init fail!');
			return false;
		}
		
		if (!mysqli_real_connect($this->_conn, $dbHost, $dbUser, $dbPass, $dbName, $dbPort, NULL, MYSQLI_CLIENT_FOUND_ROWS)){
			throw new DB_Exception ( 'connect to db fail: '.$dbHost.':'.$dbPort.'  '.$dbUser.':'.$dbPass.'  '.$dbName );
			return false;
		}
		
		
		$sql = "SET NAMES utf8";
		$this->update ( $sql );
		return true;
	}
	
	/**
	 * 关闭数据库连接
	 *
	 * 一般不需要调用此方法
	 */
	public function close() {
		if (is_object ( $this->_conn )) {
			mysqli_close ( $this->_conn );
		}
	}

    /**
     * 执行一个SQL查询
     *
     * 本函数仅限于执行SELECT类型的SQL语句
     *
     * @param string $sql SQL查询语句
     * @param mixed $limit 整型或者字符串类型，如10|10,10
     * @param boolean $quick 是否快速查询
     * @return resource 返回查询结果资源句柄
     */
    public function query($sql, $limit = null, $quick = false) {
    	interface_log(DEBUG, 0, $sql);
        if ($limit != null) {
            if (!preg_match('/^\s*SHOW/i', $sql) && !preg_match('/FOR UPDATE\s*$/i', $sql) && !preg_match('/LOCK IN SHARE MODE\s*$/i', $sql)) {
                $sql = $sql . " LIMIT " . $limit;
            }
        }
        
        if (!$this->_conn || !$this->ping($this->_conn)) {
        	if($this->_autoCommitTime){
        		throw new DB_Exception('auto commit time is not zero when reconnect to db');
        	}
        	else{
        		$this->connect();
        	}
        }

        $startTime = getMillisecond();
        $qrs = mysqli_query($this->_conn, $sql, $quick ? MYSQLI_USE_RESULT : MYSQLI_STORE_RESULT);
        if (!$qrs) {
            throw new DB_Exception('查询失败:' . mysqli_error($this->_conn));
        } else {
        	interface_log(DEBUG, EC_OK, "excute time:" . getMillisecond($startTime) . "(ms) SQL[$sql]");
            return $qrs;
        }
    }
    /**
     * 执行一个SQL删除
     *
     * 本函数仅限于执行DELETE类型的SQL语句
     * @param string $sql SQL语句
     * @return resource 返回执行结果
     */
    public function delete($sql)
    {
    	interface_log(DEBUG,0, $sql);
    	if (!$this->_conn || !$this->ping($this->_conn)) {
        	if($this->_autoCommitTime){
        		throw new DB_Exception('auto commit time is not zero when reconnect to db');
        	}
        	else{
        		$this->connect();
        	}
        }

        $startTime = getMillisecond();
        $qrs = mysqli_query($this->_conn, $sql);
        if (!$qrs) {
            throw new DB_Exception('删除失败:' . mysqli_error($this->_conn));
        } else {
        	interface_log(DEBUG, EC_OK, "耗时:" . getMillisecond($startTime) . "(ms) SQL[$sql]");
            return true;
        }
    	
    }

    /**
     * 获取结果集
     *
     * @param resource $rs 查询结果资源句柄
     * @param const $fetchMode 返回的数据格式
     * @return array 返回数据集每一行，并将$rs指针下移
     */
    public function fetch($rs, $fetchMode = self::DB_FETCH_DEFAULT) {
        $fields = mysqli_fetch_fields($rs);
    	$values = mysqli_fetch_array($rs, $fetchMode);
    	if ($values) {
	        foreach ($fields as $field) {
	            switch ($field->type) {
	                case MYSQLI_TYPE_TINY:
	                case MYSQLI_TYPE_SHORT:
	                case MYSQLI_TYPE_INT24:
	                case MYSQLI_TYPE_LONG:
	                	 if($field->type == MYSQLI_TYPE_TINY && $field->length == 1) {
							$values[$field->name] = (boolean) $values[$field->name];
	                	 } else {
	                    	$values[$field->name] = (int) $values[$field->name];
	                	 }
					break;
	                case MYSQLI_TYPE_DECIMAL:
	                case MYSQLI_TYPE_FLOAT:
	                case MYSQLI_TYPE_DOUBLE:
	                case MYSQLI_TYPE_LONGLONG:
	                    $values[$field->name] = (float) $values[$field->name];
	                break;
	            }
        	}
    	}
	
    	return $values;
    }

    /**
     * 执行一个SQL更新
     *
     * 本方法仅限数据库UPDATE操作
     *
     * @param string $sql 数据库更新SQL语句
     * @return boolean
     */
    public function update($sql) {
    	interface_log(INFO, EC_OK, "SQL[$sql]");
        if (!$this->_conn || !$this->ping($this->_conn)) {
        	if($this->_autoCommitTime){
        		throw new DB_Exception('auto commit time is not zero when reconnect to db');
        	}
        	else{
        		$this->connect();
        	}
        }
        
        $startTime = getMillisecond(); 
        $urs = mysqli_query($this->_conn, $sql);
        if (!$urs) {
            throw new DB_Exception('更新失败:' . mysqli_error($this->_conn));
        } else {
        	interface_log(INFO, EC_OK, "excute time:" . getMillisecond($startTime) . "(ms) SQL[$sql]");
            return $urs;
        }
    }
    /**
     * 执行一个SQL插入
     *
     * 本方法仅限数据库INSERT操作
     *
     * @param string $sql 数据库插入SQL语句
     * @return boolean
     */
    public function insert($sql) {
    	interface_log(INFO, EC_OK, "SQL[$sql]");
        if (!$this->_conn || !$this->ping($this->_conn)) {
        	if($this->_autoCommitTime){
        		throw new DB_Exception('auto commit time is not zero when reconnect to db');
        	}
        	else{
        		$this->connect();
        	}
        }
        
        $startTime = getMillisecond(); 
        $urs = mysqli_query($this->_conn, $sql);
        if (!$urs) {
            throw new DB_Exception('插入失败:' . mysqli_error($this->_conn));
        } else {
        	interface_log(INFO, EC_OK, "excute time:" . getMillisecond($startTime) . "(ms) SQL[$sql]");
            return true;
        }
    }

    /**
     * 返回SQL语句执行结果集中的第一行第一列数据
     *
     * @param string $sql 需要执行的SQL语句
     * @return mixed 查询结果
     */
    public function getOne($sql) {
        if (!$rs = $this->query($sql, 1, true)) {
            return false;
        }
        $row = $this->fetch($rs, self::DB_FETCH_NUM);
        $this->free($rs);
        return $row[0];
    }

    /**
     * 返回SQL语句执行结果集中的第一列数据
     *
     * @param string $sql 需要执行的SQL语句
     * @param mixed $limit 整型或者字符串类型，如10|10,10
     * @return array 结果集数组
     */
    public function getCol($sql, $limit = null) {
        if (!$rs = $this->query($sql, $limit, true)) {
            return false;
        }
        $result = array();
        while(($rows = $this->fetch($rs, self::DB_FETCH_NUM)) != null) {
            $result[] = $rows[0];
        }
        $this->free($rs);
        return $result;
    }

    /**
     * 返回SQL语句执行结果中的第一行数据
     *
     * @param string $sql 需要执行的SQL语句
     * @param const $fetchMode 返回的数据格式
     * @return array 结果集数组
     */
    public function getRow($sql, $fetchMode = self::DB_FETCH_DEFAULT) {
        if (!$rs = $this->query($sql, 1, true)) {
            return false;
        }
        $row = $this->fetch($rs, $fetchMode);
        $this->free($rs);
        return $row;
    }

    /**
     * 返回SQL语句执行结果中的所有行数据
     *
     * @param string $sql 需要执行的SQL语句
     * @param mixed $limit 整型或者字符串类型，如10|10,10
     * @param const $fetchMode 返回的数据格式
     * @return array 结果集二维数组
     */
    public function getAll($sql, $limit = null, $fetchMode = self::DB_FETCH_DEFAULT) {
        if (!$rs = $this->query($sql, $limit, true)) {
            return false;
        }
        $allRows = array();
        while(($row = $this->fetch($rs, $fetchMode)) != null) {
            $allRows[] = $row;
        }
        $this->free($rs);
        return $allRows;
    }

    /**
     * 设置是否开启事务(是否自动提交)
     *
     * 当设置为false的时候,即开启事务处理模式,表类型应该为INNODB
     *
     * @param boolean $mode
     * @return boolean
     */
    public function autoCommit($mode = false) {
   	 	if (!$this->_conn || !$this->ping($this->_conn)) {
        	if($this->_autoCommitTime){
        		throw new DB_Exception('auto commit cnt is not zero when reconnect to db');
        	}
        	else{
        		$this->connect();
        	}
        }
        
        if ($mode) {
            //如果为true，则说明要提交
            if($this->_autoCommitTime)
            {
            	throw new DB_Exception('auto commit cnt is not zero when set autocommit to true');
            	return false;
            }
        } else {
            //如果为false，则说明要一起commit，并且会积累 
            $this->_autoCommitTime++;
        }
        return mysqli_autocommit($this->_conn, $mode);
    }

    /**
     * 直接提交执行的SQL
     *
     * 当开启事务处理后,要手动提交执行的SQL语句
     *
     * @return boolean
     */
    private function commit($mode = true) {
        $result = mysqli_commit($this->_conn);
        //mysql的实现是手工提交后，并不会复原自动提交的功能
        mysqli_autocommit($this->_conn, $mode);
        return $result;
    }
    
    /**
     * 尝试提交执行的SQL【当有多个autoCommit时，仅提交最后一次！】
     *
     * 当开启事务处理后,要手动提交执行的SQL语句
     *
     * @return boolean
     */
    public function tryCommit($mode = true) {
        $this->_autoCommitTime--;
        //最后一次commit才会提交
        if ($this->_autoCommitTime <= 0) {
        	$this->_autoCommitTime = 0;
            return $this->commit($mode);
        } else {
        	return true;
        }
    }

    /**
     * 回滚
     *
     * 当开启事务处理后,有需要的时候进行回滚
     *
     * @return boolean
     */
    public function rollback() {
        return mysqli_rollback($this->_conn);
    }

    /**
     * 返回最近一次查询返回的结果集条数
     *
     * @return int
     */
    public function rows($rs) {
        return mysqli_num_rows($rs);
    }
    
    /**
     * 返回最近一次更新的结果条数
     * 
     * @return int
     */
    public function affectedRows() {
        return mysqli_affected_rows($this->_conn);
    }

    /**
     * 返回最近一次插入语句的自增长字段的值
     *
     * @return int
     */
    public function lastID() {
        return mysqli_insert_id($this->_conn);
    }

    /**
     * 释放当前查询结果资源句柄
     *
     */
    public function free($rs) {
        if ($rs) {
            return mysqli_free_result($rs);
        }
    }
    
    public function ping($conn) {
        return mysqli_ping($conn);
    }

    /**
     * 转义需要插入或者更新的字段值
     *
     * 在所有查询和更新的字段变量都需要调用此方法处理数据
     *
     * @param mixed $str 需要处理的变量
     * @return mixed 返回转义后的结果
     */
    public function escape($str) {
        if (is_array($str)) {
            foreach ($str as $key => $value) {
                $str[$key] = $this->escape($value);
            }
        } else {
            return addslashes($str);
        }
        return $str;
    }
    
    public function unescape($str) {
        if (is_array($str)) {
            foreach ($str as $key => $value) {
                $str[$key] = $this->unescape($value);
            }
        } else {
            return stripcslashes($str);
        }
        return $str;
    }
    /**
     * 析构函数，暂时不需要做什么处理
     *
     */
    public function __destruct() {
    }
    
    
    /**
     * 返回最近一次查询的错误码
     * 
     * @return int
     */
    public function getErrorNum() {
        return mysqli_errno($this->_conn);
    }
    
	public function getErrorInfo() {
        return mysqli_error($this->_conn);
    }
}

//end of script
