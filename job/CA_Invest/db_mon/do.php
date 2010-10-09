<?php
global $CA_PATH; global $gl_MysqliObj;

include("../config.php");
$CA_PATH="../";

require_once $CA_PATH.'test/ut_main_const.php';
require_once $CA_PATH.'db_mon/funcs.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html  xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<title>DB mon</title>
<link rel="stylesheet" href="do.css" type="text/css" />
</head>
<body>
<div class="Ttl_head">DB state after Unit tests. Org code template (<span class="org_code_info"><? echo  "%"._UT_ORG_CODE."%";?></span>) / <span class="host_info"><? echo  "Host: ".$Host;?></span></div>




<?php
//$orgs_qnt = getQntRecsFromTable( 'ca_agendas' );
//$arr	= getDbTbls( $DBName );
//$arr	= getDbTblClmns( 'ca_agendas' );

//echo '<pre>'.print_r( $arr, true ).'</pre>';


$tables	= getDbTbls( $DBName );

echo "<table cellpadding='0' cellspacing='0'>";

foreach( $tables as $tbl_name ){
	$fields	= getDbTblClmns( $tbl_name );

	$is_org_code	= false;
	$n_no_org	= 0;
	foreach( $fields as $field ){
		if( 'ORG_CODE' == $field ){
			$is_org_code	= true;
			break;
		}else{ $n_no_org++; }
	}

	if( $is_org_code ){
		$recs	= getQntRecsFromTable( $tbl_name );

		echo
"<tr>".
	"<td class='sell_name'>$tbl_name</td>".
	"<td class='sell_info'>".
		"<span class='ut_info ".( ( $recs[ 'ut_rows' ] > 0 ) ? 'red' : '' )."'>".$recs[ 'ut_rows' ]."</span>".
		" / ".$recs[ 'g_rows' ]."&nbsp;&nbsp;record(s)".
	"</td>".
"</tr>";
	}
}
echo "</table>";

$log_arr	= getLogInfo();

$str_htm	=
"<table cellpadding='0' cellspacing='0'>".
"<tr><td colspan='3' class='log_emt_line_TD'>&nbsp;</td></tr>".
"<tr><td colspan='3' class='log_title_TD'>Errors</td></tr>";

foreach( $log_arr as $log_rec ){
	$str_htm	.=
	"<tr>".
		"<td class='log_d_t_TD'>".date( 'd-m-Y H:i:s', strtotime( $log_rec[ 'date_time' ] ) )."</td>".
		"<td class='log_level_TD'>".$log_rec[ 'level' ]."</td>".
		"<td class='log_content_TD'>".$log_rec[ 'content' ]."</td>".
	"</tr>";
}


$str_htm	.=
"</table>";

echo $str_htm;
?>
</body>
</html>
