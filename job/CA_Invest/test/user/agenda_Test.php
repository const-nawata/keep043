<?php
/**
 * Copyright (c) 2007 Redfountain. All rights reserved
 * @author  Igor Banadiga <ibanadiga@yukon.cv.ua>
 * Changed by C.Kolenchenko <ckolenchenko@yukon.cv.ua> on 11-05-2009.
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once("./classes/bl/user_bl.php");


/**
 * Class testing user bl
 * Daniy klas realizovue robotu clienskogo  wizarda  dlja ctvorennja appointmenta v clienta
 *
 * @author		Igor Banadiga <ibanadiga@yukon.cv.ua>
 * @version		$Id: clientwizard,v 1.2 2007/11/29 14:16:27 cellog Exp $;
 * @copyright	Copyright (c) 2007 Redfountain. All rights reserved, Igor Banadiga
 * @package		user
 * @access		public
 */

class agenda_Test extends PHPUnit_Framework_TestCase{
    const _is_all = true;

    const _is_t11 = true;


    const _n_agendas = 5;

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
    /**
	 * test GetFullName
	 *
	 * @author		Igor Banadiga <ibanadiga@yukon.cv.ua>
	 * @access public
	 */
public	function testGetFullName(){
    UT_utils::setCronStatusForTest(_IS_BUSY);

		$this->assertEquals(
			user_bl::GetFullName("qwe","rty","uiop")
			,
			"qwe rty uiop");
		$this->assertEquals(
			user_bl::GetFullName("qwe","rty")
			,
			"qwe rty");
		$this->assertEquals(
			user_bl::GetFullName("qwe")
			,
			"qwe");
		$this->assertEquals(
			user_bl::GetFullName("qwe","","uiop")
			,
			"qwe uiop");
		$this->assertEquals(
			user_bl::GetFullName("","rty","uiop")
			,
			"rty uiop");
		$this->assertEquals(
			user_bl::GetFullName("qwe","rty","")
			,
			"qwe rty");
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------

public function test_getAgendasInfoByIds (){
    global $CA_PATH;include($CA_PATH."variables_DB.php");
    $agendas    = &$_SESSION['agendas'];

    if ((self::_is_t11 || self::_is_all) && !$_SESSION['is_skip']){
        self::createEnvironment_01();
        $ids = array ();
        foreach ($agendas as $agenda){
            $ids[] = $agenda["$agendasF_Id"];
        }
        $check_ags = user_bl::getAgendasInfoByIds($ids);

        $n_ags = count($check_ags);
        $this->assertEquals(self::_n_agendas, $n_ags, "***** Assert 0. Wrong quantity of agendas was faund.  *****");

        foreach ($check_ags as $chk_ag){
            $condition = false;
            foreach ($agendas as $agenda){
                if ($chk_ag["$agendasF_Id"] == $agenda["$agendasF_Id"]){
                    $this->assertEquals($agenda["$agendasF_Name"], $chk_ag["$agendasF_Name"], "***** Assert 1. Different Names were found  *****");
                    $this->assertEquals($agenda["$agendasF_Password"], $chk_ag["$agendasF_Password"], "***** Assert 2. Different Passwords were found  *****");
                    $this->assertEquals($agenda["$agendasF_Username"], $chk_ag["$agendasF_Username"], "***** Assert 3. Different User names were found  *****");
                    $this->assertEquals($agenda["$agendasF_StartTime"], $chk_ag["$agendasF_StartTime"], "***** Assert 4. Different Start times were found  *****");
                    $this->assertEquals($agenda["$agendasF_EndTime"], $chk_ag["$agendasF_EndTime"], "***** Assert 5. Different End times were found  *****");
                    $this->assertEquals($agenda["$agendasF_MsoSync"], $chk_ag["$agendasF_MsoSync"], "***** Assert 6. Different MSO Sinch were found  *****");
                    $this->assertEquals($agenda["$agendasF_Surname"], $chk_ag["$agendasF_Surname"], "***** Assert 7. Different Surnames were found  *****");
                    $condition = true; break;
                }
            }
            $this->assertTrue($condition, "***** Assert A. Agenda was not found  *****");
        }
    }else{$this->markTestSkipped('Skip');}
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
        $agendas    = &$_SESSION['agendas'];
        $agendas     = UT_utils::createAgendas_UT(self::_n_agendas);
    }


















	/*
public	function testGetAgendaNameById(){
		@session_start();
		$tempID=$_SESSION['valid_user_id'];
		$tempOC=$_SESSION['org_code'];

		unset($_SESSION['valid_user_id']);
		unset($_SESSION['org_code']);
		$this->assertEquals(
				user_bl::GetAgendaNameById()
				,'');

		$id=154;
		$org_code="root";

		$_SESSION['valid_user_id']=$id;
		$_SESSION['org_code']=$org_code;

		$this->assertEquals(
				user_bl::GetAgendaNameById()
				,'deafspraak manager');

		$this->assertEquals(
				user_bl::GetAgendaNameById($id)
				,'deafspraak manager');

		$_SESSION['valid_user_id']=$tempID;
		$_SESSION['org_code']=$tempOC;
	}*/
//---------------------------------------------------------------------

	/*public	function testGetAgendaListName(){ // TODO user_dbl::GetAgendasList not testing!!!
		@session_start();				  // TODO nado vinisti v instalator stvorennja danix
		$tempOC=$_SESSION['org_code'];
		unset($_SESSION['org_code']);
		$_SESSION['org_code']="root";

		$list=user_bl::GetAgendaListName();

		$this->assertEquals(
				count($list)
				,1);

		$this->assertEquals(
				$list[0]["id"]
				,66);

		$this->assertEquals(
				$list[0]["value"]
				,user_bl::GetAgendaNameById($list[0]["id"]));

		$_SESSION['org_code']=$tempOC;
		}*/

//---------------------------------------------------------------------------------------------------------------------------------------------------





}


/*
public function test_Teplate (){
    global $CA_PATH;include($CA_PATH."variables_DB.php");

    if ((self::_is_t11 || self::_is_all) && !$_SESSION['is_skip']){
        self::createEnvironment_01();


    }else{$this->markTestSkipped('Skip');}
}
//-----------------------------------------------------------------------------------------------------------------------------------------------
*/


?>