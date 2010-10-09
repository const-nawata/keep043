<?php
//require_once 'PHPUnit/Framework/TestCase.php';
include("./config.php");
require_once($CA_PATH."classes/bl/appointments_bl.php");
require_once($CA_PATH."classes/bl/block_days_bl.php");
require_once($CA_PATH."classes/bl/app_type_bl.php");
require_once './test/app_type/appTypeTest.php';
require_once './test/user/agenda_Test.php';
require_once './functions.php';
require_once($CA_PATH."classes/bl/validation_app_update.php");

class appTestValidation1 extends PHPUnit_Framework_TestCase{
    private $mOrgCodeTemp;
    private $mApps;
    private $mClientsIds;
    private $mAgnsIds;
    private $mNewApp;
    private $mAppTypes;
    private $mBlckTime;

    protected function setUp(){
        $this->mOrgCodeTemp = $_SESSION['org_code'];
        $_SESSION['org_code']=_UT_ORG_CODE;

        $this->mStatusOpt = utils_bl::getStatusOptions();
        $this->mOrgSettings = authentication_dbl::selectSettings($_SESSION['org_code']);
        $this->mApps = array();

        session_tuning::destroySessionData();
        session_tuning::createSessionData();
    }
//---------------------------------------------------------------------------------------------------------------------------------------------------

   protected function tearDown(){
        $_SESSION['org_code']=$this->mOrgCodeTemp;
        UT_utils::deleteOrgData();
        session_tuning::destroySessionData();
   }
//---------------------------------------------------------------------------------------------------------------------------------------------------


 /**
 * AddAppt add appointment for testig
 * @param $app - it is object op apointment e.g
 *  $app["$appointmentsF_Date"]
    $app["$appointmentsF_SartTime"]
    $app["$appointmentsF_AppTypeId"]
    $app["$appointmentsF_AgendaId"]
    $app["$appointmentsF_StatusId"]
    $app["$appointmentsF_Comment"]
    $app["$appointmentsF_CreateDate"]
    $app["$appointmentsF_EndDate"]
    $app["$appointmentsF_MaxNumberClient"]

	$app["clients"][0]=id_client
	$app["clients"][1]=id_client ....
 *
 * @param $app_attributes if $app='null' this array used for create appointment e.g.
 *     	$app_attributes=array();
    	$app_attributes["count_client"]='2';
    	$app_attributes["time"]='15:00';
    	$app_attributes["duration"]='20';
    	$app_attributes["date"]='2007-12-28';
    	$app_attributes["end_date"]='2007-12-28';
 *  @return  id of created appointment
*/
    public function AddApp($app='null',$app_attributes='null'){
     include("./variables_DB.php");
     $org_code=$_SESSION['org_code'];

      if($app=='null'){
       $app=array();
       $random=generateRandom();
       $clients = UT_utils::createClients1_UT($app_attributes["count_client"], 'null', 0, true, true, 'UT_name', 'UT_surname', 'UT_Login'.$random);

       $client_ids=array();
       foreach ($clients as $client){
           $client_ids[] = $client["$clientsF_Id"];
       }

       $agendas = UT_utils::createAgendas_UT($app_attributes["count_agenda"]);
       $agenda_ids = array();
       foreach ($agendas as $agenda){
           $agenda_ids[] = $agenda["$agendasF_Id"];
       }

       $ag_id = 0;   //   Assume agenda id in array only

       $app_type=array();
		$app_type["$appTypesF_Name"]="testapp".$random;
		$app_type["$appTypesF_Time"]=$app_attributes["duration"];
		$app_type["$appTypesF_Color"]='#ffffff';
		$app_type["$appTypesF_AppComment"]='test app';
		$app_type["$appTypesF_MinTime"]=2;
		$app_type["$appTypesF_MaxTime"]=7;
		$app_type["$appTypesF_NumberApp"]=10;
		$app_type["$appTypesF_Tariff"]=99;
		$app_type["$appTypesF_IsPublic"]=1;
		if($app_attributes["count_client"]>1){$multy=1;}else{$multy=0;}
		$app_type["$appTypesF_IsMulty"]=$multy;

	$appType_id = appTypeTest::AddAppType($app_type);




    $app["$appointmentsF_ClietnId"]        = 0;
    $app["clients"]=array();
     for($i=0;$i<count($client_ids);$i++){
       	$app["clients"][$i]=$client_ids[$i];
       }

       $app["agendas"]=array();
     for($i=0;$i<count($agenda_ids);$i++){
        $app["agendas"][$i]=$agenda_ids[$i];
       }


    $app["$appointmentsF_Date"]            = $app_attributes["date"];
    $app["$appointmentsF_SartTime"]        = $app_attributes["time"];
    $app["$appointmentsF_AppTypeId"]       = $appType_id;
    $app["$appointmentsF_AgendaId"]        = $agenda_ids[0];

    $app["$appointmentsF_StatusId"]        = '3';
    $app["$appointmentsF_Comment"]         = "TEST_APP_COMMENTS$random";
    $app["$appointmentsF_CreateDate"]      =  $app_attributes["date"];

    $app["$appointmentsF_EndDate"]         = $app_attributes["end_date"];
    $app["$appointmentsF_MaxNumberClient"] = $app_attributes["count_client"]+2;

    }

    $sql1 =" INSERT INTO $tableAppointments
    					(
                          $appointmentsF_ClietnId,
             			  $appointmentsF_Date,
      					  $appointmentsF_SartTime,
						  $appointmentsF_AppTypeId,
						  $appointmentsF_AgendaId,
						  $appointmentsF_StatusId,
						  $appointmentsF_Comment,
						  $appointmentsF_CreateDate,
						  $appointmentsF_EndDate,
						  $appointmentsF_MaxNumberClient,
                          $field_org_code)
			 VALUES (   ".$app["$appointmentsF_ClietnId"].",
                        '".$app["$appointmentsF_Date"]."',
						'".$app["$appointmentsF_SartTime"]."',
						'".$app["$appointmentsF_AppTypeId"]."',
						'".$app["$appointmentsF_AgendaId"]."',
						'".$app["$appointmentsF_StatusId"]."',
						'".$app["$appointmentsF_Comment"]."',
						'".$app["$appointmentsF_CreateDate"]."',
						'".$app["$appointmentsF_EndDate"]."',
						'".$app["$appointmentsF_MaxNumberClient"]."',
                        '$org_code'
                     )";
//echo "<br>".$sql1;

	 $rez=mysql_query($sql1);
     $id=mysql_insert_id();

     for($i=0;$i<count($app["clients"]);$i++){
      $sql2 =" INSERT INTO $tableAppClientAssign
    					( $AppClientAssignF_AppId,
                          $AppClientAssignF_ClientId,
                          $field_org_code)
			 VALUES (".$id.",
                     	".$app["clients"][$i].",
                        '$org_code')";

        $rez=mysql_query($sql2);
     }


     for($i=0;$i<count($app["agendas"]);$i++){
      $sql2 =" INSERT INTO $tableAppAgendaAssign
                        ( $AppAgendaAssignF_AppId,
                          $AppAgendaAssignF_AgendaId,
                          $field_org_code)
             VALUES (   ".$id.",
                        ".$app["agendas"][$i].",
                        '$org_code')";

        $rez=mysql_query($sql2);
     }



	 return $id;

    }


   public function DeleteApp($app_id){
        include("./variables_DB.php");

    	$org_code=$_SESSION['org_code'];

    	$sql_info="select $appointmentsF_AppTypeId,
						$appointmentsF_AgendaId
                          from $tableAppointments where $appointmentsF_AppId=$app_id";
    	 $rez_app=mysql_query($sql_info);
         list($app_type_id,$agenda_id)=mysql_fetch_array($rez_app);


//  Delete clients
         	$sql_client="select
						$AppClientAssignF_ClientId
                          from $tableAppClientAssign where $AppClientAssignF_AppId=$app_id";
    	 $rez_client=mysql_query($sql_client);

    $i=0;
while ($i<mysql_num_rows($rez_client)){
	list($client[$i])=mysql_fetch_array($rez_client);
	$rez_c_del=self::DeleteClient($client[$i]);
	$i++;
}



//  Delete agendas
            $sql_agenda="select $AppAgendaAssignF_AgendaId from $tableAppAgendaAssign where $AppAgendaAssignF_AppId=$app_id";
         $rez_agenda = mysql_query($sql_agenda);

    $i=0;
while ($i<mysql_num_rows($rez_agenda)){
    list($agenda[$i])=mysql_fetch_array($rez_agenda);
    $rez_c_del=self::DeleteAgenda($agenda[$i]);
    $i++;
}




         $sql_app="delete from $tableAppointments
          where $appointmentsF_AppId=$app_id and $field_org_code = '$org_code' ";
         $rez_app=mysql_query($sql_app);
         $sql_app_ass="delete from $tableAppClientAssign
          where $AppClientAssignF_AppId=$app_id and $field_org_code = '$org_code' ";
         $rez_app_ass=mysql_query($sql_app_ass);

         $rezult_type=appTypeTest::DeleteAppType($app_type_id);
//         $irez_agenda=agenda_Test::DeleteAgenda($agenda_id);

    }

    private function createDbEvironment(){
         global $CA_PATH;include($CA_PATH."variables_DB.php");
         $app_ids = &$this->mApps;
         $client_ids = &$this->mClientsIds;
         $ag_ids = &$this->mAgnsIds;
         $days_off = &$this->mBlckTime;
         $new_app = &$this->mNewApp;

        $app_attributes=array();
    	$app_attributes["date"]='2007-12-28';
    	$app_attributes["end_date"]='2007-12-28';

        //  Multi appointment  Time 14:00 - 14:20 client was assigned to the same agenda
        $app_attributes ["count_client"] = 5;
        $app_attributes["count_agenda"] = 1;
    	$app_attributes["time"]='14:00';
    	$app_attributes["duration"]='20';
        $app_ids[] = self::AddApp('null', $app_attributes);




/*
$sql = "select $appointmentsF_AppId, $field_org_code from $tableAppointments";
$arr = utils_bl::executeMySqlSelectQuery($sql);
utils_bl::printArray($arr, 'arr');
utils_bl::printArray($app_ids, 'app_ids');
*/


        $app0 = appointments_bl::GetAppointmentById($app_ids[0]);
        $app_type0 = app_type_bl::getAppTypeById($app0["$appointmentsF_AppTypeId"]);
        foreach ($app0['clients'] as $client_id) $client_ids[] = $client_id;
        $ag_ids[] = $app0["agendas"][0];

        //  Multi appointment  Time 15:00 - 15:30 client was not assigned to another agenda
        $app_attributes ["count_client"] = 4;
    	$app_attributes["time"]='15:00';
        $app_ids[] = self::AddApp('null', $app_attributes);
        $app1 = appointments_bl::GetAppointmentById($app_ids[1]);
        $app_type1 = app_type_bl::getAppTypeById($app1["$appointmentsF_AppTypeId"]);
        foreach ($app1['clients'] as $client_key=>$client_id) $client_ids[] = $client_id;
        $ag_ids[] = $app1["agendas"][0];

    	//  Single Appointment  Time 11:00 - 11:20  App type range is 10:00 - 15:00 another client another agenda
        $app_attributes["time"]='11:00';
    	$app_attributes["duration"]='20';
    	$app_attributes["count_client"]='1';
        $app_ids[] = self::AddApp('null', $app_attributes);
        $app2 = appointments_bl::GetAppointmentById($app_ids[2]);
        $app_type2 = app_type_bl::getAppTypeById($app2["$appointmentsF_AppTypeId"]);
        $client_ids[] = $app2['clients'][0];
        $ag_ids[] = $app2["agendas"][0];
        $app_type2["$appTypesF_PeriodStartTime"] = '10:00';
        $app_type2["$appTypesF_PeriodEndTime"] = '15:00';

    	//  Single Appointment  time 10:00 - 10:20 same client same agenda
    	$app = $app2;
    	$app["$appointmentsF_SartTime"] = '10:00';
    	$app["agendas"][0] = $ag_ids[0];
    	$app["clients"][0] = $client_ids[0];
    	$app_ids[] = self::AddApp($app);
        $app3 = appointments_bl::GetAppointmentById($app_ids[3]);
        $app_type3 = app_type_bl::getAppTypeById($app3["$appointmentsF_AppTypeId"]);

        //  Single appointment at 12:00 - 12:20 same client another agenda
        $app["$appointmentsF_SartTime"] = '12:00';
        $app["$appointmentsF_AgendaId"] = $ag_ids[2];
        $app["agendas"][0] = $ag_ids[2];
     	$app_ids[] = self::AddApp($app);
        $app4 = appointments_bl::GetAppointmentById($app_ids[4]);
        $app_type4 = app_type_bl::getAppTypeById($app4["$appointmentsF_AppTypeId"]);

        //  Single appointment at 13:00 - 13:20 another client same agenda
        $app["$appointmentsF_SartTime"] = '13:00';
        $app["$appointmentsF_AgendaId"] = $ag_ids[0];//$ag_ids[0];
        $app["agendas"][0] = $ag_ids[0];
        $app["clients"][0] = $client_ids[1];
     	$app_ids[] = self::AddApp($app);
        $app5 = appointments_bl::GetAppointmentById($app_ids[5]);
        $app_type5 = app_type_bl::getAppTypeById($app5["$appointmentsF_AppTypeId"]);

        //  Multi appointment at 17:00 - 17:20  client was assigned to another agenda
        $app["$appointmentsF_SartTime"] = '17:00';
        $app["$appointmentsF_AgendaId"] = $ag_ids[1];
        $app["agendas"][0] = $ag_ids[1];
        $app["$appointmentsF_AppTypeId"] = $app_type0["$appTypesF_Id"];
        $app["clients"][0] = $this->mClientsIds[0];
        $app["clients"][1] = $this->mClientsIds[1];
        $app["clients"][2] = $this->mClientsIds[2];
     	$app_ids[] = self::AddApp($app);
        $app6 = appointments_bl::GetAppointmentById($app_ids[6]);
        $app_type6 = app_type_bl::getAppTypeById($app6["$appointmentsF_AppTypeId"]);

        //  Multi appointment at 18:00 - 18:20  client was not assigned to the same agenda
        $app["$appointmentsF_SartTime"] = '18:00';
        $app["$appointmentsF_AgendaId"] = $ag_ids[0];
        $app["agendas"][0] = $ag_ids[0];
        $app["$appointmentsF_AppTypeId"] = $app_type0["$appTypesF_Id"];
        $app["clients"][0] = $this->mClientsIds[1];
        $app["clients"][1] = $this->mClientsIds[2];
        $app["clients"][2] = $this->mClientsIds[3];
     	$app_ids[] = self::AddApp($app);
        $app7 = appointments_bl::GetAppointmentById($app_ids[7]);
        $app_type7 = app_type_bl::getAppTypeById($app7["$appointmentsF_AppTypeId"]);

    	//  Single Appointment  time 20:00 - 20:20 same client same agenda
    	$app = $app2;
    	$app["$appointmentsF_SartTime"] = '20:00';
    	$app["$appointmentsF_AgendaId"] = $ag_ids[0];
    	$app["agendas"][0] = $ag_ids[0];
    	$app["clients"][0] = $this->mClientsIds[0];
    	$app_ids[] = self::AddApp($app);
        $app8 = appointments_bl::GetAppointmentById($app_ids[8]);
        $app_type8 = app_type_bl::getAppTypeById($app8["$appointmentsF_AppTypeId"]);


//  Blocked time 16:00 - 16:20. The same agenda.
        $blck_time_buffer = array(
            "$daysOffF_AgendaId"=>$ag_ids[0],
            "$daysOffF_Comment"=>"Unit Test Blocked time",
            "$daysOffF_BlockedDate"=>"28-12-2007",
            "$daysOffF_BlockedDateEnd"=>"28-12-2007",
            "$daysOffF_BlockedTime"=>"16:00",
            "$daysOffF_BlockedTimeEnd"=>"16:20",
        );
        $blk_id = BlockDaysBL::InsertBlockedRec($blck_time_buffer);
        $days_off[0] = $blck_time_buffer;
        $days_off[0]['ID'] = $blk_id;

        $blck_time_buffer["$daysOffF_AgendaId"] = $ag_ids[1];
        $blck_time_buffer["$daysOffF_BlockedTime"] = "19:00";
        $blck_time_buffer["$daysOffF_BlockedTimeEnd"] = "19:20";
        $blk_id = BlockDaysBL::InsertBlockedRec($blck_time_buffer);
        $days_off[1] = $blck_time_buffer;
        $days_off[1]['ID'] = $blk_id;


        //  Init new appointment
        $new_app = $app3;

        $new_app["$appointmentsF_Date"]		= '28-12-2007';
        $new_app["$appointmentsF_EndDate"]	= '28-12-2007';
        $new_app["$appointmentsF_CreateDate"]	= '28-12-2007';
    }
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    private function getConditionForTimeBusy($NewApp, $isShowMsg=0){
        global $CA_PATH;include($CA_PATH."variables_DB.php");
        $obj = new validation_app_update($NewApp);
        $obj -> EnablePrint(false);
        $condition = $obj->isTimeBusy($isShowMsg);
        $obj = NULL;
        return $condition;
    }
    //------------------------------------------------------------------------------------------------------------------------------------

    public function testIsTimeBusyInClientMode_SingleNew(){     /////     Test
        UT_utils::setCronStatusForTest(_IS_BUSY);
        if (1 && !$_SESSION['is_skip']) $this->doTestForIsTimeBusySingle(); else $this->markTestSkipped('testIsTimeBusyInClientMode_SingleNew is off!!!');
    }
    //------------------------------------------------------------------------------------------------------------------------------------

    public function testIsTimeBusyInClientMode_SingleEdit(){
    	if (1 && !$_SESSION['is_skip']) $this->doTestForIsTimeBusySingle(false); else $this->markTestSkipped('testIsTimeBusyInClientMode_SingleEdit is off!!!');
        UT_utils::setCronStatusForTest(_IS_NOT_BUSY);
        unset($_SESSION['is_skip']);
    }
    //------------------------------------------------------------------------------------------------------------------------------------

    private function doTestForIsTimeBusySingle($isAppIdNull=true){
         global $CA_PATH;include($CA_PATH."variables_DB.php");
            self::createDbEvironment();

            $new_app = $this->mNewApp;

            ($isAppIdNull) ? $new_app["$appointmentsF_AppId"] = 'null':'';

            $check_times = array(
                '20:00', '20:19', '19:40', '20:20', '19:50',// Same Client Same Agenda								Asserts 0, 1, 2, 3, 4
                '12:00', '12:19', '11:40', '12:20', '11:50',// Same Client Another Agenda
                '13:00', '13:19', '12:40', '13:20', '12:50',// Another Client Same Agenda
                '11:00', '11:19', '10:40', '11:20', '10:50',// Another Client Another Agenda
                '16:00', '16:19', '15:40', '16:20', '15:50',// Blocked time (same agenda)							Asserts 20, 21, 22, 23, 24
                '14:00', '14:19', '13:40', '14:20', '13:50',// Client was assigned to multi (same agenda			Asserts 25, 26, 27, 28, 29
                '17:00', '17:19', '16:40', '17:20', '16:50',// Client was assigned to multi (another agenda			Asserts 30, 31, 32, 33, 34
                '15:00', '15:19', '14:40', '15:20', '14:50',// Client was not assigned to multi (another agenda)
                '18:00', '18:19', '17:40', '18:20', '17:50',// Client was not assigned to multi (same agenda)
                '19:00', '19:19', '18:40', '19:20', '18:50',// Blocked time (another agenda)
                '10:00', '10:19', '9:40',  '10:20',  '9:50' // Edit mode. Client did not chage start time
            );

            $conditions = array();
            foreach ($check_times as $time_key=>$time){
                $new_app["$appointmentsF_SartTime"] = $time;
                $is_show_msg = 0;    //  This flag is set to 1 value for debug purposes
                $conditions[$time_key] = self::getConditionForTimeBusy($new_app, $is_show_msg);
            }

            UT_utils::deleteOrgData();

            $this->assertTrue($conditions[0], 'Assert 0  ********* Equel Start times are not validated. The same agenda and the same client. *********');
            $this->assertTrue($conditions[1], 'Assert 1  ********* Overlaped Start time are not validated. The same agenda and the same client. *********');
            $this->assertFalse($conditions[2], 'Assert 2  ********* Wrong validation if new end time =  existed start time. The same agenda and the same client.*********');
            $this->assertFalse($conditions[3], 'Assert 3  ********* Wrong validation if new start time =  existed end time. The same agenda and the same client.*********');
            $this->assertTrue($conditions[4], 'Assert 4  ********* Overlaped End time are not validated. The same agenda and the same client. *********');

            $this->assertTrue($conditions[5], 'Assert 5  ********* Equel Start times are not validated. Another agenda and the same client. *********');
            $this->assertTrue($conditions[6], 'Assert 6  ********* Overlaped Start time are not validated. Another agenda and the same client. *********');
            $this->assertFalse($conditions[7], 'Assert 7  ********* Wrong validation if new end time =  existed start time. Another agenda and the same client.*********');
            $this->assertFalse($conditions[8], 'Assert 8  ********* Wrong validation if new start time =  existed end time. Another agenda and the same client.*********');
            $this->assertTrue($conditions[9], 'Assert 9  ********* Overlaped End time are not validated. Another agenda and the same client. *********');

            $this->assertTrue($conditions[10], 'Assert 10  ********* Equel Start times are not validated. The same agenda and another client. *********');
            $this->assertTrue($conditions[11], 'Assert 11  ********* Overlaped Start time are not validated. The same agenda and another client. *********');
            $this->assertFalse($conditions[12], 'Assert 12  ********* Wrong validation if new end time =  existed start time. The same agenda and another client.*********');
            $this->assertFalse($conditions[13], 'Assert 13  ********* Wrong validation if new start time =  existed end time. The same agenda and another client.*********');
            $this->assertTrue($conditions[14], 'Assert 14  ********* Overlaped End time are not validated. The same agenda and another client. *********');

            $this->assertFalse($conditions[15], 'Assert 15  ********* Equel Start times wrong validation. Another agenda and another client. Appointment start time: '.$check_times[15].'. *********');
            $this->assertFalse($conditions[16], 'Assert 16 ********* Overlaped Start time wrong validation. Another agenda and another client. *********');
            $this->assertFalse($conditions[17], 'Assert 17 ********* Wrong validation if new end time =  existed start time. Another agenda and another client.*********');
            $this->assertFalse($conditions[18], 'Assert 18 ********* Wrong validation if new start time =  existed end time. Another agenda and another client.*********');
            $this->assertFalse($conditions[19], 'Assert 19 ********* Overlaped End time  wrong validation. Another agenda and another client. *********');;

            $this->assertTrue($conditions[20], 'Assert 20 ********* Equel Start times are not validated. Blocked time (same agenda). Appointment start time: '.$check_times[20].'.  *********');
            $this->assertTrue($conditions[21], 'Assert 21 ********* Overlaped Start time are not validated. Blocked time (same agenda). *********');
            $this->assertFalse($conditions[22], 'Assert 22 ********* Wrong validation if new end time =  existed start time. Blocked start time (same agenda).*********');
            $this->assertFalse($conditions[23], 'Assert 23 ********* Wrong validation if new start time =  existed end time. Blocked end time (same agenda).*********');
            $this->assertTrue($conditions[24], 'Assert 24 ********* Overlaped End time are not validated. Blocked time (same agenda). *********');

            $this->assertTrue($conditions[25], 'Assert 25 ********* Equel Start times are not validated. Client assinged to multi (same agenda). *********');
            $this->assertTrue($conditions[26], 'Assert 26 ********* Overlaped Start time are not validated. Client assinged to multi (same agenda). *********');
            $this->assertFalse($conditions[27], 'Assert 27 ********* Wrong validation if new end time =  existed start time. Client assinged to multi (same agenda).*********');
            $this->assertFalse($conditions[28], 'Assert 28 ********* Wrong validation if new start time =  existed end time. Client assinged to multi (same agenda).*********');
            $this->assertTrue($conditions[29], 'Assert 29 ********* Overlaped End time are not validated. Client assinged to multi (same agenda). *********');

            $this->assertTrue($conditions[30], 'Assert 30 ********* Equel Start times are not validated. Client assinged to multi (another agenda). Appointment start time: '.$check_times[30].'.   *********');
            $this->assertTrue($conditions[31], 'Assert 31 ********* Overlaped Start time are not validated. Client assinged to multi (another agenda). *********');
            $this->assertFalse($conditions[32], 'Assert 32 ********* Wrong validation if new end time =  existed start time. Client assinged to multi (another agenda).*********');
            $this->assertFalse($conditions[33], 'Assert 33 ********* Wrong validation if new start time =  existed end time. Client assinged to multi (another agenda).*********');
            $this->assertTrue($conditions[34], 'Assert 34 ********* Overlaped End time are not validated. Client assinged to multi (another agenda). *********');

            $this->assertFalse($conditions[35], 'Assert 35 ********* Equel Start times  wrong validation. Client was not assinged to multi (another agenda). *********');
            $this->assertFalse($conditions[36], 'Assert 36 ********* Overlaped Start time  wrong validation. Client was not assinged to multi (another agenda). *********');
            $this->assertFalse($conditions[37], 'Assert 37 ********* Wrong validation if new end time =  existed start time. Client was not assinged to multi (another agenda).*********');
            $this->assertFalse($conditions[38], 'Assert 38 ********* Wrong validation if new start time =  existed end time. Client was not assinged to multi (another agenda).*********');
            $this->assertFalse($conditions[39], 'Assert 39 ********* Overlaped End time  wrong validation. Client was not assinged to multi (another agenda). *********');

            $this->assertTrue($conditions[40], 'Assert 40 ********* Equel Start times are not validated. Client was not assinged to multi (same agenda). *********');
            $this->assertTrue($conditions[41], 'Assert 41 ********* Overlaped Start time are not validated. Client was not assinged to multi (same agenda). *********');
            $this->assertFalse($conditions[42], 'Assert 42 ********* Wrong validation if new end time = existed start time.  Client was not assinged to multi (same agenda).*********');
            $this->assertFalse($conditions[43], 'Assert 43 ********* Wrong validation if new start time =  existed end time. Client was not assinged to multi (same agenda).*********');
            $this->assertTrue($conditions[44], 'Assert 44 ********* Overlaped End time are not validated. Client was not assinged to multi (same agenda). *********');

            $this->assertFalse($conditions[45], 'Assert 45 ********* Equel Start times  wrong validation.  Blocked time (another agenda). *********');
            $this->assertFalse($conditions[46], 'Assert 46 ********* Overlaped Start time  wrong validation.  Blocked time (another agenda). *********');
            $this->assertFalse($conditions[47], 'Assert 47 ********* Wrong validation if new end time =  existed start time.  Blocked start time (another agenda).*********');
            $this->assertFalse($conditions[48], 'Assert 48 ********* Wrong validation if new start time =  existed end time. Blocked end time (another agenda).*********');
            $this->assertFalse($conditions[49], 'Assert 49 ********* Overlaped End time  wrong validation.  Blocked time (another agenda). *********');

            if (!$isAppIdNull){
                $this->assertFalse($conditions[50], 'Assert 50 ********* Equel Start times  wrong validation.  Client did not change app Start time. *********');
                $this->assertFalse($conditions[51], 'Assert 51 ********* Overlaped Start time  wrong validation.  Client did not change app Start time. *********');
                $this->assertFalse($conditions[52], 'Assert 52 ********* Wrong validation if new end time =  existed start time.  Client did not change app Start time.*********');
                $this->assertFalse($conditions[53], 'Assert 53 ********* Wrong validation if new start time =  existed end time. Client did not change app Start time.*********');
                $this->assertFalse($conditions[54], 'Assert 54 ********* Overlaped End time  wrong validation.  Client did not change app Start time. *********');
            }


    }
    //------------------------------------------------------------------------------------------------------------------------------------

    private function DeleteClient($id){
    include("variables_DB.php");
    $org_code=$_SESSION['org_code'];
    $sql="delete from $tableClients
          where $clientsF_Id=".$id." and $field_org_code = '$org_code' ";

    $rez=mysql_query($sql);
    return true;
    }
//---------------------------------------------------------------------------------------------------------------------------------------------------

    private function DeleteAgenda($id){
    include("variables_DB.php");
    $org_code=$_SESSION['org_code'];
    $sql="delete from $tableAgendas
          where $agendasF_Id=".$id." and $field_org_code = '$org_code' ";

    $rez=mysql_query($sql);
    return true;
    }
//---------------------------------------------------------------------------------------------------------------------------------------------------
}
?>