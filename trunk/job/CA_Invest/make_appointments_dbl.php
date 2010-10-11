<?php
/**
 * Created by Igor Banadiga on 20.11.2008
 * Copyright Yukon Software Ukraine 2008. All rights reserved.
 */

/**
 * This class realizes work on dataBese layer of make appointments
 * for work from question tree list and creation, change or delete question tree
 *
 * @author		Igor Banadiga <ibanadiga@yukon.cv.ua>
 * @version		$Id: widgets,v 1.0 2008/11/20 12:31:00 cellog Exp $;
 * @copyright	Copyright Yukon Software Ukraine 2008. All rights reserved.
 * @package		widgets
 * @subpackage 	makeappointments
 * @access		public
 */

class make_appointments_dbl{
	//---------------------------------------------------------------------
	/**
	 * Create appointment record
	 *
	 * @param Array[] $dataForm Array of appoitment info
	 * @return Integer $id id of appoitment
	 */
	public	function InsertAppAtWidgetStep2( $dataForm ){
		global $CA_PATH, $logger; include( $CA_PATH."variables_DB.php" );
//		$org_code	= $_SESSION[ 'org_code' ];



//print_r( $dataForm );exit;


		$id	= false;
		$query ="INSERT INTO $tableAppointments(
    					$appointmentsF_ClietnId,
    					$dbTable_StartDate,
    					$dbTable_StartTime,
    					$appointmentsF_AppTypeId,
    					$appointmentsF_AgendaId,
    					$appointmentsF_StatusId,
    					$appointmentsF_Comment,
    					$appointmentsF_CreateDate,
						$appointmentsF_CreateTime,".
    					"$dbTable_EndDate,".
    					"$appointmentsF_MaxNumberClient,
    					$appointmentsF_Creater,
    					$appointmentsF_IniWidgetParams,
    					$field_org_code)
				VALUES (   -1,
                        '".$dataForm[ $dbTable_StartDate ]."',
						'".$dataForm[ $dbTable_StartTime ]."',
						".$dataForm[ $appointmentsF_AppTypeId ].",
						".$dataForm[ $appointmentsF_AgendaId ].",
						".$dataForm[ $appointmentsF_StatusId ].",
						'".$dataForm[ $appointmentsF_Comment ]."',
						NOW(),
						NOW(),".
						"'".$dataForm[ $dbTable_StartDate ]."',".
						$dataForm[ $appointmentsF_MaxNumberClient ].",
					    2,
					    '".$dataForm[ $appointmentsF_IniWidgetParams ]."',
                        '".$_SESSION[ 'org_code' ]."'
                  		)";


//print_r($query);exit;

    	$result=mysql_query($query);
		if (!$result) {
				$logger->error(utils_bl::buildMysqlErrorInfo(get_class(),$query));
		}else{
				$id=mysql_insert_id();
		}

    	return $id;
	}//End function InsertAppAtWidgetStep2
	//---------------------------------------------------------------------
	/**
	 * Insert info about multy app to assign appoitment
	 *
	 * @param Array[] $dataForm Info about mylty app
	 * @return Integer $id Create assign app
	 */
	public	function InsertMultiAppAtWidgetStep2($dataForm){
		global $CA_PATH, $logger;
		include($CA_PATH."variables_DB.php");
		$org_code=$_SESSION['org_code'];

		$query="INSERT INTO $tableAppClientAssign(
						$AppClientAssignF_AppId,
    					$AppClientAssignF_ClientId,
						$AppClientAssignF_CreateDate,
						$AppClientAssignF_CreateTime,
    					$field_org_code)
			 	VALUES (
			 			".$dataForm["$AppClientAssignF_AppId"].",
                     	".$dataForm['newId'].",
		  				NOW(),
						NOW(),
                        '$org_code'
                     )
		";

		$result=mysql_query($query);
		if (!$result) {
			$logger->error(utils_bl::buildMysqlErrorInfo(get_class(),$query));
		}else{
			$id=mysql_insert_id();
		}

		return $id;
	}//End function InsertMultiAppAtWidgetStep2
	//---------------------------------------------------------------------
	/**
	 * Save data to database after enter comment
	 *
	 * @param Array[] $dataForm Data from form
	 * @return Boolean $result Status save comment
	 */
	public	function UpdateAppAtWidgetStep3($dataForm){
		global $CA_PATH, $logger;
		include($CA_PATH."variables_DB.php");
		$org_code=$_SESSION['org_code'];

		$query = "UPDATE $tableAppointments
					SET
		            	$appointmentsF_Comment='".$dataForm["$appointmentsF_Comment"]."'
					WHERE $appointmentsF_AppId=".$dataForm["$appointmentsF_AppId"]."
						AND $field_org_code='$org_code' ";
        $result = mysql_query($query);
		if (!$result) {
				$logger->error(utils_bl::buildMysqlErrorInfo(get_class(),$query));
		}

        return $result;
	}//End function updateAppAtWidgetStep3
	//---------------------------------------------------------------------
	/**
	 * Update information about appointment
	 *
	 * @param Array[] $dataForm Information about appointment
	 * @return Boolean $result If corect update information about appointment
	 */
	public	function UpdateAppAtWidgetLoginDetails($dataForm){
		global $CA_PATH, $logger;
		include($CA_PATH."variables_DB.php");
		$org_code=$_SESSION['org_code'];

		$query = "UPDATE $tableAppointments
					SET
		            	$appointmentsF_ClietnId=".$dataForm["$appointmentsF_ClietnId"].",
						$appointmentsF_StatusId=".$dataForm['status_id'].",
						$appointmentsF_Creater=2
					WHERE $appointmentsF_AppId=".$dataForm["$appointmentsF_AppId"]."
						AND $field_org_code='$org_code'";

        $result = mysql_query($query);
		if (!$result) {
				$logger->error(utils_bl::buildMysqlErrorInfo(get_class(),$query));
		}

        return $result;
	}
	//---------------------------------------------------------------------
	/**
	 * Assign clients to appointment
	 *
	 * @param Array[] $dataForm Data about client
	 * @return Boolean $result If correct assign clients to appointment
	 */
	public	function AssignClientsToAppAtWidget($dataForm)
	{
		global $CA_PATH, $logger;
		include($CA_PATH."variables_DB.php");
		$org_code=$_SESSION['org_code'];

	    $query ="INSERT INTO $tableAppClientAssign(
	    			$AppClientAssignF_AppId,
    				$AppClientAssignF_ClientId,
					$AppClientAssignF_CreateDate,
					$AppClientAssignF_CreateTime,
    				$field_org_code)
			 	VALUES (".
    				$dataForm[ $appointmentsF_AppId ].", ".
//    				$dataForm[ $appointmentsF_ClietnId ].", ".
    				$dataForm[ 'clients_org' ][ 0 ].", ".
		  			"NOW(), ".
					"NOW(), ".
                    "'$org_code')";

		$result=mysql_query($query);
		if (!$result) {
				$logger->error(utils_bl::buildMysqlErrorInfo(get_class(),$query));
		}

        return $result;
	}//End function AssignClientsToAppAtWidget
	//---------------------------------------------------------------------
	/**
	 * Update information about multy appointment
	 *
	 * @param Array[] $dataForm Information about appointment
	 * @return Boolean $result If corect update information about appointment
	 */
	public	function UpdateMultiAppAtWidgetLoginDetails($dataForm){
		global $CA_PATH, $logger;
		include($CA_PATH."variables_DB.php");
		$org_code	= $_SESSION['org_code'];



//	    $query	= "UPDATE $tableAppClientAssign
//					SET
//						$AppClientAssignF_ClientId=".$dataForm[$appointmentsF_ClietnId]."
//					WHERE $AppClientAssignF_Id=".$dataForm[$AppClientAssignF_Id]."
//						AND   $field_org_code='$org_code'";


	    $query	= "UPDATE $tableAppClientAssign
					SET
						$AppClientAssignF_ClientId=".$dataForm[ 'clients_org' ][ 0 ]."
					WHERE $AppClientAssignF_Id=".$dataForm[$AppClientAssignF_Id]."
						AND   $field_org_code='$org_code'";





		$result	= mysql_query($query);
		if (!$result) {
				$logger->error(utils_bl::buildMysqlErrorInfo(get_class(),$query));
		}

		return $result;
	}//End function UpdateMultiAppAtWidgetLoginDetails
	//---------------------------------------------------------------------
	/**
	 * Delete not finish assign if client close widget or refresh not finished
	 *
	 * @param Integer $AssId Id of assign client to appointment
	 * @return Boolean $result If corect unassign
	 */
	public	function UnassignClientFromMultiAppAtWidget($AssId){
		global $CA_PATH, $logger; include($CA_PATH."variables_DB.php");
		$org_code=$_SESSION['org_code'];

	    $query ="DELETE FROM $tableAppClientAssign
				    WHERE $AppClientAssignF_Id=$AssId
		                AND   $field_org_code='$org_code'";

		$result=mysql_query($query);
		if (!$result) {
				$logger->error(utils_bl::buildMysqlErrorInfo(get_class(),$query));
		}

		return $result;
	}
	//---------------------------------------------------------------------

	/**
	 * Checked if appointment is exist
	 *
	 * @param Integer $id Id of appointment
	 * @param Boolean $pending If need only pending appointment
	 * @return Boolean $result  Appointment is exist or not exist
	 */
	public	function isSetAppointment($id, $pending=false){
		global $CA_PATH, $logger;
		include($CA_PATH."variables_DB.php");
		$org_code=$_SESSION['org_code'];

		$query ="SELECT $appointmentsF_AppId
					FROM $tableAppointments
					WHERE $appointmentsF_AppId='$id'
			 			AND   $field_org_code='$org_code' ".
			 	(($pending==true)?"AND $appointmentsF_ClietnId <0 ":"");

		$result=mysql_query($query);
		if (!$result) {
				$logger->error(utils_bl::buildMysqlErrorInfo(get_class(),$query));
		}else{

			$num	= mysql_num_rows($result);

			if( $num == 1 ){
				$result=true;
			}else{
				$result=false;
			};
		}
		return $result;
	}
	//---------------------------------------------------------------------
	/**
	 * Checked if exist assign to same appointment
	 *
	 * @param Integer $id Id of assign
	 * @param Boolean $pending If need only pending appointment
	 * @return Boolean $result Assign to same appointment is exist or not exist
	 */
	public	function isSetAsign($id,$pending=false){
		global $CA_PATH, $logger;
		include($CA_PATH."variables_DB.php");

		$org_code=$_SESSION['org_code'];

		$query ="SELECT $AppClientAssignF_Id FROM $tableAppClientAssign
					WHERE $AppClientAssignF_Id='$id'
		 				AND   $field_org_code='$org_code' ".
		 		(($pending==true)?"AND $AppClientAssignF_ClientId <0 ":"");

		$result=mysql_query($query);
		if (!$result) {
				$logger->error(utils_bl::buildMysqlErrorInfo(get_class(),$query));
		}else{
			 if(mysql_num_rows($result)==1){
			 	$result=true;
			 }else{
			 	$result=false;
			};
		}

		return $result;
	}//End function isSetAsign
	//---------------------------------------------------------------------
	/**
	 * Check is exist some assigned client
	 *
	 * @param Integer $ida Id of assign
	 * @param Integer $idc Id of org user
	 * @return Integer $result Return Id of assign
	 */
	public	function isSetClientAsign($ida,$idc){
		global $CA_PATH, $logger;
		include($CA_PATH."variables_DB.php");
		$org_code=$_SESSION['org_code'];
		$id=false;
		$query ="SELECT $AppClientAssignF_Id FROM $tableAppClientAssign
					WHERE $AppClientAssignF_AppId='$ida'
						AND   $AppClientAssignF_ClientId='$idc'
		 				AND   $field_org_code='$org_code'";

		$result=mysql_query($query);
		if( !$result ){
			$logger->error(utils_bl::buildMysqlErrorInfo(get_class(),$query));
		}else{
			$row	= mysql_fetch_assoc( $result );
			$id		= $row[ $AppClientAssignF_Id ];
		}

		return $id;
	}//End function isSetClientAsign
	//---------------------------------------------------------------------

	private function dummyPlugForAnyAgenda( $agIdsLine, $dbToday ){
		$info = array();
		$week_dates	= utils_bl::getSevenDates( $dbToday );
		$ag_ids		= explode( ',', $agIdsLine );
		foreach( $week_dates as $date ){
			$db_date	= utils_bl::GetDbDate( $date );
			$ag_key	= array_rand( $ag_ids );
			$info[ $db_date ]	= $ag_ids[ $ag_key ];
		}
		return $info;

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
* @param	string	$dbToday - current date in format yyyy-mm-dd.
* @param	string	$timeNow - current time in format hh:mm.
* @param	string	$orgCode
* @return  array
*/
//	public function getAvailableDaysForMonth_24( $month, $year, $appTypeId, $agId, $catId, $dbToday, $timeNow, $orgCode ){
//		global $CA_PATH, $logger, $mysqli_obj; include( $CA_PATH."variables_DB.php" );
//
//		return $info;
//	}
	public static function getAvailableDaysForMonth_24( $month, $year, $appTypeId, $agId, $catId, $dtNow, $isTest, $orgCode ){
		global $CA_PATH, $logger, $mysqli_obj; include( $CA_PATH."variables_DB.php" );

		$is_tst	= ( $isTest ) ? 'TRUE' : 'FALSE';

		$sql	=
"CALL `get_any_agenda_month`( '".$dtNow."', ".$month.", ".$year.", ".$appTypeId.", ".$agId.", ".$catId.", '".$orgCode."', ".$is_tst." )";

		if( $mysqli_obj->multi_query( $sql ) ){
			while( $result = $mysqli_obj->use_result() ){
				while( $row = $result->fetch_array( MYSQLI_ASSOC ) ){
					$info[] = $row;
				}
				$result->close();
				$next_result	= $mysqli_obj->next_result();
			}
		}else{
			$logger->error( utils_bl::buildMysqliErrorInfo( 'make_appointments_dbl::getAnyFreeAgForMonth', $sql ) );
			$info	= false;
		}
		return	$info;
	}


//	public function getAvailableDaysForMonth_24( $month, $year, $appTypeId, $agId, $catId, $dbToday, $timeNow, $orgCode ){
//		global $CA_PATH, $logger, $mysqli_obj; include( $CA_PATH."variables_DB.php" );
//
//		$info = NULL;
//		if( 1 ){	//	on/off dummy plug
//			$sql	= "CALL get_available_days_for_month_new( '".$dbToday."', '".$timeNow."', ".$month.", ".$year.", ".$appTypeId.", ".$agId.", ".$catId.", '".$orgCode."' )";
//
//			if( $mysqli_obj->multi_query( $sql ) ){
//				while( $result = $mysqli_obj->use_result() ){
//					while( $row = $result->fetch_array( MYSQLI_ASSOC ) ){
//						$info	= $row[ 'days' ];
//					}
//					$result->close();
//					$next_result	= $mysqli_obj->next_result();
//				}
//			}else{
//				$logger->error( utils_bl::buildMysqliErrorInfo( 'make_appointments_dbl::getAnyFreeAgForMonth', $sql ) );
//			}
//		}else{//	dummy plug
//			$info	= self::dummyPlugForAnyAgenda( $agIdsLine, $dbToday );
//		}
//
//		return $info;
//	}
//--------------------------------------------------------------------------------------------------

    /**
* gets available days for seven dates period which is begun from today date
* @author	Constantine Kolenchenko <ckolenchenko@yukon.cv.ua>
* @param	integer $appTypeId - appointment type id.
* @param	string	$agIdsLine - string of agendas' ids delimeted by comma.
* @param	date	$dbToday - current date in format yyyy-mm-yy.
* @param	time	$timeNow - current time in format hh:mm.
* @param	string $orgCode
* @return  array
* */
	public function getAvailableDaysForSevenDates_24( $appTypeId, $agId, $catId, $dbToday, $timeNow, $orgCode ){
//	public function getAvailableDaysForSevenDates_24( $appTypeId, $agIdsLine, $dbToday, $timeNow, $orgCode )
		global $CA_PATH, $logger, $mysqli_obj; include( $CA_PATH."variables_DB.php" );

		$info = array();

		if( 1 ){	//	on/off dummy plug
			$sql	= "CALL `get_available_days_for_week_new`( '".$dbToday."', '".$timeNow."', ".$appTypeId.", ".$agId.", ".$catId.", '".$orgCode."' )";

			if( $mysqli_obj->multi_query( $sql ) ){
				while( $result = $mysqli_obj->use_result() ){
					while( $row = $result->fetch_array( MYSQLI_ASSOC ) ){
						$info	= $row[ 'days' ];
					}
					$result->close();
					$next_result	= $mysqli_obj->next_result();
				}
			}else{
				$logger->error( utils_bl::buildMysqliErrorInfo( 'make_appointments_dbl::getAnyFreeAgForSevenDates', $sql ) );
			}
		}else{//	dummy plug
			$info	= self::dummyPlugForAnyAgenda( $agIdsLine, $dbToday );
		}
		return $info;
	}
//--------------------------------------------------------------------------------------------------
function updateAppId($new_id,$old_id){

		global $CA_PATH;include($CA_PATH."variables_DB.php");
		$org_code=$_SESSION['org_code'];

	    $query ="UPDATE $tableAppointments set
				$appointmentsF_AppId =".$new_id."
	           where $appointmentsF_AppId =".$old_id." and $field_org_code = '$org_code'
		 ";

		$result=mysql_query($query);
		return true	;

}
//------------------------------------------------------------------------


//@THINK: Remove.
    /**
* gets the first free agenda and forms month matrix
* @author	Constantine Kolenchenko <ckolenchenko@yukon.cv.ua>
* @param	integer $appTypeId - appointment type id.
* @param	string	$agIdsLine - string of agendas' ids delimeted by comma.
* @param	date	$dbToday - current date in format yyyy-mm-yy.
* @param	time	$timeNow - current time in format hh:mm.
* @param	string $orgCode
* @return  array
* */
//	public function getAnyFreeAgForSevenDates( $appTypeId, $agIdsLine, $dbToday, $timeNow, $orgCode ){
//		global $CA_PATH, $logger, $mysqli_obj; include( $CA_PATH."variables_DB.php" );
//
//
//
//		$info = array();
//
//		if( 1 ){	//	on/off dummy plug
//
////			$sql	= "CALL get_any_agenda_for_week( '".$dbToday."', '".$timeNow."', ".$appTypeId.", '".$agIdsLine."', '".$orgCode."' )";
//			$sql	= "CALL get_any_agenda_for_week_new( '".$dbToday."', '".$timeNow."', ".$appTypeId.", '".$agIdsLine."', '".$orgCode."' )";
//
//			if( $mysqli_obj->multi_query( $sql ) ){
//				while( $result = $mysqli_obj->use_result() ){
//					while( $row = $result->fetch_array( MYSQLI_ASSOC ) ){
//						$info[ $row[ 'DATE' ] ] = 1;
//					}
//					$result->close();
//					$next_result	= $mysqli_obj->next_result();
//				}
//			}else{
//				$logger->error( utils_bl::buildMysqliErrorInfo( 'make_appointments_dbl::getAnyFreeAgForSevenDates', $sql ) );
//			}
//		}else{//	dummy plug
//			$info	= self::dummyPlugForAnyAgenda( $agIdsLine, $dbToday );
//		}
//		return $info;
//	}
//--------------------------------------------------------------------------------------------------


//@THINK: Remove.
/**
* gets the first free agenda and forms month matrix
* @author	Constantine Kolenchenko <ckolenchenko@yukon.cv.ua>
* @param	integer $month - month number.
* @param	integer $year - year number.
* @param	integer $appTypeId - appointment type id.
* @param	string	$agIdsLine - string of agendas' ids delimeted by comma.
* @param	date	$dbToday - current date in format yyyy-mm-yy.
* @param	time	$timeNow - current time in format hh:mm.
* @param	string $orgCode
* @return  array
*/
//	public function getAnyFreeAgForMonth( $month, $year, $appTypeId, $agIdsLine, $dbToday, $timeNow, $orgCode ){
//		global $CA_PATH, $logger, $mysqli_obj; include( $CA_PATH."variables_DB.php" );
//
//		$info = array();
//
//		if( 0 ){	//	on/off dummy plug
//			$sql	= "CALL get_any_agenda_for_month( '".$dbToday."', '".$timeNow."', ".$month.", ".$year.", ".$appTypeId.", '".$agIdsLine."', '".$orgCode."' )";
//			if( $mysqli_obj->multi_query( $sql ) ){
//				while( $result = $mysqli_obj->use_result() ){
//					while( $row = $result->fetch_array( MYSQLI_ASSOC ) ){
//						$info[ $row[ 'DATE' ] ] = $row[ $agendasF_Id ];
//					}
//					$result->close();
//					$next_result	= $mysqli_obj->next_result();
//				}
//			}else{
//				$logger->error( utils_bl::buildMysqliErrorInfo( 'make_appointments_dbl::getAnyFreeAgForMonth', $sql ) );
//			}
//		}else{//	dummy plug
//			$info	= self::dummyPlugForAnyAgenda( $agIdsLine, $dbToday );
//		}
//		return $info;
//	}
//--------------------------------------------------------------------------------------------------


//	This method was commented on 14-04-2010 by C.Kolenchenko <ckolenchenko@yukon.cv.ua>

	/**
	 * Delete not finish simple appointment if client close widget or refresh not finished
	 *
	 * @param Integer $AppId Id of not finished appointment
	 * @return Boolean $result If corect delete
	 */
//	public	function DeleteNotFinishedAppAtWidget($AppId){
//		global $CA_PATH, $logger;
//		include($CA_PATH."variables_DB.php");
//		$org_code=$_SESSION['org_code'];
//
//	    $query ="DELETE FROM $tableAppointments
//			    	WHERE $appointmentsF_AppId=$AppId
//	                	AND   $field_org_code='$org_code'";
//
//		$result=mysql_query($query);
//		if (!$result) {
//				$logger->error(utils_bl::buildMysqlErrorInfo(get_class(),$query));
//		}
//
//		return $result;
//	}
	//---------------------------------------------------------------------
}//End of class
?>