<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>T1</title>
</head>

<body style="font-size: 14px; font-family: arial;">
<?php
global $gl_MysqliObj;
require_once( "inc.php" );

try{
	$db_conn	= new DbConnect( $host, $db_name, $db_user, $user_pass );
	$db_conn->doConnect();
}catch( Exception $e ){
	if( $db_conn->mIsSuccess ){ $log_obj->putLogInfo( $e->getMessage() ); }else{ echo $e->getMessage(); }
	exit;
}

$sql	= "SELECT `id`, `name` FROM `cities` WHERE `country_id` = ?";
if( $stmt = $gl_MysqliObj->prepare( $sql ) ){



	$all_lists	= array();


	for( $country_id = 1; $country_id < 5; $country_id++ ){
		$list	= array();
		$stmt->bind_param( "i", $country_id );
		$stmt->execute();
		$stmt->bind_result( $id, $name );
		while( $stmt->fetch() ){
			$list[] = array( 'id' => $id, 'name' => $name );
		}
		$all_lists[ $country_id ]	= $list;
	}
	$stmt->close();



	//REMARK: EXAMPLE for multi parameters SQL query.
	//$stmt = $mysqli->prepare("INSERT INTO CountryLanguage VALUES (?, ?, ?, ?)");
	//$stmt->bind_param('sssd', $code, $language, $official, $percent);


	echo '<pre>';
	print_r( $all_lists );
	echo '</pre>';


}


echo
'</body>
</html>';
