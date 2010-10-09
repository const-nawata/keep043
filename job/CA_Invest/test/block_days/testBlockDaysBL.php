<?php
/**
 * Created by Constantine Kolenchenko on 02/08/2008
 * Copyright Yukon Software the Netherlands 2008. All rights reserved
 */
include("./config.php");
require_once $CA_PATH.'test/session_tuning.php';
require_once $CA_PATH.'test/block_days/blk_constants.php';
require_once $CA_PATH."classes/bl/block_days_bl.php";


class testBlockDaysBL extends PHPUnit_Framework_TestCase{
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

//ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!
//This test must be first in the sequence of tests. Set all new tests after this test. It is necessary to excluede cache interferance.
	public function test_isBlockedDate_NoPattern(){  //  Test 1
    	global $CA_PATH; include($CA_PATH."variables_DB.php");
    	global $is_bt1;

    	UT_utils::setCronStatusForTest(_IS_BUSY);

    	if ($is_bt1  && !$_SESSION['is_skip']){
    		$curr_year = date('Y');
    		$last_year = $curr_year + _UT_YEARS_PERIOD;

	    	$blk_param = array(
	    	"$daysOffF_AgendaId"=>$_SESSION['agendas'][0]["$agendasF_Id"],
	    	"$daysOffF_Comment"=>'Unit test for Blocked days. Which tests were created by C.Kolenchenko (ckolenchenko@yukon.cv.ua)',
	    	"$daysOffF_BlockedDate"=>_UT_DATE_START.'-'.$curr_year,
	    	"$daysOffF_BlockedDateEnd"=>_UT_DATE_END.'-'.$last_year,
	    	"$daysOffF_BlockedTime"=>'00:00:00',
	    	"$daysOffF_BlockedTimeEnd"=>'00:00:00',
	        "PATTERN_ID"=>0
	    	);
    	    BlockDaysBL::InsertBlockedRec($blk_param);

    	    $date_db = utils_bl::GetDbDate($blk_param["$daysOffF_BlockedDate"]);
    	    $date_db_end = utils_bl::GetDbDate($blk_param["$daysOffF_BlockedDateEnd"]);
    	    while ($date_db <= $date_db_end){
    	    	$date = utils_bl::GetFormDate($date_db);
    	    	$month_num = utils_bl::getNumOfMonth($date);
    	    	if (_UT_MONTH1 == $month_num ||_UT_MONTH2 == $month_num ||_UT_MONTH3 == $month_num){
	    	    	$this->assertTrue(BlockDaysBL::isBlockedDate($_SESSION['agendas'][0]["$agendasF_Id"], $date, false), "***** Blocked days testing. No pattern. Application mode, Date=$date *****");
	    	    	$this->assertTrue(BlockDaysBL::isBlockedDate($_SESSION['agendas'][0]["$agendasF_Id"], $date, true), "***** Blocked days testing. No pattern. Crone mode, Date=$date *****");
    	    	}
    	        $date_db = utils_bl::AddDaysToDbDate($date_db, 1);
    	    }
    	}else{$this->markTestSkipped('test_isBlockedDate_NoPattern is off!!!');}
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    public function test_isBlockedDate_DayPattern(){  //  Test 2
    	global $CA_PATH; include($CA_PATH."variables_DB.php");
    	global $is_bt2;

    	if ($is_bt2 && !$_SESSION['is_skip']){
    	    $curr_year = _UT_THIS_YEAR;

    		$last_year = $curr_year+_UT_YEARS_PERIOD + 10;

    		$blk_param = array(
	    	"$daysOffF_AgendaId"=>$_SESSION['agendas'][0]["$agendasF_Id"],
	    	"$daysOffF_Comment"=>'Unit test for Blocked days. Which tests were created by C.Kolenchenko (ckolenchenko@yukon.cv.ua)',
	    	"$daysOffF_BlockedDate"=>_UT_DATE_START.'-'.$curr_year,
	    	"$daysOffF_BlockedDateEnd"=>_UT_DATE_END.'-'.$last_year,
	    	"$daysOffF_BlockedTime"=>'00:00:00',
	    	"$daysOffF_BlockedTimeEnd"=>'00:00:00',
	        "PATTERN_ID"=>1,
	    	"$daysoffPatternF_Cycle"=>_BLK_PATTERN_DAYS,
	    	"$daysoffPatternF_Period"=>_UT_BLK_PERIOD_DAYS,
	    	"$daysoffPatternF_WeekDays"=>0
	    	);
    	    BlockDaysBL::InsertBlockedRec($blk_param);

    	    $date_db        = utils_bl::GetDbDate(_UT_DATE_START.'-'.($curr_year + 7));
    	    $date_db_end = utils_bl::GetDbDate(_UT_DATE_END.'-'.($curr_year + 7));

    	    $num_of_cycles = 0;
    	    $blk_date_flag = _UT_BLK_PERIOD_DAYS_BLK - 3;
    	    while ($date_db <= $date_db_end && $num_of_cycles < 15){
    	    	$date = utils_bl::GetFormDate($date_db);
    	    	if ($blk_date_flag < (_UT_BLK_PERIOD_DAYS_BLK - 1)){
	    	    	$this->assertFalse(BlockDaysBL::isBlockedDate($_SESSION['agendas'][0]["$agendasF_Id"], $date, false), "***** Assert 1. Blocked days testing. Days pattern. Application mode, Date=$date *****");
	    	    	$this->assertFalse(BlockDaysBL::isBlockedDate($_SESSION['agendas'][0]["$agendasF_Id"], $date, true), "***** Assert 2. Blocked days testing. Days pattern. Crone mode, Date=$date *****");
	    	    	$blk_date_flag++;
    	    	}else{
	    	    	$this->assertTrue(BlockDaysBL::isBlockedDate($_SESSION['agendas'][0]["$agendasF_Id"], $date, false), "***** Assert 3. Blocked days testing. Days pattern. Application mode, Date=$date *****");
	    	    	$this->assertTrue(BlockDaysBL::isBlockedDate($_SESSION['agendas'][0]["$agendasF_Id"], $date, true), "***** Assert 4. Blocked days testing. Days pattern. Crone mode, Date=$date *****");
	    	    	$blk_date_flag = 0;
    	    	}
    	    	$date_db = utils_bl::AddDaysToDbDate($date_db, 1);$num_of_cycles++;
    	    }
    	}else{$this->markTestSkipped('test_isBlockedDate_DayPattern is off!!!');}
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    public function test_isBlockedDate_WeekPattern(){  //  Test 3 // Pattern STTS
    	global $CA_PATH; include($CA_PATH."variables_DB.php");
    	global $is_bt3;
    	if ($is_bt3 && !$_SESSION['is_skip']){
    		 $curr_year = _UT_THIS_YEAR;
    		$last_year = $curr_year+_UT_YEARS_PERIOD + 10;
    		$check_year = $curr_year+_UT_YEARS_PERIOD + 5;

    		$start_test_date = _UT_DATE_START.'-'.$curr_year;
    		$end_test_date = _UT_DATE_END.'-'.$last_year;
    		for ($pattern_period = 1; $pattern_period <= 5; $pattern_period++){
	    		$blk_param = array(
		    	"$daysOffF_AgendaId"=>$_SESSION['agendas'][0]["$agendasF_Id"],
		    	"$daysOffF_Comment"=>'Unit test for Blocked days. Which tests were created by C.Kolenchenko (ckolenchenko@yukon.cv.ua)',
		    	"$daysOffF_BlockedDate"=>$start_test_date,
		    	"$daysOffF_BlockedDateEnd"=>$end_test_date,
		    	"$daysOffF_BlockedTime"=>'00:00:00',
		    	"$daysOffF_BlockedTimeEnd"=>'00:00:00',
		        "PATTERN_ID"=>1,
		    	"$daysoffPatternF_Cycle"=>_BLK_PATTERN_WEEKS,
		    	"$daysoffPatternF_Period"=>$pattern_period,
		    	"$daysoffPatternF_WeekDays"=>85
		    	);
	    	    BlockDaysBL::InsertBlockedRec($blk_param);

	    	    $date_db        = utils_bl::GetDbDate($start_test_date);
	    	    $date_db_end = utils_bl::GetDbDate($end_test_date);

	    	    $flag = 1; $num_of_cycles = 0; $year = 0;
	    	    while ($date_db <= $date_db_end && $num_of_cycles < 40 && $year <= $check_year){
	    	    	$date = utils_bl::GetFormDate($date_db);
	    	    	$num_week_day = utils_bl::getNumOfWeekDay($date);

	    	    	$year = utils_bl::getNumOfYear($date);

	    	    	if ($check_year == $year){
	    	    		$app_condition = BlockDaysBL::isBlockedDate($_SESSION['agendas'][0]["$agendasF_Id"], $date);

	    	    		if ((BlockDaysBL::c_nSunday == $num_week_day ||
		    	    	     BlockDaysBL::c_nTuesday == $num_week_day ||
		    	    	     BlockDaysBL::c_nThursday == $num_week_day ||
		    	    	     BlockDaysBL::c_nSaturday == $num_week_day) && $flag == 1){
			    	    	$this->assertTrue($app_condition, "***** Assert 1. Blocked days testing. Week pattern. Application mode, Date=$date. Period:  $pattern_period. Week day: $num_week_day  # flag: $flag *****");
		    	    	}else{
			    	    	$this->assertFalse($app_condition, "***** Assert 3. Blocked days testing. Week pattern. Application mode, Date=$date. Period:  $pattern_period Week day: $num_week_day  # flag: $flag  *****");
		    	    	}
	                    $num_of_cycles++;
	    	    	}
	    	    	($num_week_day == BlockDaysBL::c_nMonday) ? $flag++:'';
	    	    	($flag > $pattern_period) ? $flag = 1 :'';
	    	    	$date_db = utils_bl::AddDaysToDbDate($date_db, 1);
	    	    }
		    	UT_utils::deleteAllOrgDataFromTable($tableDaysoffPattern);
		    	UT_utils::deleteAllOrgDataFromTable($tableDaysOff);
    		}
    	}else{$this->markTestSkipped('test_isBlockedDate_WeekPattern is off!!!');}
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    public function testDeleteBlockedTimeSlotsFromDailyAgenda_NoPattern(){  //  Test 4
    	global $CA_PATH; include($CA_PATH."variables_DB.php");
    	global $is_bt4;
    	if ($is_bt4 && !$_SESSION['is_skip']){
    		$curr_year = date('Y');

    		$blk_items = array();

	    	$blk_param = array(
	    	"$daysOffF_AgendaId"=>$_SESSION['agendas'][0]["$agendasF_Id"],
	    	"$daysOffF_Comment"=>'Unit test for Blocked days. Which tests were created by C.Kolenchenko (ckolenchenko@yukon.cv.ua)',
	    	"$daysOffF_BlockedDate"=>'03-10-'.$curr_year,
	    	"$daysOffF_BlockedDateEnd"=>'28-10-'.$curr_year,
	    	"$daysOffF_BlockedTime"=>'09:05',
	    	"$daysOffF_BlockedTimeEnd"=>'09:40',
	        "PATTERN_ID"=>0
	    	);
    	    $blk_items[] = BlockDaysBL::InsertBlockedRec($blk_param);

    	    $blk_param["$daysOffF_BlockedDate"]       = '02-10-'.$curr_year;
    	    $blk_param["$daysOffF_BlockedDateEnd"] = '29-10-'.$curr_year;
    	    $blk_param["$daysOffF_BlockedTime"]       = '09:15';
    	    $blk_param["$daysOffF_BlockedTimeEnd"] = '09:35';
    	    $blk_items[] = BlockDaysBL::InsertBlockedRec($blk_param);

    	    $blk_param["$daysOffF_BlockedDate"]       = '01-10-'.$curr_year;
    	    $blk_param["$daysOffF_BlockedDateEnd"] = '31-10-'.$curr_year;
    	    $blk_param["$daysOffF_BlockedTime"]       = '09:10';
    	    $blk_param["$daysOffF_BlockedTimeEnd"] = '09:20';
    	    $blk_items[] = BlockDaysBL::InsertBlockedRec($blk_param);

    	    $blk_ids = array($blk_items[0]=>'on', $blk_items[2]=>'on');
    	    $check_date = '10-10-'.$curr_year;
    	    BlockDaysBL::delBlkItemsFromDaily($check_date, $blk_ids);
    	    $blk_recs = UT_utils::getAllBlkDataForOrg();

    	    $q_recs = count($blk_recs);
    	    $this->assertEquals(5, $q_recs, '***** Assert 1. Quantity records is not valid after first  deletion *****');
    	    $this->assertTrue($this->isRecExist($blk_recs, $curr_year.'-10-03', $curr_year.'-10-09', '09:05:00', '09:40:00'), '***** Assert 2. Recod has not found *****');
    	    $this->assertTrue($this->isRecExist($blk_recs, $curr_year.'-10-11', $curr_year.'-10-28', '09:05:00', '09:40:00'), '***** Assert 3. Recod has not found *****');
    	    $this->assertTrue($this->isRecExist($blk_recs, $curr_year.'-10-02', $curr_year.'-10-29', '09:15:00', '09:35:00'), '***** Assert 4. Recod has not found *****');
    	    $this->assertTrue($this->isRecExist($blk_recs, $curr_year.'-10-01', $curr_year.'-10-09', '09:10:00', '09:20:00'), '***** Assert 5. Recod has not found *****');
    	    $this->assertTrue($this->isRecExist($blk_recs, $curr_year.'-10-11', $curr_year.'-10-31', '09:10:00', '09:20:00'), '***** Assert 6. Recod has not found *****');

    	    $blk_ids = array($blk_recs[1]["$daysOffF_Id"]=>'on', $blk_recs[3]["$daysOffF_Id"]=>'on', $blk_recs[4]["$daysOffF_Id"]=>'on');
    	    $check_date = '20-10-'.$curr_year;
    	    BlockDaysBL::delBlkItemsFromDaily($check_date, $blk_ids);
    	    $blk_recs = UT_utils::getAllBlkDataForOrg();
    	    $q_recs = count($blk_recs);
    	    $this->assertEquals(8, $q_recs, '***** Assert 7. Quantity records is not valid after second deletion *****');
    	    $this->assertTrue($this->isRecExist($blk_recs, $curr_year.'-10-03', $curr_year.'-10-09', '09:05:00', '09:40:00'), '***** Assert 8. Recod has not found *****');
    	    $this->assertTrue($this->isRecExist($blk_recs, $curr_year.'-10-11', $curr_year.'-10-19', '09:05:00', '09:40:00'), '***** Assert 9. Recod has not found *****');
    	    $this->assertTrue($this->isRecExist($blk_recs, $curr_year.'-10-21', $curr_year.'-10-28', '09:05:00', '09:40:00'), '***** Assert 10. Recod has not found *****');
    	    $this->assertTrue($this->isRecExist($blk_recs, $curr_year.'-10-02', $curr_year.'-10-19', '09:15:00', '09:35:00'), '***** Assert 11. Recod has not found *****');
    	    $this->assertTrue($this->isRecExist($blk_recs, $curr_year.'-10-21', $curr_year.'-10-29', '09:15:00', '09:35:00'), '***** Assert 12. Recod has not found *****');
    	    $this->assertTrue($this->isRecExist($blk_recs, $curr_year.'-10-01', $curr_year.'-10-09', '09:10:00', '09:20:00'), '***** Assert 13. Recod has not found *****');
    	    $this->assertTrue($this->isRecExist($blk_recs, $curr_year.'-10-11', $curr_year.'-10-19', '09:10:00', '09:20:00'), '***** Assert 14. Recod has not found *****');
    	    $this->assertTrue($this->isRecExist($blk_recs, $curr_year.'-10-21', $curr_year.'-10-31', '09:10:00', '09:20:00'), '***** Assert 15. Recod has not found *****');

    	    $blk_ids = array($blk_recs[3]["$daysOffF_Id"]=>'on', $blk_recs[4]["$daysOffF_Id"]=>'on');
    	    $check_date = '11-10-'.$curr_year;
    	    BlockDaysBL::delBlkItemsFromDaily($check_date, $blk_ids);
    	    $blk_recs = UT_utils::getAllBlkDataForOrg();
    	    $q_recs = count($blk_recs);
    	    $this->assertEquals(8, $q_recs, '***** Assert 16. Quantity records is not valid after third deletion (start date) *****');
    	}else{$this->markTestSkipped('testDeleteBlockedTimeSlotsFromDailyAgenda_NoPattern is off!!!');}
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------








    //ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!
//This test must be last in the sequence of tests. Set all new tests before this test.
    public function test_LAST_MANDATORY_FICTIVE_TEST(){
        UT_utils::setCronStatusForTest(_IS_NOT_BUSY);
         unset($_SESSION['is_skip']);
        $_SESSION['is_end'] = true;
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------
    //##########################################################

    private function isRecExist($recsArray, $startDate, $endDate, $startTime, $endTime){
    	global $CA_PATH; include($CA_PATH."variables_DB.php");

    	foreach ($recsArray as $record){
    		if ($startDate == $record["$daysOffF_BlockedDate"] &&
    		     $endDate == $record["$daysOffF_BlockedDateEnd"] &&
    		     $startTime == $record["$daysOffF_BlockedTime"] &&
    		     $endTime == $record["$daysOffF_BlockedTimeEnd"]) return true;
    	}

    	return false;
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    private function createEnvironment(){
    	$_SESSION['agendas'] = UT_utils::createAgendas_UT(_UT_QNT_AGNDS);
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    private function deleteEnvironment(){
        global $CA_PATH; include($CA_PATH."variables_DB.php");
        UT_utils::deleteAllOrgDataFromTable($tableAgendas);
    	UT_utils::deleteAllOrgDataFromTable($tableDaysoffPattern);
    	UT_utils::deleteAllOrgDataFromTable($tableDaysOff);
    	UT_utils::deleteAllOrgDataFromTable($tableCache);
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

}//  Class End
?>