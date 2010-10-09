<?php
class environment{

	 public function __construct($index){
	 	UT_utils::setGlobalOrgParams();
	 	$method_name = 'environment_'.$index;
	 	$this->$method_name();
	}
//--------------------------------------------------------------------------------------------------------------------------------------------------------------

	private function createClients_1(){
		$clients	= &$_SESSION['clients'];
		$clients	= UT_utils::createClients1_UT(5);  //  Quantity of clients is 5
	}
//--------------------------------------------------------------------------------------------------------------------------------------------------------------

	private function createAgednas_1(){
        global $CA_PATH;include($CA_PATH."variables_DB.php");
        $agendas    = &$_SESSION['agendas'];

        $agenda = UT_utils::getInitialAgendaParams_UT(); $ind = 0;
        $login = $agenda["$agendasF_Login"];

        //	agenda 0
        $agenda["$agendasF_StartTime"] = '09:00';
        $agenda["$agendasF_EndTime"] = '18:00';
        $agenda["$agendasF_Duration"] = 30;
        $agenda["$agendasF_Login"] = $login.$ind++;
        $agendas = UT_utils::createSingleAgenda_UT($agenda, $agendas);


/*
        //	agenda 1
        $agenda["$agendasF_StartTime"] = '08:00';
        $agenda["$agendasF_EndTime"] = '17:00';
        $agenda["$agendasF_Duration"] = 15;
        $agenda["$agendasF_Login"] = $login.$ind++;
        $agendas = UT_utils::createSingleAgenda_UT($agenda, $agendas);

		//	agenda 2
        $agenda["$agendasF_StartTime"] = '07:00';
        $agenda["$agendasF_EndTime"] = '16:00';
        $agenda["$agendasF_Duration"] = 30;
        $agenda["$agendasF_Login"] = $login.$ind++;
        $agendas = UT_utils::createSingleAgenda_UT($agenda, $agendas);

        //	agenda 3
        $agenda["$agendasF_StartTime"] = '10:00';
        $agenda["$agendasF_EndTime"] = '19:00';
        $agenda["$agendasF_Duration"] = 15;
        $agenda["$agendasF_Login"] = $login.$ind++;
        $agendas = UT_utils::createSingleAgenda_UT($agenda, $agendas);

        //	agenda 4
        $agenda["$agendasF_StartTime"] = '07:00';
        $agenda["$agendasF_EndTime"] = '19:00';
        $agenda["$agendasF_Duration"] = 30;
        $agenda["$agendasF_Login"] = $login.$ind++;
        $agendas = UT_utils::createSingleAgenda_UT($agenda, $agendas);
*/
	 }
//--------------------------------------------------------------------------------------------------------------------------------------------------------------

	private function createAppTypes_1(){
		global $CA_PATH;include($CA_PATH."variables_DB.php");
		$app_types = &$_SESSION['app_types'];
		$app_type = UT_utils::initAppTypeParams_UT('Constantine Kolenchenko (ckolenchenko@yukon.cv.ua)');

		$app_type['main']["$appTypesMainF_Duration"] = 5; $ind = 0;
		$app_type['info']["$appTypesInfoF_Name"] = 'UT app type name '.$ind++;
		$app_types = UT_utils::addAppTypeFull_UT($app_type, $app_types);

		$app_type['main']["$appTypesMainF_Duration"] = 10;
		$app_type['info']["$appTypesInfoF_Name"] = 'UT app type name '.$ind++;
		$app_types = UT_utils::addAppTypeFull_UT($app_type, $app_types);

		$app_type['main']["$appTypesMainF_Duration"] = 15;
		$app_type['info']["$appTypesInfoF_Name"] = 'UT app type name '.$ind++;
		$app_types = UT_utils::addAppTypeFull_UT($app_type, $app_types);

		$app_type['main']["$appTypesMainF_Duration"] = 30;
		$app_type['info']["$appTypesInfoF_Name"] = 'UT app type name '.$ind++;
		$app_types = UT_utils::addAppTypeFull_UT($app_type, $app_types);

		$app_type['main']["$appTypesMainF_Duration"] = 1;
		$app_type['info']["$appTypesInfoF_Name"] = 'UT app type name '.$ind++;
		$app_types = UT_utils::addAppTypeFull_UT($app_type, $app_types);

		$app_type['main']["$appTypesMainF_Duration"] = 3;
		$app_type['info']["$appTypesInfoF_Name"] = 'UT app type name '.$ind++;
		$app_types = UT_utils::addAppTypeFull_UT($app_type, $app_types);

	}
//--------------------------------------------------------------------------------------------------------------------------------------------------------------

	private function createApps_1(){
		global $CA_PATH;include($CA_PATH."variables_DB.php");
		$apps		= &$_SESSION['apps'];
		$app_types	= &$_SESSION['app_types'];
	}
//--------------------------------------------------------------------------------------------------------------------------------------------------------------

	private function environment_1(){
//        $app_types = &$_SESSION['app_types'];
//        $apps           = &$_SESSION['apps'];

        $this->createClients_1();
		$this->createAgednas_1();
//        $this->createAppTypes_1();
//        $this->createApps_1();




//utils_bl::printArray($agendas, 'agendas');
	}
//--------------------------------------------------------------------------------------------------------------------------------------------------------------
}//  end of environment class
?>