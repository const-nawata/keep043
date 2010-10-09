<?php
require_once $CA_PATH.'test/session_tuning.php';
require_once 'environment.php';

class appTest01 extends PHPUnit_Framework_TestCase{
	const _is_all = true;   //  false  true

	const _is_t1 = false;

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
//This test must be first in the sequence of tests. Put all new tests after this test. It is necessary to excluede cache interferance.
    public function test_firstTest(){// **  _is_t1 **        //  First test. Mandatory. Don't put any test before this one.
        global $CA_PATH;include($CA_PATH."variables_DB.php");

         $log_info = UT_utils::setCronStatusForTest(_IS_BUSY);
         $_SESSION['last_date'] = utils_bl::GetFormDate($log_info["$cacheLogF_LastUpdtDate"]);

        if ((self::_is_t1 || self::_is_all) && !$_SESSION['is_skip']){
            $_SESSION['is_skip'] = true;
            $agendas    = &$_SESSION['agendas'];
            $clients	= &$_SESSION['clients'];
            $app_types	= &$_SESSION['app_types'];
            $apps		= &$_SESSION['apps'];

            $env = new environment(1); $env = NULL;


//utils_bl::printArray($apps, 'apps');

//utils_bl::printArray($_SESSION, '_SESSION');

            $_SESSION['is_skip'] = false;
        }else{$this->markTestSkipped('test_getAllAppointments is off!!!');}
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------





    //ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!
//This test must be last in the sequence of tests. Put all new tests before this test.

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
//--------------------------------------------------------------------------------------------------------------------------------------------------
}
//utils_bl::printArray($agendas, 'agendas');

/*
$sql = "select * from $tableAgendas";
$arr = utils_bl::executeMySqlSelectQuery($sql);
utils_bl::printArray($arr, 'agendas DB');

$sql = "select * from $tableClientsLogin left join $tableClients on $tableClientsLogin.$clientsLoginF_Id=$tableClients.$clientsF_Id";
$arr = utils_bl::executeMySqlSelectQuery($sql);
utils_bl::printArray($arr, 'clients DB');

*/


?>