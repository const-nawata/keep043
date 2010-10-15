<?php
/**
 * Created by Constantine Kolenchenko <ckolenchenko@yukon.cv.ua> on 22-12-2009
 * Copyright Yukon Software Ukraine 2008. All rights reserved.
 */

//require_once $CA_PATH.'test/session_tuning.php';

/**
 *
 * @author Constantine Kolenchenko <ckolenchenko@yukon.cv.ua>
 * Those unit tests are created to check DB function to create chache content.
 */
class anyAgendaForMonth_Widget extends PHPUnit_Framework_TestCase{
	const _is_all = false;   //  false  true //

//	const _is_1_1_1		= false;	//REMARK:	Performance test. It is always on.
//	const _is_1_1_2		= false;	//REMARK:	Performance test. It is always on.

	const _is_1_2_2		= true;
	const _is_1_2_3		= false;
	const _is_1_2_4		= false;
	const _is_1_2_5		= false;
	const _is_1_2_6		= false;
	const _is_1_2_7		= false;
	const _is_1_2_8		= false;
	const _is_1_2_9		= false;	//REMARK: Very long duration if not fast.
	const _is_1_2_10	= false;

	const _is_1_2_11	= false;
	const _is_1_2_12	= false;
	const _is_1_2_13	= false;
	const _is_1_2_14	= false;
	const _is_1_2_15	= false;
	const _is_1_2_16	= false;
	const _is_1_2_17	= false;
	const _is_1_2_18	= false;
	const _is_1_2_19	= false;

	const _is_1_2_20	= false;
	const _is_1_2_21	= false;
	const _is_1_2_22	= false;	//REMARK: Very long duration if not fast.
	const _is_1_2_23	= false;	//REMARK: Very long duration if not fast.

	//	Long tests limitations
	const _is_fast		= true;
	const _n_of_periods	= 3;
	const _n_max_iters	= 20;	//Number of iterations.



    protected function setUp(){
    	session_tuning::initSessionData();
    }
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

	protected function tearDown(){
    	if( $_SESSION[ 'is_clear_db' ] ){
        	UT_utils::deleteOrgData();
    	}
    	$_SESSION[ 'is_clear_db' ]	= true;
	}
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

	public static function createEnvironment_01(){
		global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );

            //	Agendas
		$add_ags	= UT_utils::createAgendas_UT( 1, '11:00', '20:00' );//0
		$agendas[]	= $add_ags[ 0 ];

		$add_ags	= UT_utils::createAgendas_UT( 1, '07:00', '16:00' );//1
		$agendas[]	= $add_ags[ 0 ];

		$add_ags	= UT_utils::createAgendas_UT( 1, '07:00', '16:00' );//2
		$agendas[]	= $add_ags[ 0 ];

		$add_ags	= UT_utils::createAgendas_UT( 1, '11:00', '20:00' );//3
		$agendas[]	= $add_ags[ 0 ];

//		$add_ags	= UT_utils::createAgendas_UT( 1, '23:00', '23:00' );//4
//		$agendas[]	= $add_ags[ 0 ];
//
//		$add_ags	= UT_utils::createAgendas_UT( 1, '08:00', '08:00' );//5
//		$agendas[]	= $add_ags[ 0 ];
//
//		$add_ags	= UT_utils::createAgendas_UT( 1, '20:00', '04:00' );//6
//		$agendas[]	= $add_ags[ 0 ];

//				CATEGORIES
//				0	- agendas 1, 3			/ app type 0
//				1	- agendas 0, 1			/ app type 1
//				2	- agendas 0, 2			/ app type 2
//				3	- agendas 2, 3			/ app type 3
//								 			/ app type 4 No category
		$category	= UT_utils::initAgendaCategoryArray();
		$ind	= 0;

		$category[ 'agendas_id' ]	=	$agendas[ 1 ][ $agendasF_Id ].','.		//	0
										$agendas[ 3 ][ $agendasF_Id ];
		$category[ $agCatF_Name ]	= 'UT category '.$ind;
		$cat_res	= category_bl::addOrEditCategory( $category ); $ind++;
		$cats[]	= category_bl::getCategoryById( $cat_res[ 'id' ] );

		$category[ 'agendas_id' ]	=	$agendas[ 0 ][ $agendasF_Id ].','.		//	1
										$agendas[ 1 ][ $agendasF_Id ];
		$category[ $agCatF_Name ]	= 'UT category '.$ind;
		$cat_res	= category_bl::addOrEditCategory( $category ); $ind++;
		$cats[]	= category_bl::getCategoryById( $cat_res[ 'id' ] );

		$category[ 'agendas_id' ]	=	$agendas[ 0 ][ $agendasF_Id ].','.		//	2
										$agendas[ 2 ][ $agendasF_Id ];
		$category[ $agCatF_Name ]	= 'UT category '.$ind;
		$cat_res	= category_bl::addOrEditCategory( $category ); $ind++;
		$cats[]	= category_bl::getCategoryById( $cat_res[ 'id' ] );

		$category[ 'agendas_id' ]	=	$agendas[ 2 ][ $agendasF_Id ].','.		//	3
										$agendas[ 3 ][ $agendasF_Id ];
		$category[ $agCatF_Name ]	= 'UT category '.$ind;
		$cat_res	= category_bl::addOrEditCategory( $category ); $ind++;
		$cats[]	= category_bl::getCategoryById( $cat_res[ 'id' ] );


		//	Clients
		$clients	= UT_utils::createClients1_UT( 5 );
	}
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

	private function doAsserts_001( $ag_id, $cat_id, $app_type_id, $nIter = 0 ){
		global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );

			$today		= '10-01-2010'; //$db_today	= utils_bl::GetDbDate( $today );
			$db_today	= date( 'Y-m-d', strtotime( $today ) );

			//Agendas ttls
			//	0		11:00 - 20:00
			//	1		07:00 - 16:00
			//	2		07:00 - 16:00
			//	3		11:00 - 20:00


			//	Asserts

			$curr_time = '12:00';	//----------------------------------
			UT_utils::doMysqliReconnect( $this, 'test_AgendasHaveNoAppsAndFreeTimesAndAppTypeHasNoConstraints' );
            $month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 1, 2010, $app_type_id, $ag_id, $cat_id, $today, $curr_time, true );

			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 1/Iteration $nIter *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );

			foreach( $month_info as $db_date => $item ){
				$condition	= ( $db_date < $db_today ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 2/Iteration $nIter *****\nWrong value for date: ".utils_bl::GetFormDate( $db_date ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}


			$curr_time = '16:00';	//----------------------------------
			UT_utils::doMysqliReconnect( $this, 'test_AgendasHaveNoAppsAndFreeTimesAndAppTypeHasNoConstraints' );
            $month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 1, 2010, $app_type_id, $ag_id, $cat_id, $today, $curr_time, true );

			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 3/Iteration $nIter *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );

			foreach( $month_info as $db_date => $item ){
				$condition	= ( $db_date < $db_today ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 4/Iteration $nIter *****\nWrong value for date: ".utils_bl::GetFormDate( $db_date ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}


			$curr_time = '16:30';	//----------------------------------
			UT_utils::doMysqliReconnect( $this, 'test_AgendasHaveNoAppsAndFreeTimesAndAppTypeHasNoConstraints' );
            $month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 1, 2010, $app_type_id, $ag_id, $cat_id, $today, $curr_time );

			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 6/Iteration $nIter *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );

			foreach( $month_info as $db_date => $item ){
				$condition	= ( $db_date < $db_today ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 7/Iteration $nIter *****\nWrong value for date: ".utils_bl::GetFormDate( $db_date ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}


			$curr_time = '19:30';	//----------------------------------
			UT_utils::doMysqliReconnect( $this, 'test_AgendasHaveNoAppsAndFreeTimesAndAppTypeHasNoConstraints' );
            $month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 1, 2010, $app_type_id, $ag_id, $cat_id, $today, $curr_time );

			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 8/Iteration $nIter *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );

			foreach( $month_info as $db_date => $item ){
				$condition	= ( $db_date < $db_today ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 9/Iteration $nIter *****\nWrong value for date: ".utils_bl::GetFormDate( $db_date ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}


			$curr_time = '19:31';	//----------------------------------
			UT_utils::doMysqliReconnect( $this, 'test_AgendasHaveNoAppsAndFreeTimesAndAppTypeHasNoConstraints' );
            $month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 1, 2010, $app_type_id, $ag_id, $cat_id, $today, $curr_time );

			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 10/Iteration $nIter *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );

			foreach( $month_info as $db_date => $item ){
				$condition	= ( $db_date <= $db_today ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 11/Iteration $nIter *****\nWrong value for date: ".utils_bl::GetFormDate( $db_date ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}


			$curr_time = '20:00';	//----------------------------------
			UT_utils::doMysqliReconnect( $this, 'test_AgendasHaveNoAppsAndFreeTimesAndAppTypeHasNoConstraints' );
            $month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 1, 2010, $app_type_id, $ag_id, $cat_id, $today, $curr_time );

			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 12/Iteration $nIter *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );

			foreach( $month_info as $db_date => $item ){
				$condition	= ( $db_date <= $db_today ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 13/Iteration $nIter *****\nWrong value for date: ".utils_bl::GetFormDate( $db_date ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}


	}
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

	private function doAsserts_002( $ag_id, $cat_id, $app_type_id, $nIter = 0 ){
		global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );

			$today = '10-01-2010'; $db_today	= utils_bl::GetDbDate( $today );

			//	Asserts

			$curr_time = '12:00';	//----------------------------------
			UT_utils::doMysqliReconnect( $this, 'test_TwoAgendasHaveNoAppointmentsAndBlockedTimesAndAppTypeHasNoConstraints_1' );
            $month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 1, 2010, $app_type_id, $ag_id, $cat_id, $today, $curr_time );

			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 1/Iteration $nIter *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );

			foreach( $month_info as $db_date => $item ){
				$condition	= ( $db_date < $db_today || $db_date > '2010-01-20' ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 2/Iteration $nIter *****\nWrong value for date: ".utils_bl::GetFormDate( $db_date ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}

	}
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

	private function doAsserts_003( $ag_id, $cat_id, $app_type_id, $nIter = 0 ){
		global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );

			$today = '01-01-2010'; $db_today	= date( 'Y-m-d', strtotime( $today ) );


			//	Asserts

			$curr_time = '12:00';	//----------------------------------

			UT_utils::doMysqliReconnect( $this, 'testAgendasHaveNoAppsAndFreeTimesAndAppTypeHasPatt_STTS' );
            $month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 1, 2010, $app_type_id, $ag_id, $cat_id, $today, $curr_time );

			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 1/Iteration $nIter *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );

			foreach( $month_info as $db_date => $item ){
				$tst_date	= utils_bl::GetFormDate( $db_date );
				$week_day	= utils_bl::getNumOfWeekDay( $tst_date );

				$condition	= ( BlockDaysBL::c_nMonday	== $week_day || BlockDaysBL::c_nWednesday	== $week_day || BlockDaysBL::c_nFriday	== $week_day ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 2/Iteration $nIter *****\nWrong value for date: ".utils_bl::GetFormDate( $db_date ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}

			UT_utils::doMysqliReconnect( $this, 'testAgendasHaveNoAppsAndFreeTimesAndAppTypeHasPatt_STTS' );
            $month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 8, 2010, $app_type_id, $ag_id, $cat_id, $today, $curr_time );

			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 3/Iteration $nIter *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );

			foreach( $month_info as $db_date => $item ){
				$tst_date	= utils_bl::GetFormDate( $db_date );
				$week_day	= utils_bl::getNumOfWeekDay( $tst_date );

				$condition	= ( BlockDaysBL::c_nMonday	== $week_day || BlockDaysBL::c_nWednesday	== $week_day || BlockDaysBL::c_nFriday	== $week_day ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 4/Iteration $nIter *****\nWrong value for date: ".utils_bl::GetFormDate( $db_date ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}

	}
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

	private function doAsserts_004( $ag_id, $cat_id, $app_type_id, $nIter = 0 ){
		global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );

			$today		= '11-08-2010'; //$db_today	= utils_bl::GetDbDate( $today );
			$db_today	= date( 'Y-m-d', strtotime( $today ) );

			//	Asserts

			$curr_time = '06:00';	//----------------------------------
			UT_utils::doMysqliReconnect( $this, 'test_TtlHasEmpty30MinLineCreatedByApps_NoFreeTimes_AppTypeStartEnd_10_15' );
            $month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 8, 2010, $app_type_id, $ag_id, $cat_id, $today, $curr_time );

			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 1/Iteration $nIter. Current time: $curr_time *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );

			foreach( $month_info as $db_date => $item ){
				$condition	= ( $db_date < $db_today ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 2/Iteration $nIter. Current time: $curr_time  *****\nWrong value for date: ".utils_bl::GetFormDate( $db_date ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}

			$curr_time = '07:00';	//----------------------------------
			UT_utils::doMysqliReconnect( $this, 'test_TtlHasEmpty30MinLineCreatedByApps_NoFreeTimes_AppTypeStartEnd_10_15' );
            $month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 8, 2010, $app_type_id, $ag_id, $cat_id, $today, $curr_time );

			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 3/Iteration $nIter. Current time: $curr_time  *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );

			foreach( $month_info as $db_date => $item ){
				$condition	= ( $db_date < $db_today ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 4/Iteration $nIter. Current time: $curr_time  *****\nWrong value for date: ".utils_bl::GetFormDate( $db_date ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}

			$curr_time = '12:00';	//----------------------------------
			UT_utils::doMysqliReconnect( $this, 'test_TtlHasEmpty30MinLineCreatedByApps_NoFreeTimes_AppTypeStartEnd_10_15' );
            $month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 8, 2010, $app_type_id, $ag_id, $cat_id, $today, $curr_time );

			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 5/Iteration $nIter. Current time: $curr_time  *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );

			foreach( $month_info as $db_date => $item ){
				$condition	= ( $db_date < $db_today ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 6/Iteration $nIter. Current time: $curr_time  *****\nWrong value for date: ".utils_bl::GetFormDate( $db_date ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}

			$curr_time = '12:09';	//----------------------------------
			UT_utils::doMysqliReconnect( $this, 'test_TtlHasEmpty30MinLineCreatedByApps_NoFreeTimes_AppTypeStartEnd_10_15' );
            $month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 8, 2010, $app_type_id, $ag_id, $cat_id, $today, $curr_time );

			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 7/Iteration $nIter. Current time: $curr_time  *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );

			foreach( $month_info as $db_date => $item ){
				$condition	= ( $db_date <= $db_today ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 8/Iteration $nIter. Current time: $curr_time  *****\nWrong value for date: ".utils_bl::GetFormDate( $db_date ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}

			$curr_time = '12:22';	//----------------------------------
			UT_utils::doMysqliReconnect( $this, 'test_TtlHasEmpty30MinLineCreatedByApps_NoFreeTimes_AppTypeStartEnd_10_15' );
            $month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 8, 2010, $app_type_id, $ag_id, $cat_id, $today, $curr_time );

			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 9/Iteration $nIter. Current time: $curr_time  *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );

			foreach( $month_info as $db_date => $item ){
				$condition	= ( $db_date <= $db_today ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 10/Iteration $nIter. Current time: $curr_time  *****\nWrong value for date: ".utils_bl::GetFormDate( $db_date ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}

			$curr_time = '12:23';	//----------------------------------
			UT_utils::doMysqliReconnect( $this, 'test_TtlHasEmpty30MinLineCreatedByApps_NoFreeTimes_AppTypeStartEnd_10_15' );
            $month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 8, 2010, $app_type_id, $ag_id, $cat_id, $today, $curr_time );

			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 11/Iteration $nIter. Current time: $curr_time  *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );

			foreach( $month_info as $db_date => $item ){
				$condition	= ( $db_date <= $db_today ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 12/Iteration $nIter. Current time: $curr_time  *****\nWrong value for date: ".utils_bl::GetFormDate( $db_date ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}

			$curr_time = '15:00';	//----------------------------------
			UT_utils::doMysqliReconnect( $this, 'test_TtlHasEmpty30MinLineCreatedByApps_NoFreeTimes_AppTypeStartEnd_10_15' );
            $month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 8, 2010, $app_type_id, $ag_id, $cat_id, $today, $curr_time );

			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 13/Iteration $nIter. Current time: $curr_time  *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );

			foreach( $month_info as $db_date => $item ){
				$condition	= ( $db_date <= $db_today ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 14/Iteration $nIter. Current time: $curr_time  *****\nWrong value for date: ".utils_bl::GetFormDate( $db_date ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}

			$curr_time = '15:30';	//----------------------------------
			UT_utils::doMysqliReconnect( $this, 'test_TtlHasEmpty30MinLineCreatedByApps_NoFreeTimes_AppTypeStartEnd_10_15' );
            $month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 8, 2010, $app_type_id, $ag_id, $cat_id, $today, $curr_time );

			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 15/Iteration $nIter. Current time: $curr_time  *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );

			foreach( $month_info as $db_date => $item ){
				$condition	= ( $db_date <= $db_today ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 16/Iteration $nIter. Current time: $curr_time  *****\nWrong value for date: ".utils_bl::GetFormDate( $db_date ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}

	}
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

	private function doAsserts_005( $ag_id, $cat_id, $app_type_id, $nIter = 0 ){
		global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );


			$today = '01-01-2010';// $db_today	= utils_bl::GetDbDate( $today );
			$db_today	= date( 'Y-m-d', strtotime( $today ) );
			$curr_time = '12:00';

			for( $pattern = 1; $pattern < 128; $pattern++ ){
				$app_type	= array(
					$appTypesF_Id			=> $app_type_id,
					$appTypesF_PeriodDay	=> $pattern
				);
				UT_utils::updateAppType( $app_type );

//	            $month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 1, 2010, $app_types[ 0 ][ $appTypesF_Id ], $ags, $today, $curr_time );
				UT_utils::doMysqliReconnect( $this, 'testTwoAgendasHaveNoAppsAndFreeTimesAndAppTypeHasMultiPatt' );
	            $month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 8, 2010, $app_type_id, $ag_id, $cat_id, $today, $curr_time );

	            $n_items	= count( $month_info );
				$this->assertEquals( 31, count( $month_info ), "Assert 1/$nIter. Wrong quantity of items were found for date: $today.".", actually: ".$n_items."\n\nReceived data:\n".print_r(  $month_info, true ) );
				foreach( $month_info as $db_date => $item ){
					$tst_date	= utils_bl::GetFormDate( $db_date );
					$logic	= self::getLogicValueForAppTypePattern( $pattern, $tst_date );
					$condition	= ( !$logic ) ? ( $item == 0 ) : ( $item == 1 );
					$this->assertTrue( $condition, "Assert 2/$nIter. Wrong value was found on date: ".$tst_date." pattern: $pattern (".utils_bl::getWeekDayByDate( $tst_date ).").\n\nReceived data:\n".print_r(  $month_info, true ) );
				}


//				$month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 8, 2015, $app_types[ 0 ][ $appTypesF_Id ], $ags, $today, $curr_time );
				UT_utils::doMysqliReconnect( $this, 'testTwoAgendasHaveNoAppsAndFreeTimesAndAppTypeHasMultiPatt' );
	            $month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 8, 2010, $app_type_id, $ag_id, $cat_id, $today, $curr_time );
				foreach( $month_info as $db_date => $item ){
					$tst_date	= utils_bl::GetFormDate( $db_date );
					$logic	= self::getLogicValueForAppTypePattern( $pattern, $tst_date );

					$week_day	= utils_bl::getNumOfWeekDay( $tst_date );
					$condition	= ( !$logic ) ? ( $item == 0 ) : ( $item == 1 );
					$this->assertTrue( $condition, "Assert 3/$nIter. Wrong value was found on date: ".$tst_date." (".utils_bl::getWeekDayByDate( $tst_date ).").\n\nReceived data:\n".print_r(  $month_info, true ) );
				}
				if( 1 && $pattern > 10 ){ break; }
			}
	}
	//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

	private function doAsserts_006( $ag_id, $cat_id, $app_type_id, $nIter = 0 ){
		$today = '10-08-2010'; $db_today	= date( 'Y-m-d', strtotime( $today ) );
		$curr_time = '12:00';

		UT_utils::doMysqliReconnect( $this, 'test_AgendasHaveNoAppsAndFreeTimes__AppTypStartEnd_21_18' );
		$month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 8, 2010, $app_type_id, $ag_id, $cat_id, $today, $curr_time );
		foreach( $month_info as $db_date => $item ){
			$date	= date( 'd-m-Y', strtotime( $db_date ) );
			$condition	= ( $db_date < $db_today ) ? ( 0 == $item ) : ( 1 == $item );
			$this->assertTrue( $condition, "Assert 1/$nIter. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
		}
	}
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

     public function test_Performance_Simple(){															//	_is_1_1_1
         if( !$_SESSION[ 'is_skip' ] ){	//	Performance
            global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );

            $agendas	= UT_utils::createAgendas_UT( 20, '07:00', '16:00' );//1
		$name	= 'UT App type ';
		$ini_app_type	= UT_utils::initAppTypeArray2(); $ind	= 0;

		//	App types
		$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
		$res	= app_type_bl::editAppType( $ini_app_type );
		$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 0

				$m	= microtime();
				list( $a1, $a2 ) = explode( " ", $m );
				$m1	= $a2 + $a1;

				$date = '01-01-2010'; $db_date = date( 'Y-m-d', strtotime( $date ) );

            $month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 1, 2010, $app_types[ 0 ][ $appTypesF_Id ], make_appointments_bl::_whole_list, make_appointments_bl::_whole_list, $date, '12:00' );


				$m	= microtime(  );
				list( $a1, $a2 ) = explode( " ", $m );
				$m2	= $a2 + $a1;


				$str	=
"<div style='position:absolute;top:0;left:400px;background-color: #FFdddd;'>Performance time for `getAvailableDaysForMonth_24` is ".( $m2 - $m1 ).". (Simple test)</div>";
				echo $str;

			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 1 *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
			foreach( $month_info as $db_date => $item ){
				$condition	= ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 2 *****\nWrong value for date: ".date( 'd_m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}


        }else{ $this->markTestSkipped(); }
     }
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

     public function test_Performance_AllDaysBlockedByFreeTimes(){										//	_is_1_1_2
         if(!$_SESSION[ 'is_skip' ] ){	//	Performance
            global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );

            $agendas	= UT_utils::createAgendas_UT( 20, '07:00', '16:00' );//1
			$name	= 'UT App type ';
			$ini_app_type	= UT_utils::initAppTypeArray2(); $ind	= 0;

			//	App types
			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 0

			$new_blk = array(
				$daysOffF_AgendaId	=> NULL,
				$daysOffF_Comment	=> 'Unit test for cache. Which tests were created by C.Kolenchenko (ckolenchenko@yukon.cv.ua)',
				$dbTable_StartDate	=> '01-10-2010',
				$dbTable_EndDate	=> '31-10-2010',
				$dbTable_StartTime	=> '07:00',
				$dbTable_EndTime	=> '16:00',
				PATTERN_ID			=> 0
			);
			foreach( $agendas as $agenda ){
				$new_blk[ $daysOffF_AgendaId ]	= $agenda[ $agendasF_Id ];
				$blk_id 		= BlockDaysBL::insertFreeTimeRec( $new_blk );
				$free_times[]	= BlockDaysBL::getFreeTimeById( $blk_id );
			}

			UT_utils::doMysqliReconnect( $this, 'test_AgendasHaveNoAppsAndFreeTimesAndAppTypeHasNoConstraints' );

				$m	= microtime();
				list( $a1, $a2 ) = explode( " ", $m );
				$m1	= $a2 + $a1;

            $month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 10, 2010, $app_types[ 0 ][ $appTypesF_Id ], make_appointments_bl::_whole_list, make_appointments_bl::_whole_list, '01-10-2010', '12:00' );

				$m	= microtime(  );
				list( $a1, $a2 ) = explode( " ", $m );
				$m2	= $a2 + $a1;


				$str	=
"<div style='position:absolute;top:18;left:400px;background-color: #FFdddd;'>Performance time for `getAvailableDaysForMonth_24` is ".( $m2 - $m1 ).". (Free times test)</div>";
				echo $str;

				$this->assertEquals( 31, count( $month_info ), "\n***** Assert 1/Iteration $nIter *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 1 *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
			foreach( $month_info as $db_date => $item ){
				$condition	= ( 0 == $item );
				$this->assertTrue( $condition, "\n***** Assert 2 *****\nWrong value for date: ".date( 'd_m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}


        }else{ $this->markTestSkipped(); }
     }
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

	public function test_AgendasHaveNoAppsAndFreeTimesAndAppTypeHasNoConstraints(){						//	_is_1_2_2
		if( ( self::_is_1_2_2 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]		= true;
			global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );

			self::createEnvironment_01();

			//	Appointment types
			$name	= 'UT App type ';
			$ini_app_type	= UT_utils::initAppTypeArray2(); $ind	= 0;

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 0 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 0

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 1 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 1

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 2 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 2

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 3 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 3

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= NULL;
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 4

			//	Asserts
			$this->doAsserts_001( make_appointments_bl::_whole_list, $cats[ 0 ][ $agCatF_Id ], $app_types[ 0 ][ $appTypesF_Id ], 0 );
			$this->doAsserts_001( make_appointments_bl::_whole_list, $cats[ 1 ][ $agCatF_Id ], $app_types[ 1 ][ $appTypesF_Id ], 1 );
			$this->doAsserts_001( make_appointments_bl::_whole_list, $cats[ 2 ][ $agCatF_Id ], $app_types[ 2 ][ $appTypesF_Id ], 2 );
			$this->doAsserts_001( make_appointments_bl::_whole_list, $cats[ 3 ][ $agCatF_Id ], $app_types[ 3 ][ $appTypesF_Id ], 3 );
			$this->doAsserts_001( make_appointments_bl::_whole_list, make_appointments_bl::_whole_list, $app_types[ 4 ][ $appTypesF_Id ], 4 );
			$this->doAsserts_001( make_appointments_bl::_whole_list, make_appointments_bl::_whole_list, $app_types[ 0 ][ $appTypesF_Id ], 5 );


			$_SESSION[ 'is_skip' ]	= false;
		}else{ $this->markTestSkipped(); }
	}
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

    public function testAgendasHaveNoAppsAndFreeTimesAndAppTypeHasMaxTime_10(){							//	_is_1_2_3
         if( ( self::_is_1_2_3 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
            $_SESSION[ 'is_skip' ]		= true;
            global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );

            self::createEnvironment_01();

			//	Appointment types
			$name	= 'UT App type ';
			$ini_app_type	= UT_utils::initAppTypeArray2(); $ind	= 0;
			$ini_app_type[ $appTypesF_MaxTime ] = 10;

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 0 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 0

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 1 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 1

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 2 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 2

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 3 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 3

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= NULL;
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 4


			//	Asserts
			$this->doAsserts_002( make_appointments_bl::_whole_list, $cats[ 0 ][ $agCatF_Id ], $app_types[ 0 ][ $appTypesF_Id ], 0 );
			$this->doAsserts_002( make_appointments_bl::_whole_list, $cats[ 1 ][ $agCatF_Id ], $app_types[ 1 ][ $appTypesF_Id ], 1 );
			$this->doAsserts_002( make_appointments_bl::_whole_list, $cats[ 2 ][ $agCatF_Id ], $app_types[ 2 ][ $appTypesF_Id ], 2 );
			$this->doAsserts_002( make_appointments_bl::_whole_list, $cats[ 3 ][ $agCatF_Id ], $app_types[ 3 ][ $appTypesF_Id ], 3 );
			$this->doAsserts_002( make_appointments_bl::_whole_list, make_appointments_bl::_whole_list, $app_types[ 4 ][ $appTypesF_Id ], 4 );


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'testAgendasHaveNoAppointmentsAndBlockedTimesAndAppTypeHasMaxTime_10 is off!!!' ); }
    }
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

    public function testAgendasHaveNoAppsAndFreeTimesAndAppTypeHasPatt_STTS(){							//	_is_1_2_4
         if( ( self::_is_1_2_4 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
            $_SESSION[ 'is_skip' ]	= true;
            global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );

            self::createEnvironment_01();

			//	Appointment types
			$name	= 'UT App type ';
			$ini_app_type	= UT_utils::initAppTypeArray2(); $ind	= 0;
			$ini_app_type[ $appTypesF_PeriodDay ] = array( 1, 0, 1, 0, 1, 0, 1 );

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 0 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 0

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 1 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 1

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 2 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 2

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 3 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 3

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= NULL;
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 4

			//	Asserts
			$this->doAsserts_003( make_appointments_bl::_whole_list, $cats[ 0 ][ $agCatF_Id ], $app_types[ 0 ][ $appTypesF_Id ], 0 );
			$this->doAsserts_003( make_appointments_bl::_whole_list, $cats[ 1 ][ $agCatF_Id ], $app_types[ 1 ][ $appTypesF_Id ], 1 );
			$this->doAsserts_003( make_appointments_bl::_whole_list, $cats[ 2 ][ $agCatF_Id ], $app_types[ 2 ][ $appTypesF_Id ], 2 );
			$this->doAsserts_003( make_appointments_bl::_whole_list, $cats[ 3 ][ $agCatF_Id ], $app_types[ 3 ][ $appTypesF_Id ], 3 );
			$this->doAsserts_003( make_appointments_bl::_whole_list, make_appointments_bl::_whole_list, $app_types[ 4 ][ $appTypesF_Id ], 4 );


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped(); }
    }
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

    public function test_TtlHasEmpty30MinByApps_AppTypStartEnd_10_15(){									//	_is_1_2_5
         if( ( self::_is_1_2_5 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
            $_SESSION[ 'is_skip' ]		= true;
            global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );

            self::createEnvironment_01();

			//	Appointment types
			$name	= 'UT App type ';
			$ini_app_type	= UT_utils::initAppTypeArray2(); $ind	= 0;
	    	$ini_app_type[ $appTypesF_PeriodStartTime ]	= '10:00';
	    	$ini_app_type[ $appTypesF_PeriodEndTime ]	= '15:00';

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 0 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 0

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 1 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 1

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 2 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 2

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 3 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 3

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= NULL;
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 4

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;			//	NO constraints
	    	$ini_app_type[ $appTypesF_PeriodStartTime ]	= '00:00';
	    	$ini_app_type[ $appTypesF_PeriodEndTime ]	= '00:00';
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 5


			$today = '11-08-2010';


			UT_utils::createBlockOfAppointments( $today, '10:00', '12:00', $agendas[ 1 ][ $agendasF_Id ], $app_types[ 5 ] );//07:00 - 16:00 (ag)
			UT_utils::createBlockOfAppointments( $today, '12:30', '15:30', $agendas[ 1 ][ $agendasF_Id ], $app_types[ 5 ] );


			//	Asserts
			$this->doAsserts_004( $agendas[ 1 ][ $agendasF_Id ], make_appointments_bl::_whole_list, $app_types[ 4 ][ $appTypesF_Id ], 0 );
			$this->doAsserts_004( $agendas[ 1 ][ $agendasF_Id ], $cats[ 0 ][ $agCatF_Id ], $app_types[ 0 ][ $appTypesF_Id ], 1 );
			$this->doAsserts_004( $agendas[ 1 ][ $agendasF_Id ], $cats[ 1 ][ $agCatF_Id ], $app_types[ 1 ][ $appTypesF_Id ], 2 );


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped(); }
    }
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

    public function test_TtlHasEmpty30MinLineByFreeTimes_AppTypStartEnd_10_15(){						//	_is_1_2_6
		if( ( self::_is_1_2_6 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]		= true;
			global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );

			self::createEnvironment_01();

			//	Appointment types
			$name	= 'UT App type ';
			$ini_app_type	= UT_utils::initAppTypeArray2(); $ind	= 0;
	    	$ini_app_type[ $appTypesF_PeriodStartTime ]	= '10:00';
	    	$ini_app_type[ $appTypesF_PeriodEndTime ]	= '15:00';

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 0 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 0

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 1 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 1

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 2 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 2

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 3 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 3

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= NULL;
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 4

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;			//	NO constraints
	    	$ini_app_type[ $appTypesF_PeriodStartTime ]	= '00:00';
	    	$ini_app_type[ $appTypesF_PeriodEndTime ]	= '00:00';
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 5


			$today = '11-08-2010';

			$blk_param = array(
				$daysOffF_AgendaId	=> $agendas[ 1 ][ $agendasF_Id ],
				$daysOffF_Comment	=> 'Unit test for cache. Which tests were created by C.Kolenchenko (ckolenchenko@yukon.cv.ua)',
				$dbTable_StartDate	=> $today,
				$dbTable_EndDate	=> $today,
				$dbTable_StartTime	=> '10:00',
				$dbTable_EndTime	=> '12:00',
				PATTERN_ID			=> 0
			);
			BlockDaysBL::insertFreeTimeRec( $blk_param );

			$blk_param[ $dbTable_StartTime ]	= '12:30';
			$blk_param[ $dbTable_EndTime ]		= '15:10';
			BlockDaysBL::insertFreeTimeRec( $blk_param );


			//	Asserts
			$this->doAsserts_004( $agendas[ 1 ][ $agendasF_Id ], make_appointments_bl::_whole_list, $app_types[ 4 ][ $appTypesF_Id ], 0 );
			$this->doAsserts_004( $agendas[ 1 ][ $agendasF_Id ], $cats[ 0 ][ $agCatF_Id ], $app_types[ 0 ][ $appTypesF_Id ], 1 );
			$this->doAsserts_004( $agendas[ 1 ][ $agendasF_Id ], $cats[ 1 ][ $agCatF_Id ], $app_types[ 1 ][ $appTypesF_Id ], 2 );


			$_SESSION[ 'is_skip' ]	= false;
		}else{ $this->markTestSkipped(); }
	}
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

    public function test_OneAgendaHasEmpty30MinLineByTopFreeTimesAndBottApps_AppTypStartEnd_10_15(){	//	_is_1_2_7
         if( ( self::_is_1_2_7 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
            $_SESSION[ 'is_skip' ]		= true;
			global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );
            self::createEnvironment_01();

			//	Appointment types
			$name	= 'UT App type ';
			$ini_app_type	= UT_utils::initAppTypeArray2(); $ind	= 0;
	    	$ini_app_type[ $appTypesF_PeriodStartTime ]	= '10:00';
	    	$ini_app_type[ $appTypesF_PeriodEndTime ]	= '15:00';

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 0 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 0

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 1 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 1

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 2 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 2

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 3 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 3

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= NULL;
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 4

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;			//	NO constraints
	    	$ini_app_type[ $appTypesF_PeriodStartTime ]	= '00:00';
	    	$ini_app_type[ $appTypesF_PeriodEndTime ]	= '00:00';
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 5


			$today = '11-08-2010';

			$blk_param = array(
				$daysOffF_AgendaId	=> $agendas[ 1 ][ $agendasF_Id ],
				$daysOffF_Comment	=> 'Unit test for cache. Which tests were created by C.Kolenchenko (ckolenchenko@yukon.cv.ua)',
				$dbTable_StartDate	=> $today,
				$dbTable_EndDate	=> $today,
				$dbTable_StartTime	=> '10:00',
				$dbTable_EndTime	=> '12:00',
				PATTERN_ID			=> 0
			);
			BlockDaysBL::insertFreeTimeRec( $blk_param );
			UT_utils::createBlockOfAppointments( $today, '12:30', '15:30', $agendas[ 1 ][ $agendasF_Id ], $app_types[ 5 ] );


			//	Asserts
			$this->doAsserts_004( $agendas[ 1 ][ $agendasF_Id ], make_appointments_bl::_whole_list, $app_types[ 4 ][ $appTypesF_Id ], 0 );
			$this->doAsserts_004( $agendas[ 1 ][ $agendasF_Id ], $cats[ 0 ][ $agCatF_Id ], $app_types[ 0 ][ $appTypesF_Id ], 1 );
			$this->doAsserts_004( $agendas[ 1 ][ $agendasF_Id ], $cats[ 1 ][ $agCatF_Id ], $app_types[ 1 ][ $appTypesF_Id ], 2 );


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped(); }
    }
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

    public function testOneAgendaHasFree30MinLineByBottFreeTimesAndTopApps_AppTypeStartEnd_10_15(){		//	_is_1_2_8
         if( ( self::_is_1_2_8 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
            $_SESSION[ 'is_skip' ]		= true;
			global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );

            self::createEnvironment_01();

			//	Appointment types
			$name	= 'UT App type ';
			$ini_app_type	= UT_utils::initAppTypeArray2(); $ind	= 0;
	    	$ini_app_type[ $appTypesF_PeriodStartTime ]	= '10:00';
	    	$ini_app_type[ $appTypesF_PeriodEndTime ]	= '15:00';

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 0 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 0

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 1 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 1

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 2 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 2

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 3 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 3

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= NULL;
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 4

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;			//	NO constraints
	    	$ini_app_type[ $appTypesF_PeriodStartTime ]	= '00:00';
	    	$ini_app_type[ $appTypesF_PeriodEndTime ]	= '00:00';
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 5

			$today = '11-08-2010';

            UT_utils::createBlockOfAppointments( $today, '10:00', '12:00', $agendas[ 1 ][ $agendasF_Id ], $app_types[ 5 ] );

			$blk_param = array(
				$daysOffF_AgendaId	=> $agendas[ 1 ][ $agendasF_Id ],
				$daysOffF_Comment	=> 'Unit test for cache. Which tests were created by C.Kolenchenko (ckolenchenko@yukon.cv.ua)',
				$dbTable_StartDate	=> $today,
				$dbTable_EndDate	=> $today,
				$dbTable_StartTime	=> '12:30',
				$dbTable_EndTime	=> '15:10',
				PATTERN_ID			=> 0
			);
			BlockDaysBL::insertFreeTimeRec( $blk_param );


			//	Asserts
			$this->doAsserts_004( $agendas[ 1 ][ $agendasF_Id ], make_appointments_bl::_whole_list, $app_types[ 4 ][ $appTypesF_Id ], 0 );
			$this->doAsserts_004( $agendas[ 1 ][ $agendasF_Id ], $cats[ 0 ][ $agCatF_Id ], $app_types[ 0 ][ $appTypesF_Id ], 1 );
			$this->doAsserts_004( $agendas[ 1 ][ $agendasF_Id ], $cats[ 1 ][ $agCatF_Id ], $app_types[ 1 ][ $appTypesF_Id ], 2 );


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped(); }
    }
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

    public function test_AgendaHasOffDays_AllWeekPatterns(){ 											//	_is_1_2_9	Long duration ( > 20 min)
         if( ( self::_is_1_2_9 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
            $_SESSION[ 'is_skip' ]	= true;
            global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );

            self::createEnvironment_01();

			//	Appointment types
			$name	= 'UT App type ';
			$ini_app_type	= UT_utils::initAppTypeArray2(); $ind	= 0;

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 0 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 0

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 1 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 1

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 2 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 2

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 3 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 3

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= NULL;
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 4


			$start_date = '01-01-2011';										$mk_start	= strtotime( $start_date );
			$end_date	= ( self::_is_fast ) ? '20-06-2011' : '20-03-2012';	$mk_end		= strtotime( $end_date );//	6/15

			$new_blk	= UT_utils::initBlkArray();
			$new_blk[ $daysOffF_AgendaId ]	= $agendas[ 1 ][ $agendasF_Id ];// 07:00 - 16:00
			$new_blk[ $dbTable_StartDate ]	= $start_date;
			$new_blk[ $dbTable_EndDate ]	= $end_date;
			unset( $new_blk[ $dbTable_StartTime ] );
			unset( $new_blk[ $dbTable_EndTime ] );
			$new_blk[ 'PATTERN_ID' ]				= 1;
			$new_blk[ $daysoffPatternF_Cycle ]		= BlockDaysBL::_weekly_cycle;
			$new_blk[ $daysoffPatternF_Period ]		= 1;
			$new_blk[ $daysoffPatternF_WeekDays ]	= 0;

			$blk_id 	= BlockDaysBL::insertDayoffRec( $new_blk );
			$blk_data	= BlockDaysBL::getDayoffById( $blk_id );

			$add_info	= "";

            $mk_s		= $mk_start;
            $mk_e		= strtotime( '+1 month', $mk_end );
            $mk_months	= array();
            while( $mk_s <= $mk_e ){
            	$mk_months[]	= $mk_s;
            	$mk_s	= strtotime( '+1 month', $mk_s );
            }

			$tst_cnt	= 0;
			for( $add_days = 0; $add_days < 7; $add_days++ ){
				$new_blk[ $dbTable_StartDate ]	= date( 'd-m-Y', strtotime( '+'.$add_days.' day', $mk_start ) );

				$today	= '07-01-2011'; $curr_time = '12:00';

//				$today	= '30-12-2010'; $curr_time = '12:00';
//				for( $tdd = 0; $tdd < 16; $tdd = $tdd + 4 ){//4
					$mk_tdd	= strtotime( $today );
					$today	= date( 'd-m-Y', strtotime( '+'.$tdd.' day', $mk_tdd ) );
					$db_today	= date( 'Y-m-d', strtotime( '+'.$tdd.' day', $mk_tdd ) );

		            for( $period = 1; $period <= 3; $period++ ){
		            	$new_blk[ $daysoffPatternF_Period ]		= $period;

		            	$start_patt	= ( !self::_is_fast ) ? 1 : 87;
		            	for( $pattern = $start_patt; $pattern < 127; $pattern++ ){
							$new_blk[ $daysoffPatternF_WeekDays ]	= $pattern;
							$add_info	= "Week days: $pattern, period: $period. Date start: ".$new_blk[ $dbTable_StartDate ].", end: ".$new_blk[ $dbTable_EndDate ].".";
							( self::_is_fast ) ? $add_info .= "\nLimited by ".self::_n_max_iters." iterations. Iteration number is $tst_cnt.":'';
			//	Blk update
							UT_utils::updateDayOff( $blk_id, $blk_data[ 'PATTERN_ID' ], $new_blk );

							foreach( $mk_months as $mk_month ){
								$month	= intval( date( 'n', $mk_month ) );
								$year	= intval( date( 'Y', $mk_month ) );
			//	Get month info
	////							UT_utils::doMysqliReconnect( $this, 'test_AgendaHasOffDays_AllWeekPatterns' );
					            $month_info	= make_appointments_bl::getAvailableDaysForMonth_24( $month, $year, $app_types[ 4 ][ $appTypesF_Id ], $agendas[ 1 ][ $agendasF_Id ], make_appointments_bl::_whole_list, $today, $curr_time );

								$month_exp	= UT_utils::findFreeDaysOfMonthByDayOffItem( $mk_month, $new_blk );
								foreach( $month_info as $db_date => $item ){
									$date	= date( 'd-m-Y', strtotime( $db_date ) );
									$is_blk	= !array_key_exists( $db_date, $month_exp );

									$condition	= ( $is_blk || $db_date < $db_today ) ? ( 0 == $item ) : ( 1 == $item );
									$this->assertTrue( $condition, "Assert 1. Wrong value was found on date: ".$date.
														"/".utils_bl::getWeekDayByDate( $date )."/Today date is $today".
														"/\n$add_info\n\nReceived data:\n".print_r(  $month_info, true ) );
								}

							}
																		$tst_cnt++;
																		if( self::_is_fast && ( $tst_cnt > self::_n_max_iters ) ){ break; }
						}
																		if( self::_is_fast && ( $tst_cnt > self::_n_max_iters ) ){ break; }
					}



//																		if( self::_is_fast && ( $tst_cnt > self::_n_max_iters ) ){ break; }
//				}
																		if( self::_is_fast && ( $tst_cnt > self::_n_max_iters ) ){ break; }
			}


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped(); }
    }
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

    private function getLogicValueForAppTypePattern( $pattern, $date ){
		$week_day	= utils_bl::getNumOfWeekDay( $date );

		$logic	= false;
		$logic = ( $pattern & BlockDaysBL::c_sundayMask )		? ( $logic || BlockDaysBL::c_nSunday == $week_day ) : $logic ;
		$logic = ( $pattern & BlockDaysBL::c_mondayMask )		? ( $logic || BlockDaysBL::c_nMonday == $week_day ) : $logic ;
		$logic = ( $pattern & BlockDaysBL::c_tuesdayMask )		? ( $logic || BlockDaysBL::c_nTuesday == $week_day ) : $logic ;
		$logic = ( $pattern & BlockDaysBL::c_wednesdayMask )	? ( $logic || BlockDaysBL::c_nWednesday == $week_day ) : $logic ;
		$logic = ( $pattern & BlockDaysBL::c_thursdayMask )		? ( $logic || BlockDaysBL::c_nThursday == $week_day ) : $logic ;
		$logic = ( $pattern & BlockDaysBL::c_fridayMask )		? ( $logic || BlockDaysBL::c_nFriday == $week_day ) : $logic ;
		$logic = ( $pattern & BlockDaysBL::c_saturdayMask )		? ( $logic || BlockDaysBL::c_nSaturday == $week_day ) : $logic ;

		return $logic;
    }
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

    public function testTwoAgendasHaveNoAppsAndFreeTimesAndAppTypeHasMultiPatt(){						//	_is_1_2_10
         if( ( self::_is_1_2_10 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
            $_SESSION[ 'is_skip' ]		= true;
            global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );
            self::createEnvironment_01();

			//	Appointment types
			$name	= 'UT App type ';
			$ini_app_type	= UT_utils::initAppTypeArray2(); $ind	= 0;

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 0 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 0

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 1 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 1

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 2 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 2

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 3 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 3

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= NULL;
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 4


			//	Asserts
			$this->doAsserts_005( make_appointments_bl::_whole_list, $cats[ 0 ][ $agCatF_Id ], $app_types[ 0 ][ $appTypesF_Id ], 0 );
			$this->doAsserts_005( make_appointments_bl::_whole_list, $cats[ 1 ][ $agCatF_Id ], $app_types[ 1 ][ $appTypesF_Id ], 1 );
			$this->doAsserts_005( make_appointments_bl::_whole_list, $cats[ 2 ][ $agCatF_Id ], $app_types[ 2 ][ $appTypesF_Id ], 2 );
			$this->doAsserts_005( make_appointments_bl::_whole_list, $cats[ 3 ][ $agCatF_Id ], $app_types[ 3 ][ $appTypesF_Id ], 3 );
			$this->doAsserts_005( make_appointments_bl::_whole_list, make_appointments_bl::_whole_list, $app_types[ 4 ][ $appTypesF_Id ], 3 );


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped(); }
    }
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

    public function test_AgendasHaveNoAppsAndFreeTimes__AppTypStartEnd_21_18(){							//	_is_1_2_11
		if( ( self::_is_1_2_11 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]		= true;
			global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );

			self::createEnvironment_01();

			//	Appointment types
			$name	= 'UT App type ';
			$ini_app_type	= UT_utils::initAppTypeArray2(); $ind	= 0;
			$ini_app_type[ $appTypesF_PeriodStartTime ]	= '21:00';
			$ini_app_type[ $appTypesF_PeriodEndTime ]	= '18:00';


			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 0 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 0

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 1 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 1

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 2 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 2

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 3 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 3

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= NULL;
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 4



			$this->doAsserts_006( make_appointments_bl::_whole_list, $cats[ 0 ][ $agCatF_Id ], $app_types[ 0 ][ $appTypesF_Id ], 0 );
			$this->doAsserts_006( make_appointments_bl::_whole_list, $cats[ 1 ][ $agCatF_Id ], $app_types[ 1 ][ $appTypesF_Id ], 1 );
			$this->doAsserts_006( make_appointments_bl::_whole_list, $cats[ 2 ][ $agCatF_Id ], $app_types[ 2 ][ $appTypesF_Id ], 2 );
			$this->doAsserts_006( make_appointments_bl::_whole_list, $cats[ 3 ][ $agCatF_Id ], $app_types[ 3 ][ $appTypesF_Id ], 3 );
			$this->doAsserts_006( make_appointments_bl::_whole_list, make_appointments_bl::_whole_list, $app_types[ 4 ][ $appTypesF_Id ], 4 );

//				CATEGORIES
//				0	- agendas 1, 3			/ app type 0
//				1	- agendas 0, 1			/ app type 1
//				2	- agendas 0, 2			/ app type 2
//				3	- agendas 2, 3			/ app type 3
//								 			/ app type 4 No category
			$this->doAsserts_006( $agendas[ 0 ][ $agendasF_Id ], make_appointments_bl::_whole_list, $app_types[ 1 ][ $appTypesF_Id ], 5 );
			$this->doAsserts_006( $agendas[ 1 ][ $agendasF_Id ], make_appointments_bl::_whole_list, $app_types[ 0 ][ $appTypesF_Id ], 6 );
			$this->doAsserts_006( $agendas[ 2 ][ $agendasF_Id ], make_appointments_bl::_whole_list, $app_types[ 2 ][ $appTypesF_Id ], 7 );
			$this->doAsserts_006( $agendas[ 3 ][ $agendasF_Id ], make_appointments_bl::_whole_list, $app_types[ 3 ][ $appTypesF_Id ], 8 );


			$_SESSION[ 'is_skip' ]	= false;
		}else{ $this->markTestSkipped(); }
	}
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

    public function test_TtlHasNoAppsNoFreeTimes_Agenda_23_23(){										//	_is_1_2_12
         if( ( self::_is_1_2_12 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
            $_SESSION[ 'is_skip' ]		= true;
            global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );

			self::createEnvironment_01();
			$add_ags	= UT_utils::createAgendas_UT( 1, '23:00', '23:00' );//4
			$agendas[]	= $add_ags[ 0 ];

			//	Appointment types
			$name	= 'UT App type ';
			$ini_app_type	= UT_utils::initAppTypeArray2(); $ind	= 0;

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= NULL;
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 0


			$today = '10-08-2010'; $db_today	= date( 'Y-m-d', strtotime( $today ) );

			$curr_time	= '22:30';
			UT_utils::doMysqliReconnect( $this, 'test_AgendaHasAlmostBusy_23_23_TTL' );
            $month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 8, 2010, $app_types[ 0 ][ $appTypesF_Id ], $agendas[ 4 ][ $agendasF_Id ], make_appointments_bl::_whole_list, $today, $curr_time, true );
			foreach( $month_info as $db_date => $item ){
				$date	= date( 'd-m-Y', strtotime( $db_date ) );
				$condition	= ( $db_date < $db_today ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "Assert 1. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
			}

			$curr_time	= '22:45';
			UT_utils::doMysqliReconnect( $this, 'test_AgendaHasAlmostBusy_23_23_TTL' );
            $month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 8, 2010, $app_types[ 0 ][ $appTypesF_Id ], $agendas[ 4 ][ $agendasF_Id ], make_appointments_bl::_whole_list, $today, $curr_time );
			foreach( $month_info as $db_date => $item ){
				$date	= date( 'd-m-Y', strtotime( $db_date ) );
				$condition	= ( $db_date < $db_today ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "Assert 2. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
			}


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped(); }
    }
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

    public function test_TtlHasNoAppsNoFreeTimes_Agenda_08_08(){										//	_is_1_2_13
		if( ( self::_is_1_2_13 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]		= true;
			global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );

			//	Agendas
			$add_ags	= UT_utils::createAgendas_UT( 1, '08:00', '08:00' );//0
			$agendas[]	= $add_ags[ 0 ];

			$add_ags	= UT_utils::createAgendas_UT( 1, '08:00', '08:00' );//1
			$agendas[]	= $add_ags[ 0 ];

			//	App types
            $app_typs  = UT_utils::createAppTypes_UT_mod( UT_utils::_is_not_multi, 0 );
            $app_types	= array_merge( $app_types, $app_typs );

			//	Clients
			$clients	= UT_utils::createClients1_UT( 5 );

			$_SESSION[ 'valid_user_id' ]	= $agendas[ 0 ][ $agendasF_Id ];

			UT_utils::doMysqliReconnect( $this, 'test_TtlHasNoAppsNoFreeTimes_Agenda_08_08' );
			$month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 9, 2010, $app_types[ 0 ][ $appTypesF_Id ], $agendas[ 0 ][ $agendasF_Id ], make_appointments_bl::_whole_list, '01-09-2010', '12:00' );
            foreach( $month_info as $db_date => $val ){
            	$date	= date( 'd-m-Y', strtotime( $db_date ) );
            	$this->assertEquals( 1, $val, "Assert 1. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
            }


			$_SESSION[ 'is_skip' ]	= false;
		}else{ $this->markTestSkipped(); }
	}
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

    public function test_TtlHasEmpty30MinLineByAppsBeyondAppType_Agenda_07_16__AppTypeStartEnd_09_12(){	//	_is_1_2_14
		if( ( self::_is_1_2_14 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]		= true;
			global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );

			self::createEnvironment_01();

			//	Appointment types
			$name	= 'UT App type ';
			$ini_app_type	= UT_utils::initAppTypeArray2(); $ind	= 0;
	    	$ini_app_type[ $appTypesF_PeriodStartTime ]	= '09:00';
	    	$ini_app_type[ $appTypesF_PeriodEndTime ]	= '12:00';

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 0 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 0

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 1 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 1

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 2 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 2

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 3 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 3

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= NULL;
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 4

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;			//	NO constraints
	    	$ini_app_type[ $appTypesF_PeriodStartTime ]	= '00:00';
	    	$ini_app_type[ $appTypesF_PeriodEndTime ]	= '00:00';
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 5

			//	Clients
			$_SESSION[ 'valid_user_id' ]	= $agendas[ 1 ][ $agendasF_Id ];
			$date	= '11-10-2010'; $db_date	= date( 'Y-m-d', strtotime( $date ) );

			//	Appointments
			UT_utils::createBlockOfAppointments( $date, '07:00', '13:00', $_SESSION[ 'valid_user_id' ], $app_types[ 5 ] );
			//	13:00 - 14:00	free line
			UT_utils::createBlockOfAppointments( $date, '14:00', '16:00', $_SESSION[ 'valid_user_id' ], $app_types[ 5 ] );


			UT_utils::doMysqliReconnect( $this, 'test_TtlHasEmpty30MinLineByAppsBeyondAppType_Agenda_07_16__AppTypeStartEnd_09_12' );
			$month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 10, 2010, $app_types[ 1 ][ $appTypesF_Id ], $_SESSION[ 'valid_user_id' ], make_appointments_bl::_whole_list, '01-10-2010', '12:01' );
            foreach( $month_info as $db_date => $item ){
            	$date	= date( 'd-m-Y', strtotime( $db_date ) );
				$condition	= ( $db_date != '2010-10-01' && $db_date != '2010-10-11' ) ? ( 1 == $item ) : ( 0 == $item );
				$this->assertTrue( $condition, "Assert 1. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
            }


			$_SESSION[ 'is_skip' ]	= false;
		}else{ $this->markTestSkipped(); }
	}
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

    public function test_TtlHasEmpty30MinLineByAppsBeyondAppType_Agenda_08_08__AppTypeStartEnd_22_09(){	//	_is_1_2_15
		if( ( self::_is_1_2_15 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]		= true;
			global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );

			//	Agendas
			$add_ags	= UT_utils::createAgendas_UT( 1, '08:00', '08:00' );//0
			$agendas[]	= $add_ags[ 0 ];

			$add_ags	= UT_utils::createAgendas_UT( 1, '08:00', '08:00' );//1
			$agendas[]	= $add_ags[ 0 ];

			//	App types
            $app_typs  = UT_utils::createAppTypes_UT_mod( UT_utils::_is_not_multi, 0 );
            $app_types	= array_merge( $app_types, $app_typs );

            $app_typs  = UT_utils::createAppTypes_UT_mod( UT_utils::_is_not_multi, 0 );
            $app_types	= array_merge( $app_types, $app_typs );
			$app_type	= array(
				$appTypesF_Id			=> $app_types[ 1 ][ $appTypesF_Id ],
				$appTypesF_PeriodStartTime	=> '22:00',
				$appTypesF_PeriodEndTime	=> '09:00'
			);
			UT_utils::updateAppType( $app_type );

			//	Clients
			$clients	= UT_utils::createClients1_UT( 5 );


			$_SESSION[ 'valid_user_id' ]	= $agendas[ 0 ][ $agendasF_Id ];

			//	Appointments
			$date	= '13-09-2010'; $db_date	= date( 'Y-m-d', strtotime( $date ) );
			$ini_app	= array(
				'AgendaId'		=> $_SESSION[ 'valid_user_id' ],
				'appTypeSel'	=> $app_types[ 0 ][ $appTypesF_Id ],
				'agendas'		=> $_SESSION[ 'valid_user_id' ],
				'date'			=> $date,
				'time'			=> '00:00',
				'max_number'	=> 1,
				'client_id'		=> $clients[ 0 ][ $clientsF_Id ],
				'comment'		=> "This appointment was created for Unit Tests on ".utils_bl::GetTodayDate()."."
			);

			$time	= '08:00'; $last_time = '12:00';

			$d_t = $d_t_start = $db_date.' 08:00:00';
			$d_t_last	= $db_date.' 12:00:00';
			while( $d_t < $d_t_last ){
				$ini_app[ 'time' ]	= date( 'H:i', strtotime( $d_t ) );
				$ini_app[ 'date' ]	= date( 'd-m-Y', strtotime( $d_t ) );
				$app	= appointments_bl::prepareAppDataToSave( $ini_app );
				UT_utils::addAppointment2_mod_UT( $app, $apps );
				$d_t	= date( 'Y-m-d H:i:s', strtotime( '+'.$app_types[ 0 ][ $appTypesF_Time ].' minute', strtotime( $d_t ) ) );
			}
			//	12:00 - 13:00	free line
			$d_t	= date( 'Y-m-d H:i:s', strtotime( '+60 minute', strtotime( $d_t ) ) );
			$d_t_last	= date( 'Y-m-d H:i:s', strtotime( '+1 day', strtotime( $d_t_start ) ) );
			while( $d_t < $d_t_last ){
				$ini_app[ 'time' ]	= date( 'H:i', strtotime( $d_t ) );
				$ini_app[ 'date' ]	= date( 'd-m-Y', strtotime( $d_t ) );
				$app	= appointments_bl::prepareAppDataToSave( $ini_app );
				UT_utils::addAppointment2_mod_UT( $app, $apps );
				$d_t	= date( 'Y-m-d H:i:s', strtotime( '+'.$app_types[ 0 ][ $appTypesF_Time ].' minute', strtotime( $d_t ) ) );
			}

			UT_utils::doMysqliReconnect( $this, 'test_TtlHasEmpty30MinLineByAppsBeyondAppType_Agenda_08_08__AppTypeStartEnd_22_09' );
			$month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 9, 2010, $app_types[ 1 ][ $appTypesF_Id ], $_SESSION[ 'valid_user_id' ], make_appointments_bl::_whole_list, '01-09-2010', '12:00' );
            foreach( $month_info as $db_date => $item ){
            	$date	= date( 'd-m-Y', strtotime( $db_date ) );
				$condition	= ( $db_date != '2010-09-13' ) ? ( 1 == $item ) : ( 0 == $item );
				$this->assertTrue( $condition, "Assert 1. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
            }


			$_SESSION[ 'is_skip' ]	= false;
		}else{ $this->markTestSkipped(); }
	}
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

    public function test_TtlHasEmpty30MinLineByAppsBeyondAppType_Agenda_00_00__AppTypeStartEnd_22_09(){	//	_is_1_2_16
		if( ( self::_is_1_2_16 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]		= true;
			global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );

			//	Agendas
			$add_ags	= UT_utils::createAgendas_UT( 1, '00:00', '00:00' );//0
			$agendas[]	= $add_ags[ 0 ];

			$add_ags	= UT_utils::createAgendas_UT( 1, '00:00', '00:00' );//1
			$agendas[]	= $add_ags[ 0 ];

			//	App types
            $app_typs  = UT_utils::createAppTypes_UT_mod( UT_utils::_is_not_multi, 0 );
            $app_types	= array_merge( $app_types, $app_typs );

            $app_typs  = UT_utils::createAppTypes_UT_mod( UT_utils::_is_not_multi, 0 );
            $app_types	= array_merge( $app_types, $app_typs );
			$app_type	= array(
				$appTypesF_Id			=> $app_types[ 1 ][ $appTypesF_Id ],
				$appTypesF_PeriodStartTime	=> '22:00',
				$appTypesF_PeriodEndTime	=> '09:00'
			);
			UT_utils::updateAppType( $app_type );

			//	Clients
			$clients	= UT_utils::createClients1_UT( 5 );


			$_SESSION[ 'valid_user_id' ]	= $agendas[ 0 ][ $agendasF_Id ];

			//	Appointments
			$date	= '13-09-2010'; $db_date	= date( 'Y-m-d', strtotime( $date ) );
			$ini_app	= array(
				'AgendaId'		=> $_SESSION[ 'valid_user_id' ],
				'appTypeSel'	=> $app_types[ 0 ][ $appTypesF_Id ],
				'agendas'		=> $_SESSION[ 'valid_user_id' ],
				'date'			=> $date,
				'time'			=> '00:00',
				'max_number'	=> 1,
				'client_id'		=> $clients[ 0 ][ $clientsF_Id ],
				'comment'		=> "This appointment was created for Unit Tests on ".utils_bl::GetTodayDate()."."
			);

			$d_t = $d_t_start = $db_date.' 00:00:00';
			$d_t_last	= $db_date.' 12:00:00';
			while( $d_t < $d_t_last ){
				$ini_app[ 'time' ]	= date( 'H:i', strtotime( $d_t ) );
				$ini_app[ 'date' ]	= date( 'd-m-Y', strtotime( $d_t ) );
				$app	= appointments_bl::prepareAppDataToSave( $ini_app );
				UT_utils::addAppointment2_mod_UT( $app, $apps );
				$d_t	= date( 'Y-m-d H:i:s', strtotime( '+'.$app_types[ 0 ][ $appTypesF_Time ].' minute', strtotime( $d_t ) ) );
			}

			$d_t	= date( 'Y-m-d H:i:s', strtotime( '+60 minute', strtotime( $d_t ) ) );
			$d_t_last	= date( 'Y-m-d H:i:s', strtotime( '+1 day', strtotime( $d_t_start ) ) );
			while( $d_t < $d_t_last ){
				$ini_app[ 'time' ]	= date( 'H:i', strtotime( $d_t ) );
				$ini_app[ 'date' ]	= date( 'd-m-Y', strtotime( $d_t ) );
				$app	= appointments_bl::prepareAppDataToSave( $ini_app );
				UT_utils::addAppointment2_mod_UT( $app, $apps );
				$d_t	= date( 'Y-m-d H:i:s', strtotime( '+'.$app_types[ 0 ][ $appTypesF_Time ].' minute', strtotime( $d_t ) ) );
			}

			$ags	= array( $agendas[ 0 ] );

			UT_utils::doMysqliReconnect( $this, 'test_AgendaHasAlmostBusy_23_23_TTL' );
			$month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 9, 2010, $app_types[ 1 ][ $appTypesF_Id ], $_SESSION[ 'valid_user_id' ], make_appointments_bl::_whole_list, '01-09-2010', '12:00' );
            foreach( $month_info as $db_date => $item ){
            	$date	= date( 'd-m-Y', strtotime( $db_date ) );
				$condition	= ( $db_date != '2010-09-13' ) ? ( 1 == $item ) : ( 0 == $item );
				$this->assertTrue( $condition, "Assert 1. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
            }


			$_SESSION[ 'is_skip' ]	= false;
		}else{ $this->markTestSkipped(); }
	}
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

    public function test_TtlHasEmpty30MinLineByAppsBeyondAppType_Agenda_08_08__AppTypeStartEnd_06_09(){	//	_is_1_2_17
		if( ( self::_is_1_2_17 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]		= true;
			global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );

			//	Agendas
			$add_ags	= UT_utils::createAgendas_UT( 1, '08:00', '08:00' );//0
			$agendas[]	= $add_ags[ 0 ];

			$add_ags	= UT_utils::createAgendas_UT( 1, '08:00', '08:00' );//1
			$agendas[]	= $add_ags[ 0 ];

			//	App types
            $app_typs  = UT_utils::createAppTypes_UT_mod( UT_utils::_is_not_multi, 0 );
            $app_types	= array_merge( $app_types, $app_typs );

            $app_typs  = UT_utils::createAppTypes_UT_mod( UT_utils::_is_not_multi, 0 );
            $app_types	= array_merge( $app_types, $app_typs );
			$app_type	= array(
				$appTypesF_Id			=> $app_types[ 1 ][ $appTypesF_Id ],
				$appTypesF_PeriodStartTime	=> '06:00',
				$appTypesF_PeriodEndTime	=> '09:00'
			);
			UT_utils::updateAppType( $app_type );

			//	Clients
			$clients	= UT_utils::createClients1_UT( 5 );


			$_SESSION[ 'valid_user_id' ]	= $agendas[ 0 ][ $agendasF_Id ];

			//	Appointments
			$date	= '13-09-2010'; $db_date	= date( 'Y-m-d', strtotime( $date ) );
			$ini_app	= array(
				'AgendaId'		=> $_SESSION[ 'valid_user_id' ],
				'appTypeSel'	=> $app_types[ 0 ][ $appTypesF_Id ],
				'agendas'		=> $_SESSION[ 'valid_user_id' ],
				'date'			=> $date,
				'time'			=> '00:00',
				'max_number'	=> 1,
				'client_id'		=> $clients[ 0 ][ $clientsF_Id ],
				'comment'		=> "This appointment was created for Unit Tests on ".utils_bl::GetTodayDate()."."
			);

			$time	= '08:00'; $last_time = '12:00';

			$d_t = $d_t_start = $db_date.' 08:00:00';
			$d_t_last	= $db_date.' 12:00:00';
			while( $d_t < $d_t_last ){
				$ini_app[ 'time' ]	= date( 'H:i', strtotime( $d_t ) );
				$ini_app[ 'date' ]	= date( 'd-m-Y', strtotime( $d_t ) );
				$app	= appointments_bl::prepareAppDataToSave( $ini_app );
				UT_utils::addAppointment2_mod_UT( $app, $apps );
				$d_t	= date( 'Y-m-d H:i:s', strtotime( '+'.$app_types[ 0 ][ $appTypesF_Time ].' minute', strtotime( $d_t ) ) );
			}
			//	12:00 - 13:00	free line
			$d_t	= date( 'Y-m-d H:i:s', strtotime( '+60 minute', strtotime( $d_t ) ) );
			$d_t_last	= date( 'Y-m-d H:i:s', strtotime( '+1 day', strtotime( $d_t_start ) ) );
			while( $d_t < $d_t_last ){
				$ini_app[ 'time' ]	= date( 'H:i', strtotime( $d_t ) );
				$ini_app[ 'date' ]	= date( 'd-m-Y', strtotime( $d_t ) );
				$app	= appointments_bl::prepareAppDataToSave( $ini_app );
				UT_utils::addAppointment2_mod_UT( $app, $apps );
				$d_t	= date( 'Y-m-d H:i:s', strtotime( '+'.$app_types[ 0 ][ $appTypesF_Time ].' minute', strtotime( $d_t ) ) );
			}

			UT_utils::doMysqliReconnect( $this, 'test_AgendaHasAlmostBusy_23_23_TTL' );
			$month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 9, 2010, $app_types[ 1 ][ $appTypesF_Id ], $_SESSION[ 'valid_user_id' ], make_appointments_bl::_whole_list, '01-09-2010', '12:00', true );
            foreach( $month_info as $db_date => $item ){
            	$date	= date( 'd-m-Y', strtotime( $db_date ) );
				$condition	= ( $db_date != '2010-09-13' ) ? ( 1 == $item ) : ( 0 == $item );
				$this->assertTrue( $condition, "Assert 1. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
            }


			$_SESSION[ 'is_skip' ]	= false;
		}else{ $this->markTestSkipped(); }
	}
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

    public function test_TtlHasEmpty30MinLineByAppsBeyondAppType_Agenda_08_08__AppTypeStartEnd_08_10(){	//	_is_1_2_18
		if( ( self::_is_1_2_18 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]		= true;
			global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );

			//	Agendas
			$add_ags	= UT_utils::createAgendas_UT( 1, '08:00', '08:00' );//0
			$agendas[]	= $add_ags[ 0 ];

			$add_ags	= UT_utils::createAgendas_UT( 1, '08:00', '08:00' );//1
			$agendas[]	= $add_ags[ 0 ];

			//	App types
            $app_typs  = UT_utils::createAppTypes_UT_mod( UT_utils::_is_not_multi, 0 );
            $app_types	= array_merge( $app_types, $app_typs );

            $app_typs  = UT_utils::createAppTypes_UT_mod( UT_utils::_is_not_multi, 0 );
            $app_types	= array_merge( $app_types, $app_typs );
			$app_type	= array(
				$appTypesF_Id			=> $app_types[ 1 ][ $appTypesF_Id ],
				$appTypesF_PeriodStartTime	=> '08:00',
				$appTypesF_PeriodEndTime	=> '10:00'
			);
			UT_utils::updateAppType( $app_type );

			//	Clients
			$clients	= UT_utils::createClients1_UT( 5 );


			$_SESSION[ 'valid_user_id' ]	= $agendas[ 0 ][ $agendasF_Id ];

			//	Appointments
			$date	= '13-09-2010'; $db_date	= date( 'Y-m-d', strtotime( $date ) );
			$ini_app	= array(
				'AgendaId'		=> $_SESSION[ 'valid_user_id' ],
				'appTypeSel'	=> $app_types[ 0 ][ $appTypesF_Id ],
				'agendas'		=> $_SESSION[ 'valid_user_id' ],
				'date'			=> $date,
				'time'			=> '00:00',
				'max_number'	=> 1,
				'client_id'		=> $clients[ 0 ][ $clientsF_Id ],
				'comment'		=> "This appointment was created for Unit Tests on ".utils_bl::GetTodayDate()."."
			);

			$time	= '08:00'; $last_time = '12:00';

			$d_t = $d_t_start = $db_date.' 08:00:00';
			$d_t_last	= $db_date.' 12:00:00';
			while( $d_t < $d_t_last ){
				$ini_app[ 'time' ]	= date( 'H:i', strtotime( $d_t ) );
				$ini_app[ 'date' ]	= date( 'd-m-Y', strtotime( $d_t ) );
				$app	= appointments_bl::prepareAppDataToSave( $ini_app );
				UT_utils::addAppointment2_mod_UT( $app, $apps );
				$d_t	= date( 'Y-m-d H:i:s', strtotime( '+'.$app_types[ 0 ][ $appTypesF_Time ].' minute', strtotime( $d_t ) ) );
			}
			//	12:00 - 13:00	free line
			$d_t	= date( 'Y-m-d H:i:s', strtotime( '+60 minute', strtotime( $d_t ) ) );
			$d_t_last	= date( 'Y-m-d H:i:s', strtotime( '+1 day', strtotime( $d_t_start ) ) );

//echo "\n$d_t_last \ ";
//$d_t_last	= date( 'Y-m-d H:i:s', strtotime( '+1 hour', strtotime( $d_t_last ) ) );
//echo "$d_t_last\n";

			while( $d_t < $d_t_last ){
				$ini_app[ 'time' ]	= date( 'H:i', strtotime( $d_t ) );
				$ini_app[ 'date' ]	= date( 'd-m-Y', strtotime( $d_t ) );
				$app	= appointments_bl::prepareAppDataToSave( $ini_app );
				UT_utils::addAppointment2_mod_UT( $app, $apps );
				$d_t	= date( 'Y-m-d H:i:s', strtotime( '+'.$app_types[ 0 ][ $appTypesF_Time ].' minute', strtotime( $d_t ) ) );
			}

			UT_utils::doMysqliReconnect( $this, 'test_TtlHasEmpty30MinLineByAppsBeyondAppType_Agenda_08_08__AppTypeStartEnd_08_10' );
			$month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 9, 2010, $app_types[ 1 ][ $appTypesF_Id ], $_SESSION[ 'valid_user_id' ], make_appointments_bl::_whole_list, '01-09-2010', '12:00', true );
            foreach( $month_info as $db_date => $item ){
            	$date	= date( 'd-m-Y', strtotime( $db_date ) );
				$condition	= ( $db_date != '2010-09-01' && $db_date != '2010-09-13' ) ? ( 1 == $item ) : ( 0 == $item );
				$this->assertTrue( $condition, "Assert 1. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
            }


			$_SESSION[ 'is_skip' ]	= false;
		}else{ $this->markTestSkipped(); }
	}
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

    public function test_TtlHasEmpty30MinLineByAppsBeyondAppType_Agenda_08_08__AppTypeStartEnd_09_11(){	//	_is_1_2_19
		if( ( self::_is_1_2_19 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]		= true;
			global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );

			//	Agendas
			$add_ags	= UT_utils::createAgendas_UT( 1, '08:00', '08:00' );//0
			$agendas[]	= $add_ags[ 0 ];

			$add_ags	= UT_utils::createAgendas_UT( 1, '08:00', '08:00' );//1
			$agendas[]	= $add_ags[ 0 ];

			//	App types
            $app_typs  = UT_utils::createAppTypes_UT_mod( UT_utils::_is_not_multi, 0 );
            $app_types	= array_merge( $app_types, $app_typs );

            $app_typs  = UT_utils::createAppTypes_UT_mod( UT_utils::_is_not_multi, 0 );
            $app_types	= array_merge( $app_types, $app_typs );
			$app_type	= array(
				$appTypesF_Id			=> $app_types[ 1 ][ $appTypesF_Id ],
				$appTypesF_PeriodStartTime	=> '09:00',
				$appTypesF_PeriodEndTime	=> '11:00'
			);
			UT_utils::updateAppType( $app_type );

			//	Clients
			$clients	= UT_utils::createClients1_UT( 5 );


			$_SESSION[ 'valid_user_id' ]	= $agendas[ 0 ][ $agendasF_Id ];

			//	Appointments
			$date	= '13-09-2010'; $db_date	= date( 'Y-m-d', strtotime( $date ) );
			$ini_app	= array(
				'AgendaId'		=> $_SESSION[ 'valid_user_id' ],
				'appTypeSel'	=> $app_types[ 0 ][ $appTypesF_Id ],
				'agendas'		=> $_SESSION[ 'valid_user_id' ],
				'date'			=> $date,
				'time'			=> '00:00',
				'max_number'	=> 1,
				'client_id'		=> $clients[ 0 ][ $clientsF_Id ],
				'comment'		=> "This appointment was created for Unit Tests on ".utils_bl::GetTodayDate()."."
			);

			$time	= '08:00'; $last_time = '12:00';

			$d_t = $d_t_start = $db_date.' 08:00:00';
			$d_t_last	= $db_date.' 12:00:00';
			while( $d_t < $d_t_last ){
				$ini_app[ 'time' ]	= date( 'H:i', strtotime( $d_t ) );
				$ini_app[ 'date' ]	= date( 'd-m-Y', strtotime( $d_t ) );
				$app	= appointments_bl::prepareAppDataToSave( $ini_app );
				UT_utils::addAppointment2_mod_UT( $app, $apps );
				$d_t	= date( 'Y-m-d H:i:s', strtotime( '+'.$app_types[ 0 ][ $appTypesF_Time ].' minute', strtotime( $d_t ) ) );
			}
			//	12:00 - 13:00	free line
			$d_t	= date( 'Y-m-d H:i:s', strtotime( '+60 minute', strtotime( $d_t ) ) );
			$d_t_last	= date( 'Y-m-d H:i:s', strtotime( '+1 day', strtotime( $d_t_start ) ) );
			while( $d_t < $d_t_last ){
				$ini_app[ 'time' ]	= date( 'H:i', strtotime( $d_t ) );
				$ini_app[ 'date' ]	= date( 'd-m-Y', strtotime( $d_t ) );
				$app	= appointments_bl::prepareAppDataToSave( $ini_app );
				UT_utils::addAppointment2_mod_UT( $app, $apps );
				$d_t	= date( 'Y-m-d H:i:s', strtotime( '+'.$app_types[ 0 ][ $appTypesF_Time ].' minute', strtotime( $d_t ) ) );
			}

			UT_utils::doMysqliReconnect( $this, 'test_AgendaHasAlmostBusy_23_23_TTL' );
			$month_info	= make_appointments_bl::getAvailableDaysForMonth_24( 9, 2010, $app_types[ 1 ][ $appTypesF_Id ], $_SESSION[ 'valid_user_id' ], make_appointments_bl::_whole_list, '01-09-2010', '12:00' );
            foreach( $month_info as $db_date => $item ){
            	$date	= date( 'd-m-Y', strtotime( $db_date ) );
				$condition	= ( $db_date != '2010-09-01' && $db_date != '2010-09-13' ) ? ( 1 == $item ) : ( 0 == $item );
				$this->assertTrue( $condition, "Assert 1. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
            }


			$_SESSION[ 'is_skip' ]	= false;
		}else{ $this->markTestSkipped(); }
	}
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

    public function test_AgendaHasOffDays_DailyPeriods(){												//	_is_1_2_20
		if( ( self::_is_1_2_20 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]		= true;
			global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );


            self::createEnvironment_01();

			//	Appointment types
			$name	= 'UT App type ';
			$ini_app_type	= UT_utils::initAppTypeArray2(); $ind	= 0;

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 0 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 0

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 1 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 1

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 2 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 2

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 3 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 3

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= NULL;
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 4

			$_SESSION[ 'valid_user_id' ]	= $agendas[ 1 ][ $agendasF_Id ];// 07:00 - 16:00

			$today	= '07-01-2010'; $curr_time = '12:00'; $db_today = utils_bl::GetDbDate( $today );

			$start_date = '01-01-2010';										$mk_start	= strtotime( $start_date );
//			$end_date	= ( self::_is_fast ) ? '20-06-2010' : '20-12-2012';	$mk_end		= strtotime( $end_date );
			$end_date	= '20-12-2012';	$mk_end		= strtotime( $end_date );

			$new_blk	= UT_utils::initBlkArray();
			$new_blk[ $daysOffF_AgendaId ]	= $_SESSION[ 'valid_user_id' ];// 07:00 - 16:00
			$new_blk[ $dbTable_StartDate ]	= $start_date;
			$new_blk[ $dbTable_EndDate ]	= $end_date;
			unset( $new_blk[ $dbTable_StartTime ] );
			unset( $new_blk[ $dbTable_EndTime ] );
			$new_blk[ 'PATTERN_ID' ]				= 1;
			$new_blk[ $daysoffPatternF_Cycle ]		= BlockDaysBL::_daily_cycle;
			$new_blk[ $daysoffPatternF_Period ]		= 1;
			$new_blk[ $daysoffPatternF_WeekDays ]	= 0;

			$blk_id 	= BlockDaysBL::insertDayoffRec( $new_blk );
			$blk_data	= BlockDaysBL::getDayoffById( $blk_id );

			$add_info	= "";

            $mk_s		= $mk_start;
            $mk_e		= strtotime( '+3 month', $mk_end );
            $mk_months	= array();
            while( $mk_s <= $mk_e ){
            	$mk_months[]	= $mk_s;
            	$mk_s	= strtotime( '+1 month', $mk_s );
            }

			for( $add_days = 0; $add_days < 7; $add_days++ ){
				$new_blk[ $dbTable_StartDate ]	= date( 'd-m-Y', strtotime( '+'.$add_days.' DAY', $mk_start ) );

				for( $period = 2; $period <= 5; $period++ ){
					$new_blk[ $daysoffPatternF_Period ]		= $period;
					$add_info	= "Period: $period. Date start: ".$new_blk[ $dbTable_StartDate ].", end: ".$new_blk[ $dbTable_EndDate ].".";

	//	Blk update
					UT_utils::updateDayOff( $blk_id, $blk_data[ 'PATTERN_ID' ], $new_blk );
					foreach( $mk_months as $mk_month ){
						$month	= intval( date( 'n', $mk_month ) );
						$year	= intval( date( 'Y', $mk_month ) );

//						UT_utils::doMysqliReconnect( $this, 'test_AgendaHasOffDays_AllWeekPatterns' );
			            $month_info	= make_appointments_bl::getAvailableDaysForMonth_24( $month, $year, $app_types[ 4 ][ $appTypesF_Id ], $_SESSION[ 'valid_user_id' ], make_appointments_bl::_whole_list, $today, $curr_time );
			            $n_items	= count( $month_info );
			            $exp_n_days	= date( 't', $mk_month );

						$this->assertEquals( $exp_n_days, $n_items, "\n*****Assert 1. *****".
							"\nWrong quantity of items were found for month: $month-$year. Must be: $exp_n_days, actually: ".$n_items.
							"\nReceived data:\n".print_r(  $month_info, true ) );

						$month_exp	= UT_utils::findFreeDaysOfMonthByDayOffItem( $mk_month, $new_blk );

						foreach( $month_info as $db_date => $item ){
							$date	= date( 'd-m-Y', strtotime( $db_date ) );
							$is_blk	= !array_key_exists( $db_date, $month_exp );

							$condition	= ( $is_blk || $db_date < $db_today ) ? ( 0 == $item ) : ( 1 == $item );
							$this->assertTrue( $condition, "Assert 2. Wrong value was found on date: ".$date.
												"/".utils_bl::getWeekDayByDate( $date )."/Today date is $today".
												"/\n$add_info\n\nReceived data:\n".print_r(  $month_info, true ) );
						}
					}
				}
			}


			$_SESSION[ 'is_skip' ]	= false;
		}else{ $this->markTestSkipped(); }
	}
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

    public function test_AgendaHasOffDays_DailyPeriods_CombiWithNoPatterns(){							//	_is_1_2_21
		if( ( self::_is_1_2_21 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]		= true;
			global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );
			global $author; $author = "C.Kolenchenko (ckolenchenko@yukon.cv.ua)";


            self::createEnvironment_01();

			//	Appointment types
			$name	= 'UT App type ';
			$ini_app_type	= UT_utils::initAppTypeArray2(); $ind	= 0;

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 0 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 0

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 1 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 1

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 2 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 2

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 3 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 3

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= NULL;
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 4

			$_SESSION[ 'valid_user_id' ]	= $agendas[ 1 ][ $agendasF_Id ];// 07:00 - 16:00

			$today	= '07-01-2010'; $curr_time = '12:00'; $db_today = utils_bl::GetDbDate( $today );

			$start_date = '01-01-2010';	$mk_start	= strtotime( $start_date );
			$end_date	= '20-12-2012';	$mk_end		= strtotime( $end_date );

			$new_blk	= UT_utils::initBlkArray();
			unset( $new_blk[ $dbTable_StartTime ] );
			unset( $new_blk[ $dbTable_EndTime ] );
			$new_blk[ $daysOffF_AgendaId ]	= $_SESSION[ 'valid_user_id' ];// 07:00 - 16:00
			$new_blk[ 'PATTERN_ID' ]				= 0;

            $mk_s		= $mk_start;
            $mk_e		= strtotime( '+3 month', $mk_end );
            $mk_months	= array();
            while( $mk_s <= $mk_e ){
            	$mk_months[]	= $mk_s;
            	$month	= date( 'm', $mk_s );
            	$year	= date( 'Y', $mk_s );
            	$ind	= $year.$month;

				$new_blk[ $dbTable_StartDate ]	= '10-'.$month.'-'.$year;
				$new_blk[ $dbTable_EndDate ]	= '20-'.$month.'-'.$year;
				$blk_id 	= BlockDaysBL::insertDayoffRec( $new_blk );
				$off_days[ $ind ]	= BlockDaysBL::getDayoffById( $blk_id );
				unset( $off_days[ $ind ][ 'PATTERN_ID' ] );
				unset( $off_days[ $ind ][ $daysoffPatternF_Cycle ] );
				unset( $off_days[ $ind ][ $daysoffPatternF_Period ] );
				unset( $off_days[ $ind ][ $daysoffPatternF_WeekDays ] );
            	$mk_s	= strtotime( '+1 month', $mk_s );
            }

            $new_blk[ $dbTable_StartDate ]	= $start_date;
			$new_blk[ $dbTable_EndDate ]	= $end_date;
			$new_blk[ 'PATTERN_ID' ]				= 1;
			$new_blk[ $daysoffPatternF_Cycle ]		= BlockDaysBL::_daily_cycle;
			$new_blk[ $daysoffPatternF_Period ]		= 1;
			$new_blk[ $daysoffPatternF_WeekDays ]	= 0;
			$blk_id 	= BlockDaysBL::insertDayoffRec( $new_blk );
			$blk_data	= BlockDaysBL::getDayoffById( $blk_id );

			$add_info	= "";

			for( $add_days = 0; $add_days < 7; $add_days++ ){
				$new_blk[ $dbTable_StartDate ]	= date( 'd-m-Y', strtotime( '+'.$add_days.' DAY', $mk_start ) );

				for( $period = 2; $period <= 5; $period++ ){
					$new_blk[ $daysoffPatternF_Period ]		= $period;
					$add_info	= "Period: $period. Date start: ".$new_blk[ $dbTable_StartDate ].", end: ".$new_blk[ $dbTable_EndDate ].".";

	//	Blk update
					UT_utils::updateDayOff( $blk_id, $blk_data[ 'PATTERN_ID' ], $new_blk );
					foreach( $mk_months as $mk_month ){
						$month	= intval( date( 'n', $mk_month ) );
						$s_month	= date( 'm', $mk_month );
						$year	= intval( date( 'Y', $mk_month ) );

//						UT_utils::doMysqliReconnect( $this, 'test_AgendaHasOffDays_AllWeekPatterns' );
			            $month_info	= make_appointments_bl::getAvailableDaysForMonth_24( $month, $year, $app_types[ 4 ][ $appTypesF_Id ], $_SESSION[ 'valid_user_id' ], make_appointments_bl::_whole_list, $today, $curr_time );
			            $n_items	= count( $month_info );
			            $exp_n_days	= date( 't', $mk_month );

						$this->assertEquals( $exp_n_days, $n_items, "\n*****Assert 1. *****".
							"\nWrong quantity of items were found for month: $month-$year. Must be: $exp_n_days, actually: ".$n_items.
							"\nReceived data:\n".print_r(  $month_info, true ) );

						$month_exp1	= UT_utils::findFreeDaysOfMonthByDayOffItem( $mk_month, $new_blk );
						$ind	= $year.$s_month;
						$month_exp2	= UT_utils::findFreeDaysOfMonthByDayOffItem( $mk_month, $off_days[ $ind ] );
						$month_exp	= array_intersect_key( $month_exp1, $month_exp2 );

						foreach( $month_info as $db_date => $item ){
							$date	= date( 'd-m-Y', strtotime( $db_date ) );
							$is_blk	= !array_key_exists( $db_date, $month_exp );

							$condition	= ( $is_blk || $db_date < $db_today ) ? ( 0 == $item ) : ( 1 == $item );
							$this->assertTrue( $condition, "Assert 2. Wrong value was found on date: ".$date.
												"/".utils_bl::getWeekDayByDate( $date )."/Today date is $today".
												"/\n$add_info\n\nReceived data:\n".print_r(  $month_info, true ) );
						}
					}
				}
			}


			$_SESSION[ 'is_skip' ]	= false;
		}else{ $this->markTestSkipped(); }
	}
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

    public function test_AgendaHasFreeTimes_AllWeekPatterns(){ 											//	_is_1_2_22	Long duration ( > 20 min)
         if( ( self::_is_1_2_22 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
            $_SESSION[ 'is_skip' ]	= true;
            global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );

            self::createEnvironment_01();

			//	Appointment types
			$name	= 'UT App type ';
			$ini_app_type	= UT_utils::initAppTypeArray2(); $ind	= 0;

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 0 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 0

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 1 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 1

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 2 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 2

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 3 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 3

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= NULL;
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 4


			$start_date = '01-01-2011';										$mk_start	= strtotime( $start_date );
			$end_date	= ( self::_is_fast ) ? '20-06-2011' : '20-03-2012';	$mk_end		= strtotime( $end_date );

			//	Free time items
			$new_blk	= UT_utils::initBlkArray();
			$new_blk[ $daysOffF_AgendaId ]	= $agendas[ 1 ][ $agendasF_Id ];// 07:00 - 16:00
			$new_blk[ $dbTable_StartDate ]	= $start_date;
			$new_blk[ $dbTable_EndDate ]	= date( 'd-m-Y', strtotime( '-1 month', $mk_end ) );
			$new_blk[ 'PATTERN_ID' ]				= 0;
			$new_blk[ $daysoffPatternF_Cycle ]		= BlockDaysBL::_weekly_cycle;
			$new_blk[ $daysoffPatternF_Period ]		= 1;
			$new_blk[ $daysoffPatternF_WeekDays ]	= 0;


			$new_blk[ $dbTable_StartTime ]	= '07:00';
			$new_blk[ $dbTable_EndTime ]	= '12:00';
			$blk_id 		= BlockDaysBL::insertFreeTimeRec( $new_blk );
			$free_times[]	= BlockDaysBL::getFreeTimeById( $blk_id );

			$new_blk[ $dbTable_StartTime ]	= '13:00';
			$new_blk[ $dbTable_EndTime ]	= '16:00';
			$blk_id 		= BlockDaysBL::insertFreeTimeRec( $new_blk );
			$free_times[]	= BlockDaysBL::getFreeTimeById( $blk_id );

			$new_blk[ $dbTable_StartTime ]	= '12:00';
			$new_blk[ $dbTable_EndTime ]	= '13:00';
			$new_blk[ $dbTable_EndDate ]	= $end_date;
			$new_blk[ 'PATTERN_ID' ]				= 1;
			$new_blk[ $daysoffPatternF_Cycle ]		= BlockDaysBL::_weekly_cycle;
			$new_blk[ $daysoffPatternF_Period ]		= 1;
			$new_blk[ $daysoffPatternF_WeekDays ]	= 0;

			$blk_id 	= BlockDaysBL::insertFreeTimeRec( $new_blk );
			$blk_data	= BlockDaysBL::getFreeTimeById( $blk_id );

			$add_info	= "";

            $mk_s		= $mk_start;
            $mk_e		= strtotime( '+1 month', $mk_end );
            $mk_months	= array();
            while( $mk_s <= $mk_e ){
            	$mk_months[]	= $mk_s;
            	$mk_s	= strtotime( '+1 month', $mk_s );
            }

			$tst_cnt	= 0;
			for( $add_days = 0; $add_days < 7; $add_days++ ){
				$new_blk[ $dbTable_StartDate ]	= date( 'd-m-Y', strtotime( '+'.$add_days.' day', $mk_start ) );

				$today	= '07-01-2011'; $db_today = date( 'Y-m-d', strtotime( $today ) );
				$curr_time = '12:00';

//				$today	= '30-12-2010'; $curr_time = '12:00';
//				for( $tdd = 0; $tdd < 16; $tdd = $tdd + 4 ){//4
//					$mk_tdd	= strtotime( $today );
//					$today	= date( 'd-m-Y', strtotime( '+'.$tdd.' day', $mk_tdd ) );
//					$db_today	= date( 'Y-m-d', strtotime( '+'.$tdd.' day', $mk_tdd ) );

		            for( $period = 1; $period <= 3; $period++ ){
		            	$new_blk[ $daysoffPatternF_Period ]		= $period;

		            	for( $pattern = 1; $pattern < 127; $pattern++ ){
							$new_blk[ $daysoffPatternF_WeekDays ]	= $pattern;
							$add_info	= "Week days: $pattern, period: $period. Date start: ".$new_blk[ $dbTable_StartDate ].", end: ".$new_blk[ $dbTable_EndDate ].".";
							( self::_is_fast ) ? $add_info .= "\nLimited by ".self::_n_max_iters." iterations. Iteration number is $tst_cnt.":'';
			//	Blk update
							UT_utils::updateFreeTime( $blk_id, $blk_data[ 'PATTERN_ID' ], $new_blk );
//
							foreach( $mk_months as $mk_month ){
								$month	= intval( date( 'n', $mk_month ) );
								$year	= intval( date( 'Y', $mk_month ) );
//			//	Get month info
//	//							UT_utils::doMysqliReconnect( $this, 'test_AgendaHasOffDays_AllWeekPatterns' );
								$month_info	= make_appointments_bl::getAvailableDaysForMonth_24( $month, $year, $app_types[ 4 ][ $appTypesF_Id ], $agendas[ 1 ][ $agendasF_Id ], make_appointments_bl::_whole_list, $today, $curr_time, true );
//
								$month_exp0	= UT_utils::findFreeDaysOfMonthByDayOffItem( $mk_month, $free_times[ 0 ] );
								$month_exp1	= UT_utils::findFreeDaysOfMonthByDayOffItem( $mk_month, $free_times[ 1 ] );
								$month_exp	= UT_utils::findFreeDaysOfMonthByDayOffItem( $mk_month, $new_blk );
								$month_exp	= array_merge( $month_exp0, $month_exp1, $month_exp );

								foreach( $month_info as $db_date => $item ){
									$date	= date( 'd-m-Y', strtotime( $db_date ) );
									$is_blk	= !array_key_exists( $db_date, $month_exp );

									$condition	= ( $is_blk || $db_date < $db_today ) ? ( 0 == $item ) : ( 1 == $item );
									$this->assertTrue( $condition, "Assert 1. Wrong value was found on date: ".$date.
														"/".utils_bl::getWeekDayByDate( $date )."/Today date is $today".
														"/\n$add_info\n\nReceived data:\n".print_r(  $month_info, true ) );
								}
							}
																		$tst_cnt++;
																		if( self::_is_fast && ( $tst_cnt > self::_n_max_iters ) ){ break; }
						}
																		if( self::_is_fast && ( $tst_cnt > self::_n_max_iters ) ){ break; }
					}
//																		if( self::_is_fast && ( $tst_cnt > self::_n_max_iters ) ){ break; }
//				}
																		if( self::_is_fast && ( $tst_cnt > self::_n_max_iters ) ){ break; }
			}


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped(); }
    }
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

    public function test_TwoAgendaHasOffDays_AllWeekPatterns(){											//	_is_1_2_23	Long duration ( > 20 min)
         if( ( self::_is_1_2_23 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
            $_SESSION[ 'is_skip' ]	= true;
            global $CA_PATH; include( $CA_PATH."test/UT_std_vars.php" ); include( $CA_PATH."variables_DB.php" );

            self::createEnvironment_01();

			//	Appointment types
			$name	= 'UT App type ';
			$ini_app_type	= UT_utils::initAppTypeArray2(); $ind	= 0;

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 0 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 0

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 1 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 1

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 2 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 2

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= $cats[ 3 ][ $agCatF_Id ];
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 3

			$ini_app_type[ $appTypesF_Name ]		= $name.$ind;
			$ini_app_type[ $appTypesF_AgeCatID ]	= NULL;
			$res	= app_type_bl::editAppType( $ini_app_type );
			$app_types[]	= app_type_bl::getAppTypeById( $res[ 'id' ] ); $ind++; //  Index 4


			$start_date = '01-01-2011';										$mk_start	= strtotime( $start_date );
			$end_date	= ( self::_is_fast ) ? '20-06-2011' : '20-12-2012';	$mk_end		= strtotime( $end_date );




			//	Selected agendas (Category 0)
			$ag_id1	= $agendas[ 1 ][ $agendasF_Id ];// 07:00 - 16:00
			$ag_id2	= $agendas[ 3 ][ $agendasF_Id ];// 11:00 - 20:00


			$new_blk1	= UT_utils::initBlkArray();
			$new_blk1[ $daysOffF_AgendaId ]	= $ag_id1;
			$new_blk1[ $dbTable_StartDate ]	= $start_date;
			$new_blk1[ $dbTable_EndDate ]	= $end_date;
			unset( $new_blk1[ $dbTable_StartTime ] );
			unset( $new_blk1[ $dbTable_EndTime ] );
			$new_blk1[ 'PATTERN_ID' ]				= 1;
			$new_blk1[ $daysoffPatternF_Cycle ]		= BlockDaysBL::_weekly_cycle;
			$new_blk1[ $daysoffPatternF_Period ]	= 1;
			$new_blk1[ $daysoffPatternF_WeekDays ]	= 0;
			$blk_id1 	= BlockDaysBL::insertDayoffRec( $new_blk1 );
			$blk_data1	= BlockDaysBL::getDayoffById( $blk_id1 );

			$new_blk2	= UT_utils::initBlkArray();
			$new_blk2[ $daysOffF_AgendaId ]	= $ag_id2;// 07:00 - 16:00
			$new_blk2[ $dbTable_StartDate ]	= $start_date;
			$new_blk2[ $dbTable_EndDate ]	= $end_date;
			unset( $new_blk2[ $dbTable_StartTime ] );
			unset( $new_blk2[ $dbTable_EndTime ] );
			$new_blk2[ 'PATTERN_ID' ]				= 1;
			$new_blk2[ $daysoffPatternF_Cycle ]		= BlockDaysBL::_weekly_cycle;
			$new_blk2[ $daysoffPatternF_Period ]	= 1;
			$new_blk2[ $daysoffPatternF_WeekDays ]	= 0;
			$blk_id2 	= BlockDaysBL::insertDayoffRec( $new_blk2 );
			$blk_data2	= BlockDaysBL::getDayoffById( $blk_id2 );

			$add_info	= "";

            $mk_s		= $mk_start;
            $mk_e		= strtotime( '+1 month', $mk_end );
            $mk_months	= array();
            while( $mk_s <= $mk_e ){
            	$mk_months[]	= $mk_s;
            	$mk_s	= strtotime( '+1 month', $mk_s );
            }

			$today	= '01-01-2011'; $db_today	= date( 'Y-m-d', strtotime( $today ) );

			$tst_cnt	= 0;
			$ptt_st1	= 1; $ptt_st2	= 2; $ptt_stp	= 2; //REMARK: You should set all this variables to 1 to prerform full-scale testing.
			for( $pattern1 = $ptt_st1; $pattern1 < 127; $pattern1 += $ptt_stp ){
				$new_blk1[ $daysoffPatternF_WeekDays ]	= $pattern1;
				UT_utils::updateDayOff( $blk_id1, $blk_data1[ 'PATTERN_ID' ], $new_blk1 );

				for( $pattern2 = $ptt_st2; $pattern2 < 127; $pattern2 += $ptt_stp ){
					$new_blk2[ $daysoffPatternF_WeekDays ]	= $pattern2;
					UT_utils::updateDayOff( $blk_id2, $blk_data2[ 'PATTERN_ID' ], $new_blk2 );

					$add_info	= "Week days1: ".$pattern1.", Week days2: ".$pattern2.
							"\n Date1 start: ".$new_blk1[ $dbTable_StartDate ].", end: ".$new_blk1[ $dbTable_EndDate ].".".
							"\n Date2 start: ".$new_blk2[ $dbTable_StartDate ].", end: ".$new_blk2[ $dbTable_EndDate ].".";
					( self::_is_fast ) ? $add_info .= "\nLimited by ".self::_n_max_iters." iterations. Iteration number is $tst_cnt.":'';


					foreach( $mk_months as $mk_month ){
						$month	= intval( date( 'n', $mk_month ) );
						$year	= intval( date( 'Y', $mk_month ) );
//			//	Get month info
//	//							UT_utils::doMysqliReconnect( $this, 'test_AgendaHasOffDays_AllWeekPatterns' );
			            $month_info	= make_appointments_bl::getAvailableDaysForMonth_24( $month, $year, $app_types[ 0 ][ $appTypesF_Id ], make_appointments_bl::_whole_list, $cats[ 0 ][ $agCatF_Id ], $today, '12:00' );
			            $month_exp1	= UT_utils::findFreeDaysOfMonthByDayOffItem( $mk_month, $new_blk1 );
			            $month_exp2	= UT_utils::findFreeDaysOfMonthByDayOffItem( $mk_month, $new_blk2 );
			            $month_exp	= array_merge( $month_exp1, $month_exp2 );

						foreach( $month_info as $db_date => $item ){
							$date	= date( 'd-m-Y', strtotime( $db_date ) );
							$is_blk	= !array_key_exists( $db_date, $month_exp );

							$condition	= ( $is_blk || $db_date < $db_today ) ? ( 0 == $item ) : ( 1 == $item );
							$this->assertTrue( $condition, "Assert 1. Wrong value was found on date: ".$date.
												"/".utils_bl::getWeekDayByDate( $date )."/Today date is $today".
												"/\n$add_info\n\nReceived data:\n".print_r(  $month_info, true ) );
						}
//
////break;
					}
																		$tst_cnt++;
																		if( self::_is_fast && ( $tst_cnt > self::_n_max_iters ) ){ break; }
				}
																		if( self::_is_fast && ( $tst_cnt > self::_n_max_iters ) ){ break; }
			}


//echo "\n\ntst_cnt: $tst_cnt\n\n";


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped(); }
    }
//-------------------------------------------------------------------------------------------------- _UT_ORG_CODE

}//  Class End
?>