<?php
define( '_PPSK_IS_CIPHER', false );	//	true	false
require_once( "inc.php" );
$log_obj	= new LogAdmin();

try{
	$auth_obj	= new Authentication();			//	This command is necessary to init session.

	$db_conn	= new DbConnect( $host, $db_name, $db_user, $user_pass );
	$db_conn->doConnect();

	$gl_Xajax	= new xajax();
	$act_obj	= new ajaxActions();	//	This object is created to start constructor where ajax handlers are initialized
	$js_xajax	= $gl_Xajax->getJavascript( $gl_Path.'libs/xajax', NULL );

	$app		= new RunApp();
	$content	= $app->getHtmlView();
}catch( Exception $e ){
	if( $db_conn->mIsSuccess ){ $log_obj->putLogInfo( $e->getMessage() ); }else{ echo $e->getMessage(); }
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" />
<html xmlns="http://www.w3.org/1999/xhtml" />
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?echo _COMPANY_TITLE;?></title>

<?php echo $js_xajax; echo iniJsEnvironment(); ?>
<link rel="stylesheet" href="css/main.css" type="text/css" />
<link rel="stylesheet" href="libs/ppsk/ppsk.css" type="text/css" />
<link rel="stylesheet" href="js/slides/slides.css" type="text/css" />
<link rel="stylesheet" href="css/general.css" type="text/css" />
<link rel="stylesheet" href="<? echo $gl_CompsPath;?>css/components.css"
	type="text/css" />
<?//IMPORTANT. This file must be last one.?>
<link rel="stylesheet" href="css/opera.css" type="opera/css"
	media="screen" />
</head>

<body id="body_id">
<div id="debug_buffer"></div>
<center>
<div class="mainContainer">
<div class="headerDiv">
<table class="headerTable" cellpadding="0" cellspacing="0">
	<tr>
		<td class="logoTd">
		<table class="logoTable" cellpadding="0" cellspacing="0">
			<tr>
				<td class="logoName"><? echo _NAME1;?></td>
				<td class="logoName contName"><? echo _CONT_NAME1;?></td>
			</tr>
			<tr>
				<td class="logoName endSign"><? echo _AND_SIGN;?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="logoName"><? echo _NAME2;?></td>
				<td class="logoName contName"><? echo _CONT_NAME2;?></td>
			</tr>
		</table>
		</td>

		<td class="headerInfoTd">
		<table class="infoTable" cellpadding="0" cellspacing="0">
			<tr>
				<td class="businessName" colspan="2"><? echo _BUSINESS_NAME;?></td>
			</tr>
			<tr>
				<td class="businessStatus"><? echo _BUSINESS_STATUS;?></td>
				<td class="placeName"><? echo _PLACE_NAME;?></td>
			</tr>
			<tr>
				<td class="tabsTd" colspan="2">
				<div id="tabs"><? echo $content[ 'tabs' ];?></div>
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
</div>

<div id="wlcmUserLine" class="wlcmUserLine"><?php echo $content[ 'wlcm' ];?></div>

<div id="mainContent" class="mainContent"><?php echo $content[ 'page' ];?></div>
</div>
</center>

<script type="text/javascript" src="js/main.js"></script>
<script type="text/javascript" src="js/scroller/jsScroller.js"></script>
<script type="text/javascript" src="js/scroller/jsScrollbar.js"></script>
<script type="text/javascript" src="js/slides/slides.js"></script>
<script type="text/javascript" src="js/run_txt/run_txt.js"></script>
<script type="text/javascript" src="libs/ppsk/ppsk.js"></script>
<script type="text/javascript"><?php echo $content[ 'js_code' ];?></script>

</body>
</html>
