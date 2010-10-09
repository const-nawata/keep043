<?php
class secondaryUtilsTestsSuite extends PHPUnit_Framework_TestCase{
	const _is_all = false;   //  false  true

	const _is_0		= false;
	const _is_1		= true;

	protected function setUp(){
		session_tuning::initSessionData();
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

    protected function tearDown(){
        utils_ut::deleteOrgData();
    }
//------------------------------------------------------------------------------------- _UT_ORG_CODE

    function test_DiffDaysTest(){
        if( ( self::_is_0 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
            $_SESSION[ 'is_skip' ]	= true;

			$db_start_date = $db_date = date( 'Y-m-d' );
			$mk_off_start	= strtotime( $db_start_date );
			$db_last_date	= date( 'Y-m-d', strtotime( '+20 year', $mk_off_start ) );

			$mk	= strtotime( $db_date );
			$exp_diff	= 0;
			while( $db_date <= $db_last_date ){
				$mk_date		= strtotime( $db_date );
				$real_diff	= intval( round( ( $mk_date - $mk_off_start ) / 86400 ) );

				$this->assertEquals( $exp_diff, $real_diff, "***** Assert 1 *****\nWrong days difference between ".date( 'd-m-Y', $mk_off_start )." and ".date( 'd-m-Y', $mk_date )." .\n" );

				$exp_diff++;
				$db_date	= date( 'Y-m-d', strtotime( '+'.$exp_diff.' day', $mk ) );
			}



            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_putLogInfo is off!!!' ); }
    }
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	function test_findFreeDaysOfMonthByDayOffItem(){															//_is_1
        if( ( self::_is_1 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
            $_SESSION[ 'is_skip' ]	= true;
			global $CA_PATH; include( $CA_PATH.'std.php' );

			utils_ut::addItem( 'agenda' );
			$month = 2; $year = 2010;

			$ini_off_day1	= array(
				'AGENDA_ID'		=> $agendas[ 0 ][ 'AGENDA_ID' ],
				'START_DATE'	=> '10-01-2010',
				'END_DATE'		=> '20-10-2015',
				'PATT_ID'		=> 1,
				'CYCLE'			=> utils_ut::_cycle_week,
				'PERIOD'		=> 1,
				'WEEK_DAYS'		=> 126
			);
			$freeDays1	= utils_ut::findFreeDaysOfMonthByDayOffItem( $month, $year, $ini_off_day1 );

print_r( $freeDays1 );
























/*
			$ini_off_day1	= array(
				'AGENDA_ID'		=> $agendas[ 0 ][ 'AGENDA_ID' ],
				'START_DATE'	=> '10-01-2010',
				'END_DATE'		=> '20-10-2015',
				'PATT_ID'		=> 1,
				'CYCLE'			=> utils_ut::_cycle_day,
				'PERIOD'		=> 2
			);
			$freeDays1	= utils_ut::findFreeDaysOfMonthByDayOffItem( $month, $year, $ini_off_day1 );

			$ini_off_day2	= array(
				'AGENDA_ID'		=> $agendas[ 0 ][ 'AGENDA_ID' ],
				'START_DATE'	=> '10-01-2010',
				'END_DATE'		=> '20-10-2015',
				'PATT_ID'		=> 1,
				'CYCLE'			=> utils_ut::_cycle_day,
				'PERIOD'		=> 3
			);
			$freeDays2	= utils_ut::findFreeDaysOfMonthByDayOffItem( $month, $year, $ini_off_day2 );

			$ini_off_day3	= array(
				'AGENDA_ID'		=> $agendas[ 0 ][ 'AGENDA_ID' ],
				'START_DATE'	=> '10-01-2010',
				'END_DATE'		=> '20-10-2015',
				'PATT_ID'		=> 1,
				'CYCLE'			=> utils_ut::_cycle_day,
				'PERIOD'		=> 4
			);
			$freeDays3	= utils_ut::findFreeDaysOfMonthByDayOffItem( $month, $year, $ini_off_day3 );


			$freeDays	= array_intersect_key( $freeDays1, $freeDays2, $freeDays3 );


print_r( $freeDays1 );
print_r( $freeDays2 );
print_r( $freeDays3 );
print_r( $freeDays );

*/

//			$freeDays	= utils_ut::findFreeDaysOfMonthByDayOffItem( 3, 2011, $ini_off_day );
//
//
//print_r( $freeDays );




/*
			utils_ut::addItem( 'off_day', $ini_off_day );

			$d_t		= '2010-01-01 00:00:00';
			$d_t_last	= '2015-12-31 00:00:00';
			while( $d_t < $d_t_last ){
				$mk	= strtotime( $d_t );
				$chk_date	= date( 'd-m-Y', $mk );

				$condition	= utils_ut::isDayOff( $chk_date, $agendas[ 0 ][ 'AGENDA_ID' ] );
				if( $d_t < date( 'Y-m-d H:i:s', strtotime( $ini_off_day[ 'START_DATE' ] ) ) || $d_t > date( 'Y-m-d H:i:s', strtotime( $ini_off_day[ 'END_DATE' ] ) ) ){
					$this->assertFalse( $condition, "\n***** Assert 1 *****\nWrong value was found for date $chk_date.\n" );
				}else{
					$this->assertTrue( $condition, "\n***** Assert 2 *****\nWrong value was found for date $chk_date.\n" );
				}

				$d_t	= date( 'Y-m-d H:i:s', strtotime( '+1 day', $mk ) );
			}
*/

            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_putLogInfo is off!!!' ); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

}//Class end





/*
				SECONDARY PART

$this->assertEquals( $expected, $real, "***** Assert 1 *****\nInfo.\n" );
$this->assertTrue( $condition, "\n***** Assert 1 *****\nInfo.\n" );
$this->assertFalse( $condition, "\n***** Assert 1 *****\nInfo.\n" );


 */
