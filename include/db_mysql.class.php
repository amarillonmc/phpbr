<?php

if (! defined ( 'IN_GAME' )) {
	exit ( 'Access Denied' );
}

class dbstuff {
	var $querynum = 0;
	
	function connect($dbhost, $dbuser, $dbpw, $dbname = '', $pconnect = 0) {
		if ($pconnect) {
			if (! mysql_pconnect ( $dbhost, $dbuser, $dbpw )) {
				$this->halt ( 'Can not connect to MySQL server' );
			}
		} else {
			if (! mysql_connect ( $dbhost, $dbuser, $dbpw )) {
				$this->halt ( 'Can not connect to MySQL server' );
			}
		}
		
		if ($this->version () > '4.1') {
			global $charset, $dbcharset;
			if (! $dbcharset && in_array ( strtolower ( $charset ), array ('gbk', 'big5', 'utf-8' ) )) {
				$dbcharset = str_replace ( '-', '', $charset );
			}
			
			if ($dbcharset) {
				mysql_query ( "SET character_set_connection=$dbcharset, character_set_results=$dbcharset, character_set_client=$dbcharset" );
			}
			
			if ($this->version () > '5.0.1') {
				mysql_query ( "SET sql_mode=''" );
			}
		}
		
		if ($dbname) {
			mysql_select_db ( $dbname );
		}
	
	}
	
	function select_db($dbname) {
		return mysql_select_db ( $dbname );
	}
	
	function fetch_array($query, $result_type = MYSQL_ASSOC) {
		return mysql_fetch_array ( $query, $result_type );
	}
	
	function query($sql, $type = '') {
		$func = $type == 'UNBUFFERED' && function_exists ( 'mysql_unbuffered_query' ) ? 'mysql_unbuffered_query' : 'mysql_query';
		if (! ($query = $func ( $sql )) && $type != 'SILENT') {
			$this->halt ( 'MySQL Query Error', $sql );
		}
		$this->querynum ++;
		return $query;
	}
//	SELECT语句变化比较多，就不设置方法了

//	function select($dbname, $where = '', $fields = '*', $limit = '') {
//		$query = "SELECT {$fields} FROM {$dbname} ";
//		if (! empty ( $where )) {
//			$query .= "WHERE {$where} ";
//		}
//		if (! empty ( $limit )) {
//			$query .= "LIMIT {$limit}";
//		}
//		return $this->query ($query);
//	}
	
//	function delete($dbname, $where){
//		$query = "DELETE FROM {$dbname} WHERE {$where}";
//		return $this->query ($query);
//	}
	
	function array_insert($dbname, $data){ //根据$data的键和键值插入数据
		$query = "INSERT INTO {$dbname} ";
		$fieldlist = $valuelist = '';
		foreach ($data as $key => $value) {
			$fieldlist .= "{$key},";
			$valuelist .= "'{$value}',";
		}
		if(!empty($fieldlist) && !empty($valuelist)){
			$query .= '(' . substr($fieldlist, 0, -1) . ') VALUES (' . substr($valuelist, 0, -1) .')';
		}
		$this->query ($query);
		return $query;
	}
	
	function array_update($dbname, $data, $where){ //根据$data的键和键值更新数据
		$query = "UPDATE {$dbname} SET ";
		foreach ($data as $key => $value) {
			$query .= "{$key} = '{$value}',";
		}
		$query = substr($query, 0, -1) . " WHERE {$where}";
		$this->query ($query);
		return $query;
	}

	
/*	function select_fetch_array($dbname, $fields = '*', $where = '', $limit = '') { //返回二维数组
		$query = "SELECT {$fields} FROM {$dbname} ";
		if (! empty ( $where )) {
			$query .= "WHERE {$where} ";
		}
		if (! empty ( $limit )) {
			$query .= "LIMIT {$limit}";
		}
		$result = $this->query ($query);
		while($data = $this->fetch_array($result)){
			
		}
	}*/
	
	function affected_rows() {
		return mysql_affected_rows ();
	}
	
	function error() {
		return mysql_error ();
	}
	
	function errno() {
		return intval ( mysql_errno () );
	}
	
	function result($query, $row) {
		$query = mysql_result ( $query, $row );
		return $query;
	}
	
	function data_seek($query, $row) {
		return mysql_data_seek ( $query, $row );
	}
	function num_rows($query) {
		$query = mysql_num_rows ( $query );
		return $query;
	}
	
	function num_fields($query) {
		return mysql_num_fields ( $query );
	}
	
	function free_result($query) {
		return mysql_free_result ( $query );
	}
	
	function insert_id() {
		$id = mysql_insert_id ();
		return $id;
	}
	
	function fetch_row($query) {
		$query = mysql_fetch_row ( $query );
		return $query;
	}
	
	function fetch_fields($query) {
		return mysql_fetch_field ( $query );
	}
	
	function version() {
		return mysql_get_server_info ();
	}
	
	function close() {
		return mysql_close ();
	}
	
	function halt($message = '', $sql = '') {
		require_once GAME_ROOT . './include/db_mysql_error.inc.php';
	}
}

?>