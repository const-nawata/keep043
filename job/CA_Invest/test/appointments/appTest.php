<?php
/**
 * Created by Constantine Kolenchenko <ckolenchenko@yukon.cv.ua> on 08-04-2009
 * Copyright Yukon Software Ukraine 2008. All rights reserved.
 */

require_once $CA_PATH.'test/session_tuning.php';


class appTest extends PHPUnit_Framework_TestCase{
	const _is_all = true;   //  false  true

	const _is_t1 = false;
	const _is_t2 = false;
	const _is_t3 = false;
	const _is_t4 = true;
	const _is_t5 = false;
	const _is_t6 = false;

	const _chk_date = '09-04-2009';
	const _strt_time = '09:00';

    protected function setUp(){
        session_tuning::destroySessionData();
        session_tuning::createSessionData();
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------

    protected function tearDown(){
        UT_utils::deleteOrgData();
        session_tuning::destroySessionData();
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------

//ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!
//This test must be first in the sequence of tests. Set all new tests after this test. It is necessary to excluede cache interferance.
    public function test_getAllAppointments(){// **  _is_t1 **        //  First test. Mandatory. Don't put any test before this one.
        global $CA_PATH;include($CA_PATH."variables_DB.php");

         $log_info = UT_utils::setCronStatusForTest(_IS_BUSY);
         $_SESSION['last_date'] = utils_bl::GetFormDate($log_info["$cacheLogF_LastUpdtDate"]);

        if ((self::_is_t1 || self::_is_all) && !$_SESSION['is_skip']){
            $_SESSION['is_skip'] = true;
            $agendas    = &$_SESSION['agendas'];
            self::createEnvironment_01();

//      There were created 3 appointments which have:
//      1) Agendas attached with 0 and 1 index number.
//      2) Agendas attached with 0, 1 and 2 index number.
//      3) Agendas attached with 0, 1, 2 and 3 index number.
//      All appointments have one client attached with 0 index number

            $app_dates[0] = self::_chk_date;

            $app_obj = new appointments_bl();
            $app_agendas[0] = $agendas[0];
            $all_apps = $app_obj->getAllAppointments($app_dates, $app_agendas);
            $qnt_apps = count($all_apps);
            $this->assertEquals(3, $qnt_apps, "***** Assert 0. Wrong quantity of appointments for agenda.  # Agenda's index: 0 *****");
            $app_obj = NULL;

            $app_obj = new appointments_bl();
            $app_agendas[0] = $agendas[1];
            $all_apps = $app_obj->getAllAppointments($app_dates, $app_agendas);
            $qnt_apps = count($all_apps);
            $this->assertEquals(3, $qnt_apps, "***** Assert 1. Wrong quantity of appointments for agenda.  # Agenda's index: 1 *****");
            $app_obj = NULL;

            $app_obj = new appointments_bl();
            $app_agendas[0] = $agendas[2];
            $all_apps = $app_obj->getAllAppointments($app_dates, $app_agendas);
            $qnt_apps = count($all_apps);
            $this->assertEquals(2, $qnt_apps, "***** Assert 2. Wrong quantity of appointments for agenda.  # Agenda's index: 2 *****");
            $app_obj = NULL;

            $app_obj = new appointments_bl();
            $app_agendas[0] = $agendas[3];
            $all_apps = $app_obj->getAllAppointments($app_dates, $app_agendas);
            $qnt_apps = count($all_apps);
            $this->assertEquals(1, $qnt_apps, "***** Assert 3. Wrong quantity of appointments for agenda.  # Agenda's index: 3 *****");
            $app_obj = NULL;

            $_SESSION['is_skip'] = false;
        }else{$this->markTestSkipped('test_getAllAppointments is off!!!');}
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------
    public function test_GetAppointmentById(){// **  _is_t2 **
        global $CA_PATH;include($CA_PATH."variables_DB.php");
        if ((self::_is_t2 || self::_is_all) && !$_SESSION['is_skip']){
            $_SESSION['is_skip'] = true;
            self::createEnvironment_01();
            $clients        = &$_SESSION['clients'];
            $agendas    = &$_SESSION['agendas'];
            $app_types = &$_SESSION['app_types'];
            $apps           = &$_SESSION['apps'];

//      There were created 3 appointments which have:
//      1) Agendas attached with 0 and 1 index number.
//      2) Agendas attached with 0, 1 and 2 index number.
//      3) Agendas attached with 0, 1, 2 and 3 index number.
//      All appointments have one client attached with 0 index number

            $app_chk = appointments_bl::GetAppointmentById($apps[0]["$appointmentsF_AppId"]);
            $condition = isset($app_chk['agendas']);

            $this->assertTrue($condition, "Assert 4. Agendaa' list is absent. *****");

            $_SESSION['is_skip'] = false;
        }else{$this->markTestSkipped('test_GetAppointmentById is off!!!');}
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------

    public function test_GetAllAppsForOrg(){// **  _is_t3 **
        global $CA_PATH;include($CA_PATH."variables_DB.php");
        if ((self::_is_t3 || self::_is_all) && !$_SESSION['is_skip']){
            $_SESSION['is_skip'] = true;
            self::createEnvironment_02();
            $clients        = &$_SESSION['clients'];
            $agendas    = &$_SESSION['agendas'];
            $app_types = &$_SESSION['app_types'];
            $apps           = &$_SESSION['apps'];

//      There were created 2 appointments which have:
//      1) Agendas attached with 0, 1 and 2 index number. Clients attached with 0 and 1 index number.
//      2) Agendas attached with 0, 1, 2 and 3 index number. Clients attached with 0, 1 and 2 index number.

//            $db_date = utils_bl::GetDbDate(self::_chk_date);

            $app_obj = new appointments_bl();
            $all_apps = $app_obj->GetAllAppsForOrg(self::_chk_date);
            $app_obj = NULL;

            $qnt_apps = count($all_apps);
            $this->assertEquals(2, $qnt_apps, "***** Assert 5. Wrong quantity of all appointments in organization. *****");

            $app_id_chk = $apps[0]["$appointmentsF_AppId"];
            $app = array();
            foreach ($all_apps as $app_key=>$app){
                if ($app_id_chk == $app["$appointmentsF_AppId"]) break;
            }

            $condition = isset ($app['clients']);
            $this->assertTrue($condition, "***** Assert 6. Clients' list was not found in appointment. *****");

            $condition = isset ($app['agendas']);
            $this->assertTrue($condition, "***** Assert 7. Agendas' list was not found in appointment. *****");

            $exp_client_count = count($apps[0]['clients']);
            $act_client_count = count($app['clients']);
            $this->assertEquals($exp_client_count, $act_client_count, "***** Assert 8. Wrong quantity of clients in appointment. *****");

            $exp_agenda_count = count($apps[0]['agendas']);
            $act_agenda_count = count($app['agendas']);
            $this->assertEquals($exp_agenda_count, $act_agenda_count, "***** Assert 9. Wrong quantity of agendas in appointment. *****");


            foreach ($apps[0]['clients'] as $client_id_chk){
                $condition = false;
                foreach ($app['clients'] as $client_id){
                    if ($client_id_chk == $client_id){
                        $condition = true;
                        break;
                    }
                }
                if (!$condition) break;
            }
            $this->assertTrue($condition, "***** Assert 10. Wrong clients' list in appointment. *****");

            foreach ($apps[0]['agendas'] as $agenda_id_chk){
                $condition = false;
                foreach ($app['agendas'] as $agenda_id){
                    if ($agenda_id_chk == $agenda_id){
                        $condition = true;
                        break;
                    }
                }
                if (!$condition) break;
            }
            $this->assertTrue($condition, "***** Assert 11. Wrong agendas' list in appointment. *****");

            $_SESSION['is_skip'] = false;
        }else{$this->markTestSkipped('test_GetAllAppsForOrg is off!!!');}
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------

    private function addAppointments_UT_appTest($agenda, $date){
        global $CA_PATH;include($CA_PATH."variables_DB.php");
            $clients        = &$_SESSION['clients'];
            $agendas    = &$_SESSION['agendas'];
            $app_types = &$_SESSION['app_types'];
            $apps           = &$_SESSION['apps'];
            $creator_id = $agendas[0]["$agendasF_Id"];


            $date_db = utils_bl::GetDbDate($date);
            $start_time = $agenda["$agendasF_StartTime"];
            $app = UT_utils::getIniArrayToCreateApp($start_time, $date_db, $app_types[13]["$appTypesF_Id"], $creator_id);
            $app["clients"][] = $clients[0]["$clientsF_Id"];
            $app["agendas"][] = $agenda["$agendasF_Id"];

            for ($i = 0; $i < 4; $i++){
                $app["$appointmentsF_SartTime"] = $start_time;
                $apps = UT_utils::addAppointment_mod_UT($app, $apps);
                $start_time = utils_bl::AddMinutesToTime($start_time, 120);
            }
            $app["$appointmentsF_SartTime"] = $start_time;

            return $app;
    }
    public function test_findFirstAvailableDateForMultiAgendaApp(){// **  _is_t4 **
        global $CA_PATH;include($CA_PATH."variables_DB.php");
        if ((self::_is_t4 || self::_is_all) && !$_SESSION['is_skip']){
            $_SESSION['is_skip'] = true;
            self::createEnvironment_03();
            $clients        = &$_SESSION['clients'];
            $agendas    = &$_SESSION['agendas'];
            $app_types = &$_SESSION['app_types'];
            $apps           = &$_SESSION['apps'];


//utils_bl::printArray($apps, 'apps');

            global $dbg_today; $dbg_today = self::_chk_date;  //  09-04-2009
            $tomorrow                  = utils_bl::GetNextDate($dbg_today);
            $after_tomorrow       = utils_bl::GetNextDate($tomorrow);

            $cache_cron_obj = new cronCache_bl();
            $cache_cron_obj->updateFreeCacheCurrent();
            $cache_cron_obj = NULL;

            $ags = array ();
            $ags[] = $agendas[0]["$agendasF_Id"];
            $ags[] = $agendas[2]["$agendasF_Id"];
            $ags[] = $agendas[4]["$agendasF_Id"];

            $app_obj = new appointments_bl();
            $found_date = $app_obj->findFirstAvailableDateForMultiAgendaApp($ags, $app_types[0]["$appTypesF_Time"]);
            $app_obj = NULL;
            $this->assertEquals($dbg_today, $found_date, "\n***** Assert 1. Wrong date was found if all agedas have no appointments *****\n");

//----------------------------------------------------------   New condition
            $selected_ag = $agendas[0];
            $app = self::addAppointments_UT_appTest($selected_ag, $dbg_today);
            $app["$appointmentsF_AppTypeId"] = $app_types[0]["$appTypesF_Id"];
            $apps = UT_utils::addAppointment_mod_UT($app, $apps);


//utils_bl::printArray($apps, 'apps');


            $app["$appointmentsF_SartTime"] = utils_bl::AddMinutesToTime($app["$appointmentsF_SartTime"], 30);
            $apps = UT_utils::addAppointment_mod_UT($app, $apps);

            $cache_obj = new cache_bl();
            $cache_obj->refreshCacheForDate($selected_ag, $dbg_today);
            $cache_obj = NULL;

//            $found_date = appointments_bl::findFirstAvailableDateForMultiAgendaApp($ags, $app_types[0]["$appTypesF_Time"]);
            $app_obj = new appointments_bl();
            $found_date = $app_obj->findFirstAvailableDateForMultiAgendaApp($ags, $app_types[0]["$appTypesF_Time"]);
            $app_obj = NULL;
            $this->assertEquals($tomorrow, $found_date, "\n***** Assert 2. Wrong date was found if agenda has no free time on today *****\n");

//----------------------------------------------------------   New condition
            $selected_ag = $agendas[2];
            $app = self::addAppointments_UT_appTest($selected_ag, $tomorrow);
            $app["$appointmentsF_AppTypeId"] = $app_types[0]["$appTypesF_Id"];
            $apps = UT_utils::addAppointment_mod_UT($app, $apps);
            $app["$appointmentsF_SartTime"]  = utils_bl::AddMinutesToTime($app["$appointmentsF_SartTime"] , 30);
            $app["$appointmentsF_AppTypeId"] = $app_types[5]["$appTypesF_Id"];    //  appointment with 8 min duration
            $apps = UT_utils::addAppointment_mod_UT($app, $apps);

            $cache_obj = new cache_bl();
            $cache_obj->refreshCacheForDate($selected_ag, $tomorrow);
            $cache_obj = NULL;

//            $found_date = appointments_bl::findFirstAvailableDateForMultiAgendaApp($ags, $app_types[0]["$appTypesF_Time"]);
            $app_obj = new appointments_bl();
            $found_date = $app_obj->findFirstAvailableDateForMultiAgendaApp($ags, $app_types[0]["$appTypesF_Time"]);
            $app_obj = NULL;
            $this->assertEquals($after_tomorrow, $found_date, "\n***** Assert 3. Wrong date was found if agendas have no free time on today and tomorrow \n(duration of free line is less then appointmen type duration) *****\n");

//----------------------------------------------------------   New condition
            $selected_ag = $agendas[4];
            $app = self::addAppointments_UT_appTest($selected_ag, $after_tomorrow);
            $app["$appointmentsF_AppTypeId"] = $app_types[0]["$appTypesF_Id"];
            $apps = UT_utils::addAppointment_mod_UT($app, $apps);

            $cache_obj = new cache_bl();
            $cache_obj->refreshCacheForDate($selected_ag, $after_tomorrow);
            $cache_obj = NULL;

//            $found_date = appointments_bl::findFirstAvailableDateForMultiAgendaApp($ags, $app_types[0]["$appTypesF_Time"]);
            $app_obj = new appointments_bl();
            $found_date = $app_obj->findFirstAvailableDateForMultiAgendaApp($ags, $app_types[0]["$appTypesF_Time"]);
            $app_obj = NULL;
            $this->assertEquals($after_tomorrow, $found_date, "\n***** Assert 4. Wrong date was found if agendas have no free time on today and tomorrow \nOne agenda has last available time slot after tomorrow *****\n");

//----------------------------------------------------------   New condition
            $selected_ag = $agendas[2];
            UT_utils::deleteRecFromTblById($apps[7]["$appointmentsF_AppId"], $tableAppointments, $appointmentsF_AppId);

            $cache_obj = new cache_bl();
            $cache_obj->refreshCacheForDate($selected_ag, $tomorrow);
            $cache_obj = NULL;

//            $found_date = appointments_bl::findFirstAvailableDateForMultiAgendaApp($ags, $app_types[0]["$appTypesF_Time"]);
            $app_obj = new appointments_bl();
            $found_date = $app_obj->findFirstAvailableDateForMultiAgendaApp($ags, $app_types[0]["$appTypesF_Time"]);
            $app_obj = NULL;
            $this->assertEquals($tomorrow, $found_date, "\nAssert 5. Wrong date was found if agendas have no free time on today and agenda has two free time slots on tomorrow \nDuration of one  of time slots is less then appointment type duraion\n");

            $_SESSION['is_skip'] = false;
        }else{$this->markTestSkipped('test_Template is off!!!');}
    }
    //----------------------------------------------------------------------------------------------------------------------------------------------- _UT_ORG_CODE

    public function test_findFirstAvailableDateForMultiAgendaApp_2(){// **  _is_t5 **
        global $CA_PATH;include($CA_PATH."variables_DB.php");
        if ((self::_is_t5 || self::_is_all) && !$_SESSION['is_skip']){
            $_SESSION['is_skip'] = true;
            self::createEnvironment_03();
            $clients        = &$_SESSION['clients'];
            $agendas    = &$_SESSION['agendas'];
            $app_types = &$_SESSION['app_types'];
            $apps           = &$_SESSION['apps'];
            $creator_id = &$agendas[0]["$agendasF_Id"];

            global $dbg_today; $dbg_today = self::_chk_date;  //  09-04-2009
            $tomorrow                  = utils_bl::GetNextDate($dbg_today);
            $after_tomorrow       = utils_bl::GetNextDate($tomorrow);

            $dbg_today_db = utils_bl::GetDbDate($dbg_today);
            $app = UT_utils::getIniArrayToCreateApp('12:00', $dbg_today_db, $app_types[0]["$appTypesF_Id"], $creator_id);
            $app["clients"][] = $clients[0]["$clientsF_Id"];
            $app["agendas"][] = $agendas[1]["$agendasF_Id"];
            $apps = UT_utils::addAppointment_mod_UT($app, $apps);

            $cache_cron_obj = new cronCache_bl();
            $cache_cron_obj->updateFreeCacheCurrent();
            $cache_cron_obj = NULL;

            $ags = array ();
            $ags[] = $agendas[0]["$agendasF_Id"];
            $ags[] = $agendas[1]["$agendasF_Id"];
            $ags[] = $agendas[2]["$agendasF_Id"];

//            $found_date = appointments_bl::findFirstAvailableDateForMultiAgendaApp($ags, $app_types[0]["$appTypesF_Time"]);
            $app_obj = new appointments_bl();
            $found_date = $app_obj->findFirstAvailableDateForMultiAgendaApp($ags, $app_types[0]["$appTypesF_Time"]);
            $app_obj = NULL;
            $this->assertEquals($dbg_today, $found_date, "\n***** Assert 1. Wrong date was found if only one ageda has an appointment *****\n");

//----------------------------------------------------------   New contition

            $app["$appointmentsF_SartTime"] = '11:00';
            $apps = UT_utils::addAppointment_mod_UT($app, $apps);

            $app["$appointmentsF_SartTime"] = '11:30';
            $apps = UT_utils::addAppointment_mod_UT($app, $apps);

            $app["$appointmentsF_SartTime"] = '12:30';
            $apps = UT_utils::addAppointment_mod_UT($app, $apps);

            $app["$appointmentsF_SartTime"] = '13:00';
            $apps = UT_utils::addAppointment_mod_UT($app, $apps);

            $app["$appointmentsF_SartTime"] = '13:30';
            $apps = UT_utils::addAppointment_mod_UT($app, $apps);

            $app["$appointmentsF_SartTime"] = '14:00';
            $app["$appointmentsF_AppTypeId"] = $app_types[13]["$appTypesF_Id"];
            $apps = UT_utils::addAppointment_mod_UT($app, $apps);

            $cache_obj = new cache_bl();
            $cache_obj->refreshCacheForDate($agendas[1], $dbg_today);
            $cache_obj = NULL;



/*
$sql = "select * from $tableCache where $field_org_code='"._UT_ORG_CODE."' and $cacheF_AgendaId in (".$ags[0].",".$ags[1].",".$ags[2].") order by $cacheF_Date";
$arr = utils_bl::executeMySqlSelectQuery($sql);
utils_bl::printArray($arr, 'Cache');
*/

            $app_obj = new appointments_bl();
            $found_date = $app_obj->findFirstAvailableDateForMultiAgendaApp($ags, $app_types[0]["$appTypesF_Time"]);



//            $found_date = appointments_bl::findFirstAvailableDateForMultiAgendaApp($ags, $app_types[0]["$appTypesF_Time"]);
            $this->assertEquals($tomorrow, $found_date, "\n***** Assert 2. Wrong date was found if agenda has no sutiable overlaped times *****\n");


                    $app_obj = NULL;

            $_SESSION['is_skip'] = false;
        }else{$this->markTestSkipped('test_Template is off!!!');}
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------

    private function checkAgAssTable ($appId, $agId) {
    	global $CA_PATH;include($CA_PATH."variables_DB.php");
    	$sql = "select count(*) as count from $tableAppAgendaAssign where $AppAgendaAssignF_AppId=$appId and $AppAgendaAssignF_AgendaId=$agId";
    	$result = mysql_query($sql);
    	$n_items = mysql_result($result, 0, 'count');
    	return $n_items;
    }
    private function checkClAssTable ($appId, $clId) {
    	global $CA_PATH;include($CA_PATH."variables_DB.php");
    	$sql = "select count(*) as count from $tableAppClientAssign where $AppClientAssignF_AppId=$appId and $AppClientAssignF_ClientId=$clId";
    	$result = mysql_query($sql);
    	$n_items = mysql_result($result, 0, 'count');
    	return $n_items;
    }
    public function test_DeleteAppById_MultiAgednaMode (){// **  _is_t6 **
        global $CA_PATH;include($CA_PATH."variables_DB.php");
        if ((self::_is_t6 || self::_is_all) && !$_SESSION['is_skip']){


//utils_bl::printArray($_SESSION, '_SESSION');

            $_SESSION['is_skip'] = true;
            $clients        = &$_SESSION['clients'];
            $agendas    = &$_SESSION['agendas'];
            $app_types = &$_SESSION['app_types'];
            $apps           = &$_SESSION['apps'];


//utils_bl::printArray($apps, 'apps');

        $agendas     = UT_utils::createAgendas_UT(10);  //  Quantity of agendas is 10
        $clients         = UT_utils::createClients1_UT(1);  //  Quantity of clients is 1
        $app_types  = UT_utils::createAppTypes_UT(0, 0);  //  Single appointment types. Only one appointment is created with index 0
        $creator_id = &$agendas[0]["$agendasF_Id"];

//----------------------------------------------    New appointment
//  Creator agenda number 1
//  Loged in agenda number 1
        $date_db = utils_bl::GetDbDate(self::_chk_date);
        $app = UT_utils::getIniArrayToCreateApp('12:00', $date_db, $app_types[0]["$appTypesF_Id"], $creator_id);
        $app["clients"][] = $clients[0]["$clientsF_Id"];
        $app["agendas"][] = $agendas[1]["$agendasF_Id"];
        $app["agendas"][] = $agendas[3]["$agendasF_Id"];
        $app["agendas"][] = $agendas[5]["$agendasF_Id"];
        $app["agendas"][] = $agendas[7]["$agendasF_Id"];
        $app["$appointmentsF_AgendaId"] = $agendas[1]["$agendasF_Id"];
        $_SESSION['valid_user_id'] = $agendas[1]["$agendasF_Id"];
        $apps = UT_utils::addAppointment_mod_UT($app, $apps);

        appointments_bl::DeleteAppById($apps[0]["$appointmentsF_AppId"], $agendas[7]["$agendasF_Id"]);//  Issue 2

        $chk_app = appointments_bl::GetAppointmentById_Mod($apps[0]["$appointmentsF_AppId"]);

        $is_emp = count($chk_app);
        $condition = ($is_emp > 0);
        $this->assertTrue($condition, "\nAssert 1. Appointment was deleted completly by creator from the screen of  related agenda.\n");

        $condition = true;
        foreach ($chk_app['agendas'] as $ag_id) {
        	if ($ag_id == $agendas[7]["$agendasF_Id"]) {$condition = false;break;}
        }
        $this->assertTrue($condition, "\nAssert 2. Agenda was not deleted from appointment list  by creator from the screen of related agenda.\n");

        $condition = !self::checkAgAssTable($apps[0]["$appointmentsF_AppId"], $agendas[7]["$agendasF_Id"]);
        $this->assertTrue($condition, "\nAssert 3. Agenda was not deleted from $tableAppAgendaAssign DB table.\n");

        $apps[0] = $chk_app;

        appointments_bl::DeleteAppById($apps[0]["$appointmentsF_AppId"], $agendas[1]["$agendasF_Id"]);//  Issue 1
        $chk_app = appointments_bl::GetAppointmentById_Mod($apps[0]["$appointmentsF_AppId"]);
        $is_emp = count($chk_app);
        $condition = ($is_emp == 0);
        $this->assertTrue($condition, "\nAssert 4. Appointment was not deleted  by creator from his own screen.\n");


        $condition = !(self::checkAgAssTable($apps[0]["$appointmentsF_AppId"], $agendas[1]["$agendasF_Id"]) ||
                                self::checkAgAssTable($apps[0]["$appointmentsF_AppId"], $agendas[3]["$agendasF_Id"]) ||
                                self::checkAgAssTable($apps[0]["$appointmentsF_AppId"], $agendas[5]["$agendasF_Id"]) ||
                                self::checkAgAssTable($apps[0]["$appointmentsF_AppId"], $agendas[7]["$agendasF_Id"]));
        $this->assertTrue($condition, "\nAssert 5. Agendas was not deleted from $tableAppAgendaAssign DB table after appointment deletion by creator from his own screen.\n");


        $condition = !(self::checkClAssTable($apps[0]["$appointmentsF_AppId"], $clients[0]["$clientsF_Id"]));
        $this->assertTrue($condition, "\nAssert 6. Clients was not deleted from $tableAppClientAssign DB table after appointment deletion by creator from his own screen.\n");

//----------------------------------------------    New appointment
//  Creator agenda number 3
//  Loged in agenda number 1
        $apps = $chk_app;
        $app["$appointmentsF_AgendaId"] = $agendas[3]["$agendasF_Id"];
        $apps = UT_utils::addAppointment_mod_UT($app, $apps);

        appointments_bl::DeleteAppById($apps[0]["$appointmentsF_AppId"], $agendas[7]["$agendasF_Id"]);//  Issue 4
        $chk_app = appointments_bl::GetAppointmentById_Mod($apps[0]["$appointmentsF_AppId"]);
        $is_emp = count($chk_app);
        $condition = ($is_emp > 0);
        $this->assertTrue($condition, "\nAssert 7. Appointment was deleted completly by related agenda from the screen of another related agenda.\n");

        $condition = true;
        foreach ($chk_app['agendas'] as $ag_id) {
        	if ($ag_id == $agendas[7]["$agendasF_Id"]) {$condition = false;break;}
        }
        $this->assertTrue($condition, "\nAssert 8. Agenda was not deleted from appointment list by related agenda from the screen of another related agenda.\n");

        $condition = !self::checkAgAssTable($apps[0]["$appointmentsF_AppId"], $agendas[7]["$agendasF_Id"]);
        $this->assertTrue($condition, "\nAssert 9. Agenda was not deleted from $tableAppAgendaAssign DB table \nduring appointment deletion by related agenda from the screen of another related agenda.\n");


        appointments_bl::DeleteAppById($apps[0]["$appointmentsF_AppId"], $agendas[1]["$agendasF_Id"]);//  Issue 3
        $chk_app = appointments_bl::GetAppointmentById_Mod($apps[0]["$appointmentsF_AppId"]);
        $is_emp = count($chk_app);
        $condition = ($is_emp > 0);
        $this->assertTrue($condition, "\nAssert 10. Appointment was deleted completly by related agenda from his own screen.\n");

        $condition = true;
        foreach ($chk_app['agendas'] as $ag_id) {
        	if ($ag_id == $agendas[1]["$agendasF_Id"]) {$condition = false;break;}
        }
        $this->assertTrue($condition, "\nAssert 11. Agenda was not deleted from appointment list  by related agenda from his own screen.\n");

        $condition = !self::checkAgAssTable($apps[0]["$appointmentsF_AppId"], $agendas[1]["$agendasF_Id"]);
        $this->assertTrue($condition, "\nAssert 12. Agenda was not deleted from $tableAppAgendaAssign DB table \nduring appointment deletion by related agenda from his own screen.\n");


        appointments_bl::DeleteAppById($apps[0]["$appointmentsF_AppId"], $agendas[3]["$agendasF_Id"]);//  Issue 5
        $chk_app = appointments_bl::GetAppointmentById_Mod($apps[0]["$appointmentsF_AppId"]);
        $is_emp = count($chk_app);
        $condition = ($is_emp > 0);
        $this->assertTrue($condition, "\nAssert 13. Appointment was deleted completly by related agenda from creator's screen.\n");

        $condition = true;
        foreach ($chk_app['agendas'] as $ag_id) {
        	if ($ag_id == $agendas[3]["$agendasF_Id"]) {$condition = false;break;}
        }
        $this->assertTrue($condition, "\nAssert 14. Agenda was not deleted from appointment list  by related agenda from creator's screen.\n");

        $condition = !self::checkAgAssTable($apps[0]["$appointmentsF_AppId"], $agendas[3]["$agendasF_Id"]);
        $this->assertTrue($condition, "\nAssert 15. Agenda was not deleted from $tableAppAgendaAssign DB table \nduring appointment deletion by related agenda from creator's screen.\n");


        appointments_bl::DeleteAppById($apps[0]["$appointmentsF_AppId"], $agendas[5]["$agendasF_Id"]);//  Issue 8
        $chk_app = appointments_bl::GetAppointmentById_Mod($apps[0]["$appointmentsF_AppId"]);
        $is_emp = count($chk_app);
        $condition = !($is_emp > 0);
        $this->assertTrue($condition, "\nAssert 16. Appointment was not deleted completly if list of agendas was empty.\n");

        $condition = !self::checkAgAssTable($apps[0]["$appointmentsF_AppId"], $agendas[5]["$agendasF_Id"]);
        $this->assertTrue($condition, "\nAssert 17. Agenda was not deleted from $tableAppAgendaAssign DB table if list of agendas was empty.\n");

        $condition = !(self::checkClAssTable($apps[0]["$appointmentsF_AppId"], $clients[0]["$clientsF_Id"]));
        $this->assertTrue($condition, "\nAssert 18. Client was not deleted from $tableAppAgendaAssign DB table if list of agendas was empty.\n");


//----------------------------------------------    New appointment
//  Creator agenda number 1
//  Loged in agenda number 0
        $apps = $chk_app;
        $app["$appointmentsF_AgendaId"] = $agendas[1]["$agendasF_Id"];
        $_SESSION['valid_user_id'] = $agendas[0]["$agendasF_Id"];
        $apps = UT_utils::addAppointment_mod_UT($app, $apps);

        appointments_bl::DeleteAppById($apps[0]["$appointmentsF_AppId"], $agendas[7]["$agendasF_Id"]);//  Issue 6
        $chk_app = appointments_bl::GetAppointmentById_Mod($apps[0]["$appointmentsF_AppId"]);
        $is_emp = count($chk_app);
        $condition = ($is_emp > 0);
        $this->assertTrue($condition, "\nAssert 19. Appointment was deleted completly by nonassigned agenda from the screen of  related agenda.\n");

        $condition = true;
        foreach ($chk_app['agendas'] as $ag_id) {
        	if ($ag_id == $agendas[7]["$agendasF_Id"]) {$condition = false;break;}
        }
        $this->assertTrue($condition, "\nAssert 20. Agenda was not deleted from appointment list by nonassigned agenda from the screen of  related agenda.\n");

        $condition = !self::checkAgAssTable($apps[0]["$appointmentsF_AppId"], $agendas[7]["$agendasF_Id"]);
        $this->assertTrue($condition, "\nAssert 21. Agenda was not deleted from $tableAppAgendaAssign DB table \nduring appointment deletion by nonassigned agenda from the screen of related agenda.\n");


        appointments_bl::DeleteAppById($apps[0]["$appointmentsF_AppId"], $agendas[1]["$agendasF_Id"]);//  Issue 7
        $chk_app = appointments_bl::GetAppointmentById_Mod($apps[0]["$appointmentsF_AppId"]);
        $is_emp = count($chk_app);
        $condition = ($is_emp > 0);
        $this->assertTrue($condition, "\nAssert 22. Appointment was deleted completly by nonassigned agenda from creator's screen.\n");

        $condition = true;
        foreach ($chk_app['agendas'] as $ag_id) {
        	if ($ag_id == $agendas[1]["$agendasF_Id"]) {$condition = false;break;}
        }
        $this->assertTrue($condition, "\nAssert 23. Agenda was not deleted from appointment list  by nonassigned agenda from creator's screen.\n");

        $condition = !self::checkAgAssTable($apps[0]["$appointmentsF_AppId"], $agendas[1]["$agendasF_Id"]);
        $this->assertTrue($condition, "\nAssert 24. Agenda was not deleted from $tableAppAgendaAssign DB table \nduring appointment deletion by nonassigned agenda from creator's screen.\n");


//utils_bl::printArray($chk_app, 'chk_app');
//utils_bl::printArray($apps, 'apps');



            $_SESSION['is_skip'] = false;
        }else{$this->markTestSkipped('test_Template is off!!!');}
    }
    //----------------------------------------------------------------------------------------------------------------------------------------------- _UT_ORG_CODE





    //ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!
//This test must be last in the sequence of tests. Set all new tests before this test.

    public function test_LAST_MANDATORY_FICTIVE_TEST(){        //  Last test. Mandatory. Don't put any test after this one.
        if (isset($_SESSION['last_date'])) {
            $cache_obj = new cache_bl();
            $cache_obj->setLogDate($_SESSION['last_date']);
            $cache_obj = NULL;
            unset($_SESSION['last_date']);
        }
        UT_utils::setCronStatusForTest(_IS_NOT_BUSY);
         unset($_SESSION['is_skip']);
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------

    private function createEnvironment_01(){
        global $CA_PATH;include($CA_PATH."variables_DB.php");

        $clients        = &$_SESSION['clients'];
        $agendas    = &$_SESSION['agendas'];
        $app_types = &$_SESSION['app_types'];
        $apps           = &$_SESSION['apps'];


        $agendas     = UT_utils::createAgendas_UT(4);  //  Quantity of agendas is 4
        $clients         = UT_utils::createClients1_UT(3);  //  Quantity of clients is 3
        $app_types  = UT_utils::createAppTypes_UT(0, 0);  //  Single appointment types. Only one appointment is created with index 0
        $creator_id = $agendas[0]["$agendasF_Id"];

        //  Appointments Creation.
        $app_ind = 0;

        $app_type0 = &$_SESSION['app_types'][0];     //  Standart app type. Duration 30 min. No constraints.
        $duration0 = &$app_type0["$appTypesF_Time"];    //  This value is used to change appointment start time.
        $date_db = utils_bl::GetDbDate(self::_chk_date);
        $start_time = self::_strt_time;
        $app = UT_utils::getIniArrayToCreateApp($start_time, $date_db, $app_type0["$appTypesF_Id"], $creator_id);
        $app["clients"][] = $clients[0]["$clientsF_Id"];
        $app["agendas"][] = $agendas[0]["$agendasF_Id"];
        $app["agendas"][] = $agendas[1]["$agendasF_Id"];
        $apps = UT_utils::addAppointment_mod_UT($app, $apps);$app_ind++;

        $start_time = utils_bl::AddMinutesToTime($start_time, $duration0);
        $app["$appointmentsF_SartTime"] = $start_time;
        $app["agendas"][] = $agendas[2]["$agendasF_Id"];
        $apps = UT_utils::addAppointment_mod_UT($app, $apps);$app_ind++;

        $start_time = utils_bl::AddMinutesToTime($start_time, $duration0);
        $app["$appointmentsF_SartTime"] = $start_time;
        $app["agendas"][] = $agendas[3]["$agendasF_Id"];
        $apps = UT_utils::addAppointment_mod_UT($app, $apps);$app_ind++;
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------

    private function createEnvironment_02(){
        global $CA_PATH;include($CA_PATH."variables_DB.php");

        $clients        = &$_SESSION['clients'];
        $agendas    = &$_SESSION['agendas'];
        $app_types = &$_SESSION['app_types'];
        $apps           = &$_SESSION['apps'];


        $agendas     = UT_utils::createAgendas_UT(4);  //  Quantity of agendas is 4
        $clients         = UT_utils::createClients1_UT(3);  //  Quantity of clients is 3
        $app_types  = UT_utils::createAppTypes_UT(0, 0);  //  Single appointment types. Only one appointment is created with index 0
        $creator_id = &$agendas[0]["$agendasF_Id"];

        //  Appointments Creation.
        $app_type0 = &$_SESSION['app_types'][0];     //  Standart app type. Duration 30 min. No constraints.
        $duration0 = &$app_type0["$appTypesF_Time"];    //  This value is used to change appointment start time.
        $date_db = utils_bl::GetDbDate(self::_chk_date);

        $start_time = self::_strt_time;
        $app = UT_utils::getIniArrayToCreateApp($start_time, $date_db, $app_type0["$appTypesF_Id"], $creator_id);
        $app["clients"][] = $clients[0]["$clientsF_Id"];
        $app["clients"][] = $clients[1]["$clientsF_Id"];

        $app_ind = 0;

        $app["agendas"][] = $agendas[0]["$agendasF_Id"];
        $app["agendas"][] = $agendas[1]["$agendasF_Id"];
        $app["agendas"][] = $agendas[2]["$agendasF_Id"];


        $apps = UT_utils::addAppointment_mod_UT($app, $apps);

        $app["clients"][] = $clients[2]["$clientsF_Id"];
        $app["agendas"][] = $agendas[3]["$agendasF_Id"];


        $apps = UT_utils::addAppointment_mod_UT($app, $apps);
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------

    private function createEnvironment_03(){
        global $CA_PATH;include($CA_PATH."variables_DB.php");

        $clients        = &$_SESSION['clients'];
        $agendas    = &$_SESSION['agendas'];
        $app_types = &$_SESSION['app_types'];
        $apps           = &$_SESSION['apps'];

        $agendas     = UT_utils::createAgendas_UT(5);  //  Quantity of agendas is 5
        $agendas[1]["$agendasF_StartTime"] = '07:00'; $agendas[1]["$agendasF_EndTime"] = '16:00';
        $sql_string = "update $tableAgendas set $agendasF_StartTime='".$agendas[1]["$agendasF_StartTime"]."', $agendasF_EndTime='".$agendas[1]["$agendasF_EndTime"]."' where $agendasF_Id=".$agendas[1]["$agendasF_Id"].";";
        $result = mysql_query($sql_string);

        $agendas[2]["$agendasF_StartTime"] = '11:00'; $agendas[2]["$agendasF_EndTime"] = '20:00';
        $sql_string = "update $tableAgendas set $agendasF_StartTime='".$agendas[2]["$agendasF_StartTime"]."', $agendasF_EndTime='".$agendas[2]["$agendasF_EndTime"]."' where $agendasF_Id=".$agendas[2]["$agendasF_Id"].";";
        $result = mysql_query($sql_string);

        $clients         = UT_utils::createClients1_UT(1);  //  Quantity of clients is 1
        $app_types  = UT_utils::createAppTypes_UT(0);
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

}//  End of appTest class



//####################   Secondary part


//$this->assertEquals($dbg_today, $found_date, "\n***** Assert 1. Wrong date was found if only one ageda has an appointment *****\n");
//$this->assertTrue($condition, "\nAssert 6. Clients was not deleted from $tableAppClientAssign DB table after appointment deletion by creator from his own screen.\n");


/*
//-----------------------------------------------------------------------------------------------------------------------------------------------

    public function test_Template(){
        global $CA_PATH;include($CA_PATH."variables_DB.php");
        if ((self::_is_t2 || self::_is_all) && !$_SESSION['is_skip']){
            $_SESSION['is_skip'] = true;
            self::createEnvironment_01();
            $clients        = &$_SESSION['clients'];
            $agendas    = &$_SESSION['agendas'];
            $app_types = &$_SESSION['app_types'];
            $apps           = &$_SESSION['apps'];

//      There were created 3 appointments which have:
//      1) Agendas attached with 0 and 1 index number.
//      2) Agendas attached with 0, 1 and 2 index number.
//      3) Agendas attached with 0, 1, 2 and 3 index number.
//      All appointments have one client attached with 0 index number



//////   asserts
//utils_bl::printArray($_SESSION, '_SESSION');



            $_SESSION['is_skip'] = false;
        }else{$this->markTestSkipped('test_Template is off!!!');}
    }
*/
?>