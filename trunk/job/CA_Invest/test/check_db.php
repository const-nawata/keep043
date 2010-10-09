<?php
global $CA_PATH;
//global $sql_string1, $sql_string2;
include("../config.php");
$CA_PATH="../";

require_once $CA_PATH.'test/ut_main_const.php';
require_once($CA_PATH."classes/bl/utils_bl.php");
require_once $CA_PATH.'test/user/user_constants.php';

function getQntRecsFromTable($table){
    global $CA_PATH; include($CA_PATH."variables_DB.php");
//    global $sql_string1, $sql_string2;
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

include($CA_PATH."variables_DB.php");
mysql_connect($Host,$User,$Pass) OR DIE(_CANNOT_CONNECT_MYSQL);
mysql_select_db($DBName) or die(_CANNOT_CONNECT_BASE);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html  xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<title>Check DB</title>

<style type="text/css">

body{
    background-color: #EEFFEE;
}

body, body * {
    font-family: Arial;
    font-size: 16px;
}

.red{
    color: #EE0000;
}

.Ttl_head{
    white-space: nowrap;
    width: 100%;
    text-align: center;
    font-size: 20px;
    font-weight: bold;
    padding-bottom: 50px;
    padding-top: 15px;
}

.org_code_info{
    color: #CC4444;
    font-style: italic;
}

.logoHead{
    text-align: center;
    font-size: 18px;
    font-weight: bold;
    color: #888822;
}

.logContent *{
background-color: #FFEEEE;
}


.infoLogoTitle{
    text-align: left;
    padding-top: 30px;
    padding-bottom: 10px;
    margin: 0;
    font-size: 14px;
    font-weight: bold;
}

.infoLogo, .infoLogo *{
    font-size: 12px;
     margin: 0;
}

.infoLogo{
}

.sell_name, .sell_info{
    padding: 5px 20px 5px 20px; /*trbl*/
    text-align: left;
}

.sell_name{
    font-weight: bold;
}

.sell_info{
}

.gen_info{
    padding: 0;
    font-size: 12px;
}

.host_info{
    font-size: 12px;
    font-weight: normal;
}
pre{
/*margin: 0;*/
padding: 0;
}
</style>

</head>
<body>
<div class="Ttl_head">DB state after Unit tests. Org code template (<span class="org_code_info"><? echo  "%"._UT_ORG_CODE."%";?></span>) / <span class="host_info"><? echo  "Host: ".$Host;?></span></div>
<?php
$orgs_qnt = getQntRecsFromTable($tableOrganisation);

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

$agendas_qnt = getQntRecsFromTable($tableAgendas);
$app_qnt = getQntRecsFromTable($tableAppointments);
$ass_clients_qnt = getQntRecsFromTable($tableAppClientAssign);
$ass_agendas_qnt = getQntRecsFromTable($tableAppAgendaAssign);
$app_type_qnt = getQntRecsFromTable($tableAppTypes);
$days_off_qnt = getQntRecsFromTable($tableDaysOff);
$days_off_patt_qnt = getQntRecsFromTable($tableDaysoffPattern);
$free_cache_qnt = getQntRecsFromTable($tableCache);
$ag_cats = getQntRecsFromTable($tableAgendasCategories);
$ag_cats_ass = getQntRecsFromTable($tableAgendasAssignedCategories);

$clients_qnt = getQntRecsFromTable($tableClients);




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


$sql_string = "select COUNT(*) as count  from $tableClientsLogin ";
$result = mysql_query($sql_string);
$row = mysql_fetch_assoc($result);
$clients_login_qnt = $row['count'];

$login =  array ('ut_rows'=>-10, 'g_rows'=>$clients_login_qnt);

$sql_string = "select * from $tableCacheLog";
$cache_log_info = utils_bl::executeMySqlSelectQuery($sql_string);
?>
<table cellpadding="0" cellspacing="0">
<?/* ?>    <tr><td colspan="2" class="sell_name"><?echo  $tableOrgPermissions;?> </td><td colspan="2" class="sell_info">: <?echo $perm['ut_rows']." / ".$perm['g_rows'];?> record(s)</td></tr>   <?*/?>
    <tr><td colspan="2" class="sell_name"><?echo  $tableAgendas;?> </td><td colspan="2" class="sell_info">: <?echo $agendas_qnt['ut_rows']." / ".$agendas_qnt['g_rows'];?> record(s)</td></tr>
    <tr><td colspan="2" class="sell_name"><?echo  $tableClients;?> </td><td colspan="2" class="sell_info">: <?echo $clients_qnt['ut_rows']." / ".$clients_qnt['g_rows'];?> record(s)</td></tr>
    <tr><td colspan="2" class="sell_name"><?echo  $tableAppointments;?> </td><td colspan="2" class="sell_info">: <?echo $app_qnt['ut_rows']." / ".$app_qnt['g_rows'];?> record(s)</td></tr>
    <tr><td colspan="2" class="sell_name"><?echo  $tableAppAgendaAssign;?> </td><td colspan="2" class="sell_info">: <?echo $ass_agendas_qnt['ut_rows']." / ".$ass_clients_qnt['g_rows'];?> record(s)</td></tr>
    <tr><td colspan="2" class="sell_name"><?echo  $tableAppClientAssign;?> </td><td colspan="2" class="sell_info">: <?echo $ass_clients_qnt['ut_rows']." / ".$ass_clients_qnt['g_rows'];?> record(s)</td></tr>
    <tr><td colspan="2" class="sell_name"><?echo  $tableAppTypes;?> </td><td colspan="2" class="sell_info">: <?echo $app_type_qnt['ut_rows']." / ".$app_type_qnt['g_rows'];?> record(s)</td></tr>
    <tr><td colspan="2" class="sell_name"><?echo  $tableDaysOff;?> </td><td colspan="2" class="sell_info">: <?echo $days_off_qnt['ut_rows']." / ".$days_off_qnt['g_rows'];?> record(s)</td></tr>
    <tr><td colspan="2" class="sell_name"><?echo  $tableDaysoffPattern;?> </td><td colspan="2" class="sell_info">: <?echo $days_off_patt_qnt['ut_rows']." / ".$days_off_patt_qnt['g_rows'];?> record(s)</td></tr>
    <tr><td colspan="2" class="sell_name"><?echo  $tableOrganisation;?> </td><td colspan="2" class="sell_info">: <?echo $orgs_qnt['ut_rows']." / ".$orgs_qnt['g_rows'];?> record(s)</td></tr>
    <tr><td colspan="2" class="sell_name"><?echo  $tableCache;?> </td><td colspan="2" class="sell_info">: <?echo $free_cache_qnt['ut_rows']." / ".$free_cache_qnt['g_rows'];?> record(s)</td></tr>
    <tr><td colspan="2" class="sell_name"><?echo  $tableAgendasCategories;?> </td><td colspan="2" class="sell_info">: <?echo $ag_cats['ut_rows']." / ".$ag_cats['g_rows'];?> record(s)</td></tr>
    <tr><td colspan="2" class="sell_name"><?echo  $tableAgendasAssignedCategories;?> </td><td colspan="2" class="sell_info">: <?echo $ag_cats_ass['ut_rows']." / ".$ag_cats_ass['g_rows'];?> record(s)</td></tr>
<?php
    $qnt_unass = count($undef_perm);
    if ($qnt_unass > 0){
        ?>
    <tr><td colspan="4">&nbsp;</td></tr>
    <tr><td colspan="4"class="sell_name red"><? echo "There are unassigned records in ".$tableOrgPermissions." table ($qnt_unass):";?> </td></tr>
<?php
        foreach ($undef_perm as &$perm_item){
?>
    <tr><td colspan="1" class="gen_info"><?echo "Rec id: ".$perm_item["$orgPermissionsF_Id"];?> </td><td colspan="3" class="gen_info"><?echo "Org id: ".$perm_item["$orgPermissionsF_OrgId"];?></td></tr>
<?php
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

<div class="logContent">
<table cellpadding="0" cellspacing="0">
    <tr><td colspan="5">&nbsp;</td></tr>
    <tr><td colspan="5" class="logoHead">Cache Log Info</td></tr>
    <tr>
        <td class="sell_name"><?echo $cacheLogF_Id;?></td>
        <td class="sell_name"><?echo $cacheLogF_LastUpdtDate;?></td>
        <td class="sell_name"><?echo "Reset date";?></td>
        <td class="sell_name"><?echo $cacheLogF_IsTest;?></td>
        <td class="sell_name"><?echo $cacheLogF_NAttempts;?></td>
    </tr>

    <tr>
<?php
foreach ($cache_log_info as $info_item){
    ?>
        <td class="sell_info"><?echo $info_item["$cacheLogF_Id"];?></td>
        <td class="sell_info"><?echo utils_bl::GetFormDate($info_item["$cacheLogF_LastUpdtDate"]);?></td>
        <td class="sell_info"><?echo utils_bl::GetFormDate($info_item["$cacheLogF_TestDate"]);?></td>
        <td class="sell_info"><?echo $info_item["$cacheLogF_IsTest"];?></td>
        <td class="sell_info"><?echo $info_item["$cacheLogF_NAttempts"];?></td>
    <?php
}
?>
    </tr>

    <tr><td colspan="5" class="infoLogoTitle">Processing messages</td></tr>
    <tr> <td colspan="5" class="infoLogo"><pre><? print_r( $info_item["$cacheLogF_ErrorsInfo"]); ?></pre></td></tr>
</table>

</div>
</body>
</html>
