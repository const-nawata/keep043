<?php
require_once("inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>Keep log info</title>


<link rel="stylesheet" href="show_log.css" type="text/css" />

</head>

<body style="font-size: 22px; font-family: sans-serif;">
<?php
function isAccess(){
	$auth_obj	= new Authentication();

	if( !$auth_obj->isGrantAccess( array( 'admin' ) ) ){
		throw new Exception( _EX."Access denied." );
	}
}
//--------------------------------------------------------------------------------------------------

function getLogInfo( $limit = 0 ){

	$lmt	= ( $limit != 0 ) ? ' LIMIT 0, '.$limit : '';

	$sql	= "SELECT 	id,
						DATE_FORMAT(date, '%d-%m-%Y' ) as date,
						TIME_FORMAT(time, '%H:%i' ) as time,
						level,
						user_id,
						tab_code,
						info
				FROM err_log ORDER BY `err_log`.`date` DESC, `err_log`.`time` DESC ".$lmt;	// , time
	$db_obj	= new PDbl();
	return $db_obj->execSelectQuery( $sql, 'getLogInfo in show_log.php' );
}
//--------------------------------------------------------------------------------------------------



$host		= "localhost";
$db_name	= 'keep';
$db_user	= 'root';
$user_pass	= '043';


try{
	//	isAccess();
	$db_conn	= new DbConnect( $host, $db_name, $db_user, $user_pass );
	$db_conn->doConnect();
}catch( Exception $e ){
	if( isset( $db_conn ) && $db_conn->mIsSuccess ){ $log_obj->putLogInfo( $e->getMessage() ); }else{ echo $e->getMessage(); }
	exit;
}


//LogAdmin::putLogInfo( 'Test message. Test message. Test message. Test message. Test message. Test message. Test message. Test message. Test message. Test message. Test message. Test message. Test message. Test message. Test message. ' );

$limit	= ( isset( $_GET[ 'limit' ] ) ) ? $_GET[ 'limit' ] : 0;

$log_info	= getLogInfo( $limit );

?>
<center>
<div class="ttl"><b>Keep log info</b>
<div>

</center>
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td class="hat_sell">id</td>
		<td class="hat_sell">Date</td>
		<td class="hat_sell">Time</td>
		<td class="hat_sell">Level</td>
		<td class="hat_sell">User Id</td>
		<td class="hat_sell">Tab Code</td>
		<td class="hat_sell">Log Info</td>
	</tr>
	<?php
	foreach( $log_info as $line ){
		?>

	<tr>
		<td class="info_sell"><? echo $line[ 'id' ]; ?></td>
		<td class="info_sell"><? echo $line[ 'date' ]; ?></td>
		<td class="info_sell"><? echo $line[ 'time' ]; ?></td>
		<td class="info_sell"><? echo $line[ 'level' ]; ?></td>
		<td class="info_sell"><? echo $line[ 'user_id' ]; ?></td>
		<td class="info_sell"><? echo $line[ 'tab_code' ]; ?></td>
		<td class="info_sell">
		<div class="info_cnt"><? echo $line[ 'info' ]; ?></div>
		</td>
	</tr>

	<?php
	}

	?>
</table>
	<?php







	//echo '<pre>';
	//print_r( $_GET );
	//echo '</pre>';





?>

</body>
</html>
