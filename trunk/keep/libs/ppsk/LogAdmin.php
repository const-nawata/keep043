<?php
class LogAdmin{
	public function putLogInfo( $message ){
		$sql_mess	= htmlspecialchars( $message,  ENT_QUOTES );

		global $gl_MysqliObj;
		$sql	= "INSERT INTO err_log ( level, user_id, tab_code, info ) VALUES ('".$_SESSION[ 'level' ]."', ".$_SESSION[ 'user_id' ].", '".$_SESSION[ 'tab_code' ]."', '$sql_mess' )";
		$result = $gl_MysqliObj->query( $sql );
	}
//______________________________________________________________________________

}
