<?php
/*
 * Copyright (c) 2008 Redfountain. All rights reserved
 */

require_once($CA_PATH."widgets/classes/pl/make_appointments_pl.php");
require_once($CA_PATH."widgets/classes/dbl/make_appointments_dbl.php");
require_once($CA_PATH."widgets/classes/act/make_appointments_act.php");
require_once($CA_PATH."classes/pl/user_pl.php");
require_once($CA_PATH."classes/pl/app_type_pl.php");
//require_once($CA_PATH."classes/bl/clientwizard_bl.php");
require_once($CA_PATH."classes/pl/message_pl.php");
require_once($CA_PATH."classes/pl/authentication_pl.php");
require_once($CA_PATH."classes/bl/authentication_bl.php");
require_once($CA_PATH."classes/bl/validation_app_update.php");
require_once($CA_PATH."classes/bl/appointments_bl.php");
require_once($CA_PATH."classes/bl/category_bl.php");
require_once($CA_PATH."classes/bl/utils_bl.php");
require_once($CA_PATH."classes/bl/edit_settings_bl.php");


/**
 * This class realizes work on business layer of make appointments widget controller
 *
 * @author
 * @version
 * @copyright	Copyright Yukon Software Ukraine 2009. All rights reserved.
 * @package		widget
 * @subpackage 	makeappointments
 * @access		public
 */
class make_appointments_bl{
	const _make_choice 	= -1;
	const _whole_list 	= -2;
	const _list_empty 	= -3;

	/**
	 * Constructor
	 *
	 * @access public
	 */
	public	function __construct(){
	}
//---------------------------------------------------------------------
	/**
	 * Destructor
	 *
	 * @access public
	 */
public function __destruct()
	{
	}//End function __destruct
//---------------------------------------------------------------------

	/**
	 * performs registering for ajax methods
	 * @access public
	 * @return object xajax $xajax - xAjax Functions
	 */
	public	function RegisterFunction(){
		global $xajax;
		$xajax->registerFunction(array("actionDoShowWidgetStep1","make_appointments_act","actionDoShowWidgetStep1"));
//		$xajax->registerFunction(array("actionDoShowWidgetStep1","make_appointments_bl","actionDoShowWidgetStep1"));
		$xajax->registerFunction(array("actionDoSaveInWidgetStep1","make_appointments_bl","actionDoSaveInWidgetStep1"));
		$xajax->registerFunction(array("actionDoSaveInWidgetStep2","make_appointments_bl","actionDoSaveInWidgetStep2"));
		$xajax->registerFunction(array("actionDoSaveInWidgetStep3","make_appointments_bl","actionDoSaveInWidgetStep3"));

		$xajax->registerFunction(array("actionDoBackInWidgetStep2","make_appointments_bl","actionDoBackInWidgetStep2"));
		$xajax->registerFunction(array("actionDoBackInWidgetStep3","make_appointments_bl","actionDoBackInWidgetStep3"));
        $xajax->registerFunction(array("actionDoBackInWidgetStep4","make_appointments_bl","actionDoBackInWidgetStep4"));
		$xajax->registerFunction(array("ShowWidgetLoginInfo","make_appointments_pl","ShowWidgetLoginInfo"));
		$xajax->registerFunction(array("actionDoLogin","make_appointments_bl","actionDoLogin"));
		$xajax->registerFunction(array("showAppTypeInfo","app_type_pl","showAppTypeInfo"));


		$xajax->registerFunction(array("OnchangeCategoriesSelBoxForWidget","make_appointments_act","OnchangeCategoriesSelBoxForWidget"));
		$xajax->registerFunction(array("OnchangeAgendasSelBoxForWidget","make_appointments_act","OnchangeAgendasSelBoxForWidget"));
		$xajax->registerFunction(array("OnchangeAppTypesSelBoxForWidget","make_appointments_act","OnchangeAppTypesSelBoxForWidget"));
		$xajax->registerFunction(array("OnchangePeriodSelBoxForWidget","make_appointments_act","OnchangePeriodSelBoxForWidget"));
		$xajax->registerFunction(array("OnchangeMonthForWidget","make_appointments_act","OnchangeMonthForWidget"));
		$xajax->registerFunction(array("showSorry","make_appointments_act","showSorry"));
		$xajax->registerFunction(array("doFeedback","make_appointments_act","doFeedback"));



		$xajax->registerFunction(array("addInfoConteiner","message_pl","addInfoConteiner"));
		$xajax->registerFunction(array("createFunctionalLinkBlockForHelp","message_pl","createFunctionalLinkBlockForHelp"));
//		$xajax->registerFunction(array("showClientFormOnLoginScreen", "authentication_pl", "showClientFormOnLoginScreen"));
		$xajax->registerFunction(array("showForgetPassOnWidgetScreen", "authentication_pl", "showForgetPassOnWidgetScreen"));
		$xajax->registerFunction(array("actionDoForGetPass","authentication_bl","actionDoForGetPass"));
	    $xajax->registerFunction(array("showClientFormOnWidgetScreen", "user_pl", "showClientFormOnWidgetScreen"));
   	    $xajax->registerFunction(array("showClientFormNoRegOnWidgetScreen", "user_pl", "showClientFormNoRegOnWidgetScreen"));
	    $xajax->registerFunction(array("addClientOnWidgetAction", "user_bl", "addClientOnWidgetAction"));
        $xajax->registerFunction(array("addClientOnWidgetNoRegAction", "user_bl", "addClientOnWidgetNoRegAction"));
	    $xajax->registerFunction(array("inFirstElementFocus","message_pl","inFirstElementFocus"));
        $xajax->registerFunction(array("showMessage","message_pl","showMessage"));
		$xajax->registerFunction(array("reloadWidgetHelp","make_appointments_pl","reloadWidgetHelp"));
//		$xajax->registerFunction(array("getCategpriesListExt","category_bl","getCategpriesListExt"));

		$xajax->registerFunction(array("showFormJoinClientToOrgOnWidget", "user_pl", "showFormJoinClientToOrgOnWidget"));
		$xajax->registerFunction(array("showClientJoinFormOnWidget", "user_pl", "showClientJoinFormOnWidget"));
		$xajax->registerFunction(array("joinClientOnLoginAction", "user_bl", "joinClientOnLoginAction"));

		return $xajax;
		}//End function RegisterFunction
//---------------------------------------------------------------------

		const _param_not_valid	= 1;
		const _param_valid		= 0;

	/**
	 * performs checking if data for some read only parameters are absent
	 * @author Constantine Kolenchenko <ckolenchenko@yukon.cv.ua>
	 * @return false if all parameters are valid.
	 */
		private function isReadOnlyParamNotValid(){
			global $CA_PATH; include( $CA_PATH."variables_DB.php" );
			$ini = &$_SESSION['ini_widget_params'];

			$result = array( 'cat' => self::_param_valid, 'agenda' => self::_param_valid, 'app_type' => self::_param_valid );
			if( isset( $ini[ 'cat_r_o' ] ) && $ini[ 'cat_r_o' ] ){
				( $ini[ 'cat_id' ] != self::_whole_list )
					? $data = category_bl::getCategoryById( $ini[ 'cat_id' ] )
					: $data[ $agCatF_Id ] = self::_whole_list;

				( $data[ $agCatF_Id ] == _EMPTY_STRING ) ? $result[ 'cat' ] = self::_param_not_valid:'';
			}

			if( isset( $ini[ 'app_type_r_o' ] ) && $ini[ 'app_type_r_o' ] ){
				$data = app_type_bl::getAppTypeById($ini[ 'app_type_id' ] );
				( $data[ $appTypesF_Id ] == _EMPTY_STRING ) ? $result[ 'app_type' ] = self::_param_not_valid:'';
			}

			if( isset( $ini[ 'ag_r_o' ] ) && $ini['ag_r_o'] ){
				$data = user_bl::GetAgendaById( $ini[ 'ag_id' ] );
				( !$data ) ? $result[ 'agenda' ] = self::_param_not_valid:'';
			}

			return ( $result[ 'cat' ] || $result[ 'app_type' ] || $result[ 'agenda' ] );	//

//			return $result;
		}
//--------------------------------------------------------------------------------------------------

/**
 * sets initial widget params for edit mode
 * @author Constantine Kolenchenko <ckolenchenko@yukon.cv.ua>
 * @return void
 */
	private function setIniParamsForEditMode(){
		global $CA_PATH; include( $CA_PATH."variables_DB.php" );
		$ini	= &$_SESSION[ 'ini_widget_params' ];
		$app	= appointments_bl::GetAppointmentById_Mod( $ini[ 'app_id' ] );

		$app_ini	= &$app[ $appointmentsF_IniWidgetParams ];

		$ini[ 'cat_r_o' ]	= $app_ini[ 'cat_r_o' ];
		$ini[ 'cat_id' ]	= $app_ini[ 'cat_id' ];

		$ini[ 'app_type_r_o' ]	= $app_ini[ 'app_type_r_o' ];
		$ini[ 'app_type_id' ]	= $app[ $appointmentsF_AppTypeId ];

		$ini[ 'ag_r_o' ]= $app_ini[ 'ag_r_o' ];
		$ini[ 'ag_id' ]	= $app[ 'agendas' ][ 0 ];

		$ini[ 'comment' ]	= $app[ $appointmentsF_Comment ];
		$ini[ 'is_quest' ]	= $app[ 'is_quest' ];
		$ini[ 'app_date' ]	= $app[ $dbTable_StartDate ];
		$ini[ 'app_time' ]	= $app[ $dbTable_StartTime ];
	}
//--------------------------------------------------------------------------------------------------

/**
 * sets initial widget params for new mode
 * @author Constantine Kolenchenko <ckolenchenko@yukon.cv.ua>
 * @return void
 */
	private function setIniParamsForNewMode(){
		global $CA_PATH; include( $CA_PATH."variables_DB.php" );
		$ini = &$_SESSION[ 'ini_widget_params' ];

		$ini[ 'cat_r_o' ]	= ( isset( $ini[ 'cat_r_o' ] ) )
			? ( ( $ini[ 'cat_r_o' ] == 'true' || $ini[ 'cat_r_o' ] == '1'  || $ini[ 'cat_r_o' ] == 1 ) ? 1 : 0 )
			: 0;

		$ini[ 'cat_id' ]	= ( isset( $ini[ 'cat_id' ] ) )
			? $ini[ 'cat_id' ]
			: self::_whole_list;



		$ini[ 'app_type_r_o' ]	= ( isset( $ini[ 'app_type_r_o' ] ) )
			? ( ( $ini[ 'app_type_r_o' ] == 'true' || $ini[ 'app_type_r_o' ] == '1'  || $ini[ 'app_type_r_o' ] == 1 ) ? 1 : 0 )
			: 0;

		$ini[ 'app_type_id' ]	= ( isset( $ini[ 'app_type_id' ] ) )
			? $ini[ 'app_type_id' ]
			: self::_make_choice;



		$ini[ 'ag_r_o' ]	= ( isset( $ini[ 'ag_r_o' ] ) )
			? ( ( $ini[ 'ag_r_o' ] == 'true' || $ini[ 'ag_r_o' ] == '1'  || $ini[ 'ag_r_o' ] == 1 ) ? 1 : 0 )
			: 0;

		$ini[ 'ag_id' ]	= ( isset( $ini[ 'ag_id' ] ) )
			? $ini[ 'ag_id' ]
			: self::_whole_list;
	}
//--------------------------------------------------------------------------------------------------

/**
 * sets initial widget params due to $_GET global veriable
 * @author Constantine Kolenchenko <ckolenchenko@yukon.cv.ua>
 * @param	array $params
 * @return 	void
 */
	private function setIniWidgetParams( $params ){
		global $CA_PATH; include( $CA_PATH."variables_DB.php" );
		$settings = settings_bl::getSystemSettings( 'null', false );//false = no orgcode, no HTML

		$_SESSION[ 'widget_questionnaire_start' ]	= '';
		$_SESSION[ 'ini_widget_params' ]			= $params;
		$ini = &$_SESSION[ 'ini_widget_params' ];
		$ini['is_show_agendas']	= $settings[ $settingsF_IsShowWidgetAgendas ];
		( !isset( $ini [ 'scr' ] ) ) ? $ini[ 'scr' ]	= authentication_bl::_scr_login:'';
		if( isset( $params[ 'app_id' ] ) ){
			$ini[ 'app_id' ] = $params[ 'app_id' ];
			self::setIniParamsForEditMode();
		}else{
			$ini[ 'app_id' ] = 0;
			self::setIniParamsForNewMode();
		}
		$ini['order'] = $settings[ $settingsF_WidgetFields ];
	}
//--------------------------------------------------------------------------------------------------

	public function doContainer( $params = array() ){
		/*
		 * The following line unsets the flag to allow the widget to show the page with comment and button
		 * after changing language.
		 * This relates to make-appointment action of the questionnaire.
		 */
		unset($_SESSION['APP_TYPE_SKIP_ADD_INFO']);

		self::setIniWidgetParams( $params );
		$start_action = 'xajax_actionDoShowWidgetStep1();';

		if( self::isReadOnlyParamNotValid() ){
			$container =
"<script language=\"JavaScript\" type=\"text/javascript\">
function validationFeedback(){
	xajax_doFeedback(xajax.getFormValues('FormFeedback'));
	return false;
}

function onSubmitFeedback() {
	var frmvalidator1  = new Validator('FormFeedback');
	frmvalidator1.setAddnlValidationFunction('validationFeedback');
}

xajax_showSorry();
</script>";
		}else{
			$info_conteiner=message_pl::initScriptInfoConteiner();
			$jsUtils=message_pl::initScriptUtilsFunction();
			$client_calendar=user_pl::calendarForClient();
			$container = "
			<script language=\"JavaScript\" src=\"../js/gen_validatorv2.js\" type=\"text/javascript\"></script>
			<script language=\"JavaScript\" type=\"text/javascript\">

function validationStep1(){
	var Categ=document.getElementById('categlist');
	var Agenda=document.getElementById('agendaslist');
	if ((Agenda.value== ".self::_make_choice.") && (Categ.value==".self::_whole_list.")) {alert(\""._CA_MAKE_AGENDA_CHOICE."\");Agenda.focus();return false;}

	var AppType=document.getElementById('apptypelist');
	if (AppType.value==".self::_make_choice."){ alert('"._CA_MAKE_APP_TYPE_CHOICE."');AppType.focus();return false;}
	var Period=document.getElementById('periodlist');
	if (Period.value==".self::_make_choice."){ alert('"._CA_MAKE_PERIOD_CHOICE."');Period.focus();return false;}

	xajax_actionDoSaveInWidgetStep1(xajax.getFormValues('FormStep1'));
	return false;
}
//---------------------------------------------------------

function onSubmitStep1() {
	var frmvalidator1  = new Validator('FormStep1');
	frmvalidator1.addValidation('agendaslist','req','"._CA_ERR_EMPTY_FIELD."');
	frmvalidator1.addValidation('apptypelist','req','"._CA_ERR_EMPTY_FIELD."');
	frmvalidator1.addValidation('periodlist','req','"._CA_ERR_EMPTY_FIELD."');

	frmvalidator1.setAddnlValidationFunction('validationStep1');
}
//---------------------------------------------------------

function SetDataInCalendarWidgetStep1(d,a){
	var f=document.getElementById('chosenday');
	f.value=d;
	var f1=document.getElementById('agendaslist');
	//f1.value=a;//artur zaglushka all agendas 2
	var f2=document.getElementById('apptypelist');
	document.getElementById('periodlist').value = 8;
	document.FormStep1.onsubmit();
}
//---------------------------------------------------------

			function SetDataInWidgetStep2(d){
				var f=document.getElementById('chosenday');
				f.value=d;
				/*ChandeAgendaInWidgetStep2();*/
				xajax_actionDoSaveInWidgetStep1(xajax.getFormValues('FormStep2'));
				}

			function ChandeAgendaInWidgetStep2(){
				//var f=document.getElementById('agendaslist');
				//if(f.value!=".self::_whole_list.")//artur change
				xajax_actionDoSaveInWidgetStep1(xajax.getFormValues('FormStep2'));
				}

function ChooseTimeInWidgetStep2( setTime, agId, setIdApp ){
	var d_t	= 	setTime.split( ' ' );
	var date_el = d_t[ 0 ].split( '-' );
	document.getElementById( 'time' ).value			= d_t[ 1 ].substr( 0, 5 );
	document.getElementById( 'chosenday' ).value	= date_el[ 2 ] + '-' + date_el[ 1 ] + '-' + date_el[ 0 ];
	document.getElementById( 'idApp' ).value		= setIdApp;
	xajax_actionDoSaveInWidgetStep2( xajax.getFormValues( 'FormStep2' ), agId );
}
//---------------------------------------------------------

			function stripSpaces(x) {
				while (x.substring(0,1) == ' ') x = x.substring(1);
				return x;
			    }
			function validationLogin() {
				var frm = document.forms[\"LoginForm\"];
				var u=frm.name.value;
				var p=frm.password.value;
				xajax_actionDoLogin(u,p);
				//alert(1);
				return false;
				}

			function onSubmitLoginForm() {
				var frmvalidator  = new Validator(\"LoginForm\");
				frmvalidator.addValidation(\"name\",\"req\",\""._CA_ERR_EMPTY_FIELD."\");
				frmvalidator.addValidation(\"name\",\"maxlen=50\", \""._CA_ERR_MAXLEN."50\");
				frmvalidator.addValidation(\"password\",\"req\",\""._CA_ERR_EMPTY_FIELD."\");
				frmvalidator.addValidation(\"password\",\"maxlen=16\",\""._CA_ERR_MAXLEN."16\");
				frmvalidator.setAddnlValidationFunction(\"validationLogin\");
			}

			function startLoadingInWidget(id){
				var loading=document.getElementById(id);
				loading.style.display='' ;
				var info=document.getElementById('Info');
				info.style.visibility='hidden' ;
				return false;
			}

			function onActionShowList(tr_id){
				var display=document.getElementById(tr_id);
				display.className='displayOn';
				return false;
			}
//--------------------------------------------------------------------------------------------------

function endLoadingInWidget(id){
	var loading=document.getElementById(id);
	loading.style.display='none';
	InfoBehindSelectBox('apptypelist','Info');
	return false;
}
//--------------------------------------------------------------------------------------------------

			function ViewAppTypeToolTipInFirstStepWidget(event1,event2){
				var f=document.getElementById('apptypelist');
				var f1=document.getElementById('AppTypeDivSelect');
				showInfoDiv('xajax_showAppTypeInfo('+f.value+',1)',event1,event2,f1,1);
			}
			$info_conteiner
			$jsUtils
			$client_calendar
			$start_action
			//xajax_showQuestionnaireOnWidget(21390);
			</script>";
		}
		return $container;
	}
//--------------------------------------------------------------------------------------------------

/**
 *saving data to session
 *
 * @access private
 * @param array $dataForm - array of data
 * @return bool $result
 */
	public function SetDataWidget( $dataForm ){
		$result = session_start();
//		if (!isset($_SESSION["WidgetData"])) session_register("WidgetData");
		foreach( $dataForm as $k => $v) {
			$_SESSION[ "WidgetData" ][ $k ] = $v;
		}
		return $result;
	}
//---------------------------------------------------------------------

/**
 *   povernennja danix
 *
 * @access private
 *
 * @return array $result - dani form
 */
	function GetDataWidget(){
		$result	= array( "session" => array() );
		@session_start();
		if( isset( $_SESSION[ "WidgetData" ] ) ){
			$result["session"]	= $_SESSION[ "WidgetData" ];

//			foreach( $_SESSION[ "WidgetData" ] as $k => $v ){
//				$result["session"][$k]=$v;
//			}
		}
		return $result;
	}
//---------------------------------------------------------------------

//---------------------------------------------------------------------
	/**
	 *  function isExistQuestionnaire -
	 *  @access public
	 *  @return $result
	 */
public function isExistQuestionnaire(){
	//$isQuestionaireModule
		return $result;
	}//End of function
//--------------------------------------------------------------------------------------------------

/**
 * prepares initial widget params if questionnary is used before appointment creation.
 * @access	private
 * @author	Constantine Kolenchenko <ckolenchenko@yukon.cv.ua>
 * @return	void
 */
	private function prepareSessDataDueToQuestionnaryResults(){
		global $CA_PATH; include( $CA_PATH."variables_DB.php" );
		$ini		= &$_SESSION[ 'ini_widget_params' ];
		$quest_act	= &$_SESSION[ 'questionnaire' ][ 'action' ][ 'action' ];

		$ini[ 'cat_r_o' ]		= ( $quest_act[ $questionnaireActionsF_CategoryCh ] )	? 0 : 1 ;
		$ini[ 'ag_r_o' ]		= ( $quest_act[ $questionnaireActionsF_AgendaCh ] )		? 0 : 1 ;
		$ini[ 'app_type_r_o' ]	= ( $quest_act[ $questionnaireActionsF_AppTypeCh ] )	? 0 : 1 ;

		if( $quest_act[ $questionnaireActionsF_CategoryId ] != '' ){ $ini[ 'cat_id' ]	= $quest_act[ $questionnaireActionsF_CategoryId ]; }
		else{ $ini[ 'cat_id' ]	= self::_whole_list; }

		if( $quest_act[ $questionnaireActionsF_AppTypeId ] != '' ){ $ini[ 'app_type_id' ]	= $quest_act[ $questionnaireActionsF_AppTypeId ]; }
		else{ $ini[ 'app_type_id' ]	= self::_make_choice; }

		if( $quest_act[ $questionnaireActionsF_AgendaId ] != '' ){ $ini[ 'ag_id' ]	= $quest_act[ $questionnaireActionsF_AgendaId ]; }
		elseif( $quest_act[ $questionnaireActionsF_IsAllAgenda ] ){ $ini[ 'ag_id' ]	= self::_whole_list; }
		else{ $ini[ 'ag_id' ]	= self::_whole_list; }
	}
//--------------------------------------------------------------------------------------------------

	const _is_make_app_widget	= 1;
	const _is_quest_widget		= 2;

/**
 *  prepares data from step1 for using them at step1
 *  @access	public
 *  @param	$withoutquest	- shows if questionary is involved.
 *  @return dataForm - prepared form data
 */
	public function PrepearDataForWidgetStep1( $withoutquest = 0 ){
		global $CA_PATH; include( $CA_PATH."variables_DB.php" );
		$ini	= &$_SESSION[ 'ini_widget_params' ];
		$is_create_mode		= ( $ini[ 'app_id' ] == 0 );

		if( $withoutquest == 1 ){
			$dataForm	= self::GetDataWidget();
			$dataForm	= $dataForm[ "session" ];
			if( $dataForm[ "QuestionTree" ][ "done" ] == "yes" ){ self::prepareSessDataDueToQuestionnaryResults(); }

		}else{
			self::deletePendingFromCookies();
			unset( $_SESSION[ "WidgetData" ] );
		}

		$dataForm[ 'categlist' ]	= $ini[ 'cat_id' ];
		$dataForm[ 'apptypelist' ]	= $ini[ 'app_type_id' ];
		$dataForm[ 'agendaslist' ]	= ( !$ini[ 'is_show_agendas' ] ) ? self::_whole_list : $ini[ 'ag_id' ];

		if( self::_is_quest_widget == $ini[ 'widgettype' ] && 'yes' != $dataForm[ 'QuestionTree' ][ 'done' ] ){
			$dataForm[ 'QuestionTree' ]	= questiontree_bl::getQuestionTreeById( $ini[ 'quest_id' ] );
			if( !isset( $dataForm[ 'QuestionTree' ][ $questionairesF_isDisabled ] ) )$dataForm[ 'QuestionTree' ][ $questionairesF_isDisabled ]	= 1;
			$dataForm["QuestionTree"]["goToQuestionTree"]	= 1;
		}else{
			$dataForm[ 'QuestionTree' ][ 'goToQuestionTree' ]	= 0;
		}

		$dataForm[ 'is_questionnaire' ]	= 'null';

		if( $is_create_mode ){
			$dataForm[ 'chosenday' ]	= utils_bl::GetTodayDate();
			$dataForm[ 'periodlist' ]	= 'null';
		}else{
			$dataForm[ 'chosenday' ]	= $ini[ 'app_date' ];
			$dataForm[ 'periodlist' ]	= _IS_CHOOSEN_CALENDAR;
			self::DoSaveInWidgetStep1( $dataForm );
		}

		self::SetDataWidget( $dataForm );
		$dataForm	= self::GetDataWidget();

		return $dataForm;
	}
//---------------------------------------------------------------------

	public	function CreateNavigatorMonth(&$dataForm, $isWidget = 0 ){

		if (
		(!isset($dataForm["session"]["Month"]))||
		($dataForm["session"]["Month"]<1) ||
		($dataForm["session"]["Month"]>12))
		$dataForm["session"]["Month"]=1;

		$month	=$dataForm["session"]["Month"];
		$year	=$dataForm["session"]["Year"];

		$prevmonth =  $month-1;
		$nextmonth =  $month+1;

		$prevyear = $year;
		$nextyear = $year;

		if($nextmonth > 12){
			$nextmonth = 1;
			$nextyear += 1;
		}
		if($prevmonth == 0) {
			$prevmonth = 12;
			$prevyear -=1;
		}

		switch ($isWidget){
			case 1:
				$dataForm["navigation"] = array(
					$prevmonth => "onclick=\"startLoadingInWidget('dateTDLoading');xajax_OnchangeMonthForWidget(-1);return false;\"",
					$nextmonth => "onclick=\"startLoadingInWidget('dateTDLoading');xajax_OnchangeMonthForWidget(1);return false;\""
						);
				break;
			case 2:
				$dataForm["navigation"] = array(
					$prevmonth => "onclick=\"xajax_showCalendar('".$prevyear."-".$prevmonth."-01');return false;\"",
					$nextmonth => "onclick=\"xajax_showCalendar('".$nextyear."-".$nextmonth."-01');return false;\""
						);
				break;
//			case 0:
//			default:
//				$dataForm["navigation"] = array(
//					$prevmonth => "onclick=\"showLoading(4);xajax_actionDoCheckDateStep2('".$prevyear."','".$prevmonth."');return false;\"",
//					$nextmonth => "onclick=\"showLoading(4);xajax_actionDoCheckDateStep2('".$nextyear."','".$nextmonth."');return false;\""
//						);
//				break;
		}
		return true;
	}
//---------------------------------------------------------------------

/**
 *  fgener dani pri zmini dati na step2
 *
 * @access public
 * @param array &$dataForm		- dani z Navigator in step 2
 * @param	integer $isWidget -
 * @return bool $result - true/false udachno chi ne udacno
 */
	public	function CreateDays( &$dataForm, $isWidget = 0 ){
		$dt_sss	= &$dataForm[ 'session' ];

		$month_info	= make_appointments_bl::getAvailableDaysForMonth_24(intval( $dt_sss[ 'Month' ] ),
																		intval( $dt_sss[ 'Year' ] ),
																		$dt_sss[ 'AppType' ],
																		$dt_sss["Agenda"], $dt_sss["CatList"] );

		foreach( $month_info as $db_date => $is_valid ){
			if( $is_valid ){
				list( $y, $m, $day ) = explode( "-" , $db_date );
				if( intval( $m ) != intval( $dt_sss[ "Month" ] ) ){ continue; }
				$date	= date( 'd-m-Y', strtotime( $db_date ) );
				$day	= intval( $day );

				$strAction='onclick="SetDataInCalendarWidgetStep1(\''.$date.'\',\''.$is_valid.'\');"';
				$dataForm[ "days" ][ $day ] = array(
					'#',
					'linked-day',
					'<span class="linked_day_span">'.$day.'</span>',
					$strAction
				);
			}
		};
		return true;
	}
//--------------------------------------------------------------------------------------------------

/*
* PHP doc for this function is needed
*
* */
	function getWidgetCalendar( $dataForm ){
		$df_sess	= &$dataForm[ 'session' ];
		$isWidget	= 1;
		$data = self::GetDataWidget();
		list($d, $m, $y) = explode("-", $df_sess["chosenday"]);
		$df_sess["Year"] = $y;
		$df_sess["Month"] = $m;
		$df_sess["AppType"] = $data["session"]["apptypelist"];
		$df_sess["Agenda"] = $data["session"]["agendaslist"];
		$dataForm["apptypelist"] = $data["session"]["apptypelist"];
		$dataForm["agendaslist"] = $data["session"]["agendaslist"];

		$data = self::PrepearDataForPeriod($dataForm);
		self::CreateNavigatorMonth($data, $isWidget);

		($df_sess["apptypelist"] != 'null') ? self::createDays( $data, $isWidget ):'';

		$calendar = generate_calendar(
				$df_sess["Year"],
				$df_sess["Month"],
				$data["days"],
				3,
				NULL,
				1,
				$data["navigation"],
				( $df_sess["Month"] == $data["session"]["Month"] && $df_sess["Year"] == $data["session"]["Year"]) ? $df_sess["Day"] : 0 );

		return $calendar;
	}
//--------------------------------------------------------------------------------------------------

/**
 *   save action at step1
 *
 * @access public
 * @param array $dataForm		- Data from form
 * @return object xajaxResponse $result - return validation message if problems or do Save action
 */
	public function actionDoSaveInWidgetStep1($dataForm){
		self::DoSaveInWidgetStep1($dataForm);
		$dataForm = self::PrepearDataForWidgetStep2();//
		$result = make_appointments_pl::ShowWidgetStep2($dataForm);
		return $result;
	}
//--------------------------------------------------------------------------------------------------

	private function GetPeriod( $arrayDates ){
		$db_today	= date( 'Y-m-d' );
		$db_tomor	= date( 'Y-m-d', strtotime( '+1 day', mktime() ) );

		$period	= array();
		foreach( $arrayDates as $db_date => $is_valid ){
			switch( $db_date ){
				case $db_today:	$title = _TODAY; break;
				case $db_tomor:	$title = _TOMMOROW; break;
				default:
					$date	= utils_bl::GetFormDate( $db_date );
					$title	= utils_bl::getWeekDayByDate( $date ).' - '.utils_bl::getBriefDate( $date, '/' );
			}

			$period[]	= array(
				'isset'		=> $is_valid,
				'date'		=> $db_date,
				'title'		=> $title
			);
		}
		$period[ _IS_CHOOSEN_CALENDAR ]	= array(
			'isset'		=> 1,
			'date'		=> NULL,
			'title'		=> _CA_MESSAGE_TO_CHOOSE_DATE
		);

		return $period;
	}
//--------------------------------------------------------------------------------------------------

/**
 *  clreates
 *
 * @access private
 * @param array &$dataForm		- dani z Navigator in step 2
 * @return bool $result - true/false udachno chi ne udacno
 */
	private function CreatePeriod( $dataForm, $appType = 'null' ){

		$d_form	= &$dataForm["session"];

		if( $appType != 'null' ){

//			if ($d_form["Agenda"] == make_appointments_bl::_whole_list){$d_form["Agenda"] = "null";};
//			if ($d_form["CatList"] == make_appointments_bl::_whole_list){$d_form["CatList"] = "null";};




//			if ($d_form["Agenda"] != "null"){
//				$ag_list = array( user_bl::GetAgendaById( $d_form[ "Agenda" ] ) );
//			}elseif( $d_form[ 'CatList' ] == 'null' ){
//				$ag_list = ( $d_form[ 'AppTypeCat' ] > 0 )
//					? category_bl::getAgListAssignedToCategoryById_ForWidget($d_form["AppTypeCat"])
//					: category_bl::getAgListAssignedToCategoryById_ForWidget("all");
//			}else{
//				$ag_list = category_bl::getAgListAssignedToCategoryById_ForWidget($d_form["CatList"]);
//			}

			$arrayDates = array();
//			if( count( $ag_list ) > 0 ){
//public function getAvailableDaysForSevenDates_24( $appTypeId, $agId, $catId, $today='', $now='', $orgCode = NULL )
//				$arrayDates	= make_appointments_bl::getAvailableDaysForSevenDates_24( $d_form[ 'AppType' ], $ag_list );
				$arrayDates	= make_appointments_bl::getAvailableDaysForSevenDates_24( $d_form[ 'AppType' ], $d_form["Agenda"], $d_form["CatList"] );
//			}


//print_r( $arrayDates );

			$period = self::GetPeriod( $arrayDates );

			$result	= array();
			foreach( $period as $ind => $item ){
				if( $item["isset"] ){
					$result[]=array(
							"id"=>$ind,
							"value"=>$item["title"],
							"isset"=>$item["isset"],
//				            "agenda"=>$item["agenda"],
				            "date"=>$item["date"]
					);
				}

			}



//			$countPeriod=count($arrayDates);
//			for($i=1; $i<=$countPeriod; $i++){
//				if($period[$i]["isset"]==1){
//					$result[]=array(
//							"id"=>($i),
//							"value"=>$period[$i]["title"],
//							"isset"=>$period[$i]["isset"],
//				            "agenda"=>$period[$i]["agenda"],
//				            "date"=>$period[$i]["date"]
//					);
//				}
//			}
//			$result[]=array("id"=>_IS_CHOOSEN_CALENDAR,"value"=>_CA_MESSAGE_TO_CHOOSE_DATE);


		}
		return $result;
	}
//--------------------------------------------------------------------------------------------------

	function PrepearDataForPeriod($dataForm){
		global $CA_PATH; include($CA_PATH."variables_DB.php");

		$appType=$dataForm["apptypelist"];
		$AgendaId=$dataForm["agendaslist"];

		$AppTypeInfo=app_type_bl::getAppTypeById($appType);


//print_r( $AppTypeInfo );

		$dataForm["session"]["numStep"] 				= -2;
		$dataForm["session"]["do"]						= "New";
		$dataForm["session"]["Agenda"] 					= $AgendaId;
		$dataForm["session"]["AppType"]					= $appType;
		$dataForm["session"]["AppTypeMulty"]			= $AppTypeInfo["$appTypesF_IsMulty"];
		$dataForm["session"]["AppTypePeriodStartTime"]	= $AppTypeInfo["$appTypesF_PeriodStartTime"];
		$dataForm["session"]["AppTypePeriodEndTime"]	= $AppTypeInfo["$appTypesF_PeriodEndTime"];
		$dataForm["session"]["AppTypePeriod"]=$AppTypeInfo["$appTypesF_PeriodDay"];
		$dataForm["session"]["AppTypeCat"]=$AppTypeInfo["$appTypesF_AgeCatID"];
		$dataForm["session"]["AgendasList"]=array($dataForm["session"]["Agenda"]);
		$dataForm["session"]["CatList"]=$dataForm["session"]["categlist"];
		return $dataForm;
	}
	//---------------------------------------------------------------------

	function PrepearDataAndCreatePeriod( &$dataForm){
		$data=self::PrepearDataForPeriod($dataForm);
		$period_list=self::CreatePeriod($data,$dataForm["apptypelist"]);
		return $period_list;
	}
//--------------------------------------------------------------------------------------------------

/**
 *  function DoSaveInWidgetStep1 - do save in widget's step1
 *  @access public
 *  @param array $dataForm		- Data from form
 *  @return $result of SetDataWidget function
 */
	public function DoSaveInWidgetStep1( $dataForm ){
		$today=getdate();
		if( isset( $dataForm[ "periodlist" ] ) ){
			switch ( $dataForm[ "periodlist" ] ){
			case _IS_CHOOSEN_CALENDAR :
				list($d, $m, $y) = explode("-", $dataForm["chosenday"]);
				$mk=mktime(0,0,0,$m,$d,$y);
			break;

			default :
				$data	= array(
						"apptypelist"	=> $dataForm[ 'apptypelist' ],
						"agendaslist"	=> $dataForm[ 'agendaslist' ],
						"session"		=> array( 'categlist' => $dataForm[ "categlist" ] )
				);

				$period	= self::PrepearDataAndCreatePeriod( $data );

				$countPeriod=count($period);
				$p=array();
				for($i=0;$i<$countPeriod;$i++){
					if ($period[$i]["id"]==$dataForm["periodlist"]){
					$p=$period[$i];
					}
				}

				if($p["isset"]==1){
						list($y, $m, $d) = explode("-", $p["date"]);
						$mk=mktime(0,0,0,$m,$d,$y);
				}else {
					/*TODO: FATTAL ERROR*/
				}
			}




			$dataForm["date"]	= date("Y-m-d",$mk);
			$dataForm["chosenday"]	= date("d-m-Y",$mk);
			$dataForm["Date"]	= date("d-m-Y",$mk);
			$dataForm["Day"]	= date("d",$mk);
			$dataForm["Month"]	= date("m",$mk);
			$dataForm["Year"]	= date("Y",$mk);

		}

		self::SetDataWidget($dataForm);
	}
//--------------------------------------------------------------------------------------------------

	/**
	 *  creates data array App List if step 3
	 *
	 * @access public
	 * @param array &$dataForm		- dani z Navigator in step 3
	 * @param array $timeTables		- time table dlja Simple App
	 * @param array $paramAppList 	- parametri dlja Simple App
	 * @return bool $result - true/false udachno chi ne udacno
	 */
	public	function CreateAppListOfSimpleApp($dataForm, $ttls, $paramAppList, $isWidget=0){
		$ini = &$_SESSION[ 'ini_widget_params' ];
		$is_create_mode		= ( $ini[ 'app_id' ] == 0 );
		$f_sess = &$dataForm[ "session" ];

		$sel_date	= $f_sess[ 'Day' ].'-'.$f_sess[ 'Month' ].'-'.$f_sess[ 'Year' ];

		$dateStr = &$paramAppList[ "dateStr" ];
		$todayDateTime = &$paramAppList[ "todayDateTime" ];
		$timeAppTyle = &$paramAppList[ "timeAppTyle" ];

		$todayDateTimeMinAdvance = &$paramAppList["todayDateTimeMinAdvance"];
		$isSetMinAdvance = &$paramAppList["isSetMinAdvance"];

		$todayDateTimeMaxAdvance = $paramAppList["todayDateTimeMaxAdvance"];
		$isSetMaxAdvance = $paramAppList["isSetMaxAdvance"];
		$strFromFree = " onmouseout=\"this.className='ico_widget_slot_free';\" onmouseover=\"this.className='ico_widget_slot_free_hover';\"";

		$day = ($f_sess["numStep"] == 2) ? substr($dateStr, 6) : $f_sess["Day"];

		if($f_sess["numStep"] == -2){
			$day = substr($dateStr, 6);
			$f_sess["Month"] = substr($dateStr, 4, 2);
			$f_sess["Year"] = substr($dateStr, 0, 4);
		}

		//prepear data to period constreint //ARTUR peredel
//		if(($f_sess["AppTypePeriodStartTime"] != _EMPTY_STRING) || ($f_sess["AppTypePeriodEndTime"] != _EMPTY_STRING)){
//			list($hPeriodStart,$mPeriodStart) = explode(":",$f_sess["AppTypePeriodStartTime"]);
//			$PeriodStartTime = $hPeriodStart.$mPeriodStart;
//			list($hPeriodEnd, $mPeriodEnd) = explode(":", $f_sess["AppTypePeriodEndTime"]);
//			$PeriodEndTime = date("Hi", mktime($hPeriodEnd, $mPeriodEnd - $timeAppTyle, 0, $f_sess["Month"], $day,  $f_sess["Year"]));
//
//			if($PeriodStartTime>$PeriodEndTime){
//				$PeriodStartTime = $dateStr.$PeriodStartTime;
//				$PeriodEndTime = date("YmdHi", mktime($hPeriodEnd, $mPeriodEnd - $timeAppTyle, 0, $f_sess["Month"], $day+1,  $f_sess["Year"]));
//			  $anomal_period_app_type=1;
//			}
//			if($PeriodStartTime==$PeriodEndTime){
//				$f_sess["AppTypePeriodStartTime"] = _EMPTY_STRING;
//			}
//		}





//print_r( $f_sess["AppTypePeriod"] );

		$isBlockedDatePeriod = false;
		$start_date_php = mktime(0, 0, 0, $f_sess["Month"], $day,  $f_sess["Year"]);
		$dey_of_week = date("w", $start_date_php);
		($f_sess["AppTypePeriod"][$dey_of_week] != 1) ? $isBlockedDatePeriod = true:'';
		$countNotavailable = 0;
		$ifDoCountNotavailable = 1;



		$app_typ_start	= ( $f_sess["AppTypePeriodStartTime"] == '' ) ? '00:00:00' : $f_sess["AppTypePeriodStartTime"].':00';
		$app_typ_end	= ( $f_sess["AppTypePeriodEndTime"] == '' ) ? '00:00:00' : $f_sess["AppTypePeriodEndTime"].':00';
//print_r( $ttls );
//echo "\napp_typ_start: ".$app_typ_start." # app_typ_end: ".$app_typ_end."\n";


	$tableAppList=array();
  foreach( $ttls as $age_id => $timeTable){
	$paramAppList["isBlockedDate"]=BlockDaysBL::isBlockedDate( $age_id, $dataForm["Date"] );
  	 foreach( $timeTable as &$line ){

			$todayDateTimeDo = date("YmdHi",strtotime($line['d_t_start']));
//			$todayDateTimeDo	= $line['d_t_start'];

			if($anomal_period_app_type==1){
			 $time_str=$todayDateTimeDo;
			}else{
			 $time_str=date("Hi",strtotime($line['d_t_start']));
			}
			$time_title=date("H:i",strtotime($line['d_t_start']));
			$prompt = $class = $do= _EMPTY_STRING;



			$app_type_time_cond	= true;
			$mk	= strtotime( $line['d_t_start'] );
			$db_chk_date	= date( 'Y-m-d', $mk );
			if( $app_typ_start > $app_typ_end ){
				$app_type_time_cond	=
					( $line['d_t_start'] >= $db_chk_date.' 00:00:00' && $line['d_t_start'] < $db_chk_date.' '.$app_typ_end ) ||
					( $line['d_t_start'] >= $db_chk_date.' '.$app_typ_start && $line['d_t_start'] < date( 'Y-m-d', strtotime( '+1 day', $mk ).' 00:00:00' ) );
			}elseif( $app_typ_start < $app_typ_end ){
				$app_type_time_cond	=
					( $line['d_t_start'] >= $db_chk_date.' '.$app_typ_start && $line['d_t_start'] < $db_chk_date.' '.$app_typ_end );
			}


			if (
				$paramAppList["isBlockedDate"] ||
//				$isBlockedDatePeriod ||
				$todayDateTimeDo <= $todayDateTime ||
				($line["maxduration"] / _MINUTE_DURATION) < $timeAppTyle   ||

				!$app_type_time_cond ||

//				($f_sess["AppTypePeriodStartTime"] != _EMPTY_STRING && $time_str < $PeriodStartTime)    ||
//				($f_sess["AppTypePeriodEndTime"] != _EMPTY_STRING && $time_str > $PeriodEndTime)     ||

				($todayDateTimeDo > $todayDateTime && $todayDateTimeDo > $todayDateTimeMaxAdvance && $isSetMaxAdvance)
			){	// Blocked
				$class="exist";
				$prompt="&nbsp;";
				($ifDoCountNotavailable) ? $countNotavailable++:'';
			}elseif( ( $todayDateTimeDo > $todayDateTime && $todayDateTimeDo < $todayDateTimeMinAdvance && $isSetMinAdvance)  ){// Advance
				$class="advance";
				$prompt=_CALL_US;
				($ifDoCountNotavailable) ? $countNotavailable++:'';
			}else{																										// Free
				$class="free";
				$do = "onclick=\"ChooseTimeInWidgetStep2('".$line["d_t_start"]."',$age_id)\" $strFromFree";
				$prompt = _AVAILABLE;

				($ifDoCountNotavailable) ? $ifDoCountNotavailable = 0:'';
			}

//echo "class: $class\n";


			if( $class!="exist" ){
				$tableAppList[$todayDateTimeDo] = array(
						"Time"=>$time_title,
						"Class"=>$class,
						"Do"=>$do,
						"Prompt"=>$prompt,
						"agId"=>$age_id
				);
			}

		}
  }


  //	Commented on 19-08-2010
  //REMARK: This verification is done by method wich gets agendas' timetables.

//		if( !$is_create_mode && $ini[ 'app_date' ] == $sel_date && ( $ini[ 'ag_id' ] == $f_sess[ 'Agenda' ] || $f_sess[ 'Agenda' ] == self::_whole_list ) ){
//			$tableAppList[ $ini[ 'app_time' ] ] = array(
//				"Time"	=> $ini[ 'app_time' ],
//				"Class"	=> 'free',
//				"Do"	=> "onclick=\"ChooseTimeInWidgetStep2('".$ini[ 'app_time' ]."',".$ini[ 'ag_id' ].")\" $strFromFree",
//				"Prompt"=> _AVAILABLE,
//				"agId"	=> $ini[ 'ag_id' ]
//			);
//		}


		ksort( $tableAppList );

		$tableAppList[ 'countNotavailable' ] = $countNotavailable;

		return $tableAppList;
	}
//--------------------------------------------------------------------------------------------------

/**
 *  function GetParamAppList - create Param data array App List if step 3
 * @access private
 * @param array $dataForm		- dani
 * @param date $dey				- dani
 * @return array $result - array of parametrs
 */
	private	function GetParamAppList($dataForm){
		$strFromFree="onmouseout=\"this.className='free'\" onmouseover=\"this.className='free_hover'\"
						title=\""._CA_MESSAGE_CLICK_RESERVE."\" alt=\""._CA_MESSAGE_CLICK_RESERVE."\"";
		//select status
		$mStatusOpt = utils_bl::getStatusOptions();

		//select if Bloced Day
		$isBlockedDate=BlockDaysBL::isBlockedDate($dataForm["session"]["Agenda"], $dataForm["Date"]);
		// select today date and time
		$today=getdate();

		$todayDateTime=date("YmdHi", mktime($today[hours], $today[minutes], $today[seconds], $today[mon], $today[mday], $today[year]));

		list($d,$m,$y) = explode("-",$dataForm["Date"]);
		$dateStr=$y.$m.$d;

		//select Date and Time Advance
		$rowSettings = authentication_dbl::selectSettings($_SESSION['org_code']);
		$isSetMinAdvance=0;
		$isSetMaxAdvance=0;
		if ((isset($rowSettings["COMPANY_TIME"]))&&($rowSettings["COMPANY_TIME"]>=0)){
			$minAdvance=$rowSettings["COMPANY_TIME"];
			$isSetMinAdvance=1;
		}

		$AppTypeInfo=app_type_bl::getAppTypeById($dataForm["session"]["AppType"]);

		$timeAppTyle=$AppTypeInfo["TIME"];

		if ((isset($AppTypeInfo["MIN_TIME"]))&&($AppTypeInfo["MIN_TIME"]>=0)){
			$minAdvance=$AppTypeInfo["MIN_TIME"];
			$isSetMinAdvance=1;
		}

		if ((isset($AppTypeInfo["MAX_TIME"]))&&($AppTypeInfo["MAX_TIME"]>=0)){
			$maxAdvance=$AppTypeInfo["MAX_TIME"];
			$isSetMaxAdvance=1;
		}

		$todayDateTimeMinAdvance=date("YmdHi", mktime($today[hours]+$minAdvance, $today[minutes], $today[seconds], $today[mon], $today[mday], $today[year]));
		$todayDateTimeMaxAdvance=date("YmdHi", mktime($today[hours], $today[minutes], $today[seconds], $today[mon], $today[mday]+$maxAdvance, $today[year]));

		$paramAppList=array(
			"dateStr"		=>$dateStr,
			"isBlockedDate"	=>$isBlockedDate,
			"todayDateTime"	=>$todayDateTime,
			"timeAppTyle"	=>$timeAppTyle,
			"mStatusOpt"	=>$mStatusOpt,

			"todayDateTimeMinAdvance"=>$todayDateTimeMinAdvance,
			"isSetMinAdvance"	=>$isSetMinAdvance,

			"todayDateTimeMaxAdvance"=>$todayDateTimeMaxAdvance,
			"isSetMaxAdvance"	=>$isSetMaxAdvance,
			"strFromFree"		=>$strFromFree
		);
		return $paramAppList;
	}
//--------------------------------------------------------------------------------------------------

/**
 *  creates data array App List if step 3
 *
 * @access public
 * @param array $dataForm		- dani z Navigator in step 3
 * @return bool $result - true/false udachno chi ne udacno
 */
	public function PreparationToAppList($dataForm,&$paramAppList){
		global $CA_PATH;include($CA_PATH."variables_DB.php");
		$ini	= &$_SESSION[ 'ini_widget_params' ];
		$AppTypeInfo=app_type_bl::getAppTypeById( $dataForm[ "session" ][ "AppType" ] );


		$week_day	= date( 'w', strtotime( $dataForm["Date"] ) );
		$ttls	= array();

		if( 1 == $AppTypeInfo[ $appTypesF_PeriodDay ][ $week_day ] ){
			if( $dataForm[ 'session' ][ 'Agenda' ] == self::_whole_list ){
				$catt_id	= ( $dataForm[ 'session' ][ 'Cat' ] == self::_whole_list )
					? ( ( $AppTypeInfo[ $appTypesF_AgeCatID ] != '' )
						? $AppTypeInfo[ $appTypesF_AgeCatID ]
						: 'all' )
					: $dataForm[ 'session' ][ 'Cat' ];

				$agendas = category_bl::getAgListAssignedToCategoryById_ForWidget( $catt_id );
			}else{
				$agendas[0] = user_bl::GetAgendaById( $dataForm[ 'session' ][ 'Agenda' ] );
			}


			$app_obj	= new appointments_bl();
			$app_obj->setThisAgendas( $agendas );
			$app_id	= ( $ini[ 'app_id' ] > 0 ) ? $ini[ 'app_id' ] : NULL;

			$ttls	= ( !$dataForm[ 'session' ][ 'AppTypeMulty' ] )
				? $app_obj->getAgendasTtlsOfAvailableTimes_24( $dataForm["Date"], $app_id, appointments_bl::_scr_widget )
				: $app_obj->getAgendasTtlsOfAvailableMultiApps_24( $dataForm["Date"], $dataForm[ "session" ][ "AppType" ] );

		}
		$paramAppList=self::GetParamAppList($dataForm);

		return $ttls;
	}
//--------------------------------------------------------------------------------------------------

/**
 * ni podviyne vxodjennja
 *
 * @access private
 * @param int  $idApp
 * @param array &$dataForm	- dani pro App
 * @return bool $result - true/false udachno chi ne udacno
 */
	private	function CheckClient($dataForm,$idClient=0){
		$result=false;
		if (count($dataForm)>0)
		foreach ($dataForm as $k=>$v)
		if($v==$idClient){
			$result=true;
			break;
		}
		return $result;
	}
//--------------------------------------------------------------------------------------------------

/**
 * creates data array App List if step 3
 *
 * @access public
 * @param array &$dataForm		- dani z Navigator in step 3
 * @param array $timeTables		- time table dlja Multi App
 * @param array $paramAppList 	- parametri dlja Multi App
 * @param array $isWidget 	- parametri oznachae sho se dlja vidgets
 * @return bool $result - true/false udachno chi ne udacno
 */
	public	function CreateAppListOfMultiApp($dataForm,$timeTable_arr,$paramAppList/*,$isWidget=0*/){
		global $CA_PATH; include($CA_PATH."variables_DB.php");

		$f_sess = &$dataForm[ "session" ];

		$dateStr=$paramAppList["dateStr"];
		$isBlockedDate=$paramAppList["isBlockedDate"];
		$todayDateTime=$paramAppList["todayDateTime"];
		$timeAppTyle=$paramAppList["timeAppTyle"];
		$mStatusOpt=$paramAppList["mStatusOpt"];

		$todayDateTimeMinAdvance=$paramAppList["todayDateTimeMinAdvance"];
		$isSetMinAdvance=$paramAppList["isSetMinAdvance"];

		$todayDateTimeMaxAdvance=$paramAppList["todayDateTimeMaxAdvance"];
		$isSetMaxAdvance=$paramAppList["isSetMaxAdvance"];
		$strFromFree=$paramAppList["strFromFree"];


		if($dataForm["session"]["numStep"]==2){
			$day=substr($dateStr,6);
		}else{
			$day=$dataForm["session"]["Day"];
		}
		if($dataForm["session"]["numStep"]==-2){
			$day=substr($dateStr,6);
			$dataForm["session"]["Month"]=substr($dateStr,4,2);
			$dataForm["session"]["Year"]=substr($dateStr,0,4);
		}


		//prepear data to period constreint
		if(($f_sess["AppTypePeriodStartTime"] != _EMPTY_STRING) || ($f_sess["AppTypePeriodEndTime"] != _EMPTY_STRING)){
			list($hPeriodStart,$mPeriodStart) = explode(":",$f_sess["AppTypePeriodStartTime"]);
			$PeriodStartTime = $hPeriodStart.$mPeriodStart;
			list($hPeriodEnd, $mPeriodEnd) = explode(":", $f_sess["AppTypePeriodEndTime"]);
			$PeriodEndTime = date("Hi", mktime($hPeriodEnd, $mPeriodEnd - $timeAppTyle, 0, $f_sess["Month"], $day,  $f_sess["Year"]));

			if($PeriodStartTime>$PeriodEndTime){
				$PeriodStartTime = $dateStr.$PeriodStartTime;
				$PeriodEndTime = date("YmdHi", mktime($hPeriodEnd, $mPeriodEnd - $timeAppTyle, 0, $f_sess["Month"], $day+1,  $f_sess["Year"]));
			  $anomal_period_app_type=1;
			}
			if($PeriodStartTime==$PeriodEndTime){
				$f_sess["AppTypePeriodStartTime"] = _EMPTY_STRING;
			}

		}


		/*

		//prepear data to period constreint
		if(($dataForm["session"]["AppTypePeriodStartTime"]!='')||($dataForm["session"]["AppTypePeriodEndTime"]!='')){
			list($hPeriodStart,$mPeriodStart) = explode(":",$dataForm["session"]["AppTypePeriodStartTime"]);
			$PeriodStartTime=$dateStr.$hPeriodStart.$mPeriodStart;
			list($hPeriodEnd,$mPeriodEnd) = explode(":",$dataForm["session"]["AppTypePeriodEndTime"]);
			$PeriodEndTime=date("YmdHi", mktime($hPeriodEnd, $mPeriodEnd-$timeAppTyle, 0, $dataForm["session"]["Month"], $day,  $dataForm["session"]["Year"]));
		}
		*/

		$tableAppList=array();

		$isBlockedDatePeriod=false;

		$start_date_php=mktime(0, 0, 0, $dataForm["session"]["Month"], $day,  $dataForm["session"]["Year"]);
		$dey_of_week=date("w",$start_date_php);

		if ($dataForm["session"]["AppTypePeriod"][$dey_of_week]!=1){
			$isBlockedDatePeriod=true;
			//	 		echo "|$day";
		}
		$countNotavailable=0;
		$ifDoCountNotavailable=1;
  foreach ($timeTable_arr as $age_id => $time_arr){
	$timeTable=$time_arr;
		foreach($timeTable as $line){
			$todayDateTimeDo = date("YmdHi",strtotime($line['d_t_start']));
			if($anomal_period_app_type==1){
			 $time_str=$todayDateTimeDo;
			}else{
			 $time_str=date("Hi",strtotime($line['d_t_start']));
			}
			$time_title=date("H:i",strtotime($line['d_t_start']));

			$do="";
			$class="";
			if (
			($isBlockedDatePeriod==true)||
			($todayDateTimeDo<=$todayDateTime)||
			($line["status"]==$mStatusOpt["free"]["id"])||
			(
			($line["app_id"]!=$dataForm["session"]["AppId"])&&
			self::CheckClient($line["clients"],$_SESSION['valid_client_org_id'])))
			{
				// Bloced time or date
				$class="exist";
				$prompt="&nbsp;";
				if ($ifDoCountNotavailable==1){$countNotavailable++;};
			}elseif(($dataForm["session"]["AppTypePeriodStartTime"]!='')&&($time_str<$PeriodStartTime)){
				$class="exist";
				$prompt="&nbsp;";
				if ($ifDoCountNotavailable==1){$countNotavailable++;};
			}elseif(($dataForm["session"]["AppTypePeriodEndTime"]!='')&&($time_str>$PeriodEndTime)){
				$class="exist";
				$prompt="&nbsp;";
				if ($ifDoCountNotavailable==1){$countNotavailable++;};
			}elseif(
			(
			(($todayDateTimeDo>$todayDateTime)&&($todayDateTimeDo<$todayDateTimeMinAdvance)&&($isSetMinAdvance==1))||
			(($todayDateTimeDo>$todayDateTime)&&($todayDateTimeDo>$todayDateTimeMaxAdvance)&&($isSetMaxAdvance==1))
			)){
				// Bloced advance
				$class="advance";
				$prompt=_CALL_US;
				if ($ifDoCountNotavailable==1){$countNotavailable++;};
			}elseif(
			(
			($line["qnt_clients"]<$line["max_num_clients"])||
			(($line["qnt_clients"]<=$line["max_num_clients"])&&($line["app_id"]==$dataForm["session"]["AppId"])))){
				// Free time
				if ($ifDoCountNotavailable==1){$ifDoCountNotavailable=0;};
				$class="free";


				    $strFromFree = " onmouseout=\"this.className='ico_widget_slot_free';\" onmouseover=\"this.className='ico_widget_slot_free_hover';\"";
					$do="onclick=\"ChooseTimeInWidgetStep2('".$line["d_t_start"]."',$age_id, '".$line["app_id"]."')\" $strFromFree";
					$prompt=_AVAILABLE;
//				}
			}else{

				// Bloced time or date
				$class="exist";
				$prompt="&nbsp;";
				if ($ifDoCountNotavailable==1){$countNotavailable++;};
			}
			if($class!="exist"){
			$tableAppList[$todayDateTimeDo]=array(
					"Time"=>$time_title,
					"Class"=>$class,
					"Do"=>$do,
					"Prompt"=>$prompt,
					"idApp"=>$line["app_id"],
					"agId"=>$age_id
			);
			}
		}
  }

		$tableAppList["countNotavailable"]=$countNotavailable;
		ksort($tableAppList);
		return $tableAppList;
	}
//--------------------------------------------------------------------------------------------------

/**
 *  prepares data from step1 for using them at step2
 *
 *  @access public
 *  @return dataForm - prepared form data
 */
public function PrepearDataForWidgetStep2(){
		global $CA_PATH; include($CA_PATH."variables_DB.php");

		$data=self::GetDataWidget();
		$AppTypeInfo=app_type_bl::getAppTypeById($data["session"]["apptypelist"]);

		$dataForm["session"]["Agenda"]	=	$data["session"]["agendaslist"];
		$dataForm["session"]["Cat"]		=	$data["session"]["categlist"];//artur for all agendas
		$dataForm["session"]["AppType"]	=	$data["session"]["apptypelist"];

//		$data["session"]["Date"]	=	/*( isset( $data["session"]["CurrentDate"] ) )
//			? $data["session"]["CurrentDate"]
//			:*/ $data["session"]["Date"];

		$dataForm["Date"]				=	$data["session"]["Date"];

		$dataForm["session"]["Month"]	=	$data["session"]["Month"];
		$dataForm["session"]["Year"]	=	$data["session"]["Year"];
		$dataForm["session"]["Day"]		=	$data["session"]["Day"];

		$dataForm["session"]["AppTypeMulty"]			= $AppTypeInfo["$appTypesF_IsMulty"];
		$dataForm["session"]["AppTypePeriod"]			= $AppTypeInfo["$appTypesF_PeriodDay"];
		$dataForm["session"]["AppTypePeriodStartTime"]	= $AppTypeInfo["$appTypesF_PeriodStartTime"];
		$dataForm["session"]["AppTypePeriodEndTime"]	= $AppTypeInfo["$appTypesF_PeriodEndTime"];
		$isWidget=1;

		$timeTables = self::PreparationToAppList($dataForm, $paramAppList);
		$tableAppList = (!$dataForm["session"]["AppTypeMulty"])
			? self::CreateAppListOfSimpleApp( $dataForm, $timeTables, $paramAppList, $isWidget )
			: self::CreateAppListOfMultiApp($dataForm, $timeTables, $paramAppList/*, $isWidget*/);

//print_r( $tableAppList );


		if ($data["session"]["QuestionTree"]["done"]!="yes"){
			$questionnaire=questiontree_bl::getQuestionTreeByAppTypeId($data["session"]["apptypelist"]);
		}else{
			$questionnaire=$data["session"]["QuestionTree"];
		}

		$dataForm	= array(
			"SelectedCat"		=> $data["session"]["categlist"],
			"SelectedAgenda"	=> $data["session"]["agendaslist"],
			"SelectedAppType"	=> $data["session"]["apptypelist"],
			"QuestionTree"		=> $questionnaire,
			"CurrentDate"		=> $data["session"]["Date"],
			"PrevDate"			=> utils_bl::GetPrevDate($data["session"]["Date"]),
			"NextDate"			=> utils_bl::GetNextDate($data["session"]["Date"]),
			"WeekDay" 			=> utils_bl::getWeekDayByDate($data["session"]["Date"]),
			"DayMonth" 			=> utils_bl::getDayMonth($data["session"]["Date"]),
			"timeTable"			=> $tableAppList
			);

		self::SetDataWidget($dataForm);

//print_r( $_SESSION );


		return $dataForm;
	}
//--------------------------------------------------------------------------------------------------

/**
 *  save action at step1
 *
 * @access public
 * @param  array $dataForm		- Data from form
 * @return $result - return validation message if problems or do Save action
 */
public function actionDoSaveInWidgetStep2( $dataForm, $ag_id ){


//print_r( $_SESSION );


//print_r( $dataForm );

	$dataForm['sel_box_ag_id']=$dataForm['agendaslist'];
	$dataForm['agendaslist']=$dataForm['SelectedAgenda']=$ag_id;

	$dataForm[ 'real_selected_date' ]	=
	$dataForm[ 'Date' ]					= $dataForm['chosenday'];
	$dataForm[ 'date' ]					= date( "Y-m-d", strtotime( $dataForm['chosenday'] ) );
//
	$test_obj = self::doValidationAtStep2( $dataForm );

//print_r( $dataForm );

		if(	!$test_obj->Valid ){
				$dataForm['agendaslist']=$dataForm['SelectedAgenda']=$dataForm['sel_box_ag_id'];

//				$dataForm['Date']=$dataForm['chosenday']=$dataForm['real_selected_date'];
//				$dataForm['date']=date("Y-m-d",strtotime($dataForm['real_selected_date']));
				$dataForm['Date']=$dataForm['chosenday']=$_SESSION[ 'WidgetData' ]['CurrentDate'];
				$dataForm['date']=date("Y-m-d",strtotime($_SESSION[ 'WidgetData' ]['CurrentDate']));


				$result = self::actionDoSaveInWidgetStep1($dataForm);
				$errors_strings = $test_obj->getListOfErrors();
				$result->addScript('alert("'.($errors_strings).'");');
		}else{
			self::SetDataWidget($dataForm);
			self::DoSaveDataInWidgetStep2($dataForm);
			$data=self::GetDataWidget();

//print_r( $dataForm );

			$dataForm=self::PrepearDataForWidgetStep3();

//print_r( $dataForm );

			$result=make_appointments_pl::ShowWidgetStep3($dataForm);
		}
		return $result;
	}
//--------------------------------------------------------------------------------------------------

/**
 * Save information to database after select data in time table
 *
 * @author Igor Banadiga <ibanadiga@yukon.cv.ua>
 * @param Array[] $dataForm Array of data form
 */
	public function DoSaveDataInWidgetStep2($dataForm){
		global $CA_PATH; include($CA_PATH."variables_DB.php");
		$ini = &$_SESSION[ 'ini_widget_params' ];

		$data=self::GetDataWidget();

//print_r( $dataForm );
//print_r($data);exit;

		$idApp=$dataForm["idApp"];

		$dataForm=array();
		$dataForm["$dbTable_StartDate"]		=$data["session"]["date"];
		$dataForm["$dbTable_StartTime"]	=$data["session"]["time"];

		$dataForm["$appointmentsF_AppTypeId"]	=$data["session"]["apptypelist"];
		$dataForm["$appointmentsF_AgendaId"]	=$data["session"]["agendaslist"];
		$dataForm["$AppClientAssignF_AppId"]	=$idApp;
		$AppTypeInfo=app_type_bl::getAppTypeById($data["session"]["apptypelist"]);

		$dataForm[ "session" ][ "AppTypeMulty" ]	= $AppTypeInfo[ $appTypesF_IsMulty ];
		if( $dataForm["session"]["AppTypeMulty"] == 0 ){
			$agendas[0]=$dataForm[ $appointmentsF_AgendaId ];
			$dataForm_temp = $dataForm;

			if( $_SESSION[ 'LicenseType' ] != authentication_bl::_licence_single ){
   				$resource_user = user_bl::GetAgendaResourceById();
   				$dataForm[ $appointmentsF_AgendaId ] = $resource_user[ $agendasF_Id ];
   			}

			$dataForm[ $appointmentsF_StatusId ]		= 11;
			$dataForm[ $appointmentsF_MaxNumberClient ]	= 1;
			$dataForm[ $appointmentsF_Comment ]			= '';

			$ag_r_o	= ( isset( $ini[ 'ag_r_o' ] ) )
				? ( ( $ini[ 'ag_r_o' ] ) ? 1 : 0 )
				: 0;

			$cat_r_o	= ( isset( $ini[ 'cat_r_o' ] ) )
				? ( ( $ini[ 'cat_r_o' ] ) ? 1 : 0 )
				: 0;

			$dataForm[ $appointmentsF_IniWidgetParams ]	= serialize( array( 'ag_r_o' => $ag_r_o, 'cat_r_o' => $cat_r_o, 'cat_id' => $data[ 'session' ][ 'categlist' ], 'app_type_r_o' => 1 ) );
			$id	= make_appointments_dbl::InsertAppAtWidgetStep2( $dataForm );

			$rez_ass_agendas=appointments_bl::AssignAgendasToApp( $agendas, $id );

			$dataForm	= $dataForm_temp;
		}else{
			$dataForm[ 'newId' ]	= 'NULL';
			$id=make_appointments_dbl::InsertMultiAppAtWidgetStep2( $dataForm );
		}

		self::SetDataWidget( array( "appoitmentId" => $id ) );
		self::setPendingInfoToCookies();
	}
//--------------------------------------------------------------------------------------------------

/**
 *  prepars data from step2 for using them at step3
 *  @access public
 *  @return dataForm - prepared form data
 */
	public function PrepearDataForWidgetStep3(){
		global $CA_PATH; include( $CA_PATH."variables_DB.php" );
		$ini	= &$_SESSION[ 'ini_widget_params' ];
		$is_create_mode		= ( $ini[ 'app_id' ] == 0 );

		$dataForm	= array();
		$dataForm	= self::GetDataWidget();

		$dataForm["AgendaName"]=user_bl::GetAgendaNameById($dataForm["session"]["agendaslist"]);

		$AppTypeInfo=app_type_bl::getAppTypeById($dataForm["session"]["apptypelist"]);
		$dataForm["session"]["AppTypeMulty"]=$AppTypeInfo["$appTypesF_IsMulty"];
		$dataForm[ "AppTypeName" ]	= $AppTypeInfo[ $appTypesF_Name ];

		if($AppTypeInfo["$appTypesF_Tariff"]!=''){
		 $currency=utils_bl::getCurrencyById($AppTypeInfo[$appTypesF_TariffCurId]);
  	   	 $dataForm["tariff"]=$currency[$CurrencyF_HtmlSign]." ".$AppTypeInfo["$appTypesF_Tariff"]." ".$AppTypeInfo["$appTypesF_TariffInfo"];
        }

//Create time for showing at step3 (e.g	11:00 - 11:45)
		$dataForm[ 'session' ][ 'TimeEnd' ]	= utils_bl::AddMinutesToTime( $dataForm[ 'session' ][ 'time' ], $AppTypeInfo[ $appTypesF_Time ] );

		// flag if show end time
		$dataForm["session"]["ifShowTimeEnd"]=$AppTypeInfo[$appTypesF_isShowedDuration];

		$dataForm[ 'AppTypeDuration' ]	= $AppTypeInfo[ $appTypesF_Time ];

		$dataForm[ 'Comment' ]	= ( isset( $dataForm[ 'session' ][ 'comment' ] ) )
			? $dataForm[ 'session' ][ 'comment' ]
			: ( ( $is_create_mode )
				? ''
				: $ini[ 'comment' ] );

		if( $AppTypeInfo[ $appTypesF_IsMulty ] == 1 ){
			$appInfo	= appointments_bl::GetAppointmentById( $dataForm[ 'session' ][ 'idApp' ] );
			$dataForm[ 'Comment' ]	= $appInfo[ $appointmentsF_Comment ];
			$dataForm[ 'CommentOptions' ]	=	"readonly";
		}else{
			$dataForm[ 'CommentOptions' ]	=	"";
		}

		return $dataForm;
	}
//--------------------------------------------------------------------------------------------------

/**
 *  function PrepearDataForWidgetStepLoginInfo - preparing data from step3  for using them at step4(Login Details)
 *
 *  @access public
 *  @return dataForm - prepared form data
 */
	public function PrepearDataForWidgetLoginInfo($dataForm=array()){
		global $CA_PATH; include($CA_PATH."variables_DB.php");
		$org_code=$_SESSION['org_code'];

		$statuses = utils_bl::getStatusOptions();

		$settings_arr = settings_bl::getSystemSettings($org_code, 0);

		if (1 == $settings_arr[ $settingsF_CreateReminderWithoutConfirm ] ){
			$dataForm['status_name'] = $statuses['confirmed']['email_name'];
			$dataForm['status_id'] =  $statuses['confirmed']['id'];
		} else {
			$dataForm['status_name'] = $statuses['new']['email_name'];
			$dataForm['status_id'] =  $statuses['new']['id'];
		}

		return $dataForm;
	}
//--------------------------------------------------------------------------------------------------

/**
 *  saves action at step3
 * @access public
 * @param array $dataForm		- Data from form
 * @return object xajaxResponse $result - return validation message if problems or do Save action
 */
	public function actionDoSaveInWidgetStep3( $dataForm ){
		global $CA_PATH,$isQuestionaireModule; include($CA_PATH."variables_DB.php");
		$ini		= &$_SESSION[ 'ini_widget_params' ];
		$org_code	= &$_SESSION['org_code'];

		$is_create_mode		= ( $ini[ 'app_id' ] == 0 );

		if( /*$is_create_mode && */!self::chekTimeLimit() ){
			$massage = _ERROR_TIME_OVER;
			$authentication_plObj = new authentication_pl();
			$result = $authentication_plObj->showWarning( $massage );
			$result->addScript( "xajax_actionDoShowWidgetStep1();" );
		}else{
			self::SetDataWidget( $dataForm );
			self::DoSaveDataInWidgetStep3();

			$data		= self::GetDataWidget();
			$dt_sess	= &$data[ 'session' ];


			$is_quest_on	= ( $is_create_mode && isset( $dt_sess[ 'QuestionTree' ][ $questionairesF_Id ] ) &&
						     $dt_sess[ 'QuestionTree' ][ $questionairesF_Id ] != '' && $isQuestionaireModule != 0 );

			$is_quest_done	= ( $dt_sess[ 'QuestionTree' ][ 'done' ] == 'yes' );

			switch( $ini[ 'scr' ] ){
				case authentication_bl::_scr_login:
					if( $is_quest_on && !$is_quest_done ){
						$result	= question_client_act::showQuestionnaireOnWidget( $dt_sess[ "apptypelist" ] );
					}else{
						$arrSettings	= settings_bl::getSystemSettings( 'null', false );
						$result	= ( $arrSettings[ $settingsF_ClientRgTypesWidget ] == 2 )
							? user_pl::showClientFormNoRegOnWidgetScreen()
							: make_appointments_pl::ShowWidgetLoginInfo();
					}
				break;

				case authentication_bl::_scr_cl_list:
				case authentication_bl::_scr_cl_portal:
					$set	= array( 'valid_client_org_id' => $_SESSION[ 'valid_client_org_id' ], 'valid_user_id' => $_SESSION[ 'valid_user_id' ] );

					$AppTypeInfo	= app_type_bl::getAppTypeById( $dt_sess[ 'apptypelist' ] );

					//  Deletion must be before updating due to time validation
//					if( !$is_create_mode ){
//						if( $AppTypeInfo[ $appTypesF_IsMulty ] ){
//							$old_ass_id	= make_appointments_dbl::isSetClientAsign( $ini[ 'app_id' ], $_SESSION[ 'valid_client_org_id' ] );
//							$result	= make_appointments_dbl::UnassignClientFromMultiAppAtWidget( $old_ass_id );
//						}else{
//							question_client_bl::changeQuestionnaireHistoryAppIdByAppId( $ini[ 'app_id'], $dt_sess[ 'appoitmentId' ] );
//							appointments_bl::deleteApp( $ini[ 'app_id' ] );		//  Deletion must be before updating due to time validation
//						}
//					}

					$test_obj = self::doUltimateValidation( $set );
					if(	!$test_obj->Valid ){
						self::DeleteNotFinishedAppAtWidget( $dt_sess[ 'appoitmentId' ], $AppTypeInfo[ $appTypesF_IsMulty ], $dt_sess[ 'appoitmentId' ] );
						$dt_sess[ 'agendaslist' ] = $dt_sess[ 'SelectedAgenda' ] = $dt_sess[ 'sel_box_ag_id' ];

					    $result = self::actionDoSaveInWidgetStep1( $dt_sess );		//???
						$errors_strings = $test_obj->getListOfErrors();
						$result->addScript( 'alert("'.$errors_strings.'");' );
					}elseif( $is_quest_on && !$is_quest_done ){
						$result	= question_client_act::showQuestionnaireOnWidget( $dt_sess[ "apptypelist" ] );
					}else{


						if( !$is_create_mode ){
							if( $AppTypeInfo[ $appTypesF_IsMulty ] ){
								$old_ass_id	= make_appointments_dbl::isSetClientAsign( $ini[ 'app_id' ], $_SESSION[ 'valid_client_org_id' ] );
								$result	= make_appointments_dbl::UnassignClientFromMultiAppAtWidget( $old_ass_id );
							}else{
								question_client_bl::changeQuestionnaireHistoryAppIdByAppId( $ini[ 'app_id'], $dt_sess[ 'appoitmentId' ] );
								appointments_bl::deleteApp( $ini[ 'app_id' ] );		//  Deletion must be before updating due to time validation
								make_appointments_dbl::updateAppId($ini[ 'app_id'], $dt_sess[ 'appoitmentId' ]);
								$dt_sess[ 'appoitmentId' ]=$ini[ 'app_id'];
								$_SESSION['WidgetData']['appoitmentId']=$dt_sess[ 'appoitmentId' ];
							}
						}


						if ($_SESSION['white_lable_data'][$whiteLabelF_ExcahngeUse]==1) {
   						 ms_exchange_dbl::addApp($dt_sess[ "appoitmentId" ]);
   					    }

						// insert history of questionnaire done
						if( $is_quest_on && $is_quest_done ){
							if ($AppTypeInfo["$appTypesF_IsMulty"]!=1){
								$validAppId=$_SESSION['WidgetData']["appoitmentId"];
							}else{
								$validAppId=$_SESSION['WidgetData']["idApp"];
							}
							question_client_bl::saveQuestionnaireClientInDB( $validAppId, $_SESSION[ 'valid_client_org_id' ] );
							question_client_bl::saveQuestionnaireFlowOnWidget($validAppId, $_SESSION[ 'valid_client_org_id' ]);
						}

						self::DoAssignClientsToAppAtWidget( $_SESSION['valid_user_id'], $_SESSION[ 'valid_client_org_id' ]);

						$set = array( 'org_code' => $org_code );
						$set = authentication_bl::getPermissionsForLoginChecking( $set );
						self::DoPrepearDataAndSendMail( $_SESSION[ 'valid_user_id' ], $set );
						unset( $_SESSION[ "WidgetData" ] );
						self::clearPendingFromCookies();

						$result = new xajaxResponse();
						$result->addScript( "self.parent.xajax_doCloseWidget();" );
						$result->addScript( "alert('"._CA_MESSAGE_ADD_APPOINTMENT_NOTIF_MAIL."')" );
					}
				break;
			}
		}
		return $result;
	}
//--------------------------------------------------------------------------------------------------

	/**
	 * Save data to database after enter comment
	 *
	 * @author Igor Banadiga <ibanadiga@yukon.cv.ua>
	 * @return Boolean $result Status save comment
	 */
	public function DoSaveDataInWidgetStep3(){
		global $CA_PATH;
		include($CA_PATH."variables_DB.php");
		$data=self::GetDataWidget();

		$dataForm	= array();
		$dataForm[ $appointmentsF_Comment ]		=$data["session"]["comment"];
		$dataForm[ $appointmentsF_AppId ]		=$data["session"]["appoitmentId"];

		$AppTypeInfo=app_type_bl::getAppTypeById($data["session"]["apptypelist"]);
		$dataForm=prepareData_sql( $dataForm );

		if( $AppTypeInfo[ $appTypesF_IsMulty ] != 1 ){
			$result=make_appointments_dbl::UpdateAppAtWidgetStep3( $dataForm );
		}
		return $result;
	}
	//---------------------------------------------------------------------
	/**
	 *  action at login page of widget
	 *
	 * @access public
	 * @param $UserLogin - user login
	 * @param $UserPass - user password
	 * @return object xajaxResponse $result - return validation message if problems or do Login action
	 */
function actionDoLogin($UserLogin,$UserPass, $newUser=0){
		global $CA_PATH,$isQuestionaireModule;
		global $APPROVE;global $QUICKLOGIN;
		include($CA_PATH."variables_DB.php");
		$objResponse = new xajaxResponse();
		$set=authentication_bl::CheckLoginAndPassword($UserLogin,$UserPass,$Id,$Level,$massage);

		$authentication_plObj=new authentication_pl();

		if ($set['level']=='none') {

//echo "Point11\n";

			if($set['client_do']=="new"){
				$objResponse = new xajaxResponse();
				authentication_bl::createSession($set);
				$objResponse->addScript("xajax_showFormJoinClientToOrgOnWidget('".($set['client_id'])."');");
			}else{
				$objResponse=$authentication_plObj->showWarning($massage);
			}
		}elseif($set['level']!='user') {

//echo "Point12\n";

			$massage=_ERROR_ONLY_CLIENT;
			$objResponse=$authentication_plObj->showWarning($massage);
		}elseif(!(authentication_bl::checkPermissions($Id,$Level,$set['org_code'],$massage))){

//echo "Point13\n";

			$massage=sprintf($massage,"<br/>");
			$objResponse=$authentication_plObj->showWarning($massage);
		}elseif(self::chekTimeLimit()==false){



			$massage=_ERROR_TIME_OVER;
			$objResponse=$authentication_plObj->showWarning($massage);
			$objResponse->addScript("xajax_actionDoShowWidgetStep1()");
		}else{
		    $test_obj	= self::doUltimateValidation( $set );			//			Validation




			if(	!$test_obj->Valid ){		//	Validatin is failed
				$data=self::GetDataWidget();
				$AppTypeInfo=app_type_bl::getAppTypeById($data["session"]["apptypelist"]);
				self::DeleteNotFinishedAppAtWidget( $data[ 'session' ][ 'appoitmentId' ], $AppTypeInfo[ $appTypesF_IsMulty ], $data[ 'session' ][ 'appoitmentId' ] );

				$data['session']['agendaslist']=$data['session']['SelectedAgenda']=$data['session']['sel_box_ag_id'];

				$data['session']['Date']=$data['session']['chosenday']=$_SESSION[ 'WidgetData' ]['CurrentDate'];
				$data['session']['date']=date("Y-m-d",strtotime($_SESSION[ 'WidgetData' ]['CurrentDate']));



			    $objResponse = self::actionDoSaveInWidgetStep1($data['session']);		//	Return to step 2
				$errors_strings = $test_obj->getListOfErrors();
				$objResponse->addScript('alert("'.$errors_strings.'");');
			}else{



				$data=self::GetDataWidget();

				self::DoAssignClientsToAppAtWidget($set['valid_user_id'],$set['valid_client_org_id']);

				if ($_SESSION['white_lable_data'][$whiteLabelF_ExcahngeUse]==1) {
   						ms_exchange_dbl::addApp($data[ 'session' ][ 'appoitmentId' ] );
   				}

   				//Save Questionnaire History
//TODO: add from CONFIG
			//if($isQuestionaireModule!=0){
			if((isset($data["session"]["QuestionTree"][$questionairesF_Id]))&&
			($data["session"]["QuestionTree"][$questionairesF_Id]!='')){
				$AppTypeInfo=app_type_bl::getAppTypeById($data["session"]["apptypelist"]);
				if ($AppTypeInfo["$appTypesF_IsMulty"]!=1){
					$id=$data["session"]["appoitmentId"];
				}else{
					$id=$data["session"]["idApp"];
				}

				question_client_bl::saveQuestionnaireClientInDB($id,$set['valid_client_org_id']);
				question_client_bl::saveQuestionnaireFlowOnWidget($id,$set['valid_client_org_id']);
			}





				self::DoPrepearDataAndSendMail($Id,$set);
				if(($UserLogin=='')&&($newUser==1)){$newUser=0;}
				if($newUser==1){
					$objResponse=$authentication_plObj->showWarning(_CA_MESSAGE_ADD_APPOINTMENT_NOTIF_MAIL_REG);
				}else{
				 	$objResponse=$authentication_plObj->showWarning(_CA_MESSAGE_ADD_APPOINTMENT_NOTIF_MAIL);
				}

				unset( $_SESSION[ "WidgetData" ] );
				self::clearPendingFromCookies();
				if( $_GET[ 'is_local' ] == 1 ){
					$objResponse->addScript("self.parent.xajax_doCloseWidget('$UserLogin','$UserPass','yes');");
				}else{
					$type_showing=$_SESSION['ini_widget_params']['style'];
					if(($type_showing=='button')||($type_showing=='link')){
					 $objResponse->addScript("window.close()");
					}else{
					 $objResponse->addScript("xajax_actionDoShowWidgetStep1()");
					}
				}










			}







		}
		return $objResponse;
	}
	//---------------------------------------------------------------------
	/**
	 * Chack time live mobile live session
	 *
	 * @author Igor Banadiga <ibanadiga@yukon.cv.ua>
	 * @access private
	 * @return Boolean $result Status if session live
	 */
	private function chekTimeLimit($pending=false){
		global $CA_PATH;
		include($CA_PATH."variables_DB.php");

		$result=false;
		$data=self::GetDataWidget();
		if (!isset($data["session"]["apptypelist"])) return false;

		$AppTypeInfo=app_type_bl::getAppTypeById($data["session"]["apptypelist"]);
		$data["session"]["AppTypeMulty"]	= $AppTypeInfo["$appTypesF_IsMulty"];

		if ($data["session"]["AppTypeMulty"]==0){
			$result=make_appointments_dbl::isSetAppointment($data["session"]["appoitmentId"],$pending);
		}else{
			$result=make_appointments_dbl::isSetAsign($data["session"]["appoitmentId"],$pending);
		}
		return $result;
	}
	//---------------------------------------------------------------------

 /**
  * sets clint list in info for reminder
  * @author	Constantine Kolenchenko <ckolenchenko@yukon.cv.ua>
  * @access	private
  * @param	array $info - mail info
  *	@return	array - info with modifided clients list
  */
	private function setClientsListOfIdsForReminder( $info ){
		global $CA_PATH; include( $CA_PATH."variables_DB.php" );

	   	$app	= appointments_bl::GetAppointmentById_Mod( $info[ $appointmentsF_AppId ] );
   		$info[ 'Client' ]	= array();
   		foreach( $app[ 'clients' ] as $org_cl_id ){
   			$client	= user_bl::getClientByHisUniqId( $org_cl_id );
   			$info[ 'Client' ][]	= $client[ $clientsF_Id ];
   		}

		return $info;
	}
//--------------------------------------------------------------------------------------------------

function DoPrepearDataAndSendMail( $userId, $set ){
	global $CA_PATH; include( $CA_PATH."variables_DB.php" );
	$ini		= &$_SESSION[ 'ini_widget_params' ];

	$is_create_mode		= ( $ini[ 'app_id' ] == 0 );

	$settings_arr = settings_bl::getSystemSettings('null', 0);
	$statuses	= utils_bl::getStatusOptions();

	$data	= self::GetDataWidget();
	$dt_sss	= &$data[ 'session' ];

	$dataForm	= self::PrepearDataForWidgetLoginInfo();

	$AppTypeInfo	= app_type_bl::getAppTypeById( $dt_sss[ 'apptypelist' ] );

	if( !$AppTypeInfo[ $appTypesF_IsMulty ] ){
		$appId = $dt_sss[ "appoitmentId" ];
		$action = 1;
	}else{
		$appId = $dt_sss["idApp"];
		$action = 5;
	}

	$agendas[0]=$dt_sss["agendaslist"];

	if( $is_create_mode ){					//		Create mode ff
		$info = array(
			'Client' => array( $userId ),
			'Agenda' => $agendas,
	        'CreatedBy' => "U",
	        'Action' => $action,
	        'status_name' => $dataForm['status_name'],
	        'Date' => $dt_sss["Date"],
	        'From' => $dt_sss["time"],
	        'AppType' => $dt_sss["apptypelist"],
	        'Description' => $dt_sss["comment"],
	        'LicenseType'	=>$set["LicenseType"],
			$appointmentsF_AppId	=> $appId
		);

		mail_bl::AppointmentSendMail( $info );

		if( $AppTypeInfo[ $appTypesF_IsMulty ] ){
			$info	= self::setClientsListOfIdsForReminder( $info );
		}

		$reminder = new mail_bl( $info, $appId );
		$reminder->ReminderCreator();

	}else{									//		Edit mode
   		$agenda = ( $_SESSION[ 'LicenseType' ] == 1 )
			? user_bl::GetAgendaMailInfoById( $ini[ 'ag_id' ] )
			: user_bl::GetResourseUserMailInfoById();

		if( $AppTypeInfo[ $appTypesF_IsMulty ] ){
																					######  (multi multi)
			$info = array(
				'Client' => array( $userId ),
				'Agenda' => $agendas,
		        'CreatedBy' => "U",
		        'Action' => 6,
		        'status_name' => $statuses['unassigned']['email_name'],
		        'Date' => $dt_sss["Date"],
		        'From' => $dt_sss["time"],
		        'AppType' => $dt_sss["apptypelist"],
		        'Description' => $dt_sss["comment"],
		        'LicenseType'	=>$set["LicenseType"],
				$appointmentsF_AppId	=> $appId
			);
			$rez_mail=mail_bl::AppointmentSendMail( $info );

			$reminder = new mail_bl( $info, $ini[ 'app_id' ] );
			$reminder	= NULL;

			$info = array(
				'Client' => array( $userId ),
				'Agenda' => $agendas,
		        'CreatedBy' => "U",
		        'Action' => 5,
		        'status_name' => $statuses['deleted']['email_name'],
		        'Date' => $dt_sss["Date"],
		        'From' => $dt_sss["time"],
		        'AppType' => $dt_sss["apptypelist"],
		        'Description' => $dt_sss["comment"],
		        'LicenseType'	=>$set["LicenseType"],
				$appointmentsF_AppId	=> $appId
			);
			$rez_mail	= mail_bl::AppointmentSendMail( $info );

			$info	= self::setClientsListOfIdsForReminder( $info );
			$reminder = new mail_bl( $info, $appId );
			$reminder->ReminderCreator();
			$reminder	= NULL;
		}else{
			$status_name = (1 == $settings_arr[ $settingsF_CreateReminderWithoutConfirm ])	//	######  (simple simple)
				? $statuses[ 'confirmed' ][ 'email_name' ]
				: $statuses[ 'changed' ][ 'email_name' ];

			$info = array(
				'Client' => array( $userId ),
				'Agenda' => $agendas,
		        'CreatedBy' => "U",
		        'Action' => 3,
		        'status_name' => $status_name,
		        'Date' => $dt_sss["Date"],
		        'From' => $dt_sss["time"],
		        'AppType' => $dt_sss["apptypelist"],
		        'Description' => $dt_sss["comment"],
		        'LicenseType'	=>$set["LicenseType"],
				$appointmentsF_AppId	=> $appId,
				'old_app_id'	=>$ini[ 'app_id' ]
			);

   			$rez_mail = ($agenda[ 'MsoSync' ] == 'Y' )
   			    ? mail_bl::AppointmentSendMail( $info, 3, _CHANGE_APPOINTMENT_MAIL_SUBJECT )
   				: mail_bl::AppointmentSendMail( $info );


			$reminder = new mail_bl( $info, $ini[ 'app_id' ] );
			$reminder->ReminderCreator();
		}
	}
}
//--------------------------------------------------------------------------------------------------

    /**
     * creates standart array for validation
     * @author Constantine Kolenchenko <ckolenchenko@yukon.cv.ua>
     *
     * @access private
     * @param $appData array contains data for validation
     * @return Array
     */
	private function prepareDataForValidationAtStep2( $dataForm ){
        global $CA_PATH; include( $CA_PATH."variables_DB.php" );
        $data	= self::GetDataWidget();


//print_r( $dataForm );

        $app_type = app_type_bl::getAppTypeById( $data[ 'session' ][ 'apptypelist' ] );
        $new_app	= array(
//        	'AppId'			=> ( ( $app_type[ $appTypesF_IsMulty ] )
//        							? $dataForm[ 'idApp' ]
//        							: ( ( $_SESSION[ 'ini_widget_params' ][ 'app_id' ] )
//        									? $_SESSION[ 'ini_widget_params' ][ 'app_id' ]
//        									: NULL
//        							)
//        						),

        	'AppId'			=> ( $app_type[ $appTypesF_IsMulty ] )
        							? $dataForm[ 'idApp' ]
        							: NULL,


        	'AgendaId'		=> $dataForm[ 'agendaslist' ],
        	'appTypeSel'	=> $data[ 'session' ][ 'apptypelist' ],
        	'agendas'		=> $dataForm[ 'agendaslist' ],
//        	'date'			=> $data[ 'session' ][ 'chosenday' ],
        	'date'			=> $dataForm[ 'real_selected_date' ],
        	'time'			=> $dataForm[ 'time' ],
        	'max_number'	=> 1,
        	'client_id'		=> '',
        	'comment'		=> ''
        );
        $new_app	= appointments_bl::prepareAppDataToSave( $new_app );

        return $new_app;
    }
//--------------------------------------------------------------------------------------------------

/**
*  performs validation at step 2
     * @author Constantine Kolenchenko <ckolenchenko@yukon.cv.ua>
     * @access private
     * @param $dataForm array contains some data for validation
     * @return Object where:
     *               Object->ErrorMsg - list of errors
     *               Object->Valid - result of validation (true/false)
     */
	private function doValidationAtStep2( $dataForm ){
		$app_params = self::prepareDataForValidationAtStep2( $dataForm );
		$test_obj = new validation_app_update( $app_params );
		$test_obj->EnablePrint( false );
        $test_obj->isTimeOccupied();
        $test_obj->isMaxNumClientsOverflowClientWiz();

		return $test_obj;
	}
//--------------------------------------------------------------------------------------------------

/**
 *  creates standart array for validation after appointment creation in widget
 * @author Constantine Kolenchenko <ckolenchenko@yukon.cv.ua>
 * @access private
 * @param $sessionAppData array contains session data obtained by GetDataWidget method
 * @return Array
 */
	private function prepareDataForValidationAtWidget( $sessionAppData ){
		global $CA_PATH; include($CA_PATH."variables_DB.php");
		$sess	= &$sessionAppData[ 'session' ];

        $app	= array(
        	'AgendaId'		=> $sess[ 'agendaslist' ],
        	'appTypeSel'	=> $sess[ 'apptypelist' ],
        	'agendas'		=> $sess[ 'agendaslist' ],
        	'date'			=> $sess[ 'chosenday' ],
        	'time'			=> $sess[ 'time' ],
        	'max_number'	=> 1,
        	'client_id'		=> $sessionAppData[ 'client_id' ],
        	'comment'		=> ''
        );

        $app[ 'AppId' ] = ( $sess[ 'idApp' ] == 'undefined' )
        	? $sess[ 'appoitmentId' ]
        	: $sess[ 'idApp' ];

        $app	= appointments_bl::prepareAppDataToSave( $app );

		return	$app;
	}
	//---------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------

   /**
     * performs ultimate validation before data writing
     * @author Constantine Kolenchenko <ckolenchenko@yukon.cv.ua>
     * @access private
     * @param $newAppData array contains  data for validation in standard format
     * @param $appTypeInfo array contains data of appoitnment type
     * @return Object where:
     *               Object->ErrorMsg - list of errors
     *               Object->Valid - result of validation (true/false)
     */
	private function doUltimateValidation( $userData ){
		global $CA_PATH; include( $CA_PATH."variables_DB.php" );
		$data	= self::GetDataWidget();
		$data[ 'client_id' ]	= $userData[ 'valid_user_id' ];


		$dataForm	= self::prepareDataForValidationAtWidget( $data );

		$test_obj = new validation_app_update( $dataForm );
		$test_obj-> EnablePrint( false );


		$test_obj->isAnyAgendaOfAppBlocked();
		$test_obj->isDateInArchiveDateZone();
		$test_obj->isOutAgendaRange();
		$test_obj->isTimeOccupied();
		$test_obj->isDateOff();
		$test_obj->isAppNumberOverflowClientWizard();
		$test_obj->isAppTypeTimeNotAvailable();
		$test_obj->isAppTypeDateNotAvailable();


		return $test_obj;
    }
//--------------------------------------------------------------------------------------------------

	/**
	 * Update information about multi appointment
	 *
	 * @author Igor Banadiga <ibanadiga@yukon.cv.ua>
	 * @param Array[] $dataForm Information about appointment
	 * @return Boolean $result If corect update information about appointment
	 */
	public	function UpdateMultiAppAtWidgetLoginDetails( $dataForm ){
		global $CA_PATH; include($CA_PATH."variables_DB.php");

		if (is_array($dataForm["appoitmentId"])){
			$dataForm["appoitmentId"]	= $dataForm["appoitmentId"]["session"]["ClientAssignId"];
		}
		$result	= make_appointments_dbl::UpdateMultiAppAtWidgetLoginDetails($dataForm);

        return $result;
	}
	//-------------------------------------------------------------------------------
	/**
	 * Update information about appointment
	 *
	 * @author Igor Banadiga <ibanadiga@yukon.cv.ua>
	 * @param Array[] $dataForm Information about appointment
	 * @return Boolean $result If corect update information about appointment
	 */
	public	function UpdateAppAtWidgetLoginDetails($dataForm){
			global $CA_PATH; include($CA_PATH."variables_DB.php");
			$result	= make_appointments_dbl::UpdateAppAtWidgetLoginDetails($dataForm);

        return $result;
	}
	//---------------------------------------------------------------------

	/*
	 *  doing save to DB after
	 *  entering login details
	 *
	 * @access public
	 * @param $userId - user ID
	 * @return $result of AssignClientsToAppAtWidget
	 */
	public function DoAssignClientsToAppAtWidget($userId, $userOrgId){
		global $CA_PATH; include($CA_PATH."variables_DB.php");

		$data		= self::GetDataWidget();
		$dataForm	= self::prepareDataForValidationAtWidget($data);
		$dataForm[  $appointmentsF_ClietnId  ]	= $userId;
		$dataForm[  $AppClientAssignF_Id  ]		= $data[ 'session' ][ 'appoitmentId' ];
		$dataForm[ 'clients' ][ 0 ]				= $userId;
		$dataForm[ 'clients_org' ][ 0 ]			= $userOrgId;
		$dataForm	= self::PrepearDataForWidgetLoginInfo( $dataForm );

		$AppTypeInfo=app_type_bl::getAppTypeById($data["session"]["apptypelist"]);

		if ($AppTypeInfo[ $appTypesF_IsMulty ] != 1){
			$result=make_appointments_dbl::AssignClientsToAppAtWidget( $dataForm );
			$result=self::UpdateAppAtWidgetLoginDetails( $dataForm );
		}else{
			$oldId	= make_appointments_dbl::isSetClientAsign( $dataForm[ $AppClientAssignF_AppId ], $dataForm[ $appointmentsF_ClietnId ] );


//echo "oldId: $oldId\n";

			if($oldId==0){
				$result	= self::UpdateMultiAppAtWidgetLoginDetails($dataForm);
			}else{
				$result	= make_appointments_dbl::UnassignClientFromMultiAppAtWidget($oldId);
				$result	= self::UpdateMultiAppAtWidgetLoginDetails($dataForm);
			}
		}
		return $result;
	}
//--------------------------------------------------------------------------------------------------

	/**
	 * function actionDoBackInWidgetStep2 - do Back action with previously selected items
	 * @access public
	 * @return $result
	 */
public function actionDoBackInWidgetStep2(){
		$dataForm = self::GetDataWidget();

		$dataForm["session"]["periodlist"] =_IS_CHOOSEN_CALENDAR;
		$dataForm["chosenday"] = $dataForm["session"]["chosenday"];
		$result = make_appointments_pl::ShowWidgetStep1($dataForm);
		return $result;

	}
//--------------------------------------------------------------------------------------------------

/*
 * does Back action and unlocks locked timeslot
 * @access public
 * @return $result
 */
public function actionDoBackInWidgetStep3(){
		global $CA_PATH;
		include($CA_PATH."variables_DB.php");
		$dataForm=self::GetDataWidget();

		if(self::chekTimeLimit()==false){
			$massage=_ERROR_TIME_OVER;
			$authentication_plObj=new authentication_pl();
			$result=$authentication_plObj->showWarning($massage);
			$result->addScript("xajax_actionDoShowWidgetStep1()");
		}else{

			$AppTypeInfo=app_type_bl::getAppTypeById($dataForm["session"]["apptypelist"]);
			self::DeleteNotFinishedAppAtWidget(
				$dataForm["session"]["appoitmentId"],
				$AppTypeInfo["$appTypesF_IsMulty"],
//				$dataForm["session"]['agendaslist'],
//				$dataForm["session"]['chosenday'],
				$dataForm["session"]["appoitmentId"]);

			$dataForm_edit['agendaslist']=$dataForm_edit['SelectedAgenda']=$dataForm["session"]['sel_box_ag_id'];
//			$dataForm_edit['Date']=$dataForm_edit['chosenday']=$dataForm["session"]['real_selected_date'];
//			$dataForm_edit['date']=date("Y-m-d",strtotime($dataForm["session"]['real_selected_date']));
			$dataForm_edit['Date']=$dataForm_edit['chosenday']=$dataForm["session"]['CurrentDate'];
			$dataForm_edit['date']=date("Y-m-d",strtotime($dataForm["session"]['CurrentDate']));



			self::SetDataWidget($dataForm_edit);
			$dataForm=self::PrepearDataForWidgetStep2();
			$result=make_appointments_pl::ShowWidgetStep2($dataForm);
		}
		return $result;
	}
//--------------------------------------------------------------------------------------------------

	/*
	 * function actionDoBackInWidgetStep4 - do Back action with previously selected items
	 * @access public
	 * @return $result
	 */
public function actionDoBackInWidgetStep4(){
		if(self::chekTimeLimit()==false){
			$massage=_ERROR_TIME_OVER;
			$authentication_plObj=new authentication_pl();
			$result=$authentication_plObj->showWarning($massage);
			$result->addScript("xajax_actionDoShowWidgetStep1()");
		}else{
			$dataForm=self::PrepearDataForWidgetStep3();
			$result=make_appointments_pl::ShowWidgetStep3($dataForm);
		}
		return $result;

	}
//--------------------------------------------------------------------------------------------------

/**
* Constants to control widget timetable appearance
*
*   ATTANTION!!! Value of      ( _ttl_height - 1 )   must be multiple of _ttl_line_height!!!
*                             Real outer widget width and height will be 2 pixels greater than _ttl_width and _ttl_height values.
* */
	const _qnt_clmns = 6;

	const _ttl_width			= 300;
	const _ttl_height			= 222;  //  13 lines  = (222 - 1)  / _ttl_line_height
	const _ttl_min_width		= 300;
	const _ttl_line_height		= 17;
	const _ttl_min_line_height	= 15;

    /**
 * adjustTimetableSize check if difined sizes were not outbouned minimal values
 * @author Constantine A. Kolenchenko <ckolenchenko@yukon.cv.ua>  $height
 * @return array
 */
	public function adjustTimetableSize(){
	    $size = array();
        (self::_ttl_width   < self::_ttl_min_width)  ?  $size['width']   = self::_ttl_min_width   : $size['width']  = self::_ttl_width;
        $size['height'] = self::_ttl_height;
        (self::_ttl_line_height < self::_ttl_min_line_height) ?  $size['line_height'] = self::_ttl_min_line_height : $size['line_height'] = self::_ttl_line_height;
	    return $size;
    }
	//---------------------------------------------------------------------

    /**
 * rebuildTimetableToRows
 * @author Constantine A. Kolenchenko <ckolenchenko@yukon.cv.ua>  $height
 * @return array
 */
    public function rebuildTimetableToRows($timetable, $size){
        if( isset( $timetable[ 'countNotavailable' ] ) ){ unset( $timetable[ 'countNotavailable' ] ); }
        $n_item = 0; $ttl_line = array();
        $new_ttl = array ();
        $old_date=date("Ymd",strtotime($_SESSION['WidgetData']['date']));
        foreach ($timetable as $key=>&$ttl_row){

//echo "key: $key\n";

        	$new_date=substr($key, 0,8);
            if (  ($n_item == self::_qnt_clmns)||($old_date!=$new_date) ){
            	$new_ttl[] = $ttl_line;
                $n_item = 0; $ttl_line = array();
            	if($old_date!=$new_date){
					$new_ttl[]['new_day'] = $key;
				}
            }
			$old_date=$new_date;
            if ($ttl_row['Class'] != 'exist'){
                switch ($ttl_row['Class']){
                    case 'advance': 	$ttl_row['Class'] = 'ico_widget_slot_call'; break;
                    case 'free': 		$ttl_row['Class'] = 'ico_widget_slot_free'; break;
                }

                $ttl_line[] = $ttl_row;
                $n_item++;
            }
        }
        (count($ttl_line) > 0) ? $new_ttl[] = $ttl_line:'';
        return $new_ttl;
    }
    //---------------------------------------------------------------------

	/**
	 * Delete not finished app in widget
	 * Changed by C.Kolenchenko <ckolenchenko@yukon.cv.ua> on 14-04-2010
	 * @param Integer $appId Id of appoinment
	 * @param Integer $appType_type Id of App type
	 * @param Integer $agendaId Id of agenda
	 * @param Date $chosenday Date app
	 * @param Integer $appAssign Id of assign client  record
	 */
	public function DeleteNotFinishedAppAtWidget( $appId=null, $appType_type=null/*,$agendaId=null,$chosenday=null*/, $appAssign=null ){
		if( $appType_type == 0 ){
 			appointments_bl::deleteApp( $appId );
		}else{
			$resultDel=make_appointments_dbl::UnassignClientFromMultiAppAtWidget( $appAssign );
		}
	}
	//---------------------------------------------------------------------
	public function setPendingInfoToCookies(){
		setcookie("cookiepending_appoitmentId", $_SESSION["WidgetData"]["appoitmentId"],time()+15*60);
		setcookie("cookiepending_apptypelist", $_SESSION["WidgetData"]["apptypelist"],time()+15*60);
		setcookie("cookiepending_SelectedAgenda", $_SESSION["WidgetData"]["SelectedAgenda"],time()+15*60);
		setcookie("cookiepending_chosenday", $_SESSION["WidgetData"]["chosenday"],time()+15*60);
	}
	//---------------------------------------------------------------------
	public function clearPendingFromCookies(){
		setcookie("cookiepending_appoitmentId", "" ,time());
		setcookie("cookiepending_apptypelist", "" ,time());
		setcookie("cookiepending_SelectedAgenda", "" ,time());
		setcookie("cookiepending_chosenday", "" ,time());
	}
	//---------------------------------------------------------------------

	public function deletePendingFromCookies(){
		global $CA_PATH;include($CA_PATH."variables_DB.php");
		if(isset($_COOKIE)&&(isset($_COOKIE["cookiepending_appoitmentId"]))){
			$data=array();
			$data["appoitmentId"]	=$_COOKIE["cookiepending_appoitmentId"];
			$data["apptypelist"]	=$_COOKIE["cookiepending_apptypelist"];
			$data["SelectedAgenda"]	=$_COOKIE["cookiepending_SelectedAgenda"];
			$data["chosenday"]		=$_COOKIE["cookiepending_chosenday"];
			self::SetDataWidget($data);
			if(self::chekTimeLimit(true)==true){
				$dataForm=self::GetDataWidget();
				$AppTypeInfo=app_type_bl::getAppTypeById($dataForm["session"]["apptypelist"]);
				self::DeleteNotFinishedAppAtWidget(
					$dataForm["session"]["appoitmentId"],
					$AppTypeInfo[ $appTypesF_IsMulty ],
//					$dataForm["session"]['SelectedAgenda'],
//					$dataForm["session"]['chosenday'],
					$dataForm["session"]["appoitmentId"]);
			}
//			self::DelDataWidget();
			unset( $_SESSION[ 'WidgetData' ] );
		}
		//self::clearPendingFromCookies();
	}
	//---------------------------------------------------------------------

	public function getCategoriesListForWidget_Mod($catId='null', $agId='null', $appTypeId='null') {
		global $CA_PATH; include($CA_PATH."variables_DB.php");
		$ini = &$_SESSION['ini_widget_params'];

		$cat_r_o 		= $ini[ 'cat_r_o' ];
		$app_type_r_o	= $ini[ 'app_type_r_o' ];
		$ag_r_o			= $ini[ 'ag_r_o' ];

		$cat_list = array ();
		if (!$cat_r_o && !$app_type_r_o && !$ag_r_o) {
			$cat_list = category_bl::getCategpriesListForWizard();
		} elseif (!$cat_r_o && !$app_type_r_o && $ag_r_o) {
			$cat_list = category_bl::getCategoryListAssignedToAgendaById ($agId);
			(!$cat_list) ? $cat_list = array ():'';
		}elseif((!$cat_r_o && $app_type_r_o && !$ag_r_o) || (!$cat_r_o && $app_type_r_o && $ag_r_o)) {
			$app_type = app_type_bl::getAppTypeById($appTypeId);
			if ($app_type["$appTypesF_AgeCatID"] != '') {
				$cat = category_bl::getCategoryById ($app_type["$appTypesF_AgeCatID"]);
				$cat_list[] = $cat;
			}
		}else{
			$cat = category_bl::getCategoryById ($catId);
			$cat_list[] =$cat;
		}
		return $cat_list;
	}
//--------------------------------------------------------------------------------------------------

/**
 * adds secondary idems to agedna list to use it for select box
 * @author Constantine Kolenchenko <ckolenchenko@yukon.cv.ua>
 * @param $list - array(
 * 									[id] - agenda's id
 * 									[name] - agenda's name
 *                             )
 * @return array. The same one as $list parameter.
 */
	public function addSecondaryItemsToAgendaList( $list = array() ){
		array_unshift( $list, array( 'id' => make_appointments_bl::_whole_list, 'name' => _ALL_AGENDAS ) );
		return $list;
	}
//--------------------------------------------------------------------------------------------------

/**
 * gets Agednas list for widget select box
 * @author	Constantine Kolenchenko <ckolenchenko@yukon.cv.ua>
 * @param	integer $catId - category id. Must be integer of 'all'
 * @param	bool $isSecondary - defines if add secondary items to list
 * @return	array. The same one as $list parameter.
 */
	public function getAgednasListForWidgetSelectBox( $catId, $isSecondary = true ){
		$agendas = category_bl::getAgListAssignedToCategoryById_ForWidget( $catId );

		( $agendas == '' ) ? $agendas = array():'';
		$ag_names = user_bl::getAgendasFullNames( $agendas );
		( $isSecondary ) ? $ag_names = self::addSecondaryItemsToAgendaList( $ag_names ):'';
		return $ag_names;
	}
//--------------------------------------------------------------------------------------------------

/**
 * gets list of agedas' names who have appointments for selected multi app type. This method is used on widget.
 * @author	Constantine Kolenchenko <ckolenchenko@yukon.cv.ua>
 * @access	public
 * @param	integer $appTypeId - app type id.
 * @param	boolean $isSecondary - defines if list must contain secondary items.
 * @return	array of agendas' names and ids
 */
	public function getAgednasListForWidgetSelectBoxForMulti( $appTypeId, $isSecondary = true ){
		$agendas = user_bl::getAgendasWhoHaveAppsForMultiAppType( $appTypeId );

		$ag_names = user_bl::getAgendasFullNames( $agendas );
		( $isSecondary ) ? $ag_names = make_appointments_bl::addSecondaryItemsToAgendaList( $ag_names/*,$isMakeCoose*/ ):'';

		return $ag_names;
	}
//--------------------------------------------------------------------------------------------------

//@THINK: Remove.
/**
* gets the first free agenda and forms month matrix
* @author	Constantine Kolenchenko <ckolenchenko@yukon.cv.ua>
* @param	integer $month - month number.
* @param	integer $year - year number.
* @param	integer $appTypeId - appointment type id.
* @param	array $agendas
* @param	date $today - current date in format dd-mm-yyyy. This param is used for debug purposes.
* @param	time $now - current time in format hh:mm. This param is used for debug purposes.
* @param	string $orgCode. This param is used for debug purposes.
* @return  array
*                (
*                    [<db_date>] => 0 or agenda's id
*                )
*/
	public function getAnyFreeAgForMonth( $month, $year, $appTypeId, $agendas, $today='', $now='',  $orgCode='' ){
		global $CA_PATH; include( $CA_PATH."variables_DB.php" );

		$org_code	= ( $orgCode == '' ) ? $_SESSION['org_code'] : $orgCode;

		$today_date	= ( $today == '' ) ? utils_bl::GetTodayDate() : $today;
		$db_today	= utils_bl::GetDbDate( $today_date );

		$time	= ( $now == '' ) ? utils_bl::GetCurrentTime() : $now;

		$month_info	= array();
		$agenda_ids_line	= "";
		foreach( $agendas as $agenda ){
			$agenda_ids_line .= $agenda[ $agendasF_Id ].",";
		}
		$l_str				= strlen( $agenda_ids_line ) - 1;
		$agenda_ids_line	= substr( $agenda_ids_line, 0, $l_str );

		$month_info	= make_appointments_dbl::getAnyFreeAgForMonth( $month, $year, $appTypeId, $agenda_ids_line, $db_today, $time, $org_code );

		$db_dates	= utils_bl::GetDbDates( utils_bl::getMonthDates( $month, $year ) );
		foreach( $db_dates as $db_date ){
			( !array_key_exists( $db_date , $month_info ) ) ? $month_info[ $db_date ]	= 0:'';
		}
		ksort( $month_info );

		return $month_info;
	}
//--------------------------------------------------------------------------------------------------

/**
* gets available days for month period which is begun from the first date
* @author	Constantine Kolenchenko <ckolenchenko@yukon.cv.ua>
* @param	integer $month - month number.
* @param	integer $year - year number.
* @param	integer $appTypeId - appointment type id.
* @param	integer	$agId - agenda's id.
* @param	integer	$catId - category id.
* @param	string	$dbToday - current date in format dd-mm-yyyy.
* @param	string	$timeNow - current time in format hh:mm.
* @param	string	$orgCode
* @return  array
* @return  array
*                (
*                    [<db_date>] => 0 or agenda's id
*                )
*/
	public function getAvailableDaysForMonth_24( $month, $year, $appTypeId, $agId, $catId, $today='', $now='', $orgCode='' ){
		global $CA_PATH; include( $CA_PATH."variables_DB.php" );

//public static function getAvailableDaysForMonth_24( $month, $year, $appTypeId, $agId, $catId, $dtNow, $isTest, $orgCode )


		$org_code	= ( NULL == $orgCode) ? $_SESSION[ 'org_code' ] : $orgCode;

		$db_today	= ( '' != $today )	? date( 'Y-m-d', strtotime( $today ) ) : date( 'Y-m-d' );
		$db_now		= ( '' != $now )	? date( 'H:i:s', strtotime( $now ) ): date( 'H:i:s' );
		$d_t_now	= $db_today.' '.$db_now;

		$info	= make_appointments_dbl::getAvailableDaysForMonth_24( $month, $year, $appTypeId, $agId, $catId, $d_t_now, false, $org_code );
		$info	= $info[ 0 ][ 'res' ];
		$info	= json_decode( $info, true );
		ksort( $info );
		return $info;













//		$org_code	= ( $orgCode == '' ) ? $_SESSION[ 'org_code' ] : $orgCode;
//		$db_today	= ( $today == '' ) ? date( 'Y-m-d' ) : date( 'Y-m-d', strtotime( $today ) );
//		$time		= ( $now == '' ) ? date( 'H:i' ) : $now;
//
//		$avail_days	= make_appointments_dbl::getAvailableDaysForMonth_24( $month, $year, $appTypeId, $agId, $catId, $db_today, $time, $org_code );
//		$db_dates	= utils_bl::GetDbDates( utils_bl::getMonthDates( $month, $year ) );
//
//		$month_info	= array();
//		foreach( $db_dates as $db_date ){
//			list( $y, $m, $d )	= explode( '-', $db_date );
//			$pos	= strpos( $avail_days, $d );
//			$month_info[ $db_date ]	= ( NULL != $pos && '' != $pos ) ? 1 : 0;
//		}
//		return $month_info;



	}
//--------------------------------------------------------------------------------------------------

    /**
* gets the first free agenda and forms week matrix
* @author	Constantine Kolenchenko <ckolenchenko@yukon.cv.ua>
* @param	integer $appTypeId - appointment type id.
* @param	array $agendas
* @param	date $today - current date in format dd-mm-yyyy. This param is used for debug purposes.
* @param	time $now - current time in format hh:mm. This param is used for debug purposes.
* @param	string $orgCode. This param is used for debug purposes.
* @return  array
*                (
*                    [<db_date>] => 0 or agenda's id
*                )
* */
	public function getAvailableDaysForSevenDates_24( $appTypeId, $agId, $catId, $today='', $now='', $orgCode = NULL ){
		global $CA_PATH; include( $CA_PATH."variables_DB.php" );
		$org_code	= ( $orgCode == NULL ) ? $_SESSION[ 'org_code' ] : $orgCode;

		$db_today	= ( $today == '' ) ? date( 'Y-m-d' ) : date( 'Y-m-d', strtotime( $today ) );
		$time	= ( $now == '' ) ? date( 'H:i' ) : $now;

		$avail_days	= make_appointments_dbl::getAvailableDaysForSevenDates_24( $appTypeId, $agId, $catId, $db_today, $time, $org_code );
		$db_dates	= utils_bl::getSevenDates( $db_today, 'Y-m-d' );

		$week_info	= array();
		foreach( $db_dates as $db_date ){
			list( $y, $m, $d )	= explode( '-', $db_date );
			$pos	= strpos( $avail_days, $d );
			$week_info[ $db_date ]	= ( NULL != $pos && '' != $pos ) ? 1 : 0;
		}
		return $week_info;
	}
//--------------------------------------------------------------------------------------------------











//@THINK: Remove.
    /**
* gets the first free agenda and forms week matrix
* @author	Constantine Kolenchenko <ckolenchenko@yukon.cv.ua>
* @param	integer $appTypeId - appointment type id.
* @param	array $agendas
* @param	date $today - current date in format dd-mm-yyyy. This param is used for debug purposes.
* @param	time $now - current time in format hh:mm. This param is used for debug purposes.
* @param	string $orgCode. This param is used for debug purposes.
* @return  array
*                (
*                    [<db_date>] => 0 or agenda's id
*                )
* */
//	public function getAnyFreeAgForSevenDates( $appTypeId, $agendas, $today='', $now='',  $orgCode='' ){
//		global $CA_PATH; include( $CA_PATH."variables_DB.php" );
//		$org_code	= ( $orgCode == '' ) ? $_SESSION['org_code'] : $orgCode;
//
//		$today_date	= ( $today == '' ) ? utils_bl::GetTodayDate() : $today;
//		$db_today	= utils_bl::GetDbDate( $today_date );
//
//		$time	= ( $now == '' ) ? utils_bl::GetCurrentTime() : $now;
//
//		$week_info	= array();
//		$agenda_ids_line	= "";
//		foreach( $agendas as $agenda ){ $agenda_ids_line .= $agenda[ $agendasF_Id ].","; }
//		$l_str				= strlen( $agenda_ids_line ) - 1;
//		$agenda_ids_line	= substr( $agenda_ids_line, 0, $l_str );
//
//		$week_info	= make_appointments_dbl::getAnyFreeAgForSevenDates( $appTypeId, $agenda_ids_line, $db_today, $time, $org_code );
//
//		$db_dates	= utils_bl::GetDbDates( utils_bl::getSevenDates( $today_date ) );
//
//		foreach( $db_dates as $db_date ){ ( !array_key_exists( $db_date , $week_info ) ) ? $week_info[ $db_date ]	= 0:''; }
//		ksort( $week_info );
//		return $week_info;
//	}
//--------------------------------------------------------------------------------------------------

//    /* *THINK: Don't delete this functions !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//     *
// * rebuildTimetableToColumns
// * @author Constantine A. Kolenchenko <ckolenchenko@yukon.cv.ua>  $height
// * @return array
// */
//    public function rebuildTimetableToColumns($timetable, $size){
//        if (isset($timetable['countNotavailable'])) unset ($timetable['countNotavailable']);
//        $qnt_lines =  count($timetable);
//
//        $qnt_wdt_lines = ($size['height'] - 1) / $size['line_height'];
//        $qnt_wdt_lines = intval ($qnt_wdt_lines);
//
//        $qnt_colamns = intval(ceil($qnt_lines / $qnt_wdt_lines));
//        $col_width = 100 / $qnt_colamns;
//        $col_width = round($col_width, 2);
//
//        $empty_sell = array ('Time'=>'&nbsp;', 'Class'=>'exist', 'Do'=>_EMPTY_STRING);
//        for ($n_wdt_line = 0; $n_wdt_line < $qnt_wdt_lines; $n_wdt_line++){
//            $new_line = array();
//            for ($n_sell = 0; $n_sell < $qnt_colamns; $n_sell++){
//                $ttl_index = $n_wdt_line + $n_sell * $qnt_wdt_lines;
//                (isset($timetable[$ttl_index])) ? $sell = &$timetable[$ttl_index] : $sell = &$empty_sell;
//                $sell['width'] = $col_width;
//                $new_line[] = $sell;
//            }
//            $new_timetable[] = $new_line;
//        }
//
//        return $new_timetable;
//    }
//
//    public function adjustTimetableSize(){
//        $size = array();
//        (self::_ttl_width   < self::_ttl_min_width)  ?  $size['width']   = self::_ttl_min_width   : $size['width']  = self::_ttl_width;
//        $size['height'] = self::_ttl_height;
//        (self::_ttl_line_height < self::_ttl_min_line_height) ?  $size['line_height'] = self::_ttl_min_line_height : $size['line_height'] = self::_ttl_line_height;
//        return $size;
//    }
//--------------------------------------------------------------------------------------------------


}//End of class

?>