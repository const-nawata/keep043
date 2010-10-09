<?php
require_once( $CA_PATH.'test/widget/bl.php' );
require_once( $CA_PATH.'test/widget/anyAgendaUtility.php' );

class anyAgendaTestsSuite extends anyAgendaUtility{

     public function test_Performance(){	//	Performance															//_is_0
		if( !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]		= true;
			global $CA_PATH; include( $CA_PATH.'std.php' );

			//	Agendas
			utils_ut::addItem( 'agenda' );
			utils_ut::addItem( 'agenda' );
			utils_ut::addItem( 'agenda' );
			utils_ut::addItem( 'agenda' );
			utils_ut::addItem( 'agenda' );
			utils_ut::addItem( 'agenda' );
			utils_ut::addItem( 'agenda' );
			utils_ut::addItem( 'agenda' );
			utils_ut::addItem( 'agenda' );
			utils_ut::addItem( 'agenda' );
			utils_ut::addItem( 'agenda' );
			utils_ut::addItem( 'agenda' );
			utils_ut::addItem( 'agenda' );
			utils_ut::addItem( 'agenda' );
			utils_ut::addItem( 'agenda' );
			utils_ut::addItem( 'agenda' );
			utils_ut::addItem( 'agenda' );
			utils_ut::addItem( 'agenda' );
			utils_ut::addItem( 'agenda' );
			utils_ut::addItem( 'agenda' );

				//	App types
			utils_ut::addItem( 'app_type' );

			$ag_ids	= array();
			foreach( $agendas as $agenda ){
				$ag_ids[]	= $agenda[ 'AGENDA_ID' ];
			}
			$ags	= implode( ',', $ag_ids );


			$d_t_now	= '2010-01-10 06:00:00'; include( $CA_PATH.'dates.php' );
//			$time_now	= '16:00';	//----------------------------------

				$m	= microtime();
				list( $a1, $a2 ) = explode( " ", $m );
				$m1	= $a2 + $a1;

//			$today = '10-01-2010'; $db_today = date( 'Y-m-d', strtotime( $today ) );
//			$now		= $db_today.' '.$time_now.':00';
//			$month_info	= bl::getAnyAgendaForMonth( $now, 1, 2010, $app_types[ 0 ][ 'ID' ], $ags, true );
			$month_info	= bl::getAnyAgendaForMonth( 1, 2010, $app_types[ 0 ][ 'ID' ], -2, -2, $today, $time_now );

				$m	= microtime(  );
				list( $a1, $a2 ) = explode( " ", $m );
				$m2	= $a2 + $a1;
				echo "<div style='position: absolute; top: 0px; left: 500px;'>Performance time for `getAnyAgendaForMonth` method is ".( $m2 - $m1 )." sec</div>";


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_Performance is off!!!' ); }
     }
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public function test_AgendasHaveNoAppsAndFreeTimesAndAppTypeHasNoConstraints(){									//_is_1_BB
		if( ( self::_is_1_BB || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]	= true;
			global $CA_PATH; include( $CA_PATH.'std.php' );

			self::createEnvironment_01();

			//	Create app types
			utils_ut::addItem( 'app_type', array( 'AGE_CAT_ID' => $cats[ 0 ][ 'AGE_CAT_ID' ] ) );	//0
			utils_ut::addItem( 'app_type', array( 'AGE_CAT_ID' => $cats[ 1 ][ 'AGE_CAT_ID' ] ) );	//1
			utils_ut::addItem( 'app_type', array( 'AGE_CAT_ID' => $cats[ 2 ][ 'AGE_CAT_ID' ] ) );	//2
			utils_ut::addItem( 'app_type', array( 'AGE_CAT_ID' => $cats[ 3 ][ 'AGE_CAT_ID' ] ) );	//3
			utils_ut::addItem( 'app_type' );														//4

			self::doAsserts_001( $app_types[ 0 ][ 'ID' ], -2, $cats[ 0 ][ 'AGE_CAT_ID' ], 0 );
			self::doAsserts_001( $app_types[ 1 ][ 'ID' ], -2, $cats[ 1 ][ 'AGE_CAT_ID' ], 1 );
			self::doAsserts_001( $app_types[ 2 ][ 'ID' ], -2, $cats[ 2 ][ 'AGE_CAT_ID' ], 2 );
			self::doAsserts_001( $app_types[ 3 ][ 'ID' ], -2, $cats[ 3 ][ 'AGE_CAT_ID' ], 3 );
			self::doAsserts_001( $app_types[ 4 ][ 'ID' ], -2, -2, 4 );


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_AgendasHaveNoAppsAndFreeTimesAndAppTypeHasNoConstraints is off!!!' ); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	function test_AgendasHaveNoAppsAndFreeTimesAndAppTypeHasMaxTime_10(){											//_is_2_BB
		if( ( self::_is_2_BB || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]	= true;
			global $CA_PATH; include( $CA_PATH.'std.php' );

			self::createEnvironment_01();

			//	Create app types
			utils_ut::addItem( 'app_type', array( 'AGE_CAT_ID' => $cats[ 0 ][ 'AGE_CAT_ID' ], 'MAX_TIME' => 10 ) );	//0
			utils_ut::addItem( 'app_type', array( 'AGE_CAT_ID' => $cats[ 1 ][ 'AGE_CAT_ID' ], 'MAX_TIME' => 10 ) );	//1
			utils_ut::addItem( 'app_type', array( 'AGE_CAT_ID' => $cats[ 2 ][ 'AGE_CAT_ID' ], 'MAX_TIME' => 10 ) );	//2
			utils_ut::addItem( 'app_type', array( 'AGE_CAT_ID' => $cats[ 3 ][ 'AGE_CAT_ID' ], 'MAX_TIME' => 10 ) );	//3
			utils_ut::addItem( 'app_type', array( 'MAX_TIME' => 10 ) );														//4

			self::doAsserts_002( $app_types[ 0 ][ 'ID' ], -2, $cats[ 0 ][ 'AGE_CAT_ID' ], 0 );
			self::doAsserts_002( $app_types[ 1 ][ 'ID' ], -2, $cats[ 1 ][ 'AGE_CAT_ID' ], 1 );
			self::doAsserts_002( $app_types[ 2 ][ 'ID' ], -2, $cats[ 2 ][ 'AGE_CAT_ID' ], 2 );
			self::doAsserts_002( $app_types[ 3 ][ 'ID' ], -2, $cats[ 3 ][ 'AGE_CAT_ID' ], 3 );
			self::doAsserts_002( $app_types[ 4 ][ 'ID' ], -2, -2, 4 );


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_AgendasHaveNoAppsAndFreeTimesAndAppTypeHasMaxTime_10 is off!!!' ); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	function test_AgendasHaveNoAppsAndFreeTimesAndAppTypeHasPattern_STTS(){											//_is_3_BB
		if( ( self::_is_3_BB || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]	= true;
			global $CA_PATH; include( $CA_PATH.'std.php' );

			self::createEnvironment_01();

			//	Create app types
			$week_patt	=	utils_ut::c_sundayMask | utils_ut::c_tuesdayMask | utils_ut::c_thursdayMask | utils_ut::c_saturdayMask;
			utils_ut::addItem( 'app_type', array( 'AT_PERIOD_DAY' => $week_patt, 'AGE_CAT_ID' => $cats[ 0 ][ 'AGE_CAT_ID' ] ) );	//0
			utils_ut::addItem( 'app_type', array( 'AT_PERIOD_DAY' => $week_patt, 'AGE_CAT_ID' => $cats[ 1 ][ 'AGE_CAT_ID' ] ) );	//1
			utils_ut::addItem( 'app_type', array( 'AT_PERIOD_DAY' => $week_patt, 'AGE_CAT_ID' => $cats[ 2 ][ 'AGE_CAT_ID' ] ) );	//2
			utils_ut::addItem( 'app_type', array( 'AT_PERIOD_DAY' => $week_patt, 'AGE_CAT_ID' => $cats[ 3 ][ 'AGE_CAT_ID' ] ) );	//3
			utils_ut::addItem( 'app_type', array( 'AT_PERIOD_DAY' => $week_patt ) );												//4


			self::doAsserts_003( $app_types[ 0 ][ 'ID' ], -2, $cats[ 0 ][ 'AGE_CAT_ID' ], 0 );
			self::doAsserts_003( $app_types[ 1 ][ 'ID' ], -2, $cats[ 1 ][ 'AGE_CAT_ID' ], 1 );
			self::doAsserts_003( $app_types[ 2 ][ 'ID' ], -2, $cats[ 2 ][ 'AGE_CAT_ID' ], 2 );
			self::doAsserts_003( $app_types[ 3 ][ 'ID' ], -2, $cats[ 3 ][ 'AGE_CAT_ID' ], 3 );
			self::doAsserts_003( $app_types[ 4 ][ 'ID' ], -2, -2, 4 );


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_AgendasHaveNoAppsAndFreeTimesAndAppTypeHasPattern_STTS is off!!!' ); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

    public function test_Ttl_11_20_HasNoAppsAndFreeTimes_AppTypeHasStart_EndTimes_21_18(){							//_is_4_BB
		if( ( self::_is_4_BB || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]		= false;
			global $CA_PATH; include( $CA_PATH.'std.php' );

			self::createEnvironment_01();

			//	App types
			utils_ut::addItem( 'app_type', array( 'AT_PERIOD_START_TIME' => '21:00', 'AT_PERIOD_END_TIME' => '18:00' ) );

			$d_t_now	= '2010-08-10 00:00:00'; include( $CA_PATH.'dates.php' );

			$time_now	= '06:00';	//----------------------------------
			$month_info	= bl::getAnyAgendaForMonth( 8, 2010, $app_types[ 0 ][ 'ID' ], $agendas[ 3 ][ 'AGENDA_ID' ], -2, $today, $time_now );
            foreach( $month_info as $db_date => $val ){
            	$date	= date( 'd-m-Y', strtotime( $db_date ) );
            	if( $db_date < $db_today ){
					$this->assertEquals( 0, $val, "Assert 1. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
            	}else{
            		$this->assertEquals( 1, $val, "Assert 2. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
            	}
            }

			$time_now	= '07:00';	//----------------------------------
			$month_info	= bl::getAnyAgendaForMonth( 8, 2010, $app_types[ 0 ][ 'ID' ], $agendas[ 3 ][ 'AGENDA_ID' ], -2, $today, $time_now );
            foreach( $month_info as $db_date => $val ){
            	$date	= date( 'd-m-Y', strtotime( $db_date ) );
            	if( $db_date < $db_today ){
					$this->assertEquals( 0, $val, "Assert 3. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
            	}else{
            		$this->assertEquals( 1, $val, "Assert 4. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
            	}
            }

			$time_now	= '08:00';	//----------------------------------
			$month_info	= bl::getAnyAgendaForMonth( 8, 2010, $app_types[ 0 ][ 'ID' ], $agendas[ 3 ][ 'AGENDA_ID' ], -2, $today, $time_now );
            foreach( $month_info as $db_date => $val ){
            	$date	= date( 'd-m-Y', strtotime( $db_date ) );
            	if( $db_date < $db_today ){
					$this->assertEquals( 0, $val, "Assert 5. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
            	}else{
            		$this->assertEquals( 1, $val, "Assert 6. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
            	}
            }

			$time_now	= '11:00';	//----------------------------------
			$month_info	= bl::getAnyAgendaForMonth( 8, 2010, $app_types[ 0 ][ 'ID' ], $agendas[ 3 ][ 'AGENDA_ID' ], -2, $today, $time_now );
            foreach( $month_info as $db_date => $val ){
            	$date	= date( 'd-m-Y', strtotime( $db_date ) );
            	if( $db_date < $db_today ){
					$this->assertEquals( 0, $val, "Assert 7. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
            	}else{
            		$this->assertEquals( 1, $val, "Assert 8. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
            	}
            }

			$time_now	= '12:00';	//----------------------------------
			$month_info	= bl::getAnyAgendaForMonth( 8, 2010, $app_types[ 0 ][ 'ID' ], $agendas[ 3 ][ 'AGENDA_ID' ], -2, $today, $time_now );
            foreach( $month_info as $db_date => $val ){
            	$date	= date( 'd-m-Y', strtotime( $db_date ) );
            	if( $db_date < $db_today ){
					$this->assertEquals( 0, $val, "Assert 9. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
            	}else{
            		$this->assertEquals( 1, $val, "Assert 10. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
            	}
            }

			$time_now	= '15:50';	//----------------------------------
			$month_info	= bl::getAnyAgendaForMonth( 8, 2010, $app_types[ 0 ][ 'ID' ], $agendas[ 3 ][ 'AGENDA_ID' ], -2, $today, $time_now );
            foreach( $month_info as $db_date => $val ){
            	$date	= date( 'd-m-Y', strtotime( $db_date ) );
            	if( $db_date < $db_today ){
					$this->assertEquals( 0, $val, "Assert 11. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
            	}else{
            		$this->assertEquals( 1, $val, "Assert 12. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
            	}
            }

			$time_now	= '16:00';	//----------------------------------
			$month_info	= bl::getAnyAgendaForMonth( 8, 2010, $app_types[ 0 ][ 'ID' ], $agendas[ 3 ][ 'AGENDA_ID' ], -2, $today, $time_now );
            foreach( $month_info as $db_date => $val ){
            	$date	= date( 'd-m-Y', strtotime( $db_date ) );
            	if( $db_date < $db_today ){
					$this->assertEquals( 0, $val, "Assert 13. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
            	}else{
            		$this->assertEquals( 1, $val, "Assert 14. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
            	}
            }

			$time_now	= '16:10';	//----------------------------------
			$month_info	= bl::getAnyAgendaForMonth( 8, 2010, $app_types[ 0 ][ 'ID' ], $agendas[ 3 ][ 'AGENDA_ID' ], -2, $today, $time_now );
            foreach( $month_info as $db_date => $val ){
            	$date	= date( 'd-m-Y', strtotime( $db_date ) );
            	if( $db_date < $db_today ){
					$this->assertEquals( 0, $val, "Assert 15. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
            	}else{
            		$this->assertEquals( 1, $val, "Assert 16. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
            	}
            }

			$time_now	= '17:50';	//----------------------------------
			$month_info	= bl::getAnyAgendaForMonth( 8, 2010, $app_types[ 0 ][ 'ID' ], $agendas[ 3 ][ 'AGENDA_ID' ], -2, $today, $time_now );
            foreach( $month_info as $db_date => $val ){
            	$date	= date( 'd-m-Y', strtotime( $db_date ) );
            	if( $db_date < $db_today ){
					$this->assertEquals( 0, $val, "Assert 17. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
            	}else{
            		$this->assertEquals( 1, $val, "Assert 18. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
            	}
            }

			$time_now	= '18:00';	//----------------------------------
			$month_info	= bl::getAnyAgendaForMonth( 8, 2010, $app_types[ 0 ][ 'ID' ], $agendas[ 3 ][ 'AGENDA_ID' ], -2, $today, $time_now );
            foreach( $month_info as $db_date => $val ){
            	$date	= date( 'd-m-Y', strtotime( $db_date ) );
            	if( $db_date < $db_today ){
					$this->assertEquals( 0, $val, "Assert 19. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
            	}else{
            		$this->assertEquals( 1, $val, "Assert 20. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
            	}
            }

			$time_now	= '19:00';	//----------------------------------
			$month_info	= bl::getAnyAgendaForMonth( 8, 2010, $app_types[ 0 ][ 'ID' ], $agendas[ 3 ][ 'AGENDA_ID' ], -2, $today, $time_now );
            foreach( $month_info as $db_date => $val ){
            	$date	= date( 'd-m-Y', strtotime( $db_date ) );
            	if( $db_date <= $db_today ){
					$this->assertEquals( 0, $val, "Assert 21. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
            	}else{
            		$this->assertEquals( 1, $val, "Assert 22. Wrong value was found for date: $date."."\n\nReceived data:\n".print_r( $month_info, true ) );
            	}
            }


			$_SESSION[ 'is_skip' ]	= false;
		}else{ $this->markTestSkipped( 'test_Ttl_11_20_HasNoAppsAndFreeTimes_AppTypeHasStart_EndTimes_21_18 is off!!!' ); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public function test_Ttl_11_20_HasEmpty30MinLineWhichCreatedByApps_AppTypStart_End_10_15(){						//_is_5_BB
		if( ( self::_is_5_BB || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]	= true;
			global $CA_PATH; include( $CA_PATH.'std.php' );

			self::createEnvironment_01();

			//	Create app types
			utils_ut::addItem( 'app_type', array( 'AT_PERIOD_START_TIME' => '10:00', 'AT_PERIOD_END_TIME' => '15:00', 'AGE_CAT_ID' => $cats[ 0 ][ 'AGE_CAT_ID' ] ) );	//0
			utils_ut::addItem( 'app_type', array( 'AT_PERIOD_START_TIME' => '10:00', 'AT_PERIOD_END_TIME' => '15:00', 'AGE_CAT_ID' => $cats[ 1 ][ 'AGE_CAT_ID' ] ) );	//1
			utils_ut::addItem( 'app_type', array( 'AT_PERIOD_START_TIME' => '10:00', 'AT_PERIOD_END_TIME' => '15:00', 'AGE_CAT_ID' => $cats[ 2 ][ 'AGE_CAT_ID' ] ) );	//2
			utils_ut::addItem( 'app_type', array( 'AT_PERIOD_START_TIME' => '10:00', 'AT_PERIOD_END_TIME' => '15:00', 'AGE_CAT_ID' => $cats[ 3 ][ 'AGE_CAT_ID' ] ) );	//3
			utils_ut::addItem( 'app_type', array( 'AT_PERIOD_START_TIME' => '10:00', 'AT_PERIOD_END_TIME' => '15:00' ) );												//4

			//	Appointments
			$d_t_now	= '2010-08-11 00:00:00'; include( $CA_PATH.'dates.php' );

			$ini_app	= array(
				'START_DATE'	=> $today,
				'APPTYPE_ID'	=> $app_types[ 4 ][ 'ID' ],
				'agendas'		=> $agendas[ 1 ][ 'AGENDA_ID' ],
				'clients'		=> $clients[ 0 ][ 'ID' ]
			);

			$time	= '10:00';
			while( $time < '12:00' ){
				$ini_app[ 'START_TIME' ]	= $time;
				utils_ut::addItem( 'app', $ini_app );
				$time	= date( 'H:i', strtotime( '+'.$app_types[ 0 ][ 'TIME' ].' MINUTE', strtotime( $time ) ) );
			}
			$time	= date( 'H:i', strtotime( '+30 MINUTE', strtotime( $time ) ) );//	12:30
			while( $time <= '15:00' ){
				$ini_app[ 'START_TIME' ]	= $time;
				utils_ut::addItem( 'app', $ini_app );
				$time	= date( 'H:i', strtotime( '+'.$app_types[ 0 ][ 'TIME' ].' MINUTE', strtotime( $time ) ) );
			}


			self::doAsserts_004( $app_types[ 4 ][ 'ID' ], $agendas[ 1 ][ 'AGENDA_ID' ], -2, 0 );


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_Ttl_11_20_HasEmpty30MinLineWhichCreatedByApps_AppTypStart_End_10_15 is off!!!' ); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public function test_Ttl_08_08_HasEmpty60MinLineInRestrictZone_AppTypStart_End_20_09_not_today(){				//_is_6_BB
		if( ( self::_is_6_BB || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]	= true;
			global $CA_PATH; include( $CA_PATH.'std.php' );

			self::createEnvironment_01();
			utils_ut::addItem( 'agenda', array( 'START_TIME' => '08:00', 'END_TIME' => '08:00' ) );//4

			//	App types
			utils_ut::addItem( 'app_type' );
			utils_ut::addItem( 'app_type', array( 'AT_PERIOD_START_TIME' => '20:00', 'AT_PERIOD_END_TIME' => '09:00' ) );

			//	Appointments
			$d_t_now	= '2010-08-11 00:00:00'; include( $CA_PATH.'dates.php' );

			$ini_app	= array(
				'APPTYPE_ID'	=> $app_types[ 0 ][ 'ID' ],
				'agendas'		=> $agendas[ 4 ][ 'AGENDA_ID' ],
				'clients'		=> $clients[ 0 ][ 'ID' ]
			);

			$d_t	= '2010-08-13 08:00:00';
			while( $d_t < '2010-08-13 14:00:00' ){
				$mk	= strtotime( $d_t );
				$ini_app[ 'START_DATE' ]	= date( 'd-m-Y', $mk );
				$ini_app[ 'START_TIME' ]	= date( 'H:i', $mk );
				utils_ut::addItem( 'app', $ini_app );
				$d_t	= date( 'Y-m-d H:i:s', strtotime( '+'.$app_types[ 0 ][ 'TIME' ].' MINUTE', $mk ) );
			}

			$d_t	= date( 'Y-m-d H:i:s', strtotime( '+60 MINUTE', strtotime( $d_t ) ) );
			while( $d_t < '2010-08-14 08:00:00' ){
				$mk	= strtotime( $d_t );
				$ini_app[ 'START_DATE' ]	= date( 'd-m-Y', $mk );
				$ini_app[ 'START_TIME' ]	= date( 'H:i', $mk );
				utils_ut::addItem( 'app', $ini_app );
				$d_t	= date( 'Y-m-d H:i:s', strtotime( '+'.$app_types[ 0 ][ 'TIME' ].' MINUTE', $mk ) );
			}


			$time_now	= '06:00';	//----------------------------------
			$month_info	= bl::getAnyAgendaForMonth( 8, 2010, $app_types[ 1 ][ 'ID' ], $agendas[ 4 ][ 'AGENDA_ID' ], -2, $today, $time_now );
			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 1. Checked time: $time_now. *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
			foreach( $month_info as $db_date => $item ){
				$condition	= ( $db_date < $db_today || $db_date == '2010-08-13' ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 2. Checked time: $time_now. *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_Ttl_08_08_HasEmpty60MinLineInRestrictZone_AppTypStart_End_20_09_not_today is off!!!' ); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public function test_Ttl_08_08_HasEmptyLinesAtStartOfDayAndInRestrictZone_AppTypStart_End_20_09_today(){		//_is_7_BB
		if( ( self::_is_7_BB || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]	= true;
			global $CA_PATH; include( $CA_PATH.'std.php' );

			self::createEnvironment_01();
			utils_ut::addItem( 'agenda', array( 'START_TIME' => '08:00', 'END_TIME' => '08:00' ) );//4

			//	App types
			utils_ut::addItem( 'app_type' );
			utils_ut::addItem( 'app_type', array( 'AT_PERIOD_START_TIME' => '20:00', 'AT_PERIOD_END_TIME' => '09:00' ) );

			//	Appointments
			$d_t_now	= '2010-08-11 00:00:00'; include( $CA_PATH.'dates.php' );

			$ini_app	= array(
				'APPTYPE_ID'	=> $app_types[ 0 ][ 'ID' ],
				'agendas'		=> $agendas[ 4 ][ 'AGENDA_ID' ],
				'clients'		=> $clients[ 0 ][ 'ID' ]
			);

			$d_t	= $db_today.' 09:00:00';
			while( $d_t < $db_today.' 14:00:00' ){
				$mk	= strtotime( $d_t );
				$ini_app[ 'START_DATE' ]	= date( 'd-m-Y', $mk );
				$ini_app[ 'START_TIME' ]	= date( 'H:i', $mk );
				utils_ut::addItem( 'app', $ini_app );
				$d_t	= date( 'Y-m-d H:i:s', strtotime( '+'.$app_types[ 0 ][ 'TIME' ].' MINUTE', $mk ) );
			}

			$d_t	= date( 'Y-m-d H:i:s', strtotime( '+60 MINUTE', strtotime( $d_t ) ) );
			while( $d_t < $db_tomor.' 08:00:00' ){
				$mk	= strtotime( $d_t );
				$ini_app[ 'START_DATE' ]	= date( 'd-m-Y', $mk );
				$ini_app[ 'START_TIME' ]	= date( 'H:i', $mk );
				utils_ut::addItem( 'app', $ini_app );
				$d_t	= date( 'Y-m-d H:i:s', strtotime( '+'.$app_types[ 0 ][ 'TIME' ].' MINUTE', $mk ) );
			}


			$ags	= $agendas[ 4 ][ 'AGENDA_ID' ];


			$time_now	= '06:00';	//----------------------------------
			$month_info	= bl::getAnyAgendaForMonth( 8, 2010, $app_types[ 1 ][ 'ID' ], $agendas[ 4 ][ 'AGENDA_ID' ], -2, $today, $time_now );
			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 1. Checked time: $time_now. *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
			foreach( $month_info as $db_date => $item ){
				$condition	= ( $db_date < $db_today ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 2. Checked time: $time_now. *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}

			$time_now	= '08:00';	//----------------------------------
			$month_info	= bl::getAnyAgendaForMonth( 8, 2010, $app_types[ 1 ][ 'ID' ], $agendas[ 4 ][ 'AGENDA_ID' ], -2, $today, $time_now );
			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 3. Checked time: $time_now. *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
			foreach( $month_info as $db_date => $item ){
				$condition	= ( $db_date < $db_today ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 4. Checked time: $time_now. *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}

			$time_now	= '08:30';	//----------------------------------
			$month_info	= bl::getAnyAgendaForMonth( 8, 2010, $app_types[ 1 ][ 'ID' ], $agendas[ 4 ][ 'AGENDA_ID' ], -2, $today, $time_now );
			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 5. Checked time: $time_now. *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
			foreach( $month_info as $db_date => $item ){
				$condition	= ( $db_date < $db_today ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 6. Checked time: $time_now. *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}

			$time_now	= '08:35';	//----------------------------------
			$month_info	= bl::getAnyAgendaForMonth( 8, 2010, $app_types[ 1 ][ 'ID' ], $agendas[ 4 ][ 'AGENDA_ID' ], -2, $today, $time_now );
			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 7. Checked time: $time_now. *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
			foreach( $month_info as $db_date => $item ){
				$condition	= ( $db_date <= $db_today ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 8. Checked time: $time_now. *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}

			$time_now	= '09:00';	//----------------------------------
			$month_info	= bl::getAnyAgendaForMonth( 8, 2010, $app_types[ 1 ][ 'ID' ], $agendas[ 4 ][ 'AGENDA_ID' ], -2, $today, $time_now );
			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 9. Checked time: $time_now. *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
			foreach( $month_info as $db_date => $item ){
				$condition	= ( $db_date <= $db_today ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 10. Checked time: $time_now. *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}

			$time_now	= '10:00';	//----------------------------------
			$month_info	= bl::getAnyAgendaForMonth( 8, 2010, $app_types[ 1 ][ 'ID' ], $agendas[ 4 ][ 'AGENDA_ID' ], -2, $today, $time_now );
			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 11. Checked time: $time_now. *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
			foreach( $month_info as $db_date => $item ){
				$condition	= ( $db_date <= $db_today ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 12. Checked time: $time_now. *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}

			$time_now	= '14:00';	//----------------------------------
			$month_info	= bl::getAnyAgendaForMonth( 8, 2010, $app_types[ 1 ][ 'ID' ], $agendas[ 4 ][ 'AGENDA_ID' ], -2, $today, $time_now );
			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 13. Checked time: $time_now. *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
			foreach( $month_info as $db_date => $item ){
				$condition	= ( $db_date <= $db_today ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 14. Checked time: $time_now. *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}

			$time_now	= '14:00';	//----------------------------------
			$month_info	= bl::getAnyAgendaForMonth( 8, 2010, $app_types[ 1 ][ 'ID' ], $agendas[ 4 ][ 'AGENDA_ID' ], -2, $today, $time_now );
			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 15. Checked time: $time_now. *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
			foreach( $month_info as $db_date => $item ){
				$condition	= ( $db_date <= $db_today ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 16. Checked time: $time_now. *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}

			$time_now	= '14:30';	//----------------------------------
			$month_info	= bl::getAnyAgendaForMonth( 8, 2010, $app_types[ 1 ][ 'ID' ], $agendas[ 4 ][ 'AGENDA_ID' ], -2, $today, $time_now );
			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 17. Checked time: $time_now. *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
			foreach( $month_info as $db_date => $item ){
				$condition	= ( $db_date <= $db_today ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 18. Checked time: $time_now. *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}

			$time_now	= '15:00';	//----------------------------------
			$month_info	= bl::getAnyAgendaForMonth( 8, 2010, $app_types[ 1 ][ 'ID' ], $agendas[ 4 ][ 'AGENDA_ID' ], -2, $today, $time_now );
			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 19. Checked time: $time_now. *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
			foreach( $month_info as $db_date => $item ){
				$condition	= ( $db_date <= $db_today ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 20. Checked time: $time_now. *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_Ttl_08_08_HasEmptyLinesAtStartOfDayAndInRestrictZone_AppTypStart_End_20_09_today is off!!!' ); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public function test_Ttl_07_16_HasNoAppsAndFreeTimes__SetOffDays_NoPtt(){											//_is_8_BB
		if( ( self::_is_8_BB || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]	= true;
			global $CA_PATH; include( $CA_PATH.'std.php' );

			self::createEnvironment_01();

			//	App types
			utils_ut::addItem( 'app_type' );

			//Off Days
			$d_t_now	= '2010-10-01 00:00:00'; include( $CA_PATH.'dates.php' );
			utils_ut::addItem( 'off_day', array( 'AGENDA_ID' => $agendas[ 1 ][ 'AGENDA_ID' ], 'START_DATE' => '10-10-2010', 'END_DATE' => '20-10-2010' ) );


			$ags	= $agendas[ 1 ][ 'AGENDA_ID' ];
			$time_now	= '06:00';	//----------------------------------
			$month_info	= bl::getAnyAgendaForMonth( 10, 2010, $app_types[ 0 ][ 'ID' ], $agendas[ 1 ][ 'AGENDA_ID' ], -2, $today, $time_now );
			$this->assertEquals( 31, count( $month_info ), "\n***** Assert 1. Checked time: $time_now. *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
			foreach( $month_info as $db_date => $item ){
				$condition	= ( $db_date >= '2010-10-10' && $db_date <= '2010-10-20' ) ? ( 0 == $item ) : ( 1 == $item );
				$this->assertTrue( $condition, "\n***** Assert 2. Checked time: $time_now. *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).".\nReceived data:\n".print_r(  $month_info, true )."\n" );
			}


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_Ttl_07_16_HasNoAppsAndFreeTimes__SetOffDays_NoPtt is off!!!' ); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public function test_Ttl_07_16_HasNoAppsAndFreeTimes__SetOffDays_DayPtt(){											//_is_9_BB
		if( 0&& ( self::_is_9_BB || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]	= true;
			global $CA_PATH; include( $CA_PATH.'std.php' );

			self::createEnvironment_01();

			//	App types
			utils_ut::addItem( 'app_type' );

			//Off Days
			$d_t_now	= '2010-10-01 00:00:00'; include( $CA_PATH.'dates.php' );
			$item_data	= array(
				'AGENDA_ID'		=> $agendas[ 1 ][ 'AGENDA_ID' ],
				'START_DATE'	=> '10-10-2010',
				'END_DATE'		=> '20-10-2010',
				'PATT_ID'		=> 1,
				'CYCLE'			=> utils_ut::_cycle_day,
				'PERIOD'		=> 2
			);
			utils_ut::addItem( 'off_day', $item_data );


			for( $period = 2; $period < 10; $period++ ){
				$new_data	= array(
					'ID'	=> $off_days[ 0 ][ 'PATT_ID' ],
					'PERIOD'	=> $period
				);
				utils_ut::updateTable( 'ca_daysoff_pattern', $new_data );
				$off_days[ 0 ]	= utils_ut::get_off_day_ById( $off_days[ 0 ][ 'DAY_ID' ] );

				$d_t	= $d_t_now;
				$off_db_dates	= array();
				while( $d_t < '2010-10-31 00:00:00' ){
					$mk	= strtotime( $d_t );
					$off_db_dates[ date( 'Y-m-d', $mk ) ]	= 1;
					$d_t	= date( 'Y-m-d H:i:s', strtotime( $period.' day', $mk ) );
				}

				$ags	= $agendas[ 1 ][ 'AGENDA_ID' ];
				$time_now	= '06:00';	//----------------------------------
				$now		= $db_today.' '.$time_now.':00';
				$month_info	= bl::getAnyAgendaForMonth( $now, 10, 2010, $app_types[ 0 ][ 'ID' ], $ags, false );
				$this->assertEquals( 31, count( $month_info ), "\n***** Assert 1. Checked time: $time_now. *****\nWrong quantity of itmes.\nReceived data:\n".print_r(  $month_info, true )."\n" );
				foreach( $month_info as $db_date => $item ){
					$condition	= array_key_exists( $db_date, $off_db_dates ) ? ( 0 == $item ) : ( 1 == $item );
					$this->assertTrue( $condition, "\n***** Assert 2. Checked time: $time_now. *****\nWrong value for date: ".date( 'd-m-Y', strtotime( $db_date ) ).". Period: $period\nReceived data:\n".print_r(  $month_info, true )."\n" );
				}
			}


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_Ttl_07_16_HasNoAppsAndFreeTimes__SetOffDays_DayPtt is off!!!' ); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public function test_Ttl_07_16_HasNoAppsAndFreeTimes__SetOffDays_WeekPtt(){											//_is_10_BB
		if( 0&& ( self::_is_10_BB || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]	= true;
			global $CA_PATH; include( $CA_PATH.'std.php' );

			self::createEnvironment_01();

			//	App types
			utils_ut::addItem( 'app_type' );

			//Off Days
			$d_t_now	= '2010-10-01 00:00:00'; include( $CA_PATH.'dates.php' );
			$item_data	= array(
				'AGENDA_ID'		=> $agendas[ 1 ][ 'AGENDA_ID' ],
				'START_DATE'	=> '01-10-2010',
				'END_DATE'		=> '31-12-2010',
				'PATT_ID'		=> 1,
				'CYCLE'			=> utils_ut::_cycle_week,
				'PERIOD'		=> 1
			);
			utils_ut::addItem( 'off_day', $item_data );


//print_r( $off_days );


//exit;

echo "\n\n";
			for( $first_day_add = 0; $first_day_add < 7; $first_day_add++ ){
				$d_t_start	= date( 'Y-m-d H:i:s', strtotime( $first_day_add.' day', $mk ) );

				$mk_dt_start	= strtotime( $d_t_start );

echo "d_t_start: $d_t_start # week day: ".date( 'N', $mk_dt_start )." # week ".date( 'W', $mk_dt_start )."\n\n";



				for( $period = 1; $period <= 4; $period++ ){
					$valid_weeks	= array();
					$d_t	= $d_t_start;
					while( $d_t <= '2010-10-31 00:00:00'){
					}



					for( $pattern = 1; $pattern < 128; $pattern++ ){
					}








//					$new_data	= array(
//						'ID'	=> $off_days[ 0 ][ 'PATT_ID' ],
//						'PERIOD'	=> $period
//					);
//					utils_ut::updateTable( 'ca_daysoff_pattern', $new_data );
//					$off_days[ 0 ]	= utils_ut::get_off_day_ById( $off_days[ 0 ][ 'DAY_ID' ] );
				}
			}



            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped(); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE


}//Class end





/*
				SECONDARY PART

$this->assertEquals( $expected, $real, "***** Assert 1 *****\nInfo.\n" );
$this->assertTrue( $condition, "\n***** Assert 1 *****\nInfo.\n" );
$this->assertFalse( $condition, "\n***** Assert 1 *****\nInfo.\n" );


 */
?>