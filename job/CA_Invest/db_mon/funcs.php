<?php
global $Host, $DBName, $User, $Pass;
@$gl_MysqliObj = new mysqli( $Host, $User, $Pass, $DBName );

require_once( $CA_PATH.'test/utils_ut.php' );

function getQntRecsFromTable( $table ){
	global $gl_MysqliObj;
    $sql	= "SELECT COUNT(*) AS `count` FROM `".$table."` WHERE `ORG_CODE` LIKE '%"._UT_ORG_CODE."%' LIMIT 1";
    $result = $gl_MysqliObj->query( $sql );

    $row = $g_row = array();
	if( !$result ){
		self::putLogInfo( utils_ut::getMysqlErrorInfo( $sql, utils_ut::getMysqlErrorInfo( $sql, 'getQntRecsFromTable function from funcs.php' ) ) );
	}else{
		$row	= $result->fetch_assoc();

		$sql	= "SELECT COUNT(*) AS `count`  FROM `".$table."` LIMIT 1";
		$result = $gl_MysqliObj->query( $sql );
		if( !$result ){
			self::putLogInfo( utils_ut::getMysqlErrorInfo( $sql, utils_ut::getMysqlErrorInfo( $sql, 'getQntRecsFromTable function from funcs.php' ) ) );
		}else{
			$g_row	= $result->fetch_assoc();
		}
	}
	$rows	= array( 'ut_rows' => $row[ 'count' ], 'g_rows' => $g_row[ 'count' ] );

    return $rows;
}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

function getDbTbls( $dbName ){
	global $gl_MysqliObj;

	$sql	=
"SHOW TABLES FROM $dbName WHERE ".
	"( Tables_in_$dbName != 'log' )";

	$arr	= utils_ut::execSelectQuery( $sql, utils_ut::getMysqlErrorInfo( $sql, utils_ut::getMysqlErrorInfo( $sql, 'getDbTbls function from funcs.php' ) ) );

	$res_arr	= array();
	foreach( $arr as $tbl_name ){
		$res_arr[]	= $tbl_name[ 'Tables_in_ca' ];
	}
	return $res_arr;
}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

function getDbTblClmns( $dbTableName ){
	global $gl_MysqliObj;

	$sql		= "SHOW COLUMNS FROM $dbTableName";
	$tbl_cols	= utils_ut::execSelectQuery( $sql, 'getDbTblClmns function from funcs.php' );
	$columns	= array();
	foreach( $tbl_cols as $col ){
		$columns[]	= $col[ 'Field' ];
	}
	return $columns;
}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

function getLogInfo(){
	$sql	=
"SELECT ".
	"`log`.`date_time` AS `date_time`, ".
	"`log`.`level` AS `level`, ".
	"`log`.`content` AS `content` ".
"FROM `log` ".
"ORDER BY `log`.`date_time` DESC LIMIT 10";

	$arr	= utils_ut::execSelectQuery( $sql, 'getLogInfo function from funcs.php' );
	return $arr;
}
//------------------------------------------------------------------------------------- _UT_ORG_CODE
?>