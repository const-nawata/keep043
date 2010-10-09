<?php
/**
 * Created by Constantine Kolenchenko <ckolenchenko@yukon.cv.ua> on 08-04-2009
 * Copyright Yukon Software Ukraine 2008. All rights reserved.
 */

require_once $CA_PATH.'test/session_tuning.php';


class appTestValidation2 extends PHPUnit_Framework_TestCase{
    const _is_all = true;   //  false  true

    const _is_t1 = true;
    const _is_t2 = false;
    const _is_t3 = false;
//    const _is_t4 = true;

    const _chk_date = '06-05-2009';

    private $mNewApp;

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
    public function test_isTimeBusyForAgenda(){        //  First test. Mandatory. Don't put any test before this one.
        global $CA_PATH;include($CA_PATH."variables_DB.php");
         UT_utils::setCronStatusForTest(_IS_BUSY);
        if ((self::_is_t1 || self::_is_all) && !$_SESSION['is_skip']){
            $_SESSION['is_skip'] = true;
            self::createEnvironment_01();


            $clients        = &$_SESSION['clients'];
            $agendas    = &$_SESSION['agendas'];
            $app_types = &$_SESSION['app_types'];
            $apps           = &$_SESSION['apps'];
            $org_code   = &$_SESSION['org_code'];

            $org_code = _UT_ORG_CODE.'0';
            $new_app = &$this->mNewApp;

            $new_app['agendas'] = array ();
            $new_app['agendas'][] = $agendas["$org_code"][1]["$agendasF_Id"];
            $new_app['agendas'][] = $agendas["$org_code"][2]["$agendasF_Id"];


            $new_app["$appointmentsF_SartTime"] = '09:00';
            $obj = new validation_app_update($new_app);
            $obj -> EnablePrint(false);
            $is_show_msg = false;
            $condition = $obj->isTimeBusyForAgenda($is_show_msg);
            $obj = NULL;
            $this->assertFalse($condition, 'Assert 0  ********* Wrong validation if start time of new appointment belongs to free line.*********');

            $new_app["$appointmentsF_SartTime"] = '11:00';
            $obj = new validation_app_update($new_app);
            $obj -> EnablePrint(false);
            $is_show_msg = false;
            $condition = $obj->isTimeBusyForAgenda($is_show_msg);
            $obj = NULL;
            $this->assertTrue($condition, 'Assert 1  ********* Wrong validation if start time of new appointment belongs to busy line. Same agendas, same clients. App start time = Busy line start time.*********');


            $new_app["$appointmentsF_SartTime"] = '11:10';
            $obj = new validation_app_update($new_app);
            $obj -> EnablePrint(false);
            $is_show_msg = false;
            $condition = $obj->isTimeBusyForAgenda($is_show_msg);
            $obj = NULL;
            $this->assertTrue($condition, 'Assert 2  ********* Wrong validation if start time of new appointment belongs to busy line. Same agendas, same clients. App start time > Busy line start time.*********');

            $new_app["$appointmentsF_SartTime"] = '11:29';
            $obj = new validation_app_update($new_app);
            $obj -> EnablePrint(false);
            $is_show_msg = false;
            $condition = $obj->isTimeBusyForAgenda($is_show_msg);
            $obj = NULL;
            $this->assertTrue($condition, 'Assert 4  ********* Wrong validation if start time of new appointment belongs to busy line. Same agendas, same clients. App start time > Busy line start time. Overlapped.*********');


            $new_app['clients'] = array ();
            $new_app['clients'][] = $clients["$org_code"][3]["$clientsF_Id"];
            $new_app["$appointmentsF_SartTime"] = '11:00';

            $obj = new validation_app_update($new_app);
            $obj -> EnablePrint(false);
            $is_show_msg = false;
            $condition = $obj->isTimeBusyForAgenda($is_show_msg);
            $obj = NULL;
            $this->assertTrue($condition, 'Assert 5  ********* Wrong validation if start time of new appointment belongs to busy line. Same agendas, different client (one) *********');

            $new_app["$appointmentsF_SartTime"] = '11:30';
            $obj = new validation_app_update($new_app);
            $obj -> EnablePrint(false);
            $is_show_msg = false;
            $condition = $obj->isTimeBusyForAgenda($is_show_msg);
            $obj = NULL;
            $this->assertTrue($condition, 'Assert 6  ********* Wrong validation if start time of new appointment belongs to busy line. Same agendas, different client (one) *********');







            $_SESSION['is_skip'] = false;
        }else $this->markTestSkipped('Skip');
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------

    public function test_isNewAppDurationNotFits1(){
        global $CA_PATH;include($CA_PATH."variables_DB.php");
        if ((self::_is_t2 || self::_is_all) && !$_SESSION['is_skip']){
            $_SESSION['is_skip'] = true;
            self::createEnvironment_01();                           //  May be another
            $clients        = &$_SESSION['clients'];
            $agendas    = &$_SESSION['agendas'];
            $app_types = &$_SESSION['app_types'];
            $apps           = &$_SESSION['apps'];
            $org_code   = &$_SESSION['org_code'];

            $org_code = _UT_ORG_CODE.'0';
            $new_app = &$this->mNewApp;

            $new_app['agendas'] = array ();
            $new_app['agendas'][] = $agendas["$org_code"][1]["$agendasF_Id"];
            $new_app['agendas'][] = $agendas["$org_code"][2]["$agendasF_Id"];

            $new_app['clients'] = array ();
            $new_app['clients'][] = $clients["$org_code"][3]["$clientsF_Id"];
            $new_app["$appointmentsF_SartTime"] = '11:00';


            $new_app["$appointmentsF_SartTime"] = '11:33';
            $obj = new validation_app_update($new_app);
            $obj -> EnablePrint(false);
            $is_show_msg = false;
            $condition = $obj->isNewAppDurationNotFits($is_show_msg);
            $obj = NULL;
            $this->assertTrue($condition, 'Assert 0  ********* Wrong validation if duration of new appointment  does not fits in free line. Same agendas, different client (one) *********');

            $new_app["$appointmentsF_SartTime"] = '11:35';
            $obj = new validation_app_update($new_app);
            $obj -> EnablePrint(false);
            $is_show_msg = false;
            $condition = $obj->isNewAppDurationNotFits($is_show_msg);
            $obj = NULL;
            $this->assertTrue($condition, 'Assert 1  ********* Wrong validation if duration of new appointment  does not fits in free line. Same agendas, different client (one) *********');

            $new_app["$appointmentsF_SartTime"] = '10:30';
            $obj = new validation_app_update($new_app);
            $obj -> EnablePrint(false);
            $is_show_msg = false;
            $condition = $obj->isNewAppDurationNotFits($is_show_msg);
            $obj = NULL;
            $this->assertFalse($condition, 'Assert 2  ********* Wrong validation if duration of new appointment  fits in free line. Same agendas, different client (one) *********');

            $new_app['agendas'] = array ();
            $new_app['agendas'][] = $agendas["$org_code"][4]["$agendasF_Id"];
            $new_app['agendas'][] = $agendas["$org_code"][5]["$agendasF_Id"];
            $new_app['clients'] = array ();
            $new_app['clients'][] = $clients["$org_code"][3]["$clientsF_Id"];
            $new_app["$appointmentsF_AppTypeId"] = $app_types["$org_code"][2]["$appTypesF_Id"];   //   App type duration = 22 min.
            $new_app["$appointmentsF_SartTime"] = '17:45';
            $obj = new validation_app_update($new_app);
            $obj -> EnablePrint(false);
            $is_show_msg = false;
            $condition = $obj->isNewAppDurationNotFits($is_show_msg);
            $obj = NULL;
            $this->assertTrue($condition, 'Assert 3  ********* Wrong validation if duration of new appointment  does not fits in last line of one agenda.  *********');


/*
utils_bl::printArray($agendas["$org_code"][4], 'agenda 4');
utils_bl::printArray($agendas["$org_code"][5], 'agenda 5');
utils_bl::printArray($new_app, 'new_app');
utils_bl::printArray($app_types["$org_code"], 'app_types for org 0');
*/


            $_SESSION['is_skip'] = false;
        }else $this->markTestSkipped('Skip');
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------

    public function test_isOutAgendaRange(){
        global $CA_PATH;include($CA_PATH."variables_DB.php");
        if ((self::_is_t3 || self::_is_all) && !$_SESSION['is_skip']){
            $_SESSION['is_skip'] = true;
            self::createEnvironment_01();
            $clients        = &$_SESSION['clients'];
            $agendas    = &$_SESSION['agendas'];
            $app_types = &$_SESSION['app_types'];
            $apps           = &$_SESSION['apps'];
            $org_code   = &$_SESSION['org_code'];

            $org_code = _UT_ORG_CODE.'0';
            $new_app = &$this->mNewApp;

            $new_app['agendas'] = array ();
            $new_app['agendas'][] = $agendas["$org_code"][3]["$agendasF_Id"];   //  Range 09:00 - 18:00
            $new_app['agendas'][] = $agendas["$org_code"][4]["$agendasF_Id"];   //  Range 09:00 - 18:00
            $new_app['agendas'][] = $agendas["$org_code"][5]["$agendasF_Id"];   //  Range 11:00 - 20:00
//utils_bl::printArray($new_app, 'new_app');

            $new_app["$appointmentsF_SartTime"] = '10:00';
            $obj = new validation_app_update($new_app);
            $obj -> EnablePrint(false);
            $condition = $obj->isOutAgendaRange();
            $obj = NULL;
            $this->assertTrue($condition, 'Assert 0  ********* Wrong validation if time out of agenda range. Different agendas, same clients*********');

            $_SESSION['is_skip'] = false;
        }else $this->markTestSkipped('Skip');
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------





    //ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!
//This test must be last in the sequence of tests. Set all new tests before this test.

    public function test_LAST_MANDATORY_FICTIVE_TEST(){        //  Last test. Mandatory. Don't put any test after this one.
        UT_utils::setCronStatusForTest(_IS_NOT_BUSY);
         unset($_SESSION['is_skip']);
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------

    private function createEnvironment_01(){
        global $CA_PATH;include($CA_PATH."variables_DB.php");

        $clients	= &$_SESSION['clients'];
        $agendas    = &$_SESSION['agendas'];
        $app_types	= &$_SESSION['app_types'];
        $apps		= &$_SESSION['apps'];
        $org_code	= &$_SESSION['org_code'];

        																							//   Organization 0
        $org_code	= _UT_ORG_CODE.'0';
        $agendas["$org_code"]	= UT_utils::createAgendas_UT(5);  //  Quantity of agendas is 5



//utils_bl::printArray($agendas, 'agendas');


        $last_ag				= UT_utils::createAgendas_UT(1, '11:00', '20:00', 15);
        $agendas["$org_code"][5]= $last_ag[0]; //  Quantity of agendas is 6

        $clients["$org_code"]         = UT_utils::createClients1_UT(6);  //  Quantity of clients is 6

        //  App types for org 0:
        //  0 => Multi. Duration 30 min.
        //  1 => Multi. Duration 3 min.
        //  2 => Multi. Duration 22 min.
        $app_types["$org_code"]  = UT_utils::createAppTypes2_UT(2);


        //  Appointments:
        //  0 => Start time 10:00, duration 30 min, app type index = 0.
        //  1 => Start time 11:00, duration 30 min, app type index = 0.
        //  2 => Start time 12:00, duration 30 min, app type index = 0.
        //  4 => Start time 11:30, duration   3 min, app type index = 1.

        //  Appointments 1 and 4 are forming free line which has duration 27 min.
        //  All appointments are multi and have the same 3 clients and 3 agendas

        $date_db = utils_bl::GetDbDate(self::_chk_date);
        $start_time = '10:00';                                                                                                                                                                              //   App ind 0
        $app = UT_utils::getIniArrayToCreateApp($start_time, $date_db, $app_types["$org_code"][0]["$appTypesF_Id"], $agendas["$org_code"][0]["$agendasF_Id"]);
        $app["$appointmentsF_MaxNumberClient"] = 10;
        $app["clients"][] = $clients["$org_code"][0]["$clientsF_Id"];
        $app["clients"][] = $clients["$org_code"][1]["$clientsF_Id"];
        $app["clients"][] = $clients["$org_code"][2]["$clientsF_Id"];
        $app["agendas"][] = $agendas["$org_code"][0]["$agendasF_Id"];
        $app["agendas"][] = $agendas["$org_code"][1]["$agendasF_Id"];
        $app["agendas"][] = $agendas["$org_code"][2]["$agendasF_Id"];
        $apps = UT_utils::addAppointment_mod_UT($app, $apps);

        $app["$appointmentsF_SartTime"] = '11:00';                                                                                                                               //   App ind 1
        $apps = UT_utils::addAppointment_mod_UT($app, $apps);

        $app["$appointmentsF_SartTime"] = '12:00';                                                                                                                               //   App ind 2
        $apps = UT_utils::addAppointment_mod_UT($app, $apps);

        $app["$appointmentsF_SartTime"] = '11:30';                                                                                                                               //   App ind 3
        $app["$appointmentsF_AppTypeId"] = $app_types["$org_code"][1]["$appTypesF_Id"];
        $apps = UT_utils::addAppointment_mod_UT($app, $apps);

        //   Another organization
        $list = array ();
        foreach ($clients["$org_code"] as $client){
            $list[] = array("$clientsLoginF_Id"=>$client["$clientsLoginF_Id"], "$clientsLoginF_Login"=>$client["$clientsLoginF_Login"]);
        }


        $org_code = _UT_ORG_CODE.'1';																				//   Organization 1
        $agendas["$org_code"]     = UT_utils::createAgendas_UT(6);  //  Quantity of agendas is 6
        $clients["$org_code"] = UT_utils::createClients1_UT(6, $list);

        //  App types for org 1:
        //  0 => Multi. Duration 30 min.
        //  1 => Multi. Duration 3 min.
        $app_types["$org_code"]  = UT_utils::createAppTypes2_UT(1);

        $new_app = &$this->mNewApp;
        $new_app = $apps[0];
        unset ($new_app["$field_org_code"]);
        $new_app["$appointmentsF_AppId"] = 'null';
        $new_app["$appointmentsF_AgendaId"] = -1;
        $new_app["$appointmentsF_ClietnId"] = -1;
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------
}


/*

    public function test_Template(){
        global $CA_PATH;include($CA_PATH."variables_DB.php");
        if ((self::_is_t3 || self::_is_all) && !$_SESSION['is_skip']){
            $_SESSION['is_skip'] = true;
            self::createEnvironment_01();                           //  May be another
            $clients        = &$_SESSION['clients'];
            $agendas    = &$_SESSION['agendas'];
            $app_types = &$_SESSION['app_types'];
            $apps           = &$_SESSION['apps'];
            $org_code   = &$_SESSION['org_code'];

            $org_code = _UT_ORG_CODE.'0';
            $new_app = &$this->mNewApp;



            $_SESSION['is_skip'] = false;
        }else $this->markTestSkipped('Skip');
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------
*/


?>