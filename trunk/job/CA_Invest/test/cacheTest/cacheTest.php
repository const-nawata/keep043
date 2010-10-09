<?php
/**
 * Created by Constantine Kolenchenko on 02/08/2008
 * Copyright Yukon Software the Netherlands 2008. All rights reserved
 */







include("./config.php");
require_once $CA_PATH.'test/cacheTest/cache_constants.php';
require_once $CA_PATH.'test/session_tuning.php';
require_once $CA_PATH.'test/UT_utils.php';
require_once($CA_PATH."classes/bl/cache_bl.php");


/*
     ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!
*
* Do not change the order of tests.
*
* Set $_SESSION['is_end'] = true; at the end of new tests
*
* Last test number is 13

*
    	//$this->markTestSkipped('Test N = 11');
* *
* * */



class cacheTest extends PHPUnit_Framework_TestCase{
    const _is_all_t = true;  //  false true

    protected function setUp(){
    	if (!isset($_SESSION['is_end'])){
        	session_tuning::createSessionData();
        	self::deleteEnvironment();
        	self::createEnvironment();
        }
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------

    protected function tearDown(){
    	global $CA_PATH; include($CA_PATH."variables_DB.php");
    	if ($_SESSION['is_end']){
    		self::deleteEnvironment();
    		session_tuning::destroySessionData();
    	}

    	UT_utils::deleteAllOrgDataFromTable($tableDaysoffPattern);
    	UT_utils::deleteAllOrgDataFromTable($tableDaysOff);
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------

    //##############################################                        Tests
    public function testLogDateUpdate(){//Test First Test                                                                                                   Mandatory.
    	global $CA_PATH; include($CA_PATH."variables_DB.php");

    	$cache_cron_obj = new cronCache_bl();
    	$log_info = $cache_cron_obj->getLogInfo();
    	session_register ('is_cron_on');
    	$_SESSION['is_cron_on'] = $log_info["$cacheLogF_IsTest"];
    	$cache_cron_obj = NULL;
    	if ($_SESSION['is_cron_on'] > 0) $this->markTestSkipped('Skip');  //  This session veriable is used to set all next tests skipping if cron is on.

       	UT_utils::setCacheStatus_UT(_IS_BUSY);//  = -1

//echo "_IS_BUSY: "._IS_BUSY."<br>";

       	$_SESSION['last_date'] = $log_info["$cacheLogF_LastUpdtDate"];

       	$cache_obj = new cache_bl();

    	global $dbg_today; $std_dbg_today = $dbg_today; $dbg_today = '28-02-2009';

    	$org_code = $_SESSION['org_code'];
    	$_SESSION['org_code'] = 'UnitTestCheck';    //  Don't change this orgcode!!! It is used for integrity chacking.
    	$ag = UT_utils::createAgendas_UT(1);
    	$_SESSION['check_ag'] = $ag[0];

    	$dbg_today_db = utils_bl::GetDbDate($dbg_today);
    	$cache_obj->setAgendaCacheStatus($ag, 0);


    	$insert_value = "(".$_SESSION['check_ag']["$agendasF_Id"].",  '".$dbg_today_db."',  500, 600, 'UnitTestCheck'),";
    	$cache_obj->insertRecordsInFreeTimeCache($insert_value);


//    	$cache_obj->addFreeTimeRecInCache($_SESSION['check_ag']["$agendasF_Id"], $dbg_today_db, 500, 600, 'UnitTestCheck');



	    $_SESSION['org_code'] = $org_code;

    	$cache_obj = NULL;

	    $date_db = utils_bl::GetDbDate($dbg_today);
	    ($date_db == $_SESSION['last_date']) ? $dbg_today = '27-02-2009':'';
	    $_SESSION['last_date'] = utils_bl::GetFormDate($_SESSION['last_date']);

	    $date_db = utils_bl::GetDbDate($dbg_today);

    	$cache_cron_obj = new cronCache_bl();
    	$cache_cron_obj->updateFreeCacheCurrent();
    	$log_info = $cache_cron_obj->getLogInfo();
    	$cache_cron_obj = NULL;

    	$_SESSION['is_end'] = true;

    	$dbg_today = $std_dbg_today;
	    $this->assertEquals($date_db, $log_info["$cacheLogF_LastUpdtDate"], "***** Assert 1. Log date is not recorded by current update*****");

    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------





    public function testCalculateCacheDates(){//Test N = 11
        if ($_SESSION['is_cron_on'] > 0) $this->markTestSkipped('Skip');

    	global $CA_PATH; include($CA_PATH."variables_DB.php");
    	global $is_t11;
    	global $dbg_today; $dbg_today = '28-02-2009';

    	if ($is_t11 || self::_is_all_t){
    		$cache_obj = new cache_bl();
    		$cache_dates = $cache_obj->getCacheDates();
    		$cache_obj = NULL;

    		$check_date = $dbg_today;

    		for ($i = 0; $i < _DAYS_CACHE_LENGTH; $i++){
    			$this->assertEquals($check_date, $cache_dates[$i], "***** Assert 1. Bad dates in cache dates array *****");
    			$check_date = utils_bl::GetNextDate($check_date);
    		}

    		$last_date1 = $cache_dates[_DAYS_CACHE_LENGTH - 1];
    		$last_date1 = utils_bl::GetNextDate($last_date1);
    		$dbg_today = utils_bl::GetNextDate($dbg_today);
    		$cache_obj = new cache_bl();
    		$cache_dates = $cache_obj->getCacheDates();
    		$cache_obj = NULL;

    		$last_date2 = $cache_dates[_DAYS_CACHE_LENGTH - 1];
    		$this->assertEquals($last_date1, $last_date2, "***** Assert 2. Bad last date in cache dates array *****");
    	}else $this->markTestSkipped('Skip');
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    public function testIsCacheUpdated(){//Test N = 13
    	if ($_SESSION['is_cron_on'] > 0) $this->markTestSkipped('Skip');
    	global $is_t13;
    	global $CA_PATH; include($CA_PATH."variables_DB.php");

    	global $dbg_curr_time;
    	global $dbg_today; $dbg_today = '10-09-2008';

    	$db_today = utils_bl::GetDbDate($dbg_today);

    	$cache_cron_obj = new cronCache_bl();
    	$cache_cron_obj->updateFreeCacheCurrent();
    	$cache_cron_obj = NULL;

    	if ($is_t13 || self::_is_all_t){
	    	$agendas[0] = $_SESSION['agendas'][0];

	    	$dates = utils_bl::getMonthDates(_OCTOBER_NUM, 2008);

	    	$cache_obj = new cache_bl();

	    	$free_times = $cache_obj->getDataFromFreeTimeCache($agendas, $dates, $_SESSION['org_code'], 'test');
	    	$cache_obj = NULL;

	    	$is_cache_empty = count($free_times);

	    	$this->assertGreaterThan(0, $is_cache_empty, "***** Cache is empty!!! *****");
    	}else $this->markTestSkipped('Skip');
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    public function testAllAgendasHaveNoAppointmentsAndBlockedTimesAndAppTypeHasNoConstraints(){//Test N = 1
    	if ($_SESSION['is_cron_on'] > 0) $this->markTestSkipped('Skip');

    	global $is_t1;
    	global $CA_PATH; include($CA_PATH."variables_DB.php");
    	global $dbg_curr_time;
    	global $dbg_today; $dbg_today = '10-09-2008';
    	$db_today = utils_bl::GetDbDate($dbg_today);

    	$cache_cron_obj = new cronCache_bl();
    	$cache_cron_obj->updateFreeCacheCurrent();
    	$cache_cron_obj = NULL;

    	if ($is_t1 || self::_is_all_t){
	    	$dbg_curr_time = '17:30';

	    	$cache_obj = new cache_bl($_SESSION['app_types'][0]["$appTypesF_Id"], $_SESSION['agendas'], _OCTOBER_NUM, 2008);
	    	$cache_info = $cache_obj->getAnyFreeAgendaForMonth();

	    	foreach ($cache_info as $db_date=>$ag_id){
	    		$this->assertEquals($_SESSION['agendas'][0]["$agendasF_Id"], $ag_id, "***** Assert 1. Not Right agenda was found. Date index=$db_date # Current time = $dbg_curr_time *****"); // Assert N = 1
	    	}
	    	$cache_obj  = NULL;
	    	$cache_obj = new cache_bl($_SESSION['app_types'][0]["$appTypesF_Id"], $_SESSION['agendas'], _DECEMBER_NUM, 2008);
	    	$cache_info = $cache_obj->getAnyFreeAgendaForMonth();
	    	foreach ($cache_info as $db_date=>$ag_id){
	    		$this->assertEquals($_SESSION['agendas'][0]["$agendasF_Id"], $ag_id, "***** Assert 2. Not Right agenda was found. Date index=$db_date # Current time = $dbg_curr_time *****"); // Assert N = 1
	    	}
	    	$cache_obj  = NULL;

	    	$dbg_curr_time = '12:00';
	    	$cache_obj = new cache_bl($_SESSION['app_types'][0]["$appTypesF_Id"], $_SESSION['agendas'], _SEPTEMBER_NUM, 2008);
	    	$cache_info = $cache_obj->getAnyFreeAgendaForMonth();
	    	foreach ($cache_info as $db_date=>$ag_id){
	    		if ($db_date < $db_today){
	    			$this->assertEquals(0, $ag_id, "***** Assert 3. Not Right Data or agenda was found. Date index=$db_date # Current time = $dbg_curr_time *****"); // Assert N = 2/1
	    		}else{
	    			$this->assertEquals($_SESSION['agendas'][0]["$agendasF_Id"], $ag_id, "***** Assert 4. Not Right Data or agenda was found. Date index=$db_date # Current time = $dbg_curr_time *****"); // Assert N = 2/2
	    		}
	    	}
	    	$cache_obj  = NULL;
	    	$dbg_curr_time = '17:31';
	    	$cache_obj = new cache_bl($_SESSION['app_types'][0]["$appTypesF_Id"], $_SESSION['agendas'], _SEPTEMBER_NUM, 2008);
	    	$cache_info = $cache_obj->getAnyFreeAgendaForMonth();
	    	foreach ($cache_info as $db_date=>$ag_id){
	    		if ($db_date <= $db_today){
	    			$this->assertEquals(0, $ag_id, "***** Assert 5. Not Right Data or agenda was found. Date index=$db_date # Current time = $dbg_curr_time *****"); // Assert N = 3/1
	    		}else{
	    			$this->assertEquals($_SESSION['agendas'][0]["$agendasF_Id"], $ag_id, "***** Assert 6. Not Right Data or agenda was found. Date index=$db_date # Current time = $dbg_curr_time *****"); // Assert N = 3/2
	    		}
	    	}
	    	$cache_obj = NULL;
    	}else $this->markTestSkipped('Skip');
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    public function testAllAgendasHaveNoAppointmentsAndBlockedTimesAndAppTypeHasMinTime(){//Test N = 2   Min time = 49 hours
    	if ($_SESSION['is_cron_on'] > 0) $this->markTestSkipped('Skip');

    	global $is_t2;
    	global $CA_PATH; include($CA_PATH."variables_DB.php");
    	global $dbg_curr_time;
    	global $dbg_today; $dbg_today =  '10-09-2008';

    	if ($is_t2 || self::_is_all_t){
            $app_types = &$_SESSION['app_types'];
            $agendas    = &$_SESSION['agendas'];

	    	$db_today = utils_bl::GetDbDate($dbg_today);
	    	$db_tomorrow = utils_bl::GetDbDate(utils_bl::GetNextDate($dbg_today));

	    	$dbg_curr_time = '12:00';
	    	$cache_obj = new cache_bl($app_types[2]["$appTypesF_Id"], $agendas, _SEPTEMBER_NUM, 2008);

	    	$cache_info = $cache_obj->getAnyFreeAgendaForMonth();


//utils_bl::printArray($cache_info, 'cache_info');


	    	foreach ($cache_info as $db_date=>$ag_id){
	    		if ($db_date < $db_today){
	    			$this->assertEquals(0, $ag_id, "***** Assert 1. Wrong agenda's id was found. Date index=$db_date # Current time = $dbg_curr_time *****"); // Assert N = 1/1
	    		}else{
	    			$this->assertEquals($agendas[0]["$agendasF_Id"], $ag_id, "***** Assert 2. Wrong agenda's id was found. Date index=$db_date # Current time = $dbg_curr_time *****"); // Assert N = 1/2
	    		}
	    	}
	    	$cache_obj = NULL;


//	    	##################       This assert is checked in test $is_t16
//	    	$dbg_curr_time = '16:31';    //   App type has min time 25 hours so green free time starts tomorrow at 17:31 and avalaible green time slot has duration 29 min
//	    	$cache_obj = new cache_bl($app_types[2]["$appTypesF_Id"], $agendas, _SEPTEMBER_NUM, 2008);
//	    	$cache_info = $cache_obj->getAnyFreeAgendaForMonth();
//
////utils_bl::printArray($cache_info, 'cache_info');
//
//	    	foreach ($cache_info as $db_date=>$ag_id){
//	    		if ($db_date < $db_tomorrow){
//	    			$this->assertEquals(0, $ag_id, "***** Assert 3. Not Right Data or agenda was found. Date index=$db_date # Current time = $dbg_curr_time *****"); // Assert N = 2/1
//	    		}else{
//	    			$this->assertEquals($agendas[0]["$agendasF_Id"], $ag_id, "***** Assert 4. Not Right Data or agenda was found. Date index=$db_date # Current time = $dbg_curr_time *****"); // Assert N = 2/2
//	    		}
//	    	}
//	    	$cache_obj = NULL;



    	}else $this->markTestSkipped('Skip');
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    public function testOnlyCallUsTimeSlotsAreAvailable_MinTimeIsTested(){//   $is_t16
        if ($_SESSION['is_cron_on'] > 0) $this->markTestSkipped('Skip');

        global $is_t16;
        global $CA_PATH; include($CA_PATH."variables_DB.php");
        global $dbg_curr_time;
        global $dbg_today; //$dbg_today = '10-09-2008';

//echo "<br>dbg_today: $dbg_today<br>";


        if ($is_t16 || self::_is_all_t){
            $app_types = &$_SESSION['app_types'];
            $agendas    = &$_SESSION['agendas'];

            $agendas[1]["$agendasF_EndTime"] = '20:00';

            $sql_string = "update $tableAgendas set $agendasF_EndTime='".$agendas[1]["$agendasF_EndTime"]."', $agendasF_CacheStatus="._CACHE_NOT_VALID." where $agendasF_Id=".$agendas[1]["$agendasF_Id"];
            $res = mysql_query($sql_string);



//$sql_string = "select * from $tableAgendas where $field_org_code='"._UT_ORG_CODE."'";
//$res = utils_bl::executeMySqlSelectQuery($sql_string);
//utils_bl::printArray($res, 'agendas');




            $cache_cron_obj = new cronCache_bl();
            $cache_cron_obj->updateFreeCacheCurrent();
            $cache_cron_obj = NULL;



//$sql_string = "select * from $tableAgendas where $field_org_code='"._UT_ORG_CODE."'";
//$res = utils_bl::executeMySqlSelectQuery($sql_string);
//utils_bl::printArray($res, 'agendas');




//$sql_string = "select * from $tableCache where $field_org_code='"._UT_ORG_CODE."'";
//$res = utils_bl::executeMySqlSelectQuery($sql_string);
//utils_bl::printArray($res, 'cache');




            $db_today                  = utils_bl::GetDbDate($dbg_today);

            $tomorrow = utils_bl::GetNextDate($dbg_today);
            $db_tomorrow           = utils_bl::GetDbDate($tomorrow);

            $after_tomorrow       = utils_bl::GetNextDate($tomorrow);
            $db_after_tomorrow = utils_bl::GetDbDate($after_tomorrow);

            $dbg_curr_time = '17:31';    //   App type has min time 25 hours so:
                                                               // agenda with 0 index has green free time which starts tomorrow at 17:31 and avalaible green time slot has duration 29 min
                                                               // agenda with 1 index has green free time which starts tomorrow at 17:31 and avalaible green time slot has duration 1 hour and 29 min
                                                               // Both agendas have yellow time slots both today and tomorrow. Durations of those time slots are greater than 30 min.

                                                                //     Conditions:
                                                               //  For tomorrow agenda with index 1  must be found
                                                               //  For today agenda with index 0  must be found
                                                               // Before today all dates must be unavailable



            $cache_obj = new cache_bl($app_types[2]["$appTypesF_Id"], $agendas, _SEPTEMBER_NUM, 2008);
            $cache_info = $cache_obj->getAnyFreeAgendaForMonth();
            $cache_obj = NULL;


//echo "db_today; $db_today # db_tomorrow: $db_tomorrow # db_after_tomorrow: $db_after_tomorrow<br>";
//utils_bl::printArray($agendas, 'agendas');
//utils_bl::printArray($cache_info, 'cache_info');





            foreach ($cache_info as $db_date=>$ag_id){
                if ($db_date < $db_today){
                    $this->assertEquals(0, $ag_id, "***** Assert 1. Not Right Data or agenda was found. Date index=$db_date # Current time = $dbg_curr_time *****");
                }elseif ($db_date == $db_today){
                    $this->assertEquals($agendas[1]["$agendasF_Id"], $ag_id, "***** Assert 2. Wrong agenda's id was found. Date index=$db_date # Current time = $dbg_curr_time *****");
                }elseif ($db_date == $db_tomorrow){
                    $this->assertEquals($agendas[0]["$agendasF_Id"], $ag_id, "***** Assert 3. Wrong agenda's id  was found. Date index=$db_date # Current time = $dbg_curr_time *****");
                }elseif ($db_date == $db_after_tomorrow){
                    $this->assertEquals($agendas[1]["$agendasF_Id"], $ag_id, "***** Assert 4. Wrong agenda's id  was found. Date index=$db_date # Current time = $dbg_curr_time *****");
                }else{
                    $this->assertEquals($agendas[0]["$agendasF_Id"], $ag_id, "***** Assert 5. Wrong agenda's id  was found. Date index=$db_date # Current time = $dbg_curr_time *****");
                }
            }




            $app_types[2]["$appTypesF_MinTime"] = 2;
            $sql_string = "update $tableAppTypes set $appTypesF_MinTime=".$app_types[2]["$appTypesF_MinTime"]." where $appTypesF_Id=".$app_types[2]["$appTypesF_Id"];
            $res = mysql_query($sql_string);

//$sql_string = "select * from $tableAppTypes where $field_org_code='"._UT_ORG_CODE."'";
//$arr = utils_bl::executeMySqlSelectQuery($sql_string);
//utils_bl::printArray($arr, 'app_types from DB');

            $dbg_curr_time = '16:31';
            $ags[0] = $agendas[0];
            $cache_obj = new cache_bl($app_types[2]["$appTypesF_Id"], $ags, _SEPTEMBER_NUM, 2008);
            $cache_info = $cache_obj->getAnyFreeAgendaForMonth();
            $cache_obj = NULL;

            $this->assertEquals($agendas[0]["$agendasF_Id"], $cache_info["$db_today"], "\n Assert 6. Wrong agenda's id  was found. \nMaybe wrong SQL query in cache_dbl::getAgendasFreeTimeCache method. \nDate index=$dbg_today # Current time = $dbg_curr_time \n");






//utils_bl::printArray($cache_info, 'cache_info');

//utils_bl::printArray($app_types, 'app_types');








        }else {
            $this->markTestSkipped('Skip');
        }
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    public function testAllAgendasHaveNoAppointmentsAndBlockedTimesAndAppTypeHasMaxTime(){//Test N = 3
    	if ($_SESSION['is_cron_on'] > 0) $this->markTestSkipped('Skip');

    	global $is_t3;
        global $dbg_curr_time; global $dbg_today;
    	global $CA_PATH; include($CA_PATH."variables_DB.php");


    	if ($is_t3 || self::_is_all_t){
	    	$db_today = utils_bl::GetDbDate($dbg_today);
	    	$db_last_day = utils_bl::GetDbDate(utils_bl::AddDaysToDate($dbg_today, _UT_APP_TYPE_MAX_TIME));

	    	$dbg_curr_time = '12:00';
	    	$cache_obj = new cache_bl($_SESSION['app_types'][3]["$appTypesF_Id"], $_SESSION['agendas'], _SEPTEMBER_NUM, 2008);

	    	$cache_info = $cache_obj->getAnyFreeAgendaForMonth();

	    	foreach ($cache_info as $db_date=>$ag_id){
	    		if ($db_date < $db_today || $db_date > $db_last_day){
	    			$this->assertEquals(0, $ag_id, "***** Assert 1. Not Right Data or agenda was found. Date index=$db_date # Current time = $dbg_curr_time *****"); // Assert N = 1/1
	    		}else{
	    			$this->assertEquals($_SESSION['agendas'][0]["$agendasF_Id"], $ag_id, "***** Assert 2. Not Right Data or agenda was found. Date index=$db_date # Current time = $dbg_curr_time *****"); // Assert N = 1/2
	    		}
	    	}
	    	$cache_obj = NULL;

	    	$cache_obj = new cache_bl($_SESSION['app_types'][3]["$appTypesF_Id"], $_SESSION['agendas'], _OCTOBER_NUM, 2008);
	    	$cache_info = $cache_obj->getAnyFreeAgendaForMonth();


//utils_bl::printArray($cache_info);


	    	foreach ($cache_info as $db_date=>$ag_id){
	    			$this->assertEquals(0, $ag_id, "***** Assert 3. Not Right Data or agenda was found. Date index=$db_date # Current time = $dbg_curr_time *****"); // Assert N = 2
	    	}
	    	$cache_obj = NULL;

	    	$cache_obj = new cache_bl($_SESSION['app_types'][3]["$appTypesF_Id"], $_SESSION['agendas'], _DECEMBER_NUM, 2008);
	    	$cache_info = $cache_obj->getAnyFreeAgendaForMonth();
	    	foreach ($cache_info as $db_date=>$ag_id){
	    			$this->assertEquals(0, $ag_id, "***** Assert 4. Not Right Data or agenda was found. Date index=$db_date # Current time = $dbg_curr_time *****"); // Assert N = 2
	    	}
	    	$cache_obj = NULL;
    	}else $this->markTestSkipped('Skip');
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    public function testAllAgendasHaveNoAppointmentsAndBlockedTimesAndAppTypeHasPattern(){//Test N = 4. Pattern STTS.
    	if ($_SESSION['is_cron_on'] > 0) $this->markTestSkipped('Skip');

    	global $is_t4;
    	global $CA_PATH; include($CA_PATH."variables_DB.php");
    	global $dbg_curr_time; global $dbg_today;

    	if ($is_t4 || self::_is_all_t){
	    	$dbg_curr_time = '12:00';
	    	$db_today = utils_bl::GetDbDate($dbg_today);
	    	$cache_obj = new cache_bl($_SESSION['app_types'][1]["$appTypesF_Id"], $_SESSION['agendas'], _SEPTEMBER_NUM, 2008);

	    	$cache_info = $cache_obj->getAnyFreeAgendaForMonth();

	    	foreach ($cache_info as $db_date=>$ag_id){
	    		$date = utils_bl::GetFormDate($db_date);
	    		$week_day = utils_bl::getWeekDayByDate($date, true);

	    		if(BlockDaysBL::c_nMonday == $week_day || BlockDaysBL::c_nWednesday == $week_day || BlockDaysBL::c_nFriday == $week_day || $db_date < $db_today){
	    			$this->assertEquals(0, $ag_id, "***** Assert 1. Not Right Data was found. Date index=$db_date # Week day = $week_day *****"); // Assert N = 1/1
	    		}else{
	    			$this->assertEquals($_SESSION['agendas'][0]["$agendasF_Id"], $ag_id, "***** Assert 2. Not Right Data or agenda was found. Date index=$db_date # Week day = $week_day *****"); // Assert N = 1/2
	    		}
	    	}
	    	$cache_obj = NULL;
    	}else $this->markTestSkipped('Skip');
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    public function testFirstAgendaHasFree22MinLineWhichCreatedByAppointments_NoBlockedTimes_AppTypeHasStart_EndTimes(){//Test N = 5. Asserts 1 - 8 / entry: 1
        if ($_SESSION['is_cron_on'] > 0) $this->markTestSkipped('Skip');

    	global $is_t5;
    	global $CA_PATH; include($CA_PATH."variables_DB.php");
    	global $dbg_curr_time; $dbg_curr_time = '12:00';
    	global $dbg_today;  //10-09-2008

    	if ($is_t5 || self::_is_all_t){
    	    $app_types = &$_SESSION['app_types'];
    	    $agendas = &$_SESSION['agendas'];

	    	$check_date = utils_bl::GetNextDate($dbg_today);
	    	$app_ag_ids = array (0=>$agendas[0]["$agendasF_Id"]);
	    	$apps = $this->setStandardAppointmentsForTimetable($check_date, _UT_AG_START, _UT_AG_END, $app_ag_ids, $app_types[0]["$appTypesF_Id"]);

	    	UT_utils::deleteRecFromTblById($apps[6]["$appointmentsF_AppId"], $tableAppointments, $appointmentsF_AppId);

	    	$app = $apps[6];

	    	$app["$appointmentsF_AppId"] = 'null';
	    	$app["$appointmentsF_AppTypeId"] = $app_types[5]["$appTypesF_Id"];   //  App type has duration 8 min. => Free line will be 22 min
	    	$app["$appointmentsF_Date"] = utils_bl::GetDbDate($app["$appointmentsF_Date"]);
	    	$app["$appointmentsF_CreateDate"] = utils_bl::GetDbDate($app["$appointmentsF_CreateDate"]);
	    	$app["$appointmentsF_EndDate"] = utils_bl::GetDbDate($app["$appointmentsF_EndDate"]);
	    	$app["$appointmentsF_IsShared"] = 0;
	    	$app["$appointmentsF_Creater"] = 1;

	    	$apps_b = array ();
	    	$apps_b = UT_utils::addAppointment_mod_UT($app, $apps_b);

	    	$apps[6]["$appointmentsF_AppId"] = $apps_b[0]["$appointmentsF_AppId"];          //   app #6 has 8 min duration now

	    	$this->execTestsForAZone($check_date, 1);
    	}else {$this->markTestSkipped('Skip');}
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    public function testFirstAgendaHasFree22MinLineWhichCreatedByBlockedTimesAndNoAppointmentsAndAppTypeHasStart_EndTimes(){//Test N = 6. Asserts 1 - 8 / entry: 2
    	if ($_SESSION['is_cron_on'] > 0) $this->markTestSkipped('Skip');

    	global $is_t6;
    	global $CA_PATH; include($CA_PATH."variables_DB.php");
    	global $dbg_curr_time; global $dbg_today;

    	if ($is_t6 || self::_is_all_t){
	    	$check_date = utils_bl::AddDaysToDate($dbg_today, 2);
	    	$date_db = utils_bl::GetDbDate($check_date);

	    	$blk_param = array(
	    	"$daysOffF_AgendaId"=>$_SESSION['agendas'][0]["$agendasF_Id"],
	    	"$daysOffF_Comment"=>'Unit test for cache. Which tests were created by C.Kolenchenko (ckolenchenko@yukon.cv.ua)',
	    	"$daysOffF_BlockedDate"=>$check_date,
	    	"$daysOffF_BlockedDateEnd"=>$check_date,
	    	"$daysOffF_BlockedTime"=>_UT_AG_START,
	    	"$daysOffF_BlockedTimeEnd"=>'12:08',
	    	"PATTERN_ID"=>0
	    	);
	    	BlockDaysBL::InsertBlockedRec($blk_param);
	    	$blk_param["$daysOffF_BlockedTime"] = '12:30';
	    	$blk_param["$daysOffF_BlockedTimeEnd"] = _UT_AG_END;
	    	BlockDaysBL::InsertBlockedRec($blk_param);

			$agendas[0] = $_SESSION['agendas'][0];
			$cache_obj = new cache_bl();
			$cache_obj->setAgendaCacheStatus($agendas, _CACHE_NOT_VALID);
			$cache_obj = NULL;
			$cache_cron_obj = new cronCache_bl();
			$cache_cron_obj->updateFreeCacheCurrent();
    		$cache_cron_obj = NULL;

	    	$this->execTestsForAZone($check_date, 2);
    	}else $this->markTestSkipped('Skip');
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    public function testFirstAgendaHasFree22MinLineWhichCreatedByTopBlockedTimesAndBottomAppointmentsAndAppTypeHasStart_EndTimes(){//Test N = 7. Asserts 1 - 8 / entry: 3
    	if ($_SESSION['is_cron_on'] > 0) $this->markTestSkipped('Skip');

    	global $is_t7;
    	global $CA_PATH; include($CA_PATH."variables_DB.php");
    	global $dbg_curr_time;  global $dbg_today;

    	if ($is_t7 || self::_is_all_t){
	    	$check_date = utils_bl::AddDaysToDate($dbg_today, 3);
	    	$date_db = utils_bl::GetDbDate($check_date);

	    	$blk_param = array(
	    	"$daysOffF_AgendaId"=>$_SESSION['agendas'][0]["$agendasF_Id"],
	    	"$daysOffF_Comment"=>'Unit test for cache. Which tests were created by C.Kolenchenko (ckolenchenko@yukon.cv.ua)',
	    	"$daysOffF_BlockedDate"=>$check_date,
	    	"$daysOffF_BlockedDateEnd"=>$check_date,
	    	"$daysOffF_BlockedTime"=>_UT_AG_START,
	    	"$daysOffF_BlockedTimeEnd"=>'12:08',
	    	"PATTERN_ID"=>0
	    	);
	    	BlockDaysBL::InsertBlockedRec($blk_param);

			$agendas[0] = $_SESSION['agendas'][0];
			$cache_obj = new cache_bl();
			$cache_obj->setAgendaCacheStatus($agendas, _CACHE_NOT_VALID);
			$cache_obj = NULL;
			$cache_cron_obj = new cronCache_bl();
			$cache_cron_obj->updateFreeCacheCurrent();
    		$cache_cron_obj = NULL;

            $app_ag_ids = array (0=>$_SESSION['agendas'][0]["$agendasF_Id"]);
            $apps = $this->setStandardAppointmentsForTimetable($check_date, '12:30', _UT_AG_END, $app_ag_ids, $_SESSION['app_types'][0]["$appTypesF_Id"]);

//    		$apps = $this->setStandardAppointmentsForTimetable($check_date, '12:30', _UT_AG_END, $_SESSION['agendas'][0]["$agendasF_Id"], $_SESSION['app_types'][0]["$appTypesF_Id"]);

    		$this->execTestsForAZone($check_date, 3);
    	} else $this->markTestSkipped('Skip');
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    public function testFirstAgendaHasFree22MinLineWhichCreatedByBottomBlockedTimesAndTopAppointmentsAndAppTypeHasStart_EndTimes(){//Test N = 8. Asserts 1 - 8 / entry: 4
    	if ($_SESSION['is_cron_on'] > 0) $this->markTestSkipped('Skip');

    	global $is_t8;
    	global $CA_PATH; include($CA_PATH."variables_DB.php");
    	global $dbg_curr_time; global $dbg_today;

    	if ($is_t8 || self::_is_all_t){
	    	$check_date = utils_bl::AddDaysToDate($dbg_today, 4);
	    	$date_db = utils_bl::GetDbDate($check_date);
	    	$agendas = &$_SESSION['agendas'];
	    	$clients = &$_SESSION['clients'];
	    	$app_types = &$_SESSION['app_types'];

	    	$blk_param = array(
	    	"$daysOffF_AgendaId"=>$agendas[0]["$agendasF_Id"],
	    	"$daysOffF_Comment"=>'Unit test for cache. Which tests were created by C.Kolenchenko (ckolenchenko@yukon.cv.ua)',
	    	"$daysOffF_BlockedDate"=>$check_date,
	    	"$daysOffF_BlockedDateEnd"=>$check_date,
	    	"$daysOffF_BlockedTime"=>'12:30',
	    	"$daysOffF_BlockedTimeEnd"=>_UT_AG_END,
	    	"PATTERN_ID"=>0
	    	);
	    	BlockDaysBL::InsertBlockedRec($blk_param);

			$agenda_selected[0] = $agendas[0];
			$cache_obj = new cache_bl();
			$cache_obj->setAgendaCacheStatus($agenda_selected, _CACHE_NOT_VALID);
			$cache_obj = NULL;
			$cache_cron_obj = new cronCache_bl();
			$cache_cron_obj->updateFreeCacheCurrent();
    		$cache_cron_obj = NULL;

            $app_ag_ids = array (0=>$agendas[0]["$agendasF_Id"]);
            $apps = $this->setStandardAppointmentsForTimetable($check_date, _UT_AG_START, '12:00', $app_ag_ids, $app_types[0]["$appTypesF_Id"]);

	    	$app = array(
	    	"$appointmentsF_AppId"=>'null',
	    	"clients"=>array(0=>$clients[0]["$clientsF_Id"]),
	    	"agendas"=>array(0=>$agendas[0]["$agendasF_Id"]),
	    	"$appointmentsF_ClietnId"=>0,
	    	"$appointmentsF_Date"=>$date_db,
	    	"$appointmentsF_AppTypeId"=>$app_types[5]["$appTypesF_Id"],
	    	"$appointmentsF_AgendaId"=>$agendas[0]["$agendasF_Id"],
	    	"$appointmentsF_StatusId"=>3,
	    	"$appointmentsF_Comment"=>'Unit test for cache. Which tests were created by C.Kolenchenko (ckolenchenko@yukon.cv.ua)',
	    	"$appointmentsF_EndDate"=>$date_db,
	    	"$appointmentsF_MaxNumberClient"=>1,
	    	"$appointmentsF_IsShared"=>0,
	    	"$appointmentsF_CreateDate"=>$date_db,
	    	"$appointmentsF_SartTime"=>'12:00',
	    	"$appointmentsF_Creater"=>1);

    		$apps = UT_utils::addAppointment_UT($app, $apps);

    		$this->execTestsForAZone($check_date, 4);
    	}else $this->markTestSkipped('Skip');;
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    public function testFirstAgendaHasBlockedDays_WeekPattern(){//Test N = 9. Pattern STTS. Every week.
    	if ($_SESSION['is_cron_on'] > 0) $this->markTestSkipped('Skip');

    	global $is_t9;
        global $dbg_curr_time; global $dbg_today;
    	global $CA_PATH; include($CA_PATH."variables_DB.php");

    	if ($is_t9 || self::_is_all_t){
	    	$blk_param = array(
	    	"$daysOffF_AgendaId"=>$_SESSION['agendas'][0]["$agendasF_Id"],
	    	"$daysOffF_Comment"=>'Unit test for cache. Which tests were created by C.Kolenchenko (ckolenchenko@yukon.cv.ua)',
	    	"$daysOffF_BlockedDate"=>'01-09-2008',
	    	"$daysOffF_BlockedDateEnd"=>'31-10-2008',
	    	"$daysOffF_BlockedTime"=>'00:00:00',
	    	"$daysOffF_BlockedTimeEnd"=>'00:00:00',
	    	"PATTERN_ID"=>1,
	    	"$daysoffPatternF_Cycle"=>_BLK_PATTERN_WEEKS,
	    	"$daysoffPatternF_Period"=>1,
	    	"$daysoffPatternF_WeekDays"=>85
	    	);
	    	BlockDaysBL::InsertBlockedRec($blk_param);

			$agendas[0] = $_SESSION['agendas'][0];
			$cache_obj = new cache_bl();
			$cache_obj->setAgendaCacheStatus($agendas, _CACHE_NOT_VALID);
			$cache_cron_obj = new cronCache_bl();
			$cache_cron_obj->updateFreeCacheCurrent();
    		$cache_cron_obj = NULL;

    		$dates = utils_bl::getMonthDates(_OCTOBER_NUM, 2008);
    		$agendas[0] = $_SESSION['agendas'][0];
    	    $free_times = $cache_obj->getDataFromFreeTimeCache($agendas, $dates, $_SESSION['org_code'], 'test');
    	    foreach ($free_times as $free_data){
    	    	$date = utils_bl::GetFormDate($free_data["$cacheF_Date"]);
    	    	$num_week_day = utils_bl::getNumOfWeekDay($date);
    	    	if (BlockDaysBL::c_nSunday == $num_week_day || BlockDaysBL::c_nTuesday == $num_week_day || BlockDaysBL::c_nThursday == $num_week_day || BlockDaysBL::c_nSaturday == $num_week_day){
    	    		$this->assertEquals(0, $free_data["$cacheF_StartTime"], 	"*********** Assert 1 # Week pattern.  Start time of free line is not zero. Date is ".$date." **********");
    	    		$this->assertEquals(0, $free_data["$cacheF_EndTime"], 	"*********** Assert 2 # Week pattern.  End time of free line is not zero. Date is ".$date." **********");
    	    	}else{
    	    		$this->assertGreaterThan(0, $free_data["$cacheF_StartTime"], 	"*********** Assert 3 # Week pattern. Start time of free line is zero. Date is ".$date." **********");
    	    		$this->assertGreaterThan(0, $free_data["$cacheF_EndTime"], 	"*********** Assert 4 # Week pattern.  End time of free line is zero. Date is ".$date." **********");
    	    	}
    	    }
    	    $cache_obj = NULL;

	    	$dbg_curr_time = '12:00';
	    	$db_today = utils_bl::GetDbDate($dbg_today);
	    	$cache_obj = new cache_bl($_SESSION['app_types'][5]["$appTypesF_Id"], $_SESSION['agendas'], _SEPTEMBER_NUM, 2008);
	    	$cache_info = $cache_obj->getAnyFreeAgendaForMonth();

	    	foreach ($cache_info as $db_date=>$ag_id){
	    		$date = utils_bl::GetFormDate($db_date);
	    		$week_day = utils_bl::getWeekDayByDate($date, true);

	    		if ($db_date < $db_today){
	    			$this->assertEquals(0, $ag_id, "***** Assert 5 # Week pattern.  Not Right Data was found. Date index=$db_date # Week day = $week_day *****");
	    		}elseif(BlockDaysBL::c_nMonday == $week_day || BlockDaysBL::c_nWednesday == $week_day || BlockDaysBL::c_nFriday == $week_day){
	    			$this->assertEquals($_SESSION['agendas'][0]["$agendasF_Id"], $ag_id, "***** Assert 6 # Week pattern.  Not Right Data was found. Date index=$db_date # Week day = $week_day *****");
	    		}else{
	    			$this->assertEquals($_SESSION['agendas'][1]["$agendasF_Id"], $ag_id, "***** Assert 7 # Week pattern.  Not Right Data or agenda was found. Date index=$db_date # Week day = $week_day *****");
	    		}
	    	}
	    	$cache_obj = NULL;
    	} else $this->markTestSkipped('Skip');
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    public function testFirstAgendaHasBlockedDays_DayPattern(){//Test N = 10. Pattern every 3d day (3 days period)
    	if ($_SESSION['is_cron_on'] > 0) $this->markTestSkipped('Skip');

    	global $is_t10;
        global $dbg_curr_time; global $dbg_today;
    	global $CA_PATH; include($CA_PATH."variables_DB.php");

    	if ($is_t10 || self::_is_all_t){
	    	$blk_param = array(
	    	"$daysOffF_AgendaId"=>$_SESSION['agendas'][0]["$agendasF_Id"],
	    	"$daysOffF_Comment"=>'Unit test for cache. Which tests were created by C.Kolenchenko (ckolenchenko@yukon.cv.ua)',
	    	"$daysOffF_BlockedDate"=>'01-09-2008',
	    	"$daysOffF_BlockedDateEnd"=>'31-12-2008',
	    	"$daysOffF_BlockedTime"=>'00:00:00',
	    	"$daysOffF_BlockedTimeEnd"=>'00:00:00',
	    	"PATTERN_ID"=>1,
	    	"$daysoffPatternF_Cycle"=>_BLK_PATTERN_DAYS,
	    	"$daysoffPatternF_Period"=>_UT_BLK_PERIOD_DAYS,
	    	"$daysoffPatternF_WeekDays"=>0
	    	);
	    	BlockDaysBL::InsertBlockedRec($blk_param);

			$agendas[0] = $_SESSION['agendas'][0];
			$cache_obj = new cache_bl();
			$cache_obj->setAgendaCacheStatus($agendas, _CACHE_NOT_VALID);
			$cache_cron_obj = new cronCache_bl();
			$cache_cron_obj->updateFreeCacheCurrent();
    		$cache_cron_obj = NULL;

    		$dates = utils_bl::getMonthDates(_OCTOBER_NUM, 2008);
    		$agendas[0] = $_SESSION['agendas'][0];
    	    $free_times = $cache_obj->getDataFromFreeTimeCache($agendas, $dates, $_SESSION['org_code'], 'test');

    	    $blk_date_flag = _UT_BLK_PERIOD_DAYS - 1;
    	    foreach ($free_times as $free_data){
    	    	$date = utils_bl::GetFormDate($free_data["$cacheF_Date"]);

    	    	if ($blk_date_flag < (_UT_BLK_PERIOD_DAYS - 1)){
    	    		$this->assertGreaterThan(0, $free_data["$cacheF_StartTime"], 	"*********** Assert 1 # Day pattern. Start time of free line is zero. Date is ".$date." $blk_date_flag **********");
    	    		$this->assertGreaterThan(0, $free_data["$cacheF_EndTime"], 	"*********** Assert 2 # Day pattern.  End time of free line is zero. Date is ".$date." **********");
    	    		$blk_date_flag++;
    	    	}else{
    	    		$this->assertEquals(0, $free_data["$cacheF_StartTime"], 	"*********** Assert 3 # Day pattern.  Start time of free line is not zero. Date is ".$date." **********");
    	    		$this->assertEquals(0, $free_data["$cacheF_EndTime"], 	"*********** Assert 4 # Day pattern.  End time of free line is not zero. Date is ".$date." **********");
    	    		$blk_date_flag = 0;
    	    	}
    	    }
    	    $cache_obj = NULL;

    	    $dbg_curr_time = '12:00';
	    	$db_today = utils_bl::GetDbDate($dbg_today);
	    	$cache_obj = new cache_bl($_SESSION['app_types'][5]["$appTypesF_Id"], $_SESSION['agendas'], _SEPTEMBER_NUM, 2008);
	    	$cache_info = $cache_obj->getAnyFreeAgendaForMonth();

	    	$blk_date_flag = _UT_BLK_PERIOD_DAYS - 1;
	    	foreach ($cache_info as $db_date=>$ag_id){
	    		$date = utils_bl::GetFormDate($db_date);
	    		if ($db_date < $db_today){
	    			$this->assertEquals(0, $ag_id, "***** Assert 5 # Day pattern.  Not Right Data was found. Date index=$db_date *****");
	    		}elseif($blk_date_flag < (_UT_BLK_PERIOD_DAYS - 1)){
	    			$this->assertEquals($_SESSION['agendas'][0]["$agendasF_Id"], $ag_id, "***** Assert 6 # Day pattern.  Not Right Data was found. Date index=$db_date  *****");
	    			$blk_date_flag++;
	    		}else{
	    			$this->assertEquals($_SESSION['agendas'][1]["$agendasF_Id"], $ag_id, "***** Assert 7 # Day pattern.  Not Right Data or agenda was found. Date index=$db_date  *****");
	    			$blk_date_flag = 0;
	    		}
	    	}
	    	$cache_obj = NULL;
    	}else $this->markTestSkipped('Skip');
    	$_SESSION['is_end'] = true;
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    public function testChangeMonthFromFeb_to_March(){//Test 12
    	if ($_SESSION['is_cron_on'] > 0) $this->markTestSkipped('Skip');

    	global $is_t12;
        global $dbg_curr_time; global $dbg_today;
    	global $CA_PATH; include($CA_PATH."variables_DB.php");



    	if ($is_t12 || self::_is_all_t){
	    	$dbg_today = '28-02-2009';
	    	$cache_cron_obj = new cronCache_bl();
	    	$cache_cron_obj->updateFreeCacheCurrent();
	    	$cache_cron_obj = NULL;



	    	$agendas[0] = $_SESSION['agendas'][0];
	    	$dates = utils_bl::getMonthDates(_MAY_NUM, 2009);

	    	$cache_obj = new cache_bl();
	    	$free_times = $cache_obj->getDataFromFreeTimeCache($agendas, $dates, $_SESSION['org_code'], 'test');
	    	$cache_obj = NULL;



	    	foreach ($free_times as $free_item){  //  Get last item
	    		$last_date1 = $free_item["$cacheF_Date"];
	    	}

	    	$last_date1 = utils_bl::GetFormDate($last_date1);
	    	$last_date1 = utils_bl::GetNextDate($last_date1);
	    	$last_date1 = utils_bl::GetDbDate($last_date1);

	    	$dbg_today = utils_bl::GetNextDate($dbg_today);   // 01-03-2009
	    	$cache_cron_obj = new cronCache_bl();

	    	$cache_cron_obj->updateFreeCache24($_SESSION['org_code']);
	    	$cache_cron_obj = NULL;

	    	$cache_obj = new cache_bl();
	    	$free_times = $cache_obj->getDataFromFreeTimeCache($agendas, $dates, $_SESSION['org_code'], 'test');
	    	$cache_obj = NULL;

	    	foreach ($free_times as $free_item){
	    		$last_date2 = $free_item["$cacheF_Date"];
	    	}

	    	$this->assertEquals($last_date1, $last_date2, "***** Assert 1. # Not valid last date of cache zone which was got after daily update *****");
    	} else $this->markTestSkipped('Skip');

    	//$_SESSION['is_end'] = true;
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    public function testMissedDayUpdate(){//Test N = 12a. )
    	if ($_SESSION['is_cron_on'] > 0) $this->markTestSkipped('Skip');

    	global $is_t14;
        global $dbg_curr_time; global $dbg_today;
    	global $CA_PATH; include($CA_PATH."variables_DB.php");

    	if ($is_t14 || self::_is_all_t){
    		$chack_date = $dbg_today = utils_bl::GetNextDate($dbg_today);
    		$dbg_today = utils_bl::GetNextDate($dbg_today);

	    	$cache_cron_obj = new cronCache_bl();
	    	$cache_cron_obj->updateFreeCache24($_SESSION['org_code']);
	    	$cache_cron_obj = NULL;

	    	$cache_obj = new cache_bl($_SESSION['app_types'][0]["$appTypesF_Id"], $_SESSION['agendas'], _MAY_NUM, 2009);
	    	$cache_info = $cache_obj->getAnyFreeAgendaForMonth();
	    	$cache_obj = NULL;

	    	$condition = true; $missed_date = '';
	    	foreach ($cache_info as $db_date=>$ag_id){
	    		if (0 == $ag_id) {
	    		    $condition = false;
	    		    $missed_date = utils_bl::GetFormDate($db_date);
	    		    break;
	    		}
	    	}

	    	$this->assertTrue($condition, "***** Assert 1. # Day ($missed_date) was missed after two days update *****");

    	} else $this->markTestSkipped('Skip');

    	$_SESSION['is_end'] = true;
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    public function testAllAppopintmentsAreMultiAgendas__and_method_refreshCacheForDate_ToMultiAgendas(){//   $is_t15
        if ($_SESSION['is_cron_on'] > 0) $this->markTestSkipped('Skip');

        global $is_t15;
        global $CA_PATH; include($CA_PATH."variables_DB.php");
        global $dbg_curr_time; $dbg_curr_time = '12:00';
        global $dbg_today;  //10-09-2008


        if ($is_t15 || self::_is_all_t){
            $app_types = &$_SESSION['app_types'];
            $agendas    = &$_SESSION['agendas'];

            $check_date = utils_bl::GetNextDate($dbg_today);
            $app_ag_ids = array (0=>$agendas[0]["$agendasF_Id"],
                                                    1=>$agendas[1]["$agendasF_Id"]);

            $apps = $this->setStandardAppointmentsForTimetable($check_date, _UT_AG_START, _UT_AG_END, $app_ag_ids, $app_types[0]["$appTypesF_Id"]);

            appointments_dbl::DeleteAppById($apps[6]["$appointmentsF_AppId"]);     //    Delete 30 min app to create 30 min free line


            $ag_ids = array ();
            foreach ($agendas as $agenda){
                $ag_ids[] = $agenda["$agendasF_Id"];
            }

            $cache_obj = new cache_bl();
            $free_times = $cache_obj->refreshCacheForDate_ToMultiAgendas($ag_ids, $check_date);
            $cache_obj = NULL;

            $this->assertEquals($free_times[0], $free_times[1], "***** Assert 100. # Multi agendas *****");
        }else {
            $this->markTestSkipped('Skip');
        }
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    public function testForOneAgeda_NoAppointments_NoOffDays_AppTypeHasPatternAndMinTime(){//   $is_t18
        if ($_SESSION['is_cron_on'] > 0) $this->markTestSkipped('Skip');

        global $is_t18;
        global $CA_PATH; include($CA_PATH."variables_DB.php");
        global $dbg_curr_time; $dbg_curr_time = '12:00';
        global $dbg_today; $dbg_today = '23-07-2009';


        if ($is_t18 || self::_is_all_t){
            $app_types = &$_SESSION['app_types'];
            $agendas    = &$_SESSION['agendas'];

            //  Cache clearing.
            UT_utils::deleteAllOrgDataFromTable($tableCache);
            $sql_string = "update $tableAgendas set $agendasF_CacheStatus="._CACHE_NOT_VALID." where $field_org_code='"._UT_ORG_CODE."'";;
            $result = mysql_query($sql_string);

            $index = count($app_types);
            $app_type = UT_utils::initAppTypeArray();
            $app_type["$appTypesF_PeriodDay"] = '0,1,1,1,1,0,0';
            $app_type["$appTypesF_MinTime"] = 24;
            $app_types = UT_utils::addAppType_UT($app_type, $app_types);


	    	$cache_cron_obj = new cronCache_bl();
	    	$cache_cron_obj->updateFreeCacheCurrent();
	    	$cache_cron_obj = NULL;


	    	$tst_ags = array();
	    	$tst_ags[] = $agendas[0];

	    	$cache_obj = new cache_bl($app_types[14]["$appTypesF_Id"], $tst_ags, _JULY_NUM, 2009);
	    	$cache_info = $cache_obj->getAnyFreeAgendaForMonth();
	    	$cache_obj = NULL;

	    	// July testing. ##############################################################################################################
			$db_date = '2009-07-01';
			for ($i = 0; $i < 22; $i++){   ///   From 1 till 23
				$condition = ($cache_info["$db_date"]) ? true : false;
				$this->assertFalse($condition, "\n***** Assert 0. # Agenda was set on date before today. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");
				$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));
			}

			//  Till end of month testing
			$condition = ($cache_info["$db_date"]) ? true : false;
			$this->assertTrue($condition, "\n***** Assert 1. # Agenda was not set on today. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

			$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Friday 24
			$condition = ($cache_info["$db_date"]) ? true : false;
			$this->assertFalse($condition, "\n***** Assert 2. # Agenda was set on Friday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

			$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Saturday 25
			$condition = ($cache_info["$db_date"]) ? true : false;
			$this->assertFalse($condition, "\n***** Assert 3. # Agenda was set on Saturday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

			$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Sunday 26
			$condition = ($cache_info["$db_date"]) ? true : false;
			$this->assertFalse($condition, "\n***** Assert 4. # Agenda was set on Sunday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

			$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Monday 27
			$condition = ($cache_info["$db_date"]) ? true : false;
			$this->assertTrue($condition, "\n***** Assert 5. # Agenda was not set on Monday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

			$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Tuesday 28
			$condition = ($cache_info["$db_date"]) ? true : false;
			$this->assertTrue($condition, "\n***** Assert 6. # Agenda was not set on Tuesday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

			$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Wednesday 29
			$condition = ($cache_info["$db_date"]) ? true : false;
			$this->assertTrue($condition, "\n***** Assert 7. # Agenda was not set on Wednesday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

			$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Thursday 30
			$condition = ($cache_info["$db_date"]) ? true : false;
			$this->assertTrue($condition, "\n***** Assert 8. # Agenda was not set on Thursday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

			$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Friday 31
			$condition = ($cache_info["$db_date"]) ? true : false;
			$this->assertFalse($condition, "\n***** Assert 9. # Agenda was set on Friday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");


			// August testing. ##############################################################################################################
	    	$cache_obj = new cache_bl($app_types[14]["$appTypesF_Id"], $tst_ags, _AUGUST_NUM, 2009);
	    	$cache_info = $cache_obj->getAnyFreeAgendaForMonth();
	    	$cache_obj = NULL;

	    	// Weekly testing (4 weeks)
	    	for ($i = 0; $i < 4; $i++){
				$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Saturday 1, 8, 15, 22
				$condition = ($cache_info["$db_date"]) ? true : false;
				$this->assertFalse($condition, "\n***** Assert (weekly testing 10-0). # Agenda was set on Saturday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

				$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Sunday 2, 9, 16, 23
				$condition = ($cache_info["$db_date"]) ? true : false;
				$this->assertFalse($condition, "\n***** Assert (weekly testing 10-1). # Agenda was set on Sunday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

				$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Monday 3, 10, 17, 24
				$condition = ($cache_info["$db_date"]) ? true : false;
				$this->assertTrue($condition, "\n***** Assert (weekly testing 10-2). # Agenda was not set on Monday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

				$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Tuesday 4, 11, 18, 25
				$condition = ($cache_info["$db_date"]) ? true : false;
				$this->assertTrue($condition, "\n***** Assert (weekly testing 10-3). # Agenda was not set on Tuesday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

				$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Wednesday 5, 12, 19, 26
				$condition = ($cache_info["$db_date"]) ? true : false;
				$this->assertTrue($condition, "\n***** Assert (weekly testing 10-4). # Agenda was not set on Wednesday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

				$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Thursday 6, 13, 20, 27
				$condition = ($cache_info["$db_date"]) ? true : false;
				$this->assertTrue($condition, "\n***** Assert (weekly testing 10-5). # Agenda was not set on Thursday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

				$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Friday 7, 14, 21, 28
				$condition = ($cache_info["$db_date"]) ? true : false;
				$this->assertFalse($condition, "\n***** Assert (weekly testing 10-6). # Agenda was set on Friday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");
	    	}

			$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Saturday 29
			$condition = ($cache_info["$db_date"]) ? true : false;
			$this->assertFalse($condition, "\n***** Assert 11. # Agenda was set on Saturday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

			$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Sunday 30
			$condition = ($cache_info["$db_date"]) ? true : false;
			$this->assertFalse($condition, "\n***** Assert 12. # Agenda was set on Sunday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

			$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Monday 31
			$condition = ($cache_info["$db_date"]) ? true : false;
			$this->assertTrue($condition, "\n***** Assert 13. # Agenda was not set on Monday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");




			// September testing. ##############################################################################################################.
	    	$cache_obj = new cache_bl($app_types[14]["$appTypesF_Id"], $tst_ags, _SEPTEMBER_NUM, 2009);
	    	$cache_info = $cache_obj->getAnyFreeAgendaForMonth();
	    	$cache_obj = NULL;

	    	// Weekly testing (4 weeks)
	    	for ($i = 0; $i < 4; $i++){
				$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Tuesday 1, 8, 15, 22
				$condition = ($cache_info["$db_date"]) ? true : false;
				$this->assertTrue($condition, "\n***** Assert (weekly testing 14-0). # Agenda was not set on Tuesday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

				$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Wednesday 2, 9, 16, 23
				$condition = ($cache_info["$db_date"]) ? true : false;
				$this->assertTrue($condition, "\n***** Assert (weekly testing 14-1). # Agenda was not set on Wednesday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

				$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Thursday 3, 10, 17, 24
				$condition = ($cache_info["$db_date"]) ? true : false;
				$this->assertTrue($condition, "\n***** Assert (weekly testing 14-2). # Agenda was not set on Thursday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

				$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Friday 4, 11, 18, 25
				$condition = ($cache_info["$db_date"]) ? true : false;
				$this->assertFalse($condition, "\n***** Assert (weekly testing 14-3). # Agenda was set on Friday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

				$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Saturday  5, 12, 19, 26
				$condition = ($cache_info["$db_date"]) ? true : false;
				$this->assertFalse($condition, "\n***** Assert (weekly testing 14-4). # Agenda was set on Saturday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

				$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Sunday 6, 13, 20, 27
				$condition = ($cache_info["$db_date"]) ? true : false;
				$this->assertFalse($condition, "\n***** Assert (weekly testing 14-5). # Agenda was set on Sunday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

				$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Monday 7, 14, 21, 28
				$condition = ($cache_info["$db_date"]) ? true : false;
				$this->assertTrue($condition, "\n***** Assert (weekly testing 14-6). # Agenda was not set on Monday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");
	    	}
			$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Tuesday 29
			$condition = ($cache_info["$db_date"]) ? true : false;
			$this->assertTrue($condition, "\n***** Assert 15. # Agenda was not set on Tuesday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

			$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Wednesday 30
			$condition = ($cache_info["$db_date"]) ? true : false;
			$this->assertTrue($condition, "\n***** Assert 16. # Agenda was not set on Wednesday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");


			//echo "<br>Current date: $db_date<br>";


			// October testing. ##############################################################################################################.
	    	$cache_obj = new cache_bl($app_types[14]["$appTypesF_Id"], $tst_ags, _OCTOBER_NUM, 2009);
	    	$cache_info = $cache_obj->getAnyFreeAgendaForMonth();
	    	$cache_obj = NULL;

	    	// Weekly testing (4 weeks)
	    	for ($i = 0; $i < 4; $i++){
				$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Thursday 1, 8, 15, 22
				$condition = ($cache_info["$db_date"]) ? true : false;
				$this->assertTrue($condition, "\n***** Assert (weekly testing 17-0). # Agenda was not set on Thursday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

				$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Friday 2, 9, 16, 23
				$condition = ($cache_info["$db_date"]) ? true : false;
				$this->assertFalse($condition, "\n***** Assert (weekly testing 17-1). # Agenda was set on Friday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

				$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Saturday 3, 10, 17, 24
				$condition = ($cache_info["$db_date"]) ? true : false;
				$this->assertFalse($condition, "\n***** Assert (weekly testing 17-2). # Agenda was set on Saturday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

				$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Sunday 4, 11, 18, 25
				$condition = ($cache_info["$db_date"]) ? true : false;
				$this->assertFalse($condition, "\n***** Assert (weekly testing 17-3). # Agenda was set on Sunday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

				$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Monday 5, 12, 19, 26
				$condition = ($cache_info["$db_date"]) ? true : false;
				$this->assertTrue($condition, "\n***** Assert (weekly testing 17-4). # Agenda was not set on Monday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

				$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Tuesday 6, 13, 20, 27
				$condition = ($cache_info["$db_date"]) ? true : false;
				$this->assertTrue($condition, "\n***** Assert (weekly testing 17-5). # Agenda was not set on Tuesday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

				$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Wednesday 7, 14, 21, 28
				$condition = ($cache_info["$db_date"]) ? true : false;
				$this->assertTrue($condition, "\n***** Assert (weekly testing 17-6). # Agenda was not set on Wednesday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");
	    	}
			$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Thursday 29
			$condition = ($cache_info["$db_date"]) ? true : false;
			$this->assertTrue($condition, "\n***** Assert 18. # Agenda was not set on Thursday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

			$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Friday 30
			$condition = ($cache_info["$db_date"]) ? true : false;
			$this->assertFalse($condition, "\n***** Assert 19. # Agenda was set on Friday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");

			$db_date = utils_bl::GetDbDate(utils_bl::GetNextDate(utils_bl::GetFormDate($db_date)));  //  Saturday 31
			$condition = ($cache_info["$db_date"]) ? true : false;
			$this->assertFalse($condition, "\n***** Assert 20. # Agenda was set on Saturday. *****\nDate: ".utils_bl::GetFormDate($db_date).", Cache info: ".$cache_info["$db_date"]."\n");
        }else {
            $this->markTestSkipped('Skip');
        }
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------


   //  This test is not needed because filtration was changed. See testOnlyCallUsTimeSlotsAreAvailable_MinTimeIsTested method
//    public function testOnlyCallUsTimeSlotsAreAvailable_MaxTimeIsTested(){//   $is_t17
//        if ($_SESSION['is_cron_on'] > 0) $this->markTestSkipped('Skip');
//
//        global $is_t17;
//        global $CA_PATH; include($CA_PATH."variables_DB.php");
//        global $dbg_curr_time; $dbg_curr_time = '12:00';
//        global $dbg_today;  //10-09-2008
//
//        if ($is_t17 || self::_is_all_t){
//            $app_types = &$_SESSION['app_types'];
//            $agendas    = &$_SESSION['agendas'];
//
//            $db_today = utils_bl::GetDbDate($dbg_today);
//            $db_last_day = utils_bl::GetDbDate(utils_bl::AddDaysToDate($dbg_today, _UT_APP_TYPE_MAX_TIME));
//
//        }else {
//            $this->markTestSkipped('Skip');
//        }
//    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------



//ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!
//Those tests must be last in the sequence of tests. Set all new tests before this text.
    public function testCacheIntegrityCheckout(){//  This test has no switch on/off        Mandatory
        $_SESSION['is_end'] = true;

    	global $CA_PATH; include($CA_PATH."variables_DB.php");

    	if ($_SESSION['is_cron_on'] > 0) {
	    	unset($_SESSION['check_ag']);
	    	unset($_SESSION['last_date']);
    	    $this->markTestSkipped('Skip');
    	}

    	$dates = utils_bl::getMonthDates(_FEBRUARY_NUM, 2009);

    	$agendas[0] = $_SESSION['check_ag'];
	    $cache_obj = new cache_bl();
	    $free_times = $cache_obj->getDataFromFreeTimeCache($agendas, $dates, 'UnitTestCheck', 'test');

	    UT_utils::deleteAllOrgDataFromTable($tableCache, "UnitTestCheck");
    	UT_utils::deleteAllOrgDataFromTable($tableAgendas, "UnitTestCheck");

        unset($_SESSION['check_ag']);

        $cache_obj->setLogDate($_SESSION['last_date']);
        unset($_SESSION['last_date']);

        UT_utils::setCacheStatus_UT(_IS_NOT_BUSY);
        $cache_obj = NULL;

    	$this->assertArrayHasKey(0, $free_times, "***** Assert 1. # DB integrity is crashed*****");
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

     public function test_LAST_TEST(){    //            Mandatory
        $_SESSION['is_end'] = true;
        if (!($_SESSION['is_cron_on'] > 0)){UT_utils::setCacheStatus_UT(_IS_NOT_BUSY);}
        unset($_SESSION['is_cron_on']);
//        $cache_obj = NULL;
     }


    //  End of all tests


    //#########################################################

    private function createEnvironment(){
        $_SESSION['agendas'] = UT_utils::createAgendas_UT(_UT_QNT_AGNDS);
        $_SESSION['clients']  = UT_utils::createClients_UT(_UT_QNT_CLNTS);
        $_SESSION['app_types']  = UT_utils::createAppTypes_UT();
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    private function deleteEnvironment(){
        global $CA_PATH; include($CA_PATH."variables_DB.php");


        UT_utils::deleteOrgData();

/*

        foreach ($_SESSION['clients'] as $client){
             user_bl::deleteClient($client["$clientsF_Id"]);
        }

        UT_utils::deleteAllOrgDataFromTable($tableAppAgendaAssign);
        UT_utils::deleteAllOrgDataFromTable($tableAppointments);
        UT_utils::deleteAllOrgDataFromTable($tableAppTypes);
    	UT_utils::deleteAllOrgDataFromTable($tableDaysOff);
    	UT_utils::deleteAllOrgDataFromTable($tableDaysoffPattern);
        UT_utils::deleteAllOrgDataFromTable($tableCache);

        UT_utils::deleteAllOrgDataFromTable($tableAgendas);*/
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------


    private function execTestsForAZone($checkDate, $entry = 1){
    	global $CA_PATH; include($CA_PATH."variables_DB.php");
    	global $dbg_curr_time;
    	$agendas = &$_SESSION['agendas'];
    	$app_types = &$_SESSION['app_types'];

    	$date_db = utils_bl::GetDbDate($checkDate);

    	$cache_obj = new cache_bl();
    	$free_times = $cache_obj->refreshCacheForDate($agendas[0], $checkDate);
    	reset($free_times);
    	$date_free_times = each($free_times);

    	$exp_count = count($date_free_times['value']);

        $check_dates[0] = $checkDate;
        $agendas_t[0] = $agendas[0];
        $cache_info = $cache_obj->getDataFromFreeTimeCache($agendas_t, $check_dates, $_SESSION['org_code'], 'test');
        $get_count = count($cache_info);
        $this->assertEquals($exp_count, $get_count, "*********** Assert 0 / entry: $entry #  Wrong cache writing. ***************");





    	$free_times = $cache_obj->refreshCacheForDate($agendas[1], $checkDate);
    	$cache_obj = NULL;



//utils_bl::printArray($agendas, 'agendas');

    	//  No A-zone. A-zone means app type which has start & end times.
    	$cache_obj = new cache_bl($app_types[6]["$appTypesF_Id"], $agendas, _SEPTEMBER_NUM, 2008);
    	$cache_info = $cache_obj->getAnyFreeAgendaForMonth();

/*
$sql_str = "select * from $tableAppTypes where $field_org_code='"._UT_ORG_CODE."';";
$app_types_db = utils_bl::executeMySqlSelectQuery($sql_str);
utils_bl::printArray($app_types_db[6], 'app_types_db');
*/

    	$this->assertEquals($agendas[0]["$agendasF_Id"], $cache_info["$date_db"], "*********** Assert 1 / entry: $entry #  No A-zone.  App duration fit ***************");
    	$cache_obj = NULL;


    	// Encolsed A-zone
    	$cache_obj = new cache_bl($app_types[4]["$appTypesF_Id"], $agendas, _SEPTEMBER_NUM, 2008);
    	$cache_info = $cache_obj->getAnyFreeAgendaForMonth();
    	$this->assertEquals($agendas[1]["$agendasF_Id"], $cache_info["$date_db"], 	"*********** Assert 2 / entry: $entry # Encolsed A-zone. App duration not fit **********");
    	$cache_obj = NULL;


    	//  Top A-zone. Overlaped.
    	$cache_obj = new cache_bl($app_types[8]["$appTypesF_Id"], $agendas, _SEPTEMBER_NUM, 2008);
    	$cache_info = $cache_obj->getAnyFreeAgendaForMonth();
    	$this->assertEquals($agendas[0]["$agendasF_Id"], $cache_info["$date_db"], "*********** Assert 3 / entry: $entry # Top A-zone. Overlaped.**********");
    	$cache_obj = NULL;

    	$cache_obj = new cache_bl($app_types[7]["$appTypesF_Id"], $agendas, _SEPTEMBER_NUM, 2008);
    	$cache_info = $cache_obj->getAnyFreeAgendaForMonth();
    	$this->assertEquals($agendas[1]["$agendasF_Id"], $cache_info["$date_db"], "*********** Assert 4 / entry: $entry # Top A-zone. Overlaped.**********");
    	$cache_obj = NULL;

    	//  Bottom A-zone. Overlaped.
    	$cache_obj = new cache_bl($app_types[9]["$appTypesF_Id"], $agendas, _SEPTEMBER_NUM, 2008);
    	$cache_info = $cache_obj->getAnyFreeAgendaForMonth();
    	$this->assertEquals($agendas[0]["$agendasF_Id"], $cache_info["$date_db"], "*********** Assert 5 / entry: $entry # Bottom A-zone. Overlaped.**********");
    	$cache_obj = NULL;

    	$cache_obj = new cache_bl($app_types[10]["$appTypesF_Id"], $agendas, _SEPTEMBER_NUM, 2008);
    	$cache_info = $cache_obj->getAnyFreeAgendaForMonth();
    	$this->assertEquals($agendas[1]["$agendasF_Id"], $cache_info["$date_db"], "*********** Assert 6 / entry: $entry # Bottom A-zone. Overlaped.**********");
    	$cache_obj = NULL;

    	//  Top A-zone. Jointed.
    	$cache_obj = new cache_bl($app_types[11]["$appTypesF_Id"], $agendas, _SEPTEMBER_NUM, 2008);
    	$cache_info = $cache_obj->getAnyFreeAgendaForMonth();
    	$this->assertEquals($agendas[1]["$agendasF_Id"], $cache_info["$date_db"], "*********** Assert 7 / entry: $entry # Top A-zone. Jointed.**********");
    	$cache_obj = NULL;

    	//  Bottom A-zone. Jointed.
    	$cache_obj = new cache_bl($app_types[12]["$appTypesF_Id"], $agendas, _SEPTEMBER_NUM, 2008);
    	$cache_info = $cache_obj->getAnyFreeAgendaForMonth();
    	$this->assertEquals($agendas[1]["$agendasF_Id"], $cache_info["$date_db"], "*********** Assert 8 / entry: $entry # Bottom A-zone. Jointed.**********");
    	$cache_obj = NULL;
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    public function setStandardAppointmentsForTimetable($checkDate, $startTime, $endTime, $agIds, $appTypeId){
    	global $CA_PATH; include($CA_PATH."variables_DB.php");

	    	$date_db             = utils_bl::GetDbDate($checkDate);
	    	$start_time_min = utils_bl::timeToMinutes($startTime);
	    	$end_time_min  = utils_bl::timeToMinutes($endTime);

	    	$app = array(
	    	"$appointmentsF_AppId"=>'null',
	    	"clients"=>array(0=>$_SESSION['clients'][0]["$clientsF_Id"]),
//	    	"agendas"=>array(0=>$agId),
	    	"agendas"=>$agIds,
	    	"$appointmentsF_ClietnId"=>0,
	    	"$appointmentsF_Date"=>$date_db,
	    	"$appointmentsF_AppTypeId"=>$appTypeId,
//	    	"$appointmentsF_AgendaId"=>$agId,
	    	"$appointmentsF_AgendaId"=>$agIds[0],
	    	"$appointmentsF_StatusId"=>3,
	    	"$appointmentsF_Comment"=>'Unit test. Which tests were created by C.Kolenchenko (ckolenchenko@yukon.cv.ua)',
	    	"$appointmentsF_EndDate"=>$date_db,
	    	"$appointmentsF_MaxNumberClient"=>1,
	    	"$appointmentsF_IsShared"=>0,
	    	"$appointmentsF_CreateDate"=>$date_db,
	    	"$appointmentsF_Creater"=>1);

	    	$time_min = $start_time_min;
	    	$apps = array();
	    	while ($time_min < $end_time_min){
	    		$time = date('H:i', mktime(0, $time_min));
	    		$app["$appointmentsF_SartTime"] = $time;
//	    	    $apps = UT_utils::addAppointment_UT($app, $apps);
	    	    $apps = UT_utils::addAppointment_mod_UT($app, $apps);
	    		$time_min += 30;
	    	}

	    	return $apps;

    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

}

/*
$sql_string = "select * from $tableCache where $field_org_code='"._UT_ORG_CODE."'";
$arr = utils_bl::executeMySqlSelectQuery($sql_string);
utils_bl::printArray($arr, 'Cache by sql');
*/

?>