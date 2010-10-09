<?php
class anyAgendaUtility extends PHPUnit_Framework_TestCase{
//	const _ptt_periods	= 3;


	const _is_all = false;   //  false  true

//	const _is_0		= true;		//REMARK: This test is always anabled

	//	Black Box (BB)
	const _is_1_BB		= false;
	const _is_2_BB		= false;
	const _is_3_BB		= false;
	const _is_4_BB		= false;
	const _is_5_BB		= false;
	const _is_6_BB		= false;
	const _is_7_BB		= false;

	//	Off days
	const _is_8_BB		= true;
	const _is_9_BB		= false;
	const _is_10_BB		= false;

	protected function setUp(){
		session_tuning::initSessionData();
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

    protected function tearDown(){
        utils_ut::deleteOrgData();
    }
//------------------------------------------------------------------------------------- _UT_ORG_CODE

    protected function createEnvironment_01(){
		global $CA_PATH; include( $CA_PATH.'std.php' );

		//	Agendas
		utils_ut::addItem( 'agenda', array( 'START_TIME' => '11:00', 'END_TIME' => '20:00' ) );//0
		utils_ut::addItem( 'agenda', array( 'START_TIME' => '07:00', 'END_TIME' => '16:00' ) );//1
		utils_ut::addItem( 'agenda', array( 'START_TIME' => '07:00', 'END_TIME' => '16:00' ) );//2
		utils_ut::addItem( 'agenda', array( 'START_TIME' => '11:00', 'END_TIME' => '20:00' ) );//3
//		utils_ut::addItem( 'agenda', array( 'START_TIME' => '08:00', 'END_TIME' => '08:00' ) );//4

		//	Cagetories
		utils_ut::addItem( 'cat' );//0
		utils_ut::addItem( 'cat' );//1
		utils_ut::addItem( 'cat' );//2
		utils_ut::addItem( 'cat' );//3

		//	Assing agendas to categories
		$ags	= array( $agendas[ 1 ][ 'AGENDA_ID' ], $agendas[ 3 ][ 'AGENDA_ID' ] );	//0
		utils_ut::assignAgendasToCat( $ags, $cats[ 0 ][ 'AGE_CAT_ID' ] );

		$ags	= array( $agendas[ 0 ][ 'AGENDA_ID' ], $agendas[ 1 ][ 'AGENDA_ID' ] );	//1
		utils_ut::assignAgendasToCat( $ags, $cats[ 1 ][ 'AGE_CAT_ID' ] );

		$ags	= array( $agendas[ 2 ][ 'AGENDA_ID' ], $agendas[ 3 ][ 'AGENDA_ID' ] );	//2
		utils_ut::assignAgendasToCat( $ags, $cats[ 2 ][ 'AGE_CAT_ID' ] );

		$ags	= array( $agendas[ 0 ][ 'AGENDA_ID' ], $agendas[ 2 ][ 'AGENDA_ID' ] );	//3
		utils_ut::assignAgendasToCat( $ags, $cats[ 3 ][ 'AGE_CAT_ID' ] );
//
//		//	Create app types
//		utils_ut::addItem( 'app_type', array( 'AGE_CAT_ID' => $cats[ 0 ][ 'AGE_CAT_ID' ] ) );	//0
//		utils_ut::addItem( 'app_type', array( 'AGE_CAT_ID' => $cats[ 1 ][ 'AGE_CAT_ID' ] ) );	//1
//		utils_ut::addItem( 'app_type', array( 'AGE_CAT_ID' => $cats[ 2 ][ 'AGE_CAT_ID' ] ) );	//2
//		utils_ut::addItem( 'app_type', array( 'AGE_CAT_ID' => $cats[ 3 ][ 'AGE_CAT_ID' ] ) );	//3
//		utils_ut::addItem( 'app_type' );														//4


		//	Clients
		utils_ut::addItem( 'client' );
		utils_ut::addItem( 'client' );
		utils_ut::addItem( 'client' );
		utils_ut::addItem( 'client' );
    }
//------------------------------------------------------------------------------------- _UT_ORG_CODE

    protected function doAsserts_001( $appTypId, $agId, $catId, $nIter = 0 ){
    	global $CA_PATH; include( $CA_PATH.'std.php' );
		$d_t_now	= '2010-01-10 00:00:00'; include( $CA_PATH.'dates.php' );

		$time_now	= '12:00';	//----------------------------------
		$month_info	= bl::getAnyAgendaForMonth( 1, 2010, $appTypId, $agId, $catId, $today, $time_now );
		$this->assertEquals( 31, count( $month_info ), "\n***** Assert 1/$nIter *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
		foreach( $month_info as $db_date => $item ){
			$condition	= ( $db_date < $db_today ) ? ( 0 == $item ) : ( 1 == $item );
			$this->assertTrue( $condition, "\n***** Assert 2/$nIter *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
		}

		$time_now	= '16:00';	//----------------------------------
		$month_info	= bl::getAnyAgendaForMonth( 1, 2010, $appTypId, $agId, $catId, $today, $time_now );
		$this->assertEquals( 31, count( $month_info ), "\n***** Assert 3/$nIter *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
		foreach( $month_info as $db_date => $item ){
			$condition	= ( $db_date < $db_today ) ? ( 0 == $item ) : ( 1 == $item );
			$this->assertTrue( $condition, "\n***** Assert 4/$nIter *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
		}

		$time_now	= '16:30';	//----------------------------------
		$month_info	= bl::getAnyAgendaForMonth( 1, 2010, $appTypId, $agId, $catId, $today, $time_now );
		$this->assertEquals( 31, count( $month_info ), "\n***** Assert 5/$nIter *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
		foreach( $month_info as $db_date => $item ){
			$condition	= ( $db_date < $db_today ) ? ( 0 == $item ) : ( 1 == $item );
			$this->assertTrue( $condition, "\n***** Assert 6/$nIter *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
		}

		$time_now	= '19:30';	//----------------------------------
		$month_info	= bl::getAnyAgendaForMonth( 1, 2010, $appTypId, $agId, $catId, $today, $time_now );
		$this->assertEquals( 31, count( $month_info ), "\n***** Assert 7/$nIter *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
		foreach( $month_info as $db_date => $item ){
			$condition	= ( $db_date < $db_today ) ? ( 0 == $item ) : ( 1 == $item );
			$this->assertTrue( $condition, "\n***** Assert 8/$nIter *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
		}

		$time_now	= '19:31';	//----------------------------------
		$month_info	= bl::getAnyAgendaForMonth( 1, 2010, $appTypId, $agId, $catId, $today, $time_now );
		$this->assertEquals( 31, count( $month_info ), "\n***** Assert 9/$nIter *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
		foreach( $month_info as $db_date => $item ){
			$condition	= ( $db_date <= $db_today ) ? ( 0 == $item ) : ( 1 == $item );
			$this->assertTrue( $condition, "\n***** Assert 10/$nIter *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
		}

		$time_now	= '20:00';	//----------------------------------
		$month_info	= bl::getAnyAgendaForMonth( 1, 2010, $appTypId, $agId, $catId, $today, $time_now );
		$this->assertEquals( 31, count( $month_info ), "\n***** Assert 11/$nIter *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
		foreach( $month_info as $db_date => $item ){
			$condition	= ( $db_date <= $db_today ) ? ( 0 == $item ) : ( 1 == $item );
			$this->assertTrue( $condition, "\n***** Assert 12/$nIter *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
		}

    }
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	protected function doAsserts_002( $appTypId, $agId, $catId, $nIter = 0 ){
		global $CA_PATH; include( $CA_PATH.'std.php' );
		$d_t_now	= '2010-01-10 00:00:00'; include( $CA_PATH.'dates.php' );

			//	Asserts
		$time_now	= '12:00';	//----------------------------------
		$month_info	= bl::getAnyAgendaForMonth( 1, 2010, $appTypId, $agId, $catId, $today, $time_now );
		$this->assertEquals( 31, count( $month_info ), "\n***** Assert 1/$nIter *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
		foreach( $month_info as $db_date => $item ){
			$condition	= ( $db_date < $db_today || $db_date > '2010-01-20' ) ? ( 0 == $item ) : ( 1 == $item );
			$this->assertTrue( $condition, "\n***** Assert 2/$nIter *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
		}
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	protected function doAsserts_003( $appTypId, $agId, $catId, $nIter = 0 ){
		global $CA_PATH; include( $CA_PATH.'std.php' );
		$d_t_now	= '2010-01-10 00:00:00'; include( $CA_PATH.'dates.php' );

			//	Asserts
		$time_now	= '12:00';	//----------------------------------

		$month_info	= bl::getAnyAgendaForMonth( 1, 2010, $appTypId, $agId, $catId, $today, $time_now );
		$this->assertEquals( 31, count( $month_info ), "\n***** Assert 1/$nIter *****\nWrong quantity of itmes.\nReceived data:\n".print_r( $month_info, true )."\n" );
		foreach( $month_info as $db_date => $item ){
			$week_day	= date( 'w', strtotime( $db_date ) );
			$condition	= ( $db_date < $db_today ||  utils_ut::c_nMonday	== $week_day || utils_ut::c_nWednesday	== $week_day || utils_ut::c_nFriday	== $week_day ) ? ( 0 == $item ) : ( 1 == $item );
			$this->assertTrue( $condition, "\n***** Assert 2/$nIter *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
		}

		$month_info	= bl::getAnyAgendaForMonth( 1, 2010, $appTypId, $agId, $catId, $today, $time_now );
		$this->assertEquals( 31, count( $month_info ), "\n***** Assert 3/$nIter *****\nWrong quantity of itmes.\nReceived data:\n".print_r( $month_info, true )."\n" );
		foreach( $month_info as $db_date => $item ){
			$week_day	= date( 'w', strtotime( $db_date ) );
			$condition	= ( utils_ut::c_nMonday	== $week_day || utils_ut::c_nWednesday	== $week_day || utils_ut::c_nFriday	== $week_day || $db_date < $db_today ) ? ( 0 == $item ) : ( 1 == $item );
			$this->assertTrue( $condition, "\n***** Assert 4/$nIter *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r( $month_info, true )."\n" );
		}
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	protected function doAsserts_004( $appTypId, $agId, $catId, $nIter = 0 ){
		global $CA_PATH; include( $CA_PATH.'std.php' );

		$d_t_now	= '2010-08-11 00:00:00'; include( $CA_PATH.'dates.php' );

			//	Asserts
		$time_now	= '06:00';	//----------------------------------
		$month_info	= bl::getAnyAgendaForMonth( 1, 2010, $appTypId, $agId, $catId, $today, $time_now );
		$this->assertEquals( 31, count( $month_info ), "\n***** Assert 1/$nIter. Checked time: $time_now. *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
		foreach( $month_info as $db_date => $item ){
			$condition	= ( $db_date < $db_today ) ? ( 0 == $item ) : ( 1 == $item );
			$this->assertTrue( $condition, "\n***** Assert 2/$nIter. Checked time: $time_now. *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
		}

		$time_now	= '07:00';	//----------------------------------
		$month_info	= bl::getAnyAgendaForMonth( 1, 2010, $appTypId, $agId, $catId, $today, $time_now );
		$this->assertEquals( 31, count( $month_info ), "\n***** Assert 3/$nIter. Checked time: $time_now. *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
		foreach( $month_info as $db_date => $item ){
			$condition	= ( $db_date < $db_today ) ? ( 0 == $item ) : ( 1 == $item );
			$this->assertTrue( $condition, "\n***** Assert 4/$nIter. Checked time: $time_now. *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
		}

		$time_now	= '12:00';	//----------------------------------
		$month_info	= bl::getAnyAgendaForMonth( 1, 2010, $appTypId, $agId, $catId, $today, $time_now );
		$this->assertEquals( 31, count( $month_info ), "\n***** Assert 5/$nIter. Checked time: $time_now. *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
		foreach( $month_info as $db_date => $item ){
			$condition	= ( $db_date < $db_today ) ? ( 0 == $item ) : ( 1 == $item );
			$this->assertTrue( $condition, "\n***** Assert 6/$nIter. Checked time: $time_now. *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
		}

		$time_now	= '12:09';	//----------------------------------
		$month_info	= bl::getAnyAgendaForMonth( 1, 2010, $appTypId, $agId, $catId, $today, $time_now );
		$this->assertEquals( 31, count( $month_info ), "\n***** Assert 7/$nIter. Checked time: $time_now. *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
		foreach( $month_info as $db_date => $item ){
			$condition	= ( $db_date <= $db_today ) ? ( 0 == $item ) : ( 1 == $item );
			$this->assertTrue( $condition, "\n***** Assert 8/$nIter. Checked time: $time_now. *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
		}

		$time_now	= '12:22';	//----------------------------------
		$month_info	= bl::getAnyAgendaForMonth( 1, 2010, $appTypId, $agId, $catId, $today, $time_now );
		$this->assertEquals( 31, count( $month_info ), "\n***** Assert 9/$nIter. Checked time: $time_now. *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
		foreach( $month_info as $db_date => $item ){
			$condition	= ( $db_date <= $db_today ) ? ( 0 == $item ) : ( 1 == $item );
			$this->assertTrue( $condition, "\n***** Assert 10/$nIter. Checked time: $time_now. *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
		}

		$time_now	= '12:23';	//----------------------------------
		$month_info	= bl::getAnyAgendaForMonth( 1, 2010, $appTypId, $agId, $catId, $today, $time_now );
		$this->assertEquals( 31, count( $month_info ), "\n***** Assert 11/$nIter. Checked time: $time_now. *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
		foreach( $month_info as $db_date => $item ){
			$condition	= ( $db_date <= $db_today ) ? ( 0 == $item ) : ( 1 == $item );
			$this->assertTrue( $condition, "\n***** Assert 12/$nIter. Checked time: $time_now. *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
		}

		$time_now	= '15:00';	//----------------------------------
		$month_info	= bl::getAnyAgendaForMonth( 1, 2010, $appTypId, $agId, $catId, $today, $time_now );
		$this->assertEquals( 31, count( $month_info ), "\n***** Assert 13/$nIter. Checked time: $time_now. *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
		foreach( $month_info as $db_date => $item ){
			$condition	= ( $db_date <= $db_today ) ? ( 0 == $item ) : ( 1 == $item );
			$this->assertTrue( $condition, "\n***** Assert 14/$nIter. Checked time: $time_now. *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
		}

		$time_now	= '15:30';	//----------------------------------
		$month_info	= bl::getAnyAgendaForMonth( 1, 2010, $appTypId, $agId, $catId, $today, $time_now );
		$this->assertEquals( 31, count( $month_info ), "\n***** Assert 15/$nIter. Checked time: $time_now. *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
		foreach( $month_info as $db_date => $item ){
			$condition	= ( $db_date <= $db_today ) ? ( 0 == $item ) : ( 1 == $item );
			$this->assertTrue( $condition, "\n***** Assert 16/$nIter. Checked time: $time_now. *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
		}

	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

}//	Class End
?>