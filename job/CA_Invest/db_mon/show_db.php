<?php

define('_MIN_TABLE_RECS', 40);
global $CA_PATH;
//global $sql_string1, $sql_string2;
include("../config.php");
$CA_PATH="../";

require_once $CA_PATH.'test/ut_main_const.php';
require_once($CA_PATH."classes/bl/utils_bl.php");
require_once $CA_PATH.'test/user/user_constants.php';

function getQntRecsFromTable($table){
    global $CA_PATH; include($CA_PATH."variables_DB.php");
    $sql_string1 = "select COUNT(*) as count  from $table  where $field_org_code like '%"._UT_ORG_CODE."%'";
    $result = mysql_query($sql_string1);
    $ut_rows = mysql_fetch_assoc($result);
    $ut_rows = $ut_rows['count'];

    $sql_string2 = "select COUNT(*) as count  from $table";
    $result = mysql_query($sql_string2);
    $g_rows = mysql_fetch_assoc($result);
    $g_rows = $g_rows['count'];

    $rows =  array ('ut_rows'=>$ut_rows, 'g_rows'=>$g_rows);


    return $rows;
}
//---------------------------------------------------------------------------------------------------------------------------------------------------

function getDbTableColumns($dbTableName){
//	global $CA_PATH; include($CA_PATH."variables_DB.php");
	$sql = "SHOW COLUMNS FROM $dbTableName";
	$tbl_cols = utils_bl::executeMySqlSelectQuery($sql);
	$columns = array();
	foreach ($tbl_cols as $col) {
		$columns[] = $col['Field'];
	}
	return $columns;
}
//---------------------------------------------------------------------------------------------------------------------------------------------------

function isOrgCodeColumnExists($dbTableName){
	global $CA_PATH; include($CA_PATH."variables_DB.php");
	$columns = getDbTableColumns($dbTableName);
	$is_exists = false;
	foreach ($columns as $col_name) {
		if ($col_name == $field_org_code){
			$is_exists = true;
			break;
		}
	}

	return $is_exists;
}
//---------------------------------------------------------------------------------------------------------------------------------------------------

function getDbTables($dbName){
	global $CA_PATH; include($CA_PATH."variables_DB.php");
	$sql = "show tables from $dbName
						where	(Tables_in_$dbName != '$tableSmsAccounts') and
								(Tables_in_$dbName != '$tableSmsPayments') and
								(Tables_in_$dbName != '$tableClientsLogin') and
								(Tables_in_$dbName != 'CA_MAIL_QUEUE') and
								(Tables_in_$dbName != 'CA_MAIL_QUEUE_SEQ') and
								(Tables_in_$dbName != '$tableOrgPermissions') and
								(Tables_in_$dbName != '$tableOrgPermissionsTemp') and
								(Tables_in_$dbName != 'CA_TEST') and

								(Tables_in_$dbName != '$tableTracking') and
								(Tables_in_$dbName != '$tableCategories') and
								(Tables_in_$dbName != '$tableTargets') and
								(Tables_in_$dbName != '$tableClientCategories') and
								(Tables_in_$dbName != '$tableClientTargets') and
								(Tables_in_$dbName != '$tableClientTracking') and
								(Tables_in_$dbName != '$tableProperties') and

								(Tables_in_$dbName != '$tableStatuses') and
								(Tables_in_$dbName != '$tableAgendasSystem') and
								(Tables_in_$dbName != '$tableLoggedIn') and
								(Tables_in_$dbName != '$tableLanguages') and
								(Tables_in_$dbName != '$tablePromptsConst') and

								(Tables_in_$dbName != '$tablePromptDescr') and
								(Tables_in_$dbName != '$tablePaymentSettings') and
								(Tables_in_$dbName != '$tablePaymentTranzactions') and
								(Tables_in_$dbName != '$tableOwner') and
								(Tables_in_$dbName != '$tableDealers') and
								(Tables_in_$dbName != '$tableDiscounts') and
								(Tables_in_$dbName != '$tableCountry') and
								(Tables_in_$dbName != '$tableFlexFields') and
								(Tables_in_$dbName != '$tableGreeds') and
								(Tables_in_$dbName != '$tableQwSteps') and
								(Tables_in_$dbName != '$tableCacheLog') and
								(Tables_in_$dbName != '$tableCurrency') and
								(Tables_in_$dbName != '$tableAnswerTypes') and
								(Tables_in_$dbName != '$tableQuestionnaireActTypes') and
								(Tables_in_$dbName != 'log4php_log') and
								(Tables_in_$dbName != '$tableSegments')";


//(Tables_in_$dbName != '$tableLangCases') and

	$db_tbls = utils_bl::executeMySqlSelectQuery($sql);
	$tables = array();
	foreach ($db_tbls as $tbl) {
		$tbl_name = &$tbl['Tables_in_ca_ref'];
		if (isOrgCodeColumnExists($tbl_name)){
			$tables[] = $tbl_name;
		}else{echo "DB table $tbl_name has no Org code column.<br>";}
	}
	return $tables;
}
//---------------------------------------------------------------------------------------------------------------------------------------------------

include($CA_PATH."variables_DB.php");
mysql_connect($Host,$User,$Pass) OR DIE(_CANNOT_CONNECT_MYSQL);
mysql_select_db($DBName) or die(_CANNOT_CONNECT_BASE);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html  xmlns="http://www.w3.org/1999/xhtml" >

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="show_db.css" />
	<title>Show DB</title>
</head>

<body>
<div class="Ttl_head">
	DB state. Org code template (<span class="org_code_info"><? echo  "%"._UT_ORG_CODE."%";?></span>) / <span class="host_info"><? echo  "Host: ".$Host;?></span>
</div>
<table cellpadding="0" cellspacing="0" width="100%">
<?php
$tables = getDbTables($DBName);
$qnt_tables = $qnt_show_tables = count($tables);
$emp_qnt = 0;
if($qnt_tables < _MIN_TABLE_RECS){
	$emp_qnt = _MIN_TABLE_RECS - $qnt_tables;
	$qnt_show_tables = _MIN_TABLE_RECS;
}
$qnt_show_tables++;
$log_height = $qnt_show_tables * 15 - 10;
		$sql_string = "select * from $tableCacheLog";
		$cache_log_info = utils_bl::executeMySqlSelectQuery($sql_string);
?>
	<tr><td colspan="4" class="qnt_tables">Quantity tables which have <?echo $field_org_code;?> field were found: <b>&nbsp;&nbsp;<?echo $qnt_tables;?></b></td></tr>

	<tr>
		<td class="sell_title columnBorder">Table Name</td>
		<td class="sell_title columnBorder">UT rec(s)</td>
		<td class="sell_title">Total rec(s)</td>
		<td class="log_info_td" rowspan="<?echo $qnt_show_tables;?>">
			<div class="logContent" style="height: <?echo $log_height;?>px;">
				<table cellpadding="0" cellspacing="0" width="100%">
				    <tr><td colspan="5" class="logHead">Cache Log Info</td></tr>
				    <tr>
				        <td class="sell_name"><?echo $cacheLogF_Id;?></td>
				        <td class="sell_name"><?echo $cacheLogF_LastUpdtDate;?></td>
				        <td class="sell_name"><?echo "Reset date";?></td>
				        <td class="sell_name"><?echo $cacheLogF_IsTest;?></td>
				        <td class="sell_name"><?echo $cacheLogF_NAttempts;?></td>
				    </tr>
<?php
		foreach ($cache_log_info as $info_item){
?>
				    <tr>
				        <td class="sell_info"><?echo $info_item["$cacheLogF_Id"];?></td>
				        <td class="sell_info"><?echo utils_bl::GetFormDate($info_item["$cacheLogF_LastUpdtDate"]);?></td>
				        <td class="sell_info"><?echo utils_bl::GetFormDate($info_item["$cacheLogF_TestDate"]);?></td>
				        <td class="sell_info"><?echo $info_item["$cacheLogF_IsTest"];?></td>
				        <td class="sell_info"><?echo $info_item["$cacheLogF_NAttempts"];?></td>
				    </tr>
				    <tr><td colspan="5" class="infoLogTitle">Processing messages</td></tr>
				    <tr> <td colspan="5" class="infoLog"><pre><? print_r($info_item["$cacheLogF_ErrorsInfo"]); ?></pre></td></tr>
<?php
		}
?>
				</table>
			</div>
		</td>
	</tr>
<?php


$grad = false;  $is_set_sell = true;
foreach ($tables as $tbl_name) {
	$qnt_recs  = getQntRecsFromTable($tbl_name);

	$line_color = ($grad) ? '#DDFFDD' : '#CFFFCF';
	$style_ut = ($qnt_recs['ut_rows'] > 0) ? "style='color: #FF0000; font-weight: bold;'" : '';
?>
	<tr style="background-color: <? echo $line_color;?>">
		<td class="sell_name columnBorder"><?echo  $tbl_name;?> </td>
		<td class="sell_info columnBorder" <?echo $style_ut;?>><?echo $qnt_recs['ut_rows'];?></td>
		<td class="sell_info"><?echo $qnt_recs['g_rows'];?></td>
	</tr>
<?php
	$grad = !$grad;
}

for ($i = 0; $i < $emp_qnt; $i++){
	$line_color = ($grad) ? '#DDFFDD' : '#CFFFCF';
?>
	<tr><td colspan="3" class="sell_name" style="background-color: <? echo $line_color;?>">&nbsp;</td></tr>
<?
	$grad = !$grad;
}
//				Check for unnassinged permissions
$sql_string = "select $organizationF_OrgId from $tableOrganisation";
$all_orgs = utils_bl::executeMySqlSelectQuery($sql_string);

$sql_string = "select $orgPermissionsF_Id, $orgPermissionsF_OrgId  from $tableOrgPermissions";
$all_perms = utils_bl::executeMySqlSelectQuery($sql_string);

$undef_perm = array();
 foreach ($all_perms as &$perm){
     $perm_org_id = &$perm["$orgPermissionsF_OrgId"];
     $is_unass = true;
     foreach ($all_orgs as &$org){
         $org_id = &$org["$organizationF_OrgId"];
         if ($org_id == $perm_org_id){$is_unass = false; break;}
     }
     ($is_unass) ? $undef_perm[] = $perm:'';
 }
$qnt_unass = count($undef_perm);
if ($qnt_unass > 0){
?>
    <tr><td colspan="4">&nbsp;</td></tr>
    <tr><td colspan="4"class="sell_name red"><? echo "There are unassigned records in ".$tableOrgPermissions." table ($qnt_unass):";?> </td></tr>
<?php
	foreach ($undef_perm as &$perm_item){
?>
    <tr>
	    <td class="gen_info"><?echo "Rec id: ".$perm_item["$orgPermissionsF_Id"];?> </td>
	    <td colspan="3" class="gen_info"><?echo "Org id: ".$perm_item["$orgPermissionsF_OrgId"];?></td>
    </tr>
<?php
	}
}


//				Check for unnassinged clients
$sql_string = "select $tableClientsLogin.$clientsLoginF_Id as $clientsLoginF_Id, $tableClients.$field_org_code as $field_org_code
                          from $tableClientsLogin
                          left join $tableClients on $tableClients.$clientsF_Id=$tableClientsLogin.$clientsLoginF_Id
                          order by $tableClientsLogin.$clientsLoginF_Id";

$arr = utils_bl::executeMySqlSelectQuery($sql_string);

$undef_clnts = array ();
foreach ($arr as $client) {
    if ($client["$field_org_code"] == '') {
        $undef_clnts[] = $client;
    }
}

$qnt_unass = count($undef_clnts);
if ($qnt_unass > 0){
?>
    <tr><td colspan="4">&nbsp;</td></tr>
    <tr><td colspan="4"class="sell_name red"><? echo "There are unassigned records in ".$tableClientsLogin." table ($qnt_unass):";?> </td></tr>
<?php
	foreach ($undef_clnts as &$client){
?>
    <tr><td colspan="4" class="gen_info"><?echo "Rec id: ".$client["$clientsLoginF_Id"];?> </td></tr>
<?php
	}
}
?>
</table>
<?php



?>


</body>
</html>
