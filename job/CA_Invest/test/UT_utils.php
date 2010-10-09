<?php
class UT_utils{

/**
* creates numbler of agendas for test envitonment
* @param $qntAgs - number of agendas which must be created
* @return array of agendas' ids
* */
    public function createAgendas_UT($qntAgs=10, $start=_UT_AG_START, $end=_UT_AG_END, $duration=30){
        global $CA_PATH; include($CA_PATH."variables_DB.php");
    	$agenda = array(
    		"$agendasF_Id"=>'null',
    	    "$agendasF_Password"=>'111qqq',
    	    "$agendasF_Username"=>'tst@tst.tst',
    	    "$agendasF_Duration"=>$duration,
    	    "$agendasF_StartTime"=>$start,
    	    "$agendasF_EndTime"=>$end,
    	    "RET_PASSWORD"=>'111qqq',
    	    "$agendasF_MsoSync"=>'N',
    	    "$agendasF_Infix"=>'tst',
    	    "$agendasF_Number"=>0,
    	    "$agendasF_Gender"=>'M',
    	    "$agendasF_UserLevel"=>_LEVEL_AGENDA,
    	    "$agendasF_IsBlocked"=>0,
    	    "$agendasF_Surname"=>'TST_Agenda',
    	    "$agendasF_MobPhone"=>'0000000000',
            "$agendasF_TypeOfNotification"=>0
    	);

    	$agendas = array();
    	for ($ag_num = 0; $ag_num < $qntAgs; $ag_num++){
    	    $rnd = rand();
    		$agenda["$agendasF_Name"] = "UT_AgName$rnd".$ag_num;
    		$agenda["$agendasF_Login"] = "UT_AgLogin$rnd".$ag_num;
    		$ag_id = self::addAgendaDbl($agenda);
    		$agendas[$ag_num] = user_bl::GetAgendaById($ag_id);
    		$agendas[$ag_num]["$field_org_code"] = $_SESSION['org_code'];
    	}

    	return $agendas;
    }
//-----------------------------------------------------------------------------------------------------------------

/**
* create init array of agenda parameters
* @param $prefix - string. Value which is used for name, surname and so on.
* @return array
* */
    public function getInitialAgendaParams_UT($prefix='UT_tst_'){
        global $CA_PATH; include($CA_PATH."variables_DB.php");
    	return array(
    		"$agendasF_Id"=>'null',
    		"$agendasF_Login"=>$prefix.'login',
    		"$agendasF_Name"=>$prefix.'FirstName',
    	    "$agendasF_Password"=>'UT_pass'.$prefix,
    	    "$agendasF_Username"=>'tst'.$prefix.'@tst.tst',
    	    "$agendasF_Duration"=>30,
    	    "$agendasF_StartTime"=>'09:00',
    	    "$agendasF_EndTime"=>'18:00',
    	    "RET_PASSWORD"=>'UT_pass'.$prefix,
    	    "$agendasF_MsoSync"=>'N',
    	    "$agendasF_Infix"=>'ut',
    	    "$agendasF_Number"=>0,
    	    "$agendasF_Gender"=>'M',
    	    "$agendasF_UserLevel"=>_LEVEL_AGENDA,
    	    "$agendasF_IsBlocked"=>0,
    	    "$agendasF_Surname"=>'UT_Surname'.$prefix,
            "$agendasF_TypeOfNotification"=>0
    	);
    }
//-----------------------------------------------------------------------------------------------------------------

    public function addAgendaShift_UT($agenda){
        global $CA_PATH; include($CA_PATH."variables_DB.php");
        $org_code = &$_SESSION['org_code'];

//utils_bl::printArray($agenda, 'agenda');


//	Create agenda's start time
		list($h, $m) = explode(":", $agenda["$agendasF_StartTime"]);
		$app_type_duration = $h * _MINUTE_DURATION + $m;
		$app_type = self::getAppTypeFullByDuration_UT($app_type_duration, 'root');
		if (!$app_type){
	        $app_type = self::initAppTypeParams_UT('Constantine Kolenchenko (ckolenchenko@yukon.cv.ua)');
	        $app_type['main']["$appTypesMainF_Duration"] = $app_type_duration;
			self::addAppTypeMain_UT($app_type['main'], 'root');
			$app_type = self::getAppTypeFullByDuration_UT($app_type_duration, 'root');// Must be!!!
		}
        $app = self::initAppParams_UT('Constantine Kolenchenko (ckolenchenko@yukon.cv.ua)');
        $main_app = &$app['main'];
        $main_app["$appsMainF_AppTypeId"] = $app_type['main']["$appTypesMainF_Id"];
        $main_app['agendas'][] = $agenda["$agendasF_Id"];
        self::addAppMain_UT($main_app, $org_code);

//	Create agenda's end time
		list($h, $m) = explode(":", $agenda["$agendasF_EndTime"]);
		$app_type_duration = 24 * _MINUTE_DURATION - ($h * _MINUTE_DURATION + $m);
		$app_type = self::getAppTypeFullByDuration_UT($app_type_duration, 'root');
		if (!$app_type){
	        $app_type = self::initAppTypeParams_UT('Constantine Kolenchenko (ckolenchenko@yukon.cv.ua)');
	        $app_type['main']["$appTypesMainF_Duration"] = $app_type_duration;
			self::addAppTypeMain_UT($app_type['main'], 'root');
			$app_type = self::getAppTypeFullByDuration_UT($app_type_duration, 'root');// Must be!!!
		}
        $app = self::initAppParams_UT('Constantine Kolenchenko (ckolenchenko@yukon.cv.ua)');
        $main_app = &$app['main'];
        $main_app["$appsMainF_AppTypeId"] = $app_type['main']["$appTypesMainF_Id"];
        $main_app['agendas'][] = $agenda["$agendasF_Id"];
        self::addAppMain_UT($main_app, $org_code);

        return true;
    }
//--------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
* creates agenda item in DB
* @param $agPrms - array of agendas parameters. See getInitialAgendaParams_UT method
* @param $agendas - array of agendas. New agenda is added to this array
* @return array of agendas' ids
* */
    public function createSingleAgenda_UT($agPrms=NULL, $agendas=array()){
        global $CA_PATH; include($CA_PATH."variables_DB.php");
        if ($agPrms == NULL){
        	$ag_uniq_param = count($agendas);
        	$new_agenda = self::getInitialAgendaParams_UT('UT_tst_'.$ag_uniq_param);
        }else{
        	$new_agenda = $agPrms;
        }
        $new_agenda["$agendasF_Id"] = self::addAgendaDbl($new_agenda);
        self::addAgendaShift_UT($new_agenda);
        $agendas[] = $new_agenda;
    	return $agendas;
    }
//--------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function initAppTypeParams_UT($author=''){
        global $CA_PATH; include($CA_PATH."variables_DB.php");



        $main = array(
        	"$appTypesMainF_Duration"			=>30,
        	"$appTypesMainF_MinTime"			=>"null",
        	"$appTypesMainF_MaxTime"			=>"null",
        	"$appTypesMainF_PeriodStartTime"	=>"00:00",
        	"$appTypesMainF_PeriodEndTime"		=>"00:00",
        	"$appTypesMainF_PeriodDay"			=>"1,1,1,1,1,1,1"
        );


        $date = utils_bl::GetTodayDate();
        $info = array(
        	"$appTypesInfoF_AppTypeId"			=>"null",
        	"$appTypesInfoF_Color"				=>'#FFFFFF',
        	"$appTypesInfoF_Name"				=>'UT App type',
        	"$appTypesInfoF_Comment"			=>"This app type was created for Unit Tests on $date. Author of this test is $author.",
        	"$appTypesInfoF_NumberApp"			=>"null",
        	"$appTypesInfoF_IsPublic"			=>1,
        	"$appTypesInfoF_Tariff"				=>"null",
        	"$appTypesInfoF_IsMulti"			=>0,
        	"$appTypesInfoF_ReminderTime"		=>"null",
        	"$appTypesInfoF_AgeCatId"			=>"null",
        	"$appTypesInfoF_TariffCurId"		=>1,
        	"$appTypesInfoF_IsShowedDuration"	=>1,
        	"$appTypesInfoF_IsDisabled"			=>1,
        	"$appTypesInfoF_Code"				=>'',
        	"$appTypesInfoF_Vat"				=>"null"
        );

        return array ('main'=>$main, 'info'=>$info);
    }
//--------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function addAppTypeMain_UT($data, $orgCode=_UT_ORG_CODE){
        global $CA_PATH; include($CA_PATH."variables_DB.php");
        $sql = "insert into $tableAppTypesMain (
        				$appTypesMainF_Duration,
        				$appTypesMainF_MinTime,
        				$appTypesMainF_MaxTime,
        				$appTypesMainF_PeriodStartTime,
        				$appTypesMainF_PeriodEndTime,
        				$appTypesMainF_PeriodDay,
        				$field_org_code
        		)values(".$data["$appTypesMainF_Duration"].",
        				".$data["$appTypesMainF_MinTime"].",
        				".$data["$appTypesMainF_MaxTime"].",
        				'".$data["$appTypesMainF_PeriodStartTime"]."',
        				'".$data["$appTypesMainF_PeriodEndTime"]."',
        				'".$data["$appTypesMainF_PeriodDay"]."',
        				'".$orgCode."')";

        $result = mysql_query($sql);
        $id = mysql_insert_id();
        return $id;
    }
//--------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function addAppTypeInfo_UT($data, $orgCode=_UT_ORG_CODE){
        global $CA_PATH; include($CA_PATH."variables_DB.php");
        $sql = "insert into $tableAppTypesInfo (
						$appTypesInfoF_AppTypeId,
						$appTypesInfoF_Color,
						$appTypesInfoF_Name,
						$appTypesInfoF_Comment,
						$appTypesInfoF_NumberApp,
						$appTypesInfoF_IsPublic,
						$appTypesInfoF_Tariff,
						$appTypesInfoF_IsMulti,
						$appTypesInfoF_ReminderTime,
						$appTypesInfoF_AgeCatId,
						$appTypesInfoF_TariffCurId,
						$appTypesInfoF_IsShowedDuration,
						$appTypesInfoF_IsDisabled,
						$appTypesInfoF_Code,
						$appTypesInfoF_Vat,
        				$field_org_code
        		)values(".$data["$appTypesInfoF_AppTypeId"].",
        				'".$data["$appTypesInfoF_Color"]."',
        				'".$data["$appTypesInfoF_Name"]."',
        				'".$data["$appTypesInfoF_Comment"]."',
        				".$data["$appTypesInfoF_NumberApp"].",
        				".$data["$appTypesInfoF_IsPublic"].",
        				".$data["$appTypesInfoF_Tariff"].",
        				".$data["$appTypesInfoF_IsMulti"].",
        				".$data["$appTypesInfoF_ReminderTime"].",
        				".$data["$appTypesInfoF_AgeCatId"].",
        				".$data["$appTypesInfoF_TariffCurId"].",
        				".$data["$appTypesInfoF_IsShowedDuration"].",
        				".$data["$appTypesInfoF_IsDisabled"].",
        				'".$data["$appTypesInfoF_Code"]."',
        				".$data["$appTypesInfoF_Vat"].",
        				'".$orgCode."')";


//echo "$sql<br>";


        $result = mysql_query($sql);
        $id = mysql_insert_id();
        return $id;
    }
//--------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function buildAppTypeArrayFromDb_UT($data){
        global $CA_PATH; include($CA_PATH."variables_DB.php");
        if ($data){
        	$app_type = array(
        		'main'=>array(
        			"$appTypesMainF_Id"=>$data["main_id"],
        			"$appTypesMainF_Duration"=>$data["$appTypesMainF_Duration"],
        			"$appTypesMainF_MinTime"=>$data["$appTypesMainF_MinTime"],
        			"$appTypesMainF_MaxTime"=>$data["$appTypesMainF_MaxTime"],
        			"$appTypesMainF_PeriodStartTime"=>$data["$appTypesMainF_PeriodStartTime"],
        			"$appTypesMainF_PeriodEndTime"=>$data["$appTypesMainF_PeriodEndTime"],
        			"$appTypesMainF_PeriodDay"=>$data["$appTypesMainF_PeriodDay"],
        			"$field_org_code"=>$data["$field_org_code"]
        		),
        		'info'=>array(
        			"$appTypesInfoF_Id"=>$data["info_id"],
        			"$appTypesInfoF_Color"=>$data["$appTypesInfoF_Color"],
        			"$appTypesInfoF_Name"=>$data["$appTypesInfoF_Name"],
        			"$appTypesInfoF_Comment"=>$data["$appTypesInfoF_Comment"],
        			"$appTypesInfoF_NumberApp"=>$data["$appTypesInfoF_NumberApp"],
        			"$appTypesInfoF_IsPublic"=>$data["$appTypesInfoF_IsPublic"],
        			"$appTypesInfoF_Tariff"=>$data["$appTypesInfoF_Tariff"],
        			"$appTypesInfoF_IsMulti"=>$data["$appTypesInfoF_IsMulti"],
        			"$appTypesInfoF_ReminderTime"=>$data["$appTypesInfoF_ReminderTime"],
        			"$appTypesInfoF_AgeCatId"=>$data["$appTypesInfoF_AgeCatId"],
        			"$appTypesInfoF_TariffCurId"=>$data["$appTypesInfoF_TariffCurId"],
        			"$appTypesInfoF_IsShowedDuration"=>$data["$appTypesInfoF_IsShowedDuration"],
        			"$appTypesInfoF_IsDisabled"=>$data["$appTypesInfoF_IsDisabled"],
        			"$appTypesInfoF_Code"=>$data["$appTypesInfoF_Code"],
        			"$appTypesInfoF_Vat"=>$data["$appTypesInfoF_Vat"]
        		)
        	);
        }else{$app_type = false;}
        return $app_type;
    }
//--------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function builAppTypeSelectQuery_UT($orgCode){
        global $CA_PATH; include($CA_PATH."variables_DB.php");
        return "select	$tableAppTypesMain.$appTypesMainF_Id as main_id,
        				$tableAppTypesMain.$appTypesMainF_Duration as $appTypesMainF_Duration,
        				$tableAppTypesMain.$appTypesMainF_MinTime as $appTypesMainF_MinTime,
        				$tableAppTypesMain.$appTypesMainF_MaxTime as $appTypesMainF_MaxTime,
        				$tableAppTypesMain.$appTypesMainF_PeriodStartTime as $appTypesMainF_PeriodStartTime,
        				$tableAppTypesMain.$appTypesMainF_PeriodEndTime as $appTypesMainF_PeriodEndTime,
        				$tableAppTypesMain.$appTypesMainF_PeriodDay as $appTypesMainF_PeriodDay,
        				$tableAppTypesMain.$field_org_code as $field_org_code,

        				$tableAppTypesInfo.$appTypesInfoF_Id as info_id,
        				$tableAppTypesInfo.$appTypesInfoF_Color as $appTypesInfoF_Color,
        				$tableAppTypesInfo.$appTypesInfoF_Name as $appTypesInfoF_Name,
        				$tableAppTypesInfo.$appTypesInfoF_Comment as $appTypesInfoF_Comment,
        				$tableAppTypesInfo.$appTypesInfoF_NumberApp as $appTypesInfoF_NumberApp,
        				$tableAppTypesInfo.$appTypesInfoF_IsPublic as $appTypesInfoF_IsPublic,
        				$tableAppTypesInfo.$appTypesInfoF_Tariff as $appTypesInfoF_Tariff,
        				$tableAppTypesInfo.$appTypesInfoF_IsMulti as $appTypesInfoF_IsMulti,
        				$tableAppTypesInfo.$appTypesInfoF_ReminderTime as $appTypesInfoF_ReminderTime,
        				$tableAppTypesInfo.$appTypesInfoF_AgeCatId as $appTypesInfoF_AgeCatId,
        				$tableAppTypesInfo.$appTypesInfoF_TariffCurId as $appTypesInfoF_TariffCurId,
        				$tableAppTypesInfo.$appTypesInfoF_IsShowedDuration as $appTypesInfoF_IsShowedDuration,
        				$tableAppTypesInfo.$appTypesInfoF_IsDisabled as $appTypesInfoF_IsDisabled,
        				$tableAppTypesInfo.$appTypesInfoF_Code as $appTypesInfoF_Code,
        				$tableAppTypesInfo.$appTypesInfoF_Vat as $appTypesInfoF_Vat
        		from $tableAppTypesMain
        		left join $tableAppTypesInfo on $tableAppTypesMain.$appTypesMainF_Id=$tableAppTypesInfo.$appTypesInfoF_AppTypeId
        		where ($tableAppTypesMain.$field_org_code='$orgCode')";
    }
//--------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function getAppTypeFullById_UT($id, $orgCode=_UT_ORG_CODE){
        global $CA_PATH; include($CA_PATH."variables_DB.php");
        $sql = self::builAppTypeSelectQuery_UT($orgCode)." and ($tableAppTypesMain.$appTypesMainF_Id=$id)";
        $result = mysql_query($sql);
        $row = @mysql_fetch_assoc($result);
        $app_type = self::buildAppTypeArrayFromDb_UT($row);
        return $app_type;
    }
//--------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function getAppTypeFullByDuration_UT($duration, $orgCode=_UT_ORG_CODE){
        global $CA_PATH; include($CA_PATH."variables_DB.php");
        $sql = self::builAppTypeSelectQuery_UT($orgCode)." and ($tableAppTypesMain.$appTypesMainF_Duration=$duration)";
        $result = mysql_query($sql);
        $row = @mysql_fetch_assoc($result);
        $app_type = self::buildAppTypeArrayFromDb_UT($row);
        return $app_type;
    }
//--------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function addAppTypeFull_UT($data, $appTypes=array(), $orgCode=_UT_ORG_CODE){
        global $CA_PATH; include($CA_PATH."variables_DB.php");
        $main_id = self::addAppTypeMain_UT($data['main'], $orgCode);
        $data['info']["$appTypesInfoF_AppTypeId"] = $main_id;
        $info_id = self::addAppTypeInfo_UT($data['info']);

    	$app_type = self::getAppTypeFullById_UT($main_id, $orgCode);
    	$appTypes[] = $app_type;
        return $appTypes;
    }
//--------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function initAppParams_UT($author=''){
        global $CA_PATH; include($CA_PATH."variables_DB.php");
        $main = array(
        	"$appsMainF_StartDate"	=>'null',
        	"$appsMainF_EndDate"	=>'null',
        	"$appsMainF_StartTime"	=>0,
        	"$appsMainF_AppTypeId"	=>0,
        	"$appsMainF_Status"	=>0,
        	'agendas'=>array(),
        	'clients'=>array()
        );

        $date = utils_bl::GetTodayDate();

        $info = array(
        	"$appsInfoF_AppId"	=>'null',
        	"$appsInfoF_ClientId"	=>'null',
        	"$appsInfoF_AgendaId"	=>'null',

        	"$appsInfoF_Comment"	=>"This appointment was created for Unit Tests on $date. Author of this test is $author.",
        	"$appsInfoF_CreateDate"	=>utils_bl::GetDbDate($date),
        	"$appsInfoF_CreateTime"	=>utils_bl::GetCurrentTime(),
        	"$appsInfoF_MaxNumberClient"	=>100,
        	"$appsInfoF_IsShared"	=>0,
        	"$appsInfoF_AppCrator"	=>1
        );
        return array ('main'=>$main, 'info'=>$info);
    }
//--------------------------------------------------------------------------------------------------------------------------------------------------------------

	public function addAppMain_UT($data, $orgCode=_UT_ORG_CODE){
		global $CA_PATH; include($CA_PATH."variables_DB.php");
		$db_start_date = ('null' == $data["$appsMainF_StartDate"]) ? $data["$appsMainF_StartDate"] : "'".$data["$appsMainF_StartDate"]."'";
		$db_end_date = ('null' == $data["$appsMainF_EndDate"]) ? $data["$appsMainF_EndDate"] : "'".$data["$appsMainF_EndDate"]."'";

		$sql = "insert into $tableAppsMain (
							$appsMainF_StartDate,
							$appsMainF_EndDate,
							$appsMainF_StartTime,
							$appsMainF_AppTypeId,
							$appsMainF_Status,
							$field_org_code
					)values(
							".$db_start_date.",
							".$db_end_date.",
							".$data["$appsMainF_StartTime"].",
							".$data["$appsMainF_AppTypeId"].",
							".$data["$appsMainF_Status"].",
							'".$orgCode."')";
        $result = mysql_query($sql);
        $app_id = mysql_insert_id();

		$db_today = utils_bl::GetDbDate(utils_bl::GetTodayDate());
        if ($result){
//	Add assigned agedas
        	$recods = '';
			foreach ($data['agendas'] as $user_id){
				$recods .= "($app_id, $user_id, '$db_today', '".utils_bl::GetCurrentTime()."', '$orgCode'),";
			}
			if ($recods != ''){
				$str_length = strlen($recods) - 1;
		        $recods = substr($recods, 0, $str_length);

				$sql1 = "insert into $tableAppAgendaAssign (
									$AppAgendaAssignF_AppId,
									$AppAgendaAssignF_AgendaId,
									$AppAgendaAssignF_CreateDate,
									$AppAgendaAssignF_CreateTime,
									$field_org_code
							)values $recods";
				$result = mysql_query($sql1);
			}
        }

        if ($result){
//	Add assigned clients
			$recods = '';
			foreach ($data['clients'] as $user_id){
				$is_clients = true;
				$recods .= "($app_id, $user_id, '$db_today', '".utils_bl::GetCurrentTime()."', '$orgCode'),";
			}
			if ($recods != ''){
				$str_length = strlen($recods) - 1;
		        $recods = substr($recods, 0, $str_length);

				$sql2 = "insert into $tableAppClientAssign (
									$AppClientAssignF_AppId,
									$AppClientAssignF_ClientId,
									$AppClientAssignF_CreateDate,
									$AppClientAssignF_CreateTime,
									$field_org_code
							)values $recods";

				$result = mysql_query($sql2);
			}
        }
        return $app_id;
	}
//--------------------------------------------------------------------------------------------------------------------------------------------------------------
/*
    public function addAppFull_UT($data, $apps=array(), $orgCode=_UT_ORG_CODE){
        global $CA_PATH; include($CA_PATH."variables_DB.php");
        $main_id = self::addAppMain_UT($data['main'], $orgCode);
        $data['info']["$appTypesInfoF_AppTypeId"] = $main_id;
        $info_id = self::addAppInfo_UT($data['info']);

    	$app = self::getAppFullById_UT($main_id, $orgCode);
    	$appTypes[] = $app_type;
        return $appTypes;
    }*/
//--------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function getAppFullById_UT($id, $orgCode=_UT_ORG_CODE){
        global $CA_PATH; include($CA_PATH."variables_DB.php");
        $app = array();



        return $app;
    }
//--------------------------------------------------------------------------------------------------------------------------------------------------------------













//--------------------------------------------------------------------------------------------------------------------------------------------------------------

    /**
* addAgendaDbl
* @author    Constantine Kolenchenko <ckolenchenko@yukon.cv.ua>
* @param $mode
* */
    public function addAgendaDbl($agenda){
        global $CA_PATH; include($CA_PATH."variables_DB.php");
        $org_code = &$_SESSION['org_code'];
        $agenda_id = $agenda["$agendasF_Id"];

        $sql = "INSERT INTO $tableAgendas(
                        $agendasF_Name,
                        $agendasF_Password,
                        $agendasF_Username,
                        $agendasF_Duration,
                        $agendasF_StartTime,
                        $agendasF_EndTime,
                        $agendasF_MsoSync,
                        $agendasF_Infix,
                        $agendasF_Surname,
                        $agendasF_Number,
                        $agendasF_IsBlocked,
                        $agendasF_UserLevel,
                        $agendasF_Login,
                        $agendasF_Gender,
                        $agendasF_CacheStatus,
                        $field_org_code
                )VALUES(
                '".$agenda["$agendasF_Name"]."',
                '".$agenda["$agendasF_Password"]."',
                '".$agenda["$agendasF_Username"]."',
                '".$agenda["$agendasF_Duration"]."',
                '".$agenda["$agendasF_StartTime"]."',
                '".$agenda["$agendasF_EndTime"]."',
                '".$agenda["$agendasF_MsoSync"]."',
                '".$agenda["$agendasF_Infix"]."',
                '".$agenda["$agendasF_Surname"]."',
                '".$agenda["$agendasF_Number"]."',
                '".$agenda["$agendasF_IsBlocked"]."',
                ".$agenda["$agendasF_UserLevel"].",
                '".$agenda["$agendasF_Login"]."',
                'M',
                "._CACHE_NOT_VALID.",
                '$org_code')";

        $result = mysql_query($sql);

        if ($agenda_id == 'null'){
            $agenda_id = mysql_insert_id();
       }

        return $agenda_id;
    }
//--------------------------------------------------------------------------------------------------------------------------------------------------------------

	/**
* createClients_UT method creates numbler of clients for test envitonment
* */
    public function createClients_UT($qntCls=10, $startNum=0, $is_diff_name=true, $is_diff_surname=true, $name='UT_Name_zzz', $surname='UT_Surname_xxx'){
        //  IMPORTANT!!!  Don't change addClient.
    	global $CA_PATH; include($CA_PATH."variables_DB.php");

    	$client = array(
    	    /*"$clientsF_Id"=>'null',*/
    	    "$clientsF_Gender"=>'M',
    	    "$clientsF_BirthDate"=>'1960-10-16',
    	    "$clientsF_Address"=>'UT Adress',
    	    "$clientsF_ZipCode"=>'UT Zip Code',
    	    "$clientsF_City"=>'UT City',
    	    "$clientsF_Phone"=>'000000',
    	    "$clientsF_Password"=>'UtlUtlUtlUtlUtl',   //   Don't change this value
    	    "$clientsF_IsBlocked"=>0,
    	    "$clientsF_IsDisabled"=>0,
    	    "$clientsF_Infix"=>'tst',
    	    "$clientsF_Username"=>'tst@tst.tst',
    	    "$clientsF_MobPhone"=>'000000',
    	    "$clientsF_TypeOfReminder"=>0
    	);

    	$clients = array();
    	for ($cl_num = $startNum; $cl_num < ($qntCls + $startNum); $cl_num++){
    		$client["$clientsF_Id"] = "null";

    		$client["$clientsF_Name"] = $name;
    		($is_diff_name) ? $client["$clientsF_Name"] .= $cl_num:'';

    		$client["$clientsF_Surname"] = $surname;
    		($is_diff_surname) ? $client["$clientsF_Surname"] .= $cl_num:'';


    		$client["$clientsF_Number"] = "UT_ClNumber".$cl_num;
    		$client["$clientsF_Login"] = "UT_ClLogin".$cl_num;   //   Don't change this value
    		$client["$clientsF_Id"] = user_dbl::addClient($client);
    		$clients[$cl_num] = $client;
    	}
    	return $clients;
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    /**
* createClients1_UT method creates numbler of clients for test envitonment for user_bl methods testing.
* */
    public function createClients1_UT($qntCls=10, $list='null',  $startNum=0, $is_diff_name=true, $is_diff_surname=true, $name='UT_Name_zzz', $surname='UT_Surname_xxx', $login = 'UT_ClLogin'){
        //  IMPORTANT!!!  Don't change addClient.
        global $CA_PATH; include($CA_PATH."variables_DB.php");

        $client = array(
            "$clientsF_Gender"=>'M',
            "$clientsF_BirthDate"=>'23-03-2009',
            "$clientsF_Address"=>'UT Adress',
            "$clientsF_StreetNum"=>'UT Street Num',
            "$clientsF_ZipCode"=>'UT Zip Code',
            "$clientsF_City"=>'UT City',
            "$clientsF_Phone"=>'000000',
            "$clientsF_Infix"=>'tst',
            "$clientsF_Username"=>'tst@tst.tst',
            "$clientsF_MobPhone"=>'000000',
            "$clientsF_TypeOfReminder"=>0,
        	"capcha_code"=>_UT_CAPCHA_CODE
        );

        $clients = array();
        for ($cl_num = 0; $cl_num < $qntCls; $cl_num++){
            if ($list == 'null') {
                $client["Table_$clientsF_Id"] = "null";
                $type = 'register';
            }else{
                 $client["Table_$clientsF_Id"]  = $list[$cl_num]["$clientsF_Id"];
                 $type = 'join';
            }

            $client["$clientsF_Name"] = $name;
            ($is_diff_name) ? $client["$clientsF_Name"] .= $cl_num:'';

            $client["$clientsF_Surname"] = $surname;
            ($is_diff_surname) ? $client["$clientsF_Surname"] .= $cl_num:'';


            $client["$clientsF_Number"] = "UT_ClNumber".$cl_num;
            $client["$clientsF_Login"] = $login.$cl_num;   //   Don't change this value

            $res = user_bl::addClient($client, $type, true);

            $client["$clientsF_Id"] = $res['id'];
            $client["$clientsF_Password"] = $res['pass'];

            $clients[$cl_num] = $client;
        }

/*
$sql_string = "select $clientsF_Id, $field_org_code from $tableClients";
$fclients = utils_bl::executeMySqlSelectQuery($sql_string);
utils_bl::printArray($fclients, 'fclients from createClients1_UT:');
*/
        return $clients;
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

	/**
* createAppTypes_UT method create number of app types with different parameters
* @return array of app types
* */
    public function createAppTypes_UT($isMulti = 0, $lastIndex = 13){
    	global $CA_PATH; include($CA_PATH."variables_DB.php");
    	$app_type = self::initAppTypeArray();
    	$app_types = array();

    	$index = 0;
    	$app_types = self::addAppType_UT($app_type, $app_types);$index++;  //  Index 0
    	if ($index > $lastIndex) return $app_types;

    	$app_type["$appTypesF_PeriodDay"] = '1,0,1,0,1,0,1';
    	$app_types = self::addAppType_UT($app_type, $app_types);$index++;  //  Index 1
        if ($index > $lastIndex) return $app_types;

    	$app_type["$appTypesF_PeriodDay"] = '1,1,1,1,1,1,1';
    	$app_type["$appTypesF_MinTime"] = _UT_APP_TYPE_MIN_TIME;
    	$app_types = self::addAppType_UT($app_type, $app_types);$index++;  //  Index 2
        if ($index > $lastIndex) return $app_types;

    	$app_type["$appTypesF_MinTime"] = 'null';
    	$app_type["$appTypesF_MaxTime"] = _UT_APP_TYPE_MAX_TIME;
    	$app_types = self::addAppType_UT($app_type, $app_types);$index++;  //  Index 3
        if ($index > $lastIndex) return $app_types;

    	$app_type["$appTypesF_MaxTime"] = 'null';
    	$app_type["$appTypesF_PeriodStartTime"] = '11:00';
    	$app_type["$appTypesF_PeriodEndTime"] = '16:00';
    	$app_types = self::addAppType_UT($app_type, $app_types);$index++;  //  Index 4
        if ($index > $lastIndex) return $app_types;

    	$app_type["$appTypesF_PeriodStartTime"] = '00:00';
    	$app_type["$appTypesF_PeriodEndTime"] = '00:00';
    	$app_type["$appTypesF_Time"] = 8;
    	$app_types = self::addAppType_UT($app_type, $app_types);$index++;  //  Index 5
        if ($index > $lastIndex) return $app_types;

    	$app_type["$appTypesF_Time"] = 14;
    	$app_types = self::addAppType_UT($app_type, $app_types);$index++;  //  Index 6
        if ($index > $lastIndex) return $app_types;

    	$app_type["$appTypesF_Time"] = 20;
    	$app_type["$appTypesF_PeriodStartTime"] = '11:00';
    	$app_type["$appTypesF_PeriodEndTime"] = '12:27';
    	$app_types = self::addAppType_UT($app_type, $app_types);$index++;  //  Index 7
        if ($index > $lastIndex) return $app_types;

    	$app_type["$appTypesF_PeriodEndTime"] = '12:28';
    	$app_types = self::addAppType_UT($app_type, $app_types);$index++;  //  Index 8
        if ($index > $lastIndex) return $app_types;

    	$app_type["$appTypesF_PeriodStartTime"] = '12:10';
    	$app_type["$appTypesF_PeriodEndTime"] = '16:00';
    	$app_types = self::addAppType_UT($app_type, $app_types);$index++;  //  Index 9
        if ($index > $lastIndex) return $app_types;

    	$app_type["$appTypesF_PeriodStartTime"] = '12:11';
    	$app_types = self::addAppType_UT($app_type, $app_types);$index++;  //  Index 10
        if ($index > $lastIndex) return $app_types;

    	$app_type["$appTypesF_PeriodStartTime"] = '09:00';
    	$app_type["$appTypesF_PeriodEndTime"] = '12:08';
    	$app_types = self::addAppType_UT($app_type, $app_types);$index++;  //  Index 11
        if ($index > $lastIndex) return $app_types;

    	$app_type["$appTypesF_PeriodStartTime"] = '12:30';
    	$app_type["$appTypesF_PeriodEndTime"] = '16:00';
    	$app_types = self::addAppType_UT($app_type, $app_types);  //  Index 12
    	if ($index > $lastIndex) return $app_types;

        $app_type["$appTypesF_PeriodStartTime"] = '00:00';
        $app_type["$appTypesF_PeriodEndTime"] = '00:00';
        $app_type["$appTypesF_Time"] = 120;
        $app_types = self::addAppType_UT($app_type, $app_types);$index++;  //  Index 13
        if ($index > $lastIndex) return $app_types;

    	return $app_types;
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    public function createAppTypes2_UT ($lastIndex = 1000){
        global $CA_PATH; include($CA_PATH."variables_DB.php");

        $app_type = self::initAppTypeArray();
        $app_type["$appTypesF_IsMulty"] = 1;

        $app_types = array();
        $index = 0;

        $app_types = self::addAppType_UT($app_type, $app_types);$index++;  //  Index 0  Duration 30 min
        if ($index > $lastIndex) return $app_types;

        $app_type["$appTypesF_Time"] = 3;
        $app_types = self::addAppType_UT($app_type, $app_types);$index++;  //  Index 1  Duration 3 min
        if ($index > $lastIndex) return $app_types;

        $app_type["$appTypesF_Time"] = 22;
        $app_types = self::addAppType_UT($app_type, $app_types);$index++;  //  Index 2  Duration 22 min
        if ($index > $lastIndex) return $app_types;

        return $app_types;
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    public function initAppTypeArray(){
        global $CA_PATH; include($CA_PATH."variables_DB.php");
        return array(
        "$appTypesF_Time"=>30,
        "$appTypesF_Color"=>'#000000',
        "$appTypesF_AppComment"=>'Unit test. Environment creation. Which tests were created by C.Kolenchenko (ckolenchenko@yukon.cv.ua)',
        "$appTypesF_MinTime"=>"null",
        "$appTypesF_MaxTime"=>"null",
        "$appTypesF_NumberApp"=>"null",
        "$appTypesF_Tariff"=>"null",
        "$appTypesF_IsPublic"=>1,
        "$appTypesF_IsMulty"=>0,
        "$appTypesF_PeriodStartTime"=>'00:00',
        "$appTypesF_PeriodEndTime"=>'00:00',
        "$appTypesF_PeriodDay"=>'1,1,1,1,1,1,1',
        "$appTypesF_ReminderTime"=>"null",
        "$appTypesF_AgeCatID"=>"null",
        "$appTypesF_TariffCurId"=>1,
        "$appTypesF_isShowedDuration"=>1,
        "$field_org_code" => $_SESSION['org_code']
        );
    }

	/**
* addAppType_UT method inserts app type for Unint Tests
* @param $appType - values of new app type.
* @param $appTypes - array of created app types.
* @return array of app types
* * */
    public function addAppType_UT($appType, $appTypes){
    	global $CA_PATH; include($CA_PATH."variables_DB.php");
    	$org_code = &$_SESSION['org_code'];
    	$app_type = $appType;
    	$app_types = $appTypes;
    	$num = count($app_types);

    	$app_type["$appTypesF_Name"] = 'AppType'.$num;

$sql_srting = "insert into $tableAppTypes (
       $appTypesF_Name,
       $appTypesF_Time,
       $appTypesF_Color,
       $appTypesF_AppComment,
       $appTypesF_MinTime,
       $appTypesF_MaxTime,
       $appTypesF_NumberApp,
       $appTypesF_Tariff,
       $appTypesF_IsPublic,
       $appTypesF_IsMulty,
       $appTypesF_PeriodStartTime,
       $appTypesF_PeriodEndTime,
       $appTypesF_PeriodDay,
       $appTypesF_ReminderTime,
       $appTypesF_AgeCatID,
       $appTypesF_TariffCurId,
       $appTypesF_isShowedDuration,
       $field_org_code
       )
values(
'".$app_type["$appTypesF_Name"]."',
".$app_type["$appTypesF_Time"].",
'".$app_type["$appTypesF_Color"]."',
'".$app_type["$appTypesF_AppComment"]."',
".$app_type["$appTypesF_MinTime"].",
".$app_type["$appTypesF_MaxTime"].",
".$app_type["$appTypesF_NumberApp"].",
".$app_type["$appTypesF_Tariff"].",
".$app_type["$appTypesF_IsPublic"].",
".$app_type["$appTypesF_IsMulty"].",
'".$app_type["$appTypesF_PeriodStartTime"]."',
'".$app_type["$appTypesF_PeriodEndTime"]."',
'".$app_type["$appTypesF_PeriodDay"]."',
".$app_type["$appTypesF_ReminderTime"].",
".$app_type["$appTypesF_AgeCatID"].",
".$app_type["$appTypesF_TariffCurId"].",
".$app_type["$appTypesF_isShowedDuration"].",
'$org_code'
) ";

//echo $sql_srting."<br>"; exit;
        $result = mysql_query($sql_srting);
        $app_type_id = mysql_insert_id();




//    	$app_type_id = app_type_dbl::addAppType($app_type);
    	$app_types[$num] = $app_type;
    	$app_types[$num]["$appTypesF_Id"] = $app_type_id;

    	return $app_types;
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

	/**
* addAppointment_UT method inserts appointment for Unint Tests
* @param $appTypeId - app type id
* @param $date - date in format dd-mm-yyyy
* @param $startTime - time in format hh:mm
* @param $agId - agenda's id
* @param $apps - array of created appointments.
* @return array of appointment
* * */
    public function addAppointment_UT($app, $apps){
    	global $CA_PATH; include($CA_PATH."variables_DB.php");
    	$org_code = &$_SESSION['org_code'];
    	$all_apps = $apps;

    	$num = count($all_apps);
    	$app_id = appointments_dbl::addApp($app);
    	$all_apps[$num] = $app;

    	$all_apps[$num]["$appointmentsF_AppId"] = $app_id;
    	$all_apps[$num]["$field_org_code"] = $org_code;

    	$app_agendas = &$app['agendas'];
    	foreach ($app_agendas as $ag_id){
    	    $sql_string = "insert into $tableAppAgendaAssign (
    	                                                  $AppAgendaAssignF_AppId,
    	                                                  $AppAgendaAssignF_AgendaId,
    	                                                  $AppAgendaAssignF_CreateDate,
    	                                                  $AppAgendaAssignF_CreateTime,
    	                                                  $field_org_code)
    	                                     values ($app_id, ".
    	                                                    $ag_id.",
    	                                                    '".$app["$appointmentsF_CreateDate"]."',
    	                                                    '".$app["$appointmentsF_SartTime"]."',
    	                                                    '".$org_code."') ";
            $result = mysql_query($sql_string);
    	}
    	return $all_apps;
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    public function addAppointment_mod_UT($app, $apps){
        global $CA_PATH; include($CA_PATH."variables_DB.php");
        $org_code = &$_SESSION['org_code'];
        $all_apps = $apps;


//utils_bl::printArray($app, 'app');


        $num = count($all_apps);
        $app_id = appointments_dbl::addApp($app);

        $app_agendas = &$app['agendas'];
        foreach ($app_agendas as $ag_id){
            $sql_string = "insert into $tableAppAgendaAssign (
                                                          $AppAgendaAssignF_AppId,
                                                          $AppAgendaAssignF_AgendaId,
                                                          $AppAgendaAssignF_CreateDate,
                                                          $AppAgendaAssignF_CreateTime,
                                                          $field_org_code)
                                             values ($app_id, ".
                                                            $ag_id.",
                                                            '".$app["$appointmentsF_CreateDate"]."',
                                                            '".$app["$appointmentsF_SartTime"]."',
                                                            '".$org_code."') ";
            $result = mysql_query($sql_string);
        }

        $clients = &$app['clients'];
        foreach ($clients as $client_id){
          $sql2 =" INSERT INTO $tableAppClientAssign
                            ( $AppClientAssignF_AppId,
                              $AppClientAssignF_ClientId,
                              $field_org_code)
                 VALUES (".$app_id.",
                            ".$client_id.",
                            '$org_code')";

            $rez=mysql_query($sql2);
        }




        $app_db = appointments_bl::GetAppointmentById($app_id);


//utils_bl::printArray($app_db, 'app_db');


        $app_db["$appointmentsF_Date"] = utils_bl::GetFormDate($app_db["$appointmentsF_Date"]);
        $app_db["$appointmentsF_CreateDate"] = utils_bl::GetFormDate($app_db["$appointmentsF_CreateDate"]);
        $app_db["$appointmentsF_EndDate"] = utils_bl::GetFormDate($app_db["$appointmentsF_EndDate"]);
        $app_db["$appointmentsF_SartTime"] = utils_bl::GetFormTime($app_db["$appointmentsF_SartTime"]);


        $all_apps[$num] = $app_db;

        return $all_apps;
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    /**
* addOrg_UT method inserts organization
* @param $index - integer number which will be added to org code (see const _UT_ORG_CODE)
* @return id of organization
* * */
    public function addOrg_UT($index){
        global $CA_PATH; include($CA_PATH."variables_DB.php");
        $org_indt = _UT_ORG_CODE.$index;
        $org = array (
    "$organizationF_OrgId"=>'null',
    "$organizationF_OrgCode"=>$org_indt,
    "$organizationF_OrgName"=>"Name_".$org_indt,
    "$organizationF_OrgDesck"=>"Description_".$org_indt,
    "$organizationF_OrgEnabled"=>1,
    "$organizationF_OrgUrlPrefix"=>"prefix_url_".$org_indt,
    "$organizationF_OrgAdress"=>"Org_Address_".$org_indt,
    "$organizationF_OrgAdressNumber"=>"A_".$org_indt,
    "$organizationF_OrgZipcode"=>"Zip_".$org_indt,
    "$organizationF_OrgCity"=>"City_".$org_indt,
    "$organizationF_OrgPhone"=>"P_".$org_indt,
    "$organizationF_OrgIsTrial"=>0
        );



        $sql_string = "INSERT INTO $tableOrganisation (
    $organizationF_OrgCode,
    $organizationF_OrgName,
    $organizationF_OrgDesck,
    $organizationF_OrgEnabled,
    $organizationF_OrgUrlPrefix,
    $organizationF_OrgAdress,
    $organizationF_OrgAdressNumber,
    $organizationF_OrgZipcode,
    $organizationF_OrgCity,
    $organizationF_OrgPhone,
    $organizationF_OrgIsTrial
) VALUES (
    '".$org["$organizationF_OrgCode"]."',
    '".$org["$organizationF_OrgName"]."',
    '".$org["$organizationF_OrgDesck"]."',
    ".$org["$organizationF_OrgEnabled"].",
    '".$org["$organizationF_OrgUrlPrefix"]."',
    '".$org["$organizationF_OrgAdress"]."',
    '".$org["$organizationF_OrgAdressNumber"]."',
    '".$org["$organizationF_OrgZipcode"]."',
    '".$org["$organizationF_OrgCity"]."',
    '".$org["$organizationF_OrgPhone"]."',
    ".$org["$organizationF_OrgIsTrial"]."
)";

        $result = mysql_query($sql_string);
        $org["$organizationF_OrgId"] = mysql_insert_id ();





        $date = date("Y-m-d");
        $date_start = utils_bl::AddDaysToDbDate($date, -3);
        $date_end  = utils_bl::AddDaysToDbDate($date,   3);

        $perm = array (
        "$orgPermissionsF_Id"=>'null',
        "$orgPermissionsF_OrgId"=>$org["$organizationF_OrgId"],
        "$orgPermissionsF_StartDate"=>$date_start,
        "$orgPermissionsF_EndDate"=>$date_end,
        "$orgPermissionsF_CountClient"=>50,
        "$orgPermissionsF_CountAgenda"=>20,
        "$orgPermissionsF_MaxLogin"=>10,
        "$orgPermissionsF_UnitTreking"=>1,
        "$orgPermissionsF_UnitQuestionnaire"=>1,
        "$orgPermissionsF_UnitReminder"=>1,
        "$orgPermissionsF_LicenseType"=>0,
        "$orgPermissionsF_LicenseFee"=>0
        );

         $sql_string = "INSERT INTO $tableOrgPermissions (
    $orgPermissionsF_OrgId,
    $orgPermissionsF_StartDate,
    $orgPermissionsF_EndDate,
    $orgPermissionsF_CountClient,
    $orgPermissionsF_CountAgenda,
    $orgPermissionsF_MaxLogin,
    $orgPermissionsF_UnitTreking,
    $orgPermissionsF_UnitQuestionnaire,
    $orgPermissionsF_UnitReminder,
    $orgPermissionsF_LicenseType,
    $orgPermissionsF_LicenseFee
) VALUES (
    ".$perm["$orgPermissionsF_OrgId"].",
    '".$perm["$orgPermissionsF_StartDate"]."',
    '".$perm["$orgPermissionsF_EndDate"]."',
    ".$perm["$orgPermissionsF_CountClient"].",
    ".$perm["$orgPermissionsF_CountAgenda"].",
    ".$perm["$orgPermissionsF_MaxLogin"].",
    ".$perm["$orgPermissionsF_UnitTreking"].",
    ".$perm["$orgPermissionsF_UnitQuestionnaire"].",
    ".$perm["$orgPermissionsF_UnitReminder"].",
    ".$perm["$orgPermissionsF_LicenseType"].",
    ".$perm["$orgPermissionsF_LicenseFee"]."
)";
        $result = mysql_query($sql_string);
        $perm["$orgPermissionsF_Id"] = mysql_insert_id ();

        $data = array ('org'=>$org, 'perm'=>$perm);
        return $data;
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

	/**
* deleteAllDataFromTable method deletes all data from required table
* @param $table - name of table which used in select query
* @return result of execution
* */
    public function deleteAllOrgDataFromTable($table, $orgCode=_UT_ORG_CODE){
    	global $CA_PATH; include($CA_PATH."variables_DB.php");
    	$sql_string = "delete from ".$table." where ".$field_org_code." like '%".$orgCode."%' and $field_org_code != 'root'";
    	$result = mysql_query($sql_string);
    	return $result;
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

	/**
* deleteAllDataFromTable method deletes all data from required table
* @param $table - name of table which used in select query
* @return result of execution
* */
    public function getAllBlkDataForOrg(){
    	global $CA_PATH; include($CA_PATH."variables_DB.php");
    	$sql_string = "select * from ".$tableDaysOff." where ".$field_org_code."='".$_SESSION['org_code']."'";



    	$result = utils_bl::executeMySqlSelectQuery($sql_string);
    	return $result;
    }
//-----------------------------------------------------------------------------------------------------------------

    /**
* setCacheStatus_UT set test mode
* @author	 Constantine Kolenchenko <ckolenchenko@yukon.cv.ua>
* @param $mode
* @param $UpdDate in format dd-mm-yyyy
* */
    public function setCacheStatus_UT($mode=_IS_NOT_BUSY){
    	global $CA_PATH; include($CA_PATH."variables_DB.php");

    	$sql_string = "update $tableCacheLog set $cacheLogF_IsTest=".$mode."  where ".$cacheLogF_Id."=1";
    	$result = mysql_query($sql_string);
    }
//-----------------------------------------------------------------------------------------------------------------

    /**
* checkCron_IsBusy
* @author    Constantine Kolenchenko <ckolenchenko@yukon.cv.ua>
* */
    public function setCronStatusForTest($status){
        global $CA_PATH; include($CA_PATH."variables_DB.php");
        $cache_cron_obj = new cronCache_bl();
        $log_info = $cache_cron_obj->getLogInfo();

        if ($log_info["$cacheLogF_IsTest"] > 0) {
            $_SESSION['is_skip'] = true;
        }else{
            self::setCacheStatus_UT($status);
            $_SESSION['is_skip'] = false;
        }
        $cache_cron_obj = NULL;
        return $log_info;
    }
//-----------------------------------------------------------------------------------------------------------------

    /**
* deleteClients
* @author    Constantine Kolenchenko <ckolenchenko@yukon.cv.ua>
* */
    public function deleteClients($orgCode = _UT_ORG_CODE){
        global $CA_PATH; include($CA_PATH."variables_DB.php");

        $sql_string = "select $clientsF_Id from $tableClients where $field_org_code like '%$orgCode%'";
        $fclients = utils_bl::executeMySqlSelectQuery($sql_string);
        self::deleteAllOrgDataFromTable($tableClients, $orgCode);

		foreach ($fclients as $client){
			$sql_string = "delete from $tableClientsLogin where $clientsLoginF_Id=".$client["$clientsLoginF_Id"];
			$result = mysql_query($sql_string);
		}
        return true;
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------

    /**
*
* @author    Constantine Kolenchenko <ckolenchenko@yukon.cv.ua>
* */
    public function deleteOrgData($orgCode = _UT_ORG_CODE){
        global $CA_PATH; include($CA_PATH."variables_DB.php");

        self::deleteAllOrgDataFromTable($tableCache, $orgCode);
        self::deleteAllOrgDataFromTable($tableAppAgendaAssign, $orgCode);
        self::deleteAllOrgDataFromTable($tableAppClientAssign, $orgCode);


        self::deleteAllOrgDataFromTable($tableAppTypesMain, $orgCode);
        self::deleteAllOrgDataFromTable($tableAppsMain, $orgCode);


        self::deleteAllOrgDataFromTable($tableAppointments, $orgCode);		////////////  Delete
        self::deleteAllOrgDataFromTable($tableAppTypes, $orgCode);			////////////  Delete



        self::deleteAllOrgDataFromTable($tableDaysOff, $orgCode);
        self::deleteAllOrgDataFromTable($tableAgendasAssignedCategories, $orgCode);
        self::deleteAllOrgDataFromTable($tableAgendasCategories, $orgCode);
        self::deleteAllOrgDataFromTable($tableFlexFields, $orgCode);

        self::deleteClients($orgCode);
        self::deleteAllOrgDataFromTable($tableAgendas, $orgCode);

        self::deleteOrganization($orgCode);  //  Must be last one
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------

    public function deleteOrganization($orgCode){
        global $CA_PATH; include($CA_PATH."variables_DB.php");
        $sql_string = "select * from $tableOrganisation where $field_org_code like '%$orgCode%';";
        $orgs = utils_bl::executeMySqlSelectQuery($sql_string);

        $ids = "";
        foreach ($orgs as $org){
            $ids .= $org["$organizationF_OrgId"].",";
        }
        $length = strlen($ids) - 1;
        $ids = substr($ids, 0, $length);
        $sql_string = "delete from $tableOrgPermissions where $orgPermissionsF_Id in ($ids);";
        $result = mysql_query($sql_string);
        self::deleteAllOrgDataFromTable($tableOrganisation, $orgCode);
        return $result;
    }

    public function getIniArrayToCreateApp($startTime, $dateDb, $appTypeId, $creatorId){
        global $CA_PATH;include($CA_PATH."variables_DB.php");
        return  array(
            "$appointmentsF_AppId"=>'null',
            "clients"=>array(),
            "agendas"=>array (),
            "$appointmentsF_SartTime"=>$startTime,
            "$appointmentsF_ClietnId"=>0,
            "$appointmentsF_Date"=>$dateDb,
            "$appointmentsF_AppTypeId"=>$appTypeId,
            "$appointmentsF_AgendaId"=>$creatorId,
            "$appointmentsF_StatusId"=>3,
            "$appointmentsF_Comment"=>'Unit test. Which tests were created by C.Kolenchenko (ckolenchenko@yukon.cv.ua)',
            "$appointmentsF_EndDate"=>$dateDb,
            "$appointmentsF_MaxNumberClient"=>1,
            "$appointmentsF_IsShared"=>0,
            "$appointmentsF_CreateDate"=>$dateDb,
            "$appointmentsF_Creater"=>1);
    }
//-----------------------------------------------------------------------------------------------------------------

    public function deleteRecFromTblById ($recId, $table, $nameId='ID') {
        $sql_string = "delete from $table where $nameId=$recId";
        $result = mysql_query($sql_string);
        return $result;
    }
//-----------------------------------------------------------------------------------------------------------------

/**
 * creates new category for agednas
* @author    Constantine Kolenchenko <ckolenchenko@yukon.cv.ua>
* @param $cat - array(
* 												[name]=>sring value
* 											)
* */
    public function createAgCategory($cat, $agendas = array(), $orgCode = _UT_ORG_CODE) {
    	 global $CA_PATH; include($CA_PATH."variables_DB.php");

        $sql_string = "INSERT INTO $tableAgendasCategories
                                                          ($agCatF_Name,
                                                          $field_org_code)
                                              VALUES ('".$cat['name']."',
                                              				'$orgCode')";

        $result = mysql_query($sql_string);
        $cat_id = mysql_insert_id ();


        if (!empty($agendas)){
	        $recs = "";
	        foreach ($agendas as $agenda){$recs .= "($cat_id, ".$agenda["$agendasF_Id"].", '$orgCode'),";}
	        $lng = strlen($recs) - 1; $recs = substr($recs, 0, $lng);

	        $sql_string = "INSERT INTO $tableAgendasAssignedCategories
	                                                           ($agCatF_Id,
	                                                           	$agendasF_Id,
	                                                           	$field_org_code)
	                                               VALUES $recs";

	        $result = mysql_query($sql_string);
        }

        return $cat_id;
    }
//-----------------------------------------------------------------------------------------------------------------

/**
 * sets ...
* @author    Constantine Kolenchenko <ckolenchenko@yukon.cv.ua>
* 											)
* */
    public function createFlexFields($orgCode = _UT_ORG_CODE) {
		global $CA_PATH; include($CA_PATH."variables_DB.php");
		$sql = "INSERT INTO $tableFlexFields
							($flexFieldsF_FieldName, $flexFieldsF_IsShown, $flexFieldsF_IsMandatory, $flexFieldsF_FormName, $field_org_code)
				VALUES	('$clientsF_Number',0,0,'client','"._UT_ORG_CODE."'),
						('$clientsF_BirthDate',0,0,'client','"._UT_ORG_CODE."'),
						('$clientsF_Address',0,0,'client','"._UT_ORG_CODE."'),
						('$clientsF_Phone',0,0,'client','"._UT_ORG_CODE."'),
						('$clientsF_City',0,0,'client','"._UT_ORG_CODE."'),
						('$clientsF_ZipCode',0,0,'client','"._UT_ORG_CODE."'),
						('$clientsF_Initials',0,0,'client','"._UT_ORG_CODE."')";
		$result = mysql_query($sql);
    }
//-----------------------------------------------------------------------------------------------------------------

/**
 * sets ...
* @author    Constantine Kolenchenko <ckolenchenko@yukon.cv.ua>
* 											)
* */
    public function setGlobalOrgParams($orgCode = _UT_ORG_CODE) {
		global $CA_PATH; include($CA_PATH."variables_DB.php");
		self::createFlexFields($orgCode);
    }
//-----------------------------------------------------------------------------------------------------------------
}
?>