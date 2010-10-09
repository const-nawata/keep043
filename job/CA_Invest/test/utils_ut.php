<?php
class utils_ut{

	const _is_not_multi	= 0;
	const _is_multi		= 1;

    const c_nSunday		= 0; const c_sundayMask		= 1;	const c_iso_nSunday		= 7;
    const c_nMonday		= 1; const c_mondayMask		= 2;	const c_iso_nMonday		= 1;
    const c_nTuesday	= 2; const c_tuesdayMask	= 4;	const c_iso_nTuesday	= 2;
    const c_nWednesday	= 3; const c_wednesdayMask	= 8;	const c_iso_nWednesday	= 3;
    const c_nThursday	= 4; const c_thursdayMask	= 16;	const c_iso_nThursday	= 4;
    const c_nFriday		= 5; const c_fridayMask		= 32;	const c_iso_nFriday		= 5;
    const c_nSaturday	= 6; const c_saturdayMask	= 64;	const c_iso_nSaturday	= 6;


	//		Agenda
	private static function prepareAgendaToSave( &$data, $orgCode ){
		$data	= array(
			'NAME'			=> ( isset( $data[ 'NAME' ] ) ) ? $data[ 'NAME' ] : 'UT_agenda_'.$_SESSION[ 'm_ind' ]++,
			'START_TIME'	=> ( isset( $data[ 'START_TIME' ] ) ) ? $data[ 'START_TIME' ] : '09:00',
			'END_TIME'		=> ( isset( $data[ 'END_TIME' ] ) ) ? $data[ 'END_TIME' ] : '18:00',
			'DURATION'		=> ( isset( $data[ 'DURATION' ] ) ) ? $data[ 'DURATION' ] : '30',
			'ORG_CODE'		=> ( $orgCode == NULL ) ? _UT_ORG_CODE : $orgCode
		);
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public static function create_agenda( $data = array(), $orgCode = NULL ){
		global $gl_MysqliObj;

		self::prepareAgendaToSave( $data, $orgCode );
		$sql	=
"INSERT INTO `ca_agendas` ( ".
	"`NAME`, ".
	"`START_TIME`, ".
	"`END_TIME`, ".
	"`DURATION`, ".
	"`ORG_CODE` ".
" ) VALUES ( ".
	"'".$data[ 'NAME' ]."', ".
	"'".$data[ 'START_TIME' ]."', ".
	"'".$data[ 'END_TIME' ]."', ".
		$data[ 'DURATION' ].", ".
		"'".$data[ 'ORG_CODE' ]."' )";

		$result = $gl_MysqliObj->query( $sql );

		if( !$result ){
			self::putLogInfo( self::getMysqlErrorInfo( $sql, 'utils_ut::createAgendaUser' ) );
			$id	= 0;
		}else{
			$id	= $gl_MysqliObj->insert_id;
		}
		return $id;
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public static function get_agenda_ById( $id ){
		global $gl_MysqliObj;
		$data	= array();

		$sql	=
"SELECT ".
	"`AGENDA_ID`, ".
	"`NAME`, ".
	"TIME_FORMAT( `START_TIME`, '%H:%i' ) AS `START_TIME`, ".
	"TIME_FORMAT( `END_TIME`, '%H:%i' ) AS `END_TIME`, ".
	"`DURATION`, ".
	"`ORG_CODE` ".
"FROM `ca_agendas` ".
"WHERE `AGENDA_ID` = ".$id." LIMIT 1";

		$result = $gl_MysqliObj->query( $sql );

		if( !$result ){
			self::putLogInfo( self::getMysqlErrorInfo( $sql, 'utils_ut::getAgendaById' ) );
		}else{
			$data = $result->fetch_assoc();
		}
		return $data;
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	//		Category of agendas
	private static function prepareCatToSave( &$data, $orgCode ){
		$data	= array(
			'AGE_CAT_NAME'	=> ( isset( $data[ 'AGE_CAT_NAME' ] ) ) ? $data[ 'AGE_CAT_NAME' ] : 'UT_agenda_'.$_SESSION[ 'm_ind' ]++,
			'ORG_CODE'		=> ( $orgCode == NULL ) ? _UT_ORG_CODE : $orgCode
		);
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public static function create_cat( $data = array(), $orgCode = NULL ){
		global $gl_MysqliObj;

		self::prepareCatToSave( $data, $orgCode );
		$sql	=
"INSERT INTO `ca_agendas_categories` ( ".
	"`AGE_CAT_NAME`, ".
	"`ORG_CODE` ".
" ) VALUES ( ".
	"'".$data[ 'AGE_CAT_NAME' ]."', ".
	"'".$data[ 'ORG_CODE' ]."' )";

		$result = $gl_MysqliObj->query( $sql );

		if( !$result ){
			self::putLogInfo( self::getMysqlErrorInfo( $sql, 'utils_ut::createAgendaUser' ) );
			$id	= 0;
		}else{
			$id	= $gl_MysqliObj->insert_id;
		}
		return $id;
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public static function get_cat_ById( $id ){
		global $gl_MysqliObj;
		$data	= array();

		$sql	=
"SELECT ".
	"`AGE_CAT_ID`, ".
	"`AGE_CAT_NAME`, ".
	"`ORG_CODE` ".
"FROM `ca_agendas_categories` ".
"WHERE `AGE_CAT_ID` = ".$id." LIMIT 1";

		$result = $gl_MysqliObj->query( $sql );

		if( !$result ){
			self::putLogInfo( self::getMysqlErrorInfo( $sql, 'utils_ut::getAgendaById' ) );
		}else{
			$data = $result->fetch_assoc();
		}
		return $data;
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE


	//		Appointment type
	private static function prepareAppTypeDataToSave( &$data, $orgCode ){
		$data	= array(
			'TIME'					=> ( isset( $data[ 'TIME' ] ) ) ? $data[ 'TIME' ] : 30,
			'MIN_TIME'				=> ( isset( $data[ 'MIN_TIME' ] ) ) ? $data[ 'MIN_TIME' ] : 'NULL',
			'MAX_TIME'				=> ( isset( $data[ 'MAX_TIME' ] ) ) ? $data[ 'MAX_TIME' ] : 'NULL',
			'IS_PUBLIC'				=> ( isset( $data[ 'IS_PUBLIC' ] ) ) ? $data[ 'IS_PUBLIC' ] : 1,
			'IS_MULTY'				=> ( isset( $data[ 'IS_MULTY' ] ) ) ? $data[ 'IS_MULTY' ] : 0,
			'AT_PERIOD_START_TIME'	=> ( isset( $data[ 'AT_PERIOD_START_TIME' ] ) ) ? $data[ 'AT_PERIOD_START_TIME' ] : NULL,
			'AT_PERIOD_END_TIME'	=> ( isset( $data[ 'AT_PERIOD_END_TIME' ] ) ) ? $data[ 'AT_PERIOD_END_TIME' ] : NULL,
			'AT_PERIOD_DAY'			=> ( isset( $data[ 'AT_PERIOD_DAY' ] ) ) ? $data[ 'AT_PERIOD_DAY' ] : 127,
			'NAME'					=> ( isset( $data[ 'NAME' ] ) ) ? $data[ 'NAME' ] : 'UT App Type'.$_SESSION[ 'm_ind' ]++,
			'AGE_CAT_ID'			=> ( isset( $data[ 'AGE_CAT_ID' ] ) ) ? $data[ 'AGE_CAT_ID' ] : 'NULL',
			'ORG_CODE'				=> ( $orgCode == NULL ) ? _UT_ORG_CODE : $orgCode
		);
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public static function create_app_type( $data, $orgCode = NULL ){
		global $gl_MysqliObj;
		self::prepareAppTypeDataToSave( $data, $orgCode );

		$sql	=
"INSERT INTO `ca_appointment_types` (".
	"`TIME`, ".
	"`MIN_TIME`, ".
	"`MAX_TIME`, ".
	"`IS_PUBLIC`, ".
	"`IS_MULTY`, ".
	"`AT_PERIOD_START_TIME`, ".
	"`AT_PERIOD_END_TIME`, ".
	"`AT_PERIOD_DAY`, ".
	"`NAME`, ".
	"`AGE_CAT_ID`, ".
	"`ORG_CODE` ".
") VALUES (".
	$data[ 'TIME' ].",".
	$data[ 'MIN_TIME' ].",".
	$data[ 'MAX_TIME' ].",".
	$data[ 'IS_PUBLIC' ].",".
	$data[ 'IS_MULTY' ].",".
	( ( NULL != $data[ 'AT_PERIOD_START_TIME' ] ) ? "'".$data[ 'AT_PERIOD_START_TIME' ]."'" : "NULL" ).",".
	( ( NULL != $data[ 'AT_PERIOD_END_TIME' ] ) ? "'".$data[ 'AT_PERIOD_END_TIME' ]."'" : "NULL" ).",".
	$data[ 'AT_PERIOD_DAY' ].",".
	"'".$data[ 'NAME' ]."',".
	"".$data[ 'AGE_CAT_ID' ].",".
	"'".$data[ 'ORG_CODE' ]."'".
")";

		$result = $gl_MysqliObj->query( $sql );

		if( !$result ){
			self::putLogInfo( self::getMysqlErrorInfo( $sql, 'utils_ut::createAppType' ) );
			$id	= 0;
		}else{
			$id	= $gl_MysqliObj->insert_id;
		}
		return $id;
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public static function get_app_type_ById( $id ){
		global $gl_MysqliObj;
		$data	= array();

		$sql	=
"SELECT ".
	"`ID`, ".
	"`TIME`, ".
	"`MIN_TIME`, ".
	"`MAX_TIME`, ".
	"`IS_PUBLIC`, ".
	"`IS_MULTY`, ".
	"TIME_FORMAT( `AT_PERIOD_START_TIME`, '%H:%i' ) AS `AT_PERIOD_START_TIME`, ".
	"TIME_FORMAT( `AT_PERIOD_END_TIME`, '%H:%i' ) AS `AT_PERIOD_END_TIME`, ".
	"`AT_PERIOD_DAY`, ".
	"`NAME`, ".
	"`AGE_CAT_ID`, ".
	"`ORG_CODE` ".
"FROM `ca_appointment_types` ".
"WHERE `ID` = ".$id." LIMIT 1";

		$result = $gl_MysqliObj->query( $sql );

		if( !$result ){
			self::putLogInfo( self::getMysqlErrorInfo( $sql, 'utils_ut::getAppTypeById' ) );
		}else{
			$data = $result->fetch_assoc();
		}
		return $data;
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	//		Client
	private static function prepareClientDataToSave( &$data, $orgCode ){
		$data	= array(
			'FIRSTNAME'	=> ( isset( $data[ 'FIRSTNAME' ] ) ) ? $data[ 'FIRSTNAME' ] : 'UT_client_'.$_SESSION[ 'm_ind' ]++,
			'ORG_CODE'	=> ( $orgCode == NULL ) ? _UT_ORG_CODE : $orgCode
		);
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public static function create_client( $data = array(), $orgCode = NULL ){
		global $gl_MysqliObj;
		self::prepareClientDataToSave( $data, $orgCode );

		$sql	=
"INSERT INTO `ca_clients` (".
	"`FIRSTNAME`, ".
	"`ORG_CODE` ".
") VALUES (".
	"'".$data[ 'FIRSTNAME' ]."',".
	"'".$data[ 'ORG_CODE' ]."'".
")".
"";

		$result = $gl_MysqliObj->query( $sql );

		if( !$result ){
			self::putLogInfo( self::getMysqlErrorInfo( $sql, 'utils_ut::createClientUser' ) );
			$id	= 0;
		}else{
			$id	= $gl_MysqliObj->insert_id;
		}
		return $id;
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public static function get_client_ById( $id ){
		global $gl_MysqliObj;
		$data	= array();

		$sql	=
"SELECT ".
	"`ID`, ".
	"`FIRSTNAME`, ".
	"`ORG_CODE` ".
"FROM `ca_clients` ".
"WHERE `ID` = ".$id." LIMIT 1".
"";

		$result = $gl_MysqliObj->query( $sql );

		if( !$result ){
			self::putLogInfo( self::getMysqlErrorInfo( $sql, 'utils_ut::getClientById' ) );
		}else{
			$data = $result->fetch_assoc();
		}
		return $data;
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

    //		Appointment
	private static function prepareAppDataToSave( &$data, $orgCode ){
		$data[ 'START_DATE' ]			= date( 'Y-m-d', strtotime( $data[ 'START_DATE' ] ) );
		$data[ 'MAX_NUMBER_CLIENT' ]	= ( isset( $data[ 'MAX_NUMBER_CLIENT' ] ) ) ? $data[ 'MAX_NUMBER_CLIENT' ] : 1;
		$data[ 'COMMENT' ]				= ( isset( $data[ 'COMMENT' ] ) ) ? $data[ 'COMMENT' ] : "UT appointment";
		$data[ 'ORG_CODE' ]				= ( $orgCode == NULL ) ? _UT_ORG_CODE : $orgCode;
		$data[ 'agendas' ]				= explode( ',', $data[ 'agendas' ] );
		$data[ 'clients' ]				= explode( ',', $data[ 'clients' ] );
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public static function create_app( $data, $orgCode = NULL ){
		global $gl_MysqliObj;
		self::prepareAppDataToSave( $data, $orgCode );

		$sql	=
"INSERT INTO `ca_appointments` (".
	"`APPTYPE_ID`, ".
	"`START_DATE`, ".
	"`START_TIME`, ".
	"`MAX_NUMBER_CLIENT`, ".
	"`COMMENT`, ".
	"`ORG_CODE` ".
") VALUES (".
	$data[ 'APPTYPE_ID' ].",".
	"'".$data[ 'START_DATE' ]."',".
	"'".$data[ 'START_TIME' ]."',".
	$data[ 'MAX_NUMBER_CLIENT' ].",".
	"'".$data[ 'COMMENT' ]."',".
	"'".$data[ 'ORG_CODE' ]."'".
")".
"";
		$result = $gl_MysqliObj->query( $sql );

		if( !$result ){
			self::putLogInfo( self::getMysqlErrorInfo( $sql, 'utils_ut::createApp' ) );
			return 0;
		}
		$app_id	= $gl_MysqliObj->insert_id;


		//	Agendas assigning
		$values	= array();
		foreach( $data[ 'agendas' ] as $user_id ){
			$values[]	= "( ".$user_id.", ".$app_id." )";
		}
		$values	= implode( ', ', $values );

		$sql	=
"INSERT INTO `ca_app_assigned_agendas` ( `AGENDA_ID`, `APP_ID` ) VALUES ".$values;

		$result = $gl_MysqliObj->query( $sql );

		if( !$result ){
			self::putLogInfo( self::getMysqlErrorInfo( $sql, 'utils_ut::createApp' ) );
			return 0;
		}

		//	Clients assigning
		$values	= array();
		foreach( $data[ 'clients' ] as $user_id ){
			$values[]	= "( ".$user_id.", ".$app_id." )";
		}
		$values	= implode( ', ', $values );

		$sql	=
"INSERT INTO `ca_app_assigned_clients` ( `CLIENT_ID`, `APP_ID` ) VALUES ".$values;

		$result = $gl_MysqliObj->query( $sql );

		if( !$result ){
			self::putLogInfo( self::getMysqlErrorInfo( $sql, 'utils_ut::createApp' ) );
			return 0;
		}

		return $app_id;
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public static function get_app_ById( $id ){
		global $gl_MysqliObj;
		$data	= array();

		$sql	=
"SELECT ".
	"`APP_ID`, ".
	"`APPTYPE_ID`, ".
	"DATE_FORMAT( `START_DATE`, '%d-%m-%Y' ) AS `START_DATE`, ".
	"DATE_FORMAT( `END_DATE`, '%d-%m-%Y' ) AS `END_DATE`, ".
	"TIME_FORMAT( `START_TIME`, '%H:%i' ) AS `START_TIME`, ".
	"TIME_FORMAT( `END_TIME`, '%H:%i' ) AS `END_TIME`, ".
	"`MAX_NUMBER_CLIENT`, ".
	"`COMMENT`, ".
	"`PATT_ID`, ".

	"`AppAgendasIds`( `APP_ID` ) AS `agendas`, ".
	"`AppClientsIds`( `APP_ID` ) AS `clients`, ".


	"`ORG_CODE` ".
"FROM `ca_appointments` ".
"WHERE `APP_ID` = ".$id." LIMIT 1".
"";

		$result = $gl_MysqliObj->query( $sql );

		if( !$result ){
			self::putLogInfo( self::getMysqlErrorInfo( $sql, 'utils_ut::getAppById' ) );
		}else{
			$data = $result->fetch_assoc();
			$data[ 'agendas' ]	= explode( ',', $data[ 'agendas' ] );
			$data[ 'clients' ]	= explode( ',', $data[ 'clients' ] );
		}
		return $data;
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE


    //		Free time
    const _cycle_day	= 1;
    const _cycle_week	= 2;


    private static function create_pattern( &$data ){
    	global $gl_MysqliObj;

		$sql	=
"INSERT INTO `ca_daysoff_pattern` (".
	"`CYCLE`, ".
	"`PERIOD`, ".
	"`WEEK_DAYS` ".
") VALUES (".
	$data[ 'CYCLE' ].",".
	$data[ 'PERIOD' ].",".
	$data[ 'WEEK_DAYS' ].
")".
"";
		$result = $gl_MysqliObj->query( $sql );

		if( !$result ){
			self::putLogInfo( self::getMysqlErrorInfo( $sql, 'utils_ut::create_pattern' ) );
			unset( $data[ 'PATT_ID' ] );
			return;
		}
		$id	= $gl_MysqliObj->insert_id;

		//	Strings for SQL query
		$data[ 'PATT_ID' ]	= array(
			'fld'	=> "`PATT_ID`, ",
			'val'	=> $id.", "
		);
    }
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	private static function prepareBlockedDataToSave( &$data, $orgCode ){
		$data[ 'START_DATE' ]	= date( 'Y-m-d', strtotime( $data[ 'START_DATE' ] ) );
		$data[ 'END_DATE' ]		= date( 'Y-m-d', strtotime( $data[ 'END_DATE' ] ) );
		$data[ 'ORG_CODE' ]		= ( $orgCode == NULL ) ? _UT_ORG_CODE : $orgCode;

		if( isset( $data[ 'PATT_ID' ] ) && $data[ 'PATT_ID' ] ){
			$data[ 'CYCLE' ]	= ( isset( $data[ 'CYCLE' ] ) ) ? $data[ 'CYCLE' ] : self::_cycle_day;
			$data[ 'PERIOD' ]	= ( isset( $data[ 'PERIOD' ] ) ) ? $data[ 'PERIOD' ] : 1;

			switch( $data[ 'CYCLE' ] ){

				case self::_cycle_day:
					if( 1 == $data[ 'PERIOD' ] ){
						//	Strings for SQL query
						$data[ 'PATT_ID' ]	= array(
							'fld'	=> "",
							'val'	=> ""
						);
						return;
					}
					$data[ 'WEEK_DAYS' ]	= 0;
				break;

				case self::_cycle_week:
					$data[ 'WEEK_DAYS' ]	= ( isset( $data[ 'WEEK_DAYS' ] ) ) ? $data[ 'WEEK_DAYS' ] : 0;
				break;

				default;
					//	Strings for SQL query
					$data[ 'PATT_ID' ]	= array(
						'fld'	=> "",
						'val'	=> ""
					);
					return;
			}
		}else{
			//	Strings for SQL query
			$data[ 'PATT_ID' ]	= array(
				'fld'	=> "",
				'val'	=> ""
			);
			return;
		}

		self::create_pattern( $data );
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public static function create_free_time( $data, $orgCode = NULL ){
		global $gl_MysqliObj;
		self::prepareBlockedDataToSave( $data, $orgCode );

		if( array_key_exists( 'PATT_ID', $data ) ){

			$sql	=
"INSERT INTO `ca_free_times` (".
	"`AGENDA_ID`, ".
	"`START_DATE`, ".
	"`END_DATE`, ".
	"`START_TIME`, ".
	"`END_TIME`, ".
	$data[ 'PATT_ID' ][ 'fld' ].
	"`ORG_CODE` ".
") VALUES (".
	$data[ 'AGENDA_ID' ].",".
	"'".$data[ 'START_DATE' ]."',".
	"'".$data[ 'END_DATE' ]."',".
	"'".$data[ 'START_TIME' ]."',".
	"'".$data[ 'END_TIME' ]."',".
	$data[ 'PATT_ID' ][ 'val' ].
	"'".$data[ 'ORG_CODE' ]."'".
")".
"";
			$result = $gl_MysqliObj->query( $sql );

			if( !$result ){
				self::putLogInfo( self::getMysqlErrorInfo( $sql, 'utils_ut::create_free_time' ) );
				return 0;
			}
		}else{
			return 0;
		}

		$id	= $gl_MysqliObj->insert_id;
		return $id;
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public static function get_free_time_ById( $id ){
		global $gl_MysqliObj;
		$data	= array();

		$sql	=
"SELECT ".
	"`ca_free_times`.`DAY_ID` AS `DAY_ID`, ".
	"`ca_free_times`.`AGENDA_ID` AS `AGENDA_ID`, ".
	"DATE_FORMAT( `ca_free_times`.`START_DATE`, '%d-%m-%Y' ) AS `START_DATE`, ".
	"DATE_FORMAT( `ca_free_times`.`END_DATE`, '%d-%m-%Y' ) AS `END_DATE`, ".
	"TIME_FORMAT( `ca_free_times`.`START_TIME`, '%H:%i' ) AS `START_TIME`, ".
	"TIME_FORMAT( `ca_free_times`.`END_TIME`, '%H:%i' ) AS `END_TIME`, ".

	"`ca_daysoff_pattern`.`ID` AS `PATT_ID`, ".
	"`ca_daysoff_pattern`.`CYCLE` AS `CYCLE`, ".
	"`ca_daysoff_pattern`.`PERIOD` AS `PERIOD`, ".
	"`ca_daysoff_pattern`.`WEEK_DAYS` AS `WEEK_DAYS`, ".

	"`ca_free_times`.`ORG_CODE` AS `ORG_CODE` ".
"FROM `ca_free_times` ".
"LEFT JOIN `ca_daysoff_pattern` ON `ca_daysoff_pattern`.`ID` = `ca_free_times`.`PATT_ID` ".
"WHERE `ca_free_times`.`DAY_ID` = ".$id." LIMIT 1".
"";

		$result = $gl_MysqliObj->query( $sql );

		if( !$result ){
			self::putLogInfo( self::getMysqlErrorInfo( $sql, 'utils_ut::getAppById' ) );
		}else{
			$data = $result->fetch_assoc();
		}
		return $data;
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

    //		Day off

	public static function create_off_day( $data, $orgCode = NULL ){
		global $gl_MysqliObj;
		self::prepareBlockedDataToSave( $data, $orgCode );

		if( array_key_exists( 'PATT_ID', $data ) ){

			$sql	=
"INSERT INTO `ca_daysoff` (".
	"`AGENDA_ID`, ".
	"`START_DATE`, ".
	"`END_DATE`, ".
	$data[ 'PATT_ID' ][ 'fld' ].
	"`ORG_CODE` ".
") VALUES (".
	$data[ 'AGENDA_ID' ].",".
	"'".$data[ 'START_DATE' ]."',".
	"'".$data[ 'END_DATE' ]."',".
	$data[ 'PATT_ID' ][ 'val' ].
	"'".$data[ 'ORG_CODE' ]."'".
")".
"";
			$result = $gl_MysqliObj->query( $sql );

			if( !$result ){
				self::putLogInfo( self::getMysqlErrorInfo( $sql, 'utils_ut::create_free_time' ) );
				return 0;
			}
		}else{
			return 0;
		}

		$id	= $gl_MysqliObj->insert_id;
		return $id;
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public static function get_off_day_ById( $id ){
		global $gl_MysqliObj;
		$data	= array();

		$sql	=
"SELECT ".
	"`ca_daysoff`.`DAY_ID` AS `DAY_ID`, ".
	"`ca_daysoff`.`AGENDA_ID` AS `AGENDA_ID`, ".
	"DATE_FORMAT( `ca_daysoff`.`START_DATE`, '%d-%m-%Y' ) AS `START_DATE`, ".
	"DATE_FORMAT( `ca_daysoff`.`END_DATE`, '%d-%m-%Y' ) AS `END_DATE`, ".

	"`ca_daysoff_pattern`.`ID` AS `PATT_ID`, ".
	"`ca_daysoff_pattern`.`CYCLE` AS `CYCLE`, ".
	"`ca_daysoff_pattern`.`PERIOD` AS `PERIOD`, ".
	"`ca_daysoff_pattern`.`WEEK_DAYS` AS `WEEK_DAYS`, ".

	"`ca_daysoff`.`ORG_CODE` AS `ORG_CODE` ".
"FROM `ca_daysoff` ".
"LEFT JOIN `ca_daysoff_pattern` ON `ca_daysoff_pattern`.`ID` = `ca_daysoff`.`PATT_ID` ".
"WHERE `ca_daysoff`.`DAY_ID` = ".$id." LIMIT 1".
"";

		$result = $gl_MysqliObj->query( $sql );

		if( !$result ){
			self::putLogInfo( self::getMysqlErrorInfo( $sql, 'utils_ut::getAppById' ) );
		}else{
			$data = $result->fetch_assoc();
		}
		return $data;
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

    //	General

/**
 * updates data in specifyed table.
 * @param	string $tbl - DB table name
 * @param	array $data - data to update. Array key - field name, item - field value. The first item of array is id key and value.
 */
	public static function updateTable( $tbl, $data = array() ){
		global $gl_MysqliObj;

		$sql	=
"UPDATE `".$tbl."` SET ";

		$lines	= array();
		$is_id	= true;
		foreach( $data as $fld => $val ){
			if( $is_id ){
				$is_id		= false;
				$id_name	= $fld;
				$id_val		= $val;
			}else{
				$lines[]	= "`".$fld."` = ".( ( is_numeric( $val ) ) ? $val : "'".$val."'" );
			}
		}
		$sql_data	= implode( ',', $lines );
		$sql	.= $sql_data." WHERE `".$id_name."` = ".$id_val;


		$result = $gl_MysqliObj->query( $sql );

		if( !$result ){
			self::putLogInfo( self::getMysqlErrorInfo( $sql, 'utils_ut::updateTable' ) );
		}

		return $result;
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE
	public static function addItem( $radical, $data = array() ){
    	$func_name	= 'create_'.$radical;
    	$id			= self::$func_name( $data );

    	$func_name	= 'get_'.$radical.'_ById';
    	$arr_name	= $radical.'s';
    	$_SESSION[ $arr_name ][]	= self::$func_name( $id );
    }
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public static function execSelectQuery( $sql, $resource = 'Undefined' ){
		global $gl_MysqliObj;

		$list	= array();
		$result = $gl_MysqliObj->query( $sql );
		if( $result ){
			while( $row = $result->fetch_assoc() ){
				$list[] = $row;
			}
			$result->close();

		}else{
			self::putLogInfo( self::getMysqlErrorInfo( $sql, $resource ), 'Error' );
		}

		return $list;
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public static function getMysqlErrorInfo( $sql, $resource = 'Underfined'){
		global $gl_MysqliObj;
		$content	= "MySQL error ".$gl_MysqliObj->errno.". '".$gl_MysqliObj->error."'. Resource: ".$resource.". The whole query is '".$sql."'";
		$content	= htmlspecialchars( $content,  ENT_QUOTES );
		return $content;
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public static function putLogInfo( $content = 'Undefined', $level = 'info' ){
		global $gl_MysqliObj;
		$d_t	= date( 'Y-m-d H:i:s' );
		$sql	=
"INSERT INTO `log` ( `date_time`, `level`, `content` ) VALUES ( '".$d_t."', '".$level."', '".$content."' )";

		$result = $gl_MysqliObj->query( $sql );
		return $result;
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public static function deleteAllOrgDataFromTable( $table, $orgCode ){
		global $gl_MysqliObj;
		$sql	=
"DELETE FROM `".$table."` WHERE `ORG_CODE` LIKE '%".$orgCode."%'";
		$result = $gl_MysqliObj->query( $sql );
		return $result;
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public static function getBusyItemsData( $orgCode = _UT_ORG_CODE ){
		$sql	=
"SELECT ".
	"`cur_date` AS `date`, ".
	"`d_t_start`, ".
	"`d_t_end` ".
"FROM `dbg_busy_items_tbl` ".
"";

		$info	= self::execSelectQuery( $sql, 'utils_ut::getBusyItemsData' );

		$res	= array();
		foreach( $info as $item ){
			$res[ $item[ 'date' ] ][]	= array(
				'd_t_start' => $item[ 'd_t_start' ],
				'd_t_end' => $item[ 'd_t_end' ]
			);
		}

		return $res;
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public static function deleteOrgData( $orgCode = _UT_ORG_CODE ){
		self::deleteAllOrgDataFromTable( 'ca_appointment_types', $orgCode );
		self::deleteAllOrgDataFromTable( 'ca_clients', $orgCode );
		self::deleteAllOrgDataFromTable( 'ca_agendas', $orgCode );
		self::deleteAllOrgDataFromTable( 'ca_agendas_categories', $orgCode );
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public static function assignAgendasToCat( $agIds, $catId, $orgCode = _UT_ORG_CODE ){
		global $gl_MysqliObj;

		$sql	=
"INSERT INTO `ca_agendas_assigned_categories` (".
	"`AGE_CAT_ID`,".
	"`AGENDA_ID`,".
	"`ORG_CODE`".
") VALUES ";

		$sql_vals	= array();
		foreach( $agIds as $ag_id ){
			$sql_vals[]	=
"( ".$catId.",".$ag_id.",'".$orgCode."' )";
		}

		$sql_vals	= implode( ',', $sql_vals );
		$sql	.= $sql_vals;

		$result = $gl_MysqliObj->query( $sql );

		if( !$result ){
			self::putLogInfo( self::getMysqlErrorInfo( $sql, 'utils_ut::assignAgendasToCat' ) );
		}
		return $result;
	}
	//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public static function getCategoryAssignedInfo( $catId, $orgCode = _UT_ORG_CODE ){
		$sql	=
"SELECT ".
	"`ca_agendas_categories`.`AGE_CAT_ID` AS `AGE_CAT_ID`, ".
	"`ca_agendas_categories`.`AGE_CAT_NAME` AS `AGE_CAT_NAME`, ".

	"`ca_agendas`.`AGENDA_ID` AS `AGENDA_ID`, ".
	"`ca_appointment_types`.`ID` AS `app_type_id` ".

"FROM `ca_agendas_categories` ".
"LEFT JOIN `ca_agendas_assigned_categories` ON `ca_agendas_assigned_categories`.`AGE_CAT_ID` = `ca_agendas_categories`.`AGE_CAT_ID` ".
"LEFT JOIN `ca_agendas` ON `ca_agendas`.`AGENDA_ID` = `ca_agendas_assigned_categories`.`AGENDA_ID` ".
"LEFT JOIN `ca_appointment_types` ON `ca_appointment_types`.`AGE_CAT_ID` = `ca_agendas_categories`.`AGE_CAT_ID` ".
"WHERE	`ca_agendas_categories`.`AGE_CAT_ID` = ".$catId.
		" AND `ca_agendas_categories`.`ORG_CODE` = '".$orgCode."'".
	"";
		$info	= self::execSelectQuery( $sql, 'utils_ut::getCategoryAssignedInfo' );

		$cat_typs	= array();	$app_ind	= 0;
		$cat_aggs	= array();	$ag_ind		= 0;
		foreach( $info as $item ){
			if( $item[ 'app_type_id' ] != NULL && $item[ 'app_type_id' ] != '' ){
				$cat_typs[ $item[ 'app_type_id' ] ]	= $app_ind;
				$app_ind++;
			}

			if( $item[ 'AGENDA_ID' ] != NULL && $item[ 'AGENDA_ID' ] != '' ){
				$cat_aggs[ $item[ 'AGENDA_ID' ] ]	= $ag_ind;
				$ag_ind++;
			}
		}
		$cat_typs	= array_flip( $cat_typs );
		$cat_aggs	= array_flip( $cat_aggs );

		$res	= array(
			'AGE_CAT_ID'	=> $info[ 0 ][ 'AGE_CAT_ID' ],
			'AGE_CAT_NAME'	=> $info[ 0 ][ 'AGE_CAT_NAME' ],
			'ags'	=> $cat_aggs,
			'typs'	=> $cat_typs
		);

		return $res;
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	//	Secondary

/**
 * checks if day is off for selected agenda user.
 * @param	string $date - date in format `dd-mm-yyyy`
 * @param	integer $agId - agendas id.
 * @return	boolean - true if day is off
 */
	public static function isDayOff( $date, $agId ){
		global $gl_MysqliObj;

		$db_date	= date( 'Y-m-d', strtotime( $date ) );

		$sql =
"SELECT ".
	"CONCAT( '".$db_date."', ' ',  '00:00:00' ) AS `d_t_start_real`, ".
	"`START_DATE` AS `db_start_date`, ".
	"`END_DATE` AS `db_end_date`, ".

	"IFNULL(`CYCLE`, 1 ) AS `CYCLE`, ".
	"IFNULL(`PERIOD`, 1) AS `PERIOD`, ".
	"IFNULL(`WEEK_DAYS`, 0 ) AS `WEEK_DAYS` ".
"FROM `ca_daysoff` ".
"LEFT JOIN `ca_daysoff_pattern` ON `ca_daysoff`.`PATT_ID` = `ca_daysoff_pattern`.`ID` ".
"WHERE "."( '".$db_date."'  BETWEEN `ca_daysoff`.`START_DATE` AND `ca_daysoff`.`END_DATE` )".
		" AND `ca_daysoff`."."`AGENDA_ID` = ".$agId.
		" AND `isDateValidByPattern`( '".$db_date."', `START_DATE`, IFNULL(`CYCLE`, 1 ), IFNULL(`PERIOD`, 1), IFNULL(`WEEK_DAYS`, 0 ) )".
		"";

//echo "\n\n$sql\n\n";


		$list	= array();
		$result = $gl_MysqliObj->query( $sql );
		if( $result ){
			while( $row = $result->fetch_assoc() ){
//				if( BlockDaysBL::isDatetimeItemValidByPattern_24( $row ) ){
			        $list[] = $row;
//				}
			}
			$result->close();
		}else{
			self::putLogInfo( self::getMysqlErrorInfo( $sql, 'utils_ut::isDayOff' ), 'Error' );
		}

		$count	= count( $list );
		if( $count > 0 ){ return true; }
		else			{ return false; }


//print_r( $list );





//		$result = mysql_query( $sql );
//		$list = array();
//		if( !$result ){
//			self::putLogInfo( self::getMysqlErrorInfo( $sql, 'utils_ut::getAppById' ) );
//		}else{
//			while( $row = @mysql_fetch_assoc( $result ) ){
//				if( BlockDaysBL::isDatetimeItemValidByPattern_24( $row ) ){
//			        if( $row[ $daysOffF_SyncId ] ){
//			        	$desc	= unserialize( $row[ $daysOffF_Comment ] );
//			        	$row[ $daysOffF_Comment ]	= $desc[ 'desc' ];
//			        }
//					$list[] = $row;
//				}
//			}
//		}
//		return $list;




//		return true;
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	private static function getMonthArr( $month, $year ){
		$mk				= mktime( 0, 0, 0, $month, 1, $year);
		$db_date_c		= date( 'Y-m-d', $mk );
		$db_last_date	= date( 'Y-m-'.date( 't', $mk ), $mk );
		$free_days	= array();
		while( $db_date_c <= $db_last_date ){
			$mk	= strtotime( $db_date_c );
			$free_days[ $db_date_c ]	= 1;
			$db_date_c	= date( 'Y-m-d', strtotime( '+1 day', $mk ) );
		}
		return $free_days;
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	private static function getWeekDayMask( $isoWeekDay ){
		$mask	= 0;
		switch( $isoWeekDay ){
			case self::c_iso_nMonday:		$mask	= self::c_mondayMask; break;
			case self::c_iso_nTuesday:		$mask	= self::c_tuesdayMask; break;
			case self::c_iso_nWednesday:	$mask	= self::c_wednesdayMask; break;
			case self::c_iso_nThursday:		$mask	= self::c_thursdayMask; break;
			case self::c_iso_nFriday:		$mask	= self::c_fridayMask; break;
			case self::c_iso_nSaturday:		$mask	= self::c_saturdayMask; break;
			case self::c_iso_nSunday:		$mask	= self::c_sundayMask; break;
		}
		return $mask;
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public static function findFreeDaysOfMonthByDayOffItem( $month, $year, $daysOff ){
		$db_days_off	= $daysOff;
		$mk_off_start	= strtotime( $daysOff[ 'START_DATE' ] );
		$mk_off_end		= strtotime( $daysOff[ 'END_DATE' ] );

		$db_days_off[ 'START_DATE' ]	= date( 'Y-m-d', $mk_off_start );
		$db_days_off[ 'END_DATE' ]		= date( 'Y-m-d', $mk_off_end );

		$week_day_start	= date( 'N', $mk_off_start );
		$mk_first_week_monday	= strtotime( '-'.( $week_day_start - 1 ).' day', $mk_off_start );

		$free_days	= self::getMonthArr( $month, $year );

		$is_check_patt	=
			isset( $db_days_off[ 'PATT_ID' ] ) &&
			$db_days_off[ 'PATT_ID' ] &&
			!( utils_ut::_cycle_day == $db_days_off[ 'CYCLE' ] && 1 == $db_days_off[ 'PERIOD' ] );

		foreach( $free_days as $db_date_ind => $val ){
			if( $db_date_ind >= $db_days_off[ 'START_DATE' ] && $db_date_ind <= $db_days_off[ 'END_DATE' ] ){
				if( $is_check_patt ){
					$mk_ind		= strtotime( $db_date_ind );
					switch( $db_days_off[ 'CYCLE' ] ){

						case utils_ut::_cycle_day:
							$period_cond	= intval( round( ( $mk_ind - $mk_off_start ) / 86400 ) ) % $db_days_off[ 'PERIOD' ];
							if( $period_cond == 0 ){
								unset( $free_days[ $db_date_ind ] );
							}
						break;

						case utils_ut::_cycle_week:
							$week_day_ind	= date( 'N', $mk_ind );
							$mk_ind_week_monday	= strtotime( '-'.( $week_day_ind - 1 ).' day', $mk_ind );
							$period_cond	= intval( round( ( $mk_ind_week_monday - $mk_first_week_monday ) / 86400 ) / 7 ) % $db_days_off[ 'PERIOD' ];
							$week_day_mask	= self::getWeekDayMask( $week_day_ind );

							if( $period_cond == 0 && ( $db_days_off[ 'WEEK_DAYS' ] & $week_day_mask ) ){
								unset( $free_days[ $db_date_ind ] );
							}
						break;

					}
				}else{
					unset( $free_days[ $db_date_ind ] );
				}
			}
		}

		return $free_days;
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE


}//	Class end
?>