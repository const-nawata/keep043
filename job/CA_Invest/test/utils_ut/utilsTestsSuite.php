<?php
class utilsTestsSuite extends PHPUnit_Framework_TestCase{
	const _is_all = true;   //  false  true

	const _is_1		= false;
	const _is_2		= false;
	const _is_3		= false;
	const _is_4		= false;
	const _is_5		= false;

	const _is_6		= false;
	const _is_7		= false;
	const _is_8		= false;
	const _is_9		= false;
	const _is_10	= false;
	const _is_11	= false;

	const _is_12	= false;
	const _is_13	= false;
	const _is_14	= false;
	const _is_15	= false;
	const _is_16	= false;
	const _is_17	= false;

	const _is_18	= false;
	const _is_19	= true;

	protected function setUp(){
		session_tuning::initSessionData();
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

    protected function tearDown(){
        utils_ut::deleteOrgData();
    }
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	function test_putLogInfo(){															//_is_1
        if( ( self::_is_1 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
            $_SESSION[ 'is_skip' ]	= true;
            global $gl_MysqliObj;
            $content = $level = "TST";

            $result	= utils_ut::putLogInfo( $content, $level );
			$this->assertTrue( $result, "\n***** Assert 1 *****\nNo writing in log.\n" );

			$id		= $gl_MysqliObj->insert_id;
			$sql	= "DELETE FROM `log` WHERE `id`=".$id;
			$result = $gl_MysqliObj->query( $sql );

            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_putLogInfo is off!!!' ); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	function test_createAgendaUser_getAgendaById(){										//_is_2
        if( ( self::_is_2 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
            $_SESSION[ 'is_skip' ]	= true;

            $agenda	= array(
            	'NAME'			=> 'AGENda_NaMe',
            	'START_TIME'	=> '04:00',
            	'END_TIME'		=> '09:00',
            	'DURATION'		=> 15
            );
            $ag_id	= utils_ut::create_agenda( $agenda );
            $condition	= ( $ag_id > 0 );
            $this->assertTrue( $condition, "\n***** Assert 1 *****\nAgenda was not created.\n" );

            $tst_ag	= utils_ut::get_agenda_ById( $ag_id );

            $condition 	= (	array_key_exists( 'NAME', $tst_ag ) &&
            				array_key_exists( 'START_TIME', $tst_ag ) &&
            				array_key_exists( 'END_TIME', $tst_ag ) &&
            				array_key_exists( 'AGENDA_ID', $tst_ag ) &&
            				array_key_exists( 'DURATION', $tst_ag ) &&
            				array_key_exists( 'ORG_CODE', $tst_ag ) );

            $this->assertTrue( $condition, "\n***** Assert 2 *****\nBad data was got.\nReceived data:\n".print_r( $tst_ag, true )."\n" );
            $this->assertEquals( $agenda[ 'START_TIME' ], $tst_ag[ 'START_TIME' ], "***** Assert 3 *****\nBad agenda's start time was got.\nReceived data:\n".print_r( $tst_ag, true )."\n" );
            $this->assertEquals( $agenda[ 'END_TIME' ], $tst_ag[ 'END_TIME' ], "***** Assert 4 *****\nBad agenda's end time was got.\nReceived data:\n".print_r( $tst_ag, true )."\n" );
            $this->assertEquals( $agenda[ 'DURATION' ], $tst_ag[ 'DURATION' ], "***** Assert 5 *****\nBad agenda's DURATION was got.\nReceived data:\n".print_r( $tst_ag, true )."\n" );
            $this->assertEquals( $agenda[ 'NAME' ], $tst_ag[ 'NAME' ], "***** Assert 6 *****\nBad agenda's NAME was got.\nReceived data:\n".print_r( $tst_ag, true )."\n" );


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_createAgendaUser_getAgendaById is off!!!' ); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	function test_createAppType_getAppTypeById(){										//_is_3
        if( ( self::_is_3 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
            $_SESSION[ 'is_skip' ]	= true;

            $ini_data	= array(
            	'TIME'					=> 30,
            	'MIN_TIME'				=> 10,
            	'MAX_TIME'				=> 10,
            	'IS_PUBLIC'				=> 1,
            	'IS_MULTY'				=> 0,
            	'AT_PERIOD_START_TIME'	=> '04:00',
            	'AT_PERIOD_END_TIME'	=> '09:00',
            	'AT_PERIOD_DAY'			=> 111,
            	'NAME'					=> 'Test name'
            );

            $id			= utils_ut::create_app_type( $ini_data );
            $condition	= ( $id > 0 );
            $this->assertTrue( $condition, "\n***** Assert 1 *****\nNew record was not created.\n" );

            $tst_data	= utils_ut::get_app_type_ById( $id );

            $condition 	= (	array_key_exists( 'ID', $tst_data ) &&
            				array_key_exists( 'TIME', $tst_data ) &&
            				array_key_exists( 'MIN_TIME', $tst_data ) &&
            				array_key_exists( 'MAX_TIME', $tst_data ) &&
            				array_key_exists( 'IS_PUBLIC', $tst_data ) &&
            				array_key_exists( 'IS_MULTY', $tst_data ) &&
            				array_key_exists( 'AT_PERIOD_START_TIME', $tst_data ) &&
            				array_key_exists( 'AT_PERIOD_END_TIME', $tst_data ) &&
            				array_key_exists( 'AT_PERIOD_DAY', $tst_data ) &&
            				array_key_exists( 'NAME', $tst_data ) &&
            				array_key_exists( 'ORG_CODE', $tst_data ) );

            $this->assertTrue( $condition, "\n***** Assert 2 *****\nWrong data was got.\nReceived data:\n".print_r( $tst_data, true )."\n" );


            foreach( $ini_data as $field => $value ){
            	$this->assertEquals( $value, $tst_data[ $field ], "***** Assert 3 *****\nWrong data was got for field $field.\nReceived data:\n".print_r( $tst_data, true )."\n" );
            }


            $null_data	= array();

            $id			= utils_ut::create_app_type( $null_data );
            $condition	= ( $id > 0 );
            $this->assertTrue( $condition, "\n***** Assert 4 *****\nNew record was not created.\n" );

            $tst_data	= utils_ut::get_app_type_ById( $id );

            $condition 	= (	array_key_exists( 'ID', $tst_data ) &&
            				array_key_exists( 'TIME', $tst_data ) &&
            				array_key_exists( 'MIN_TIME', $tst_data ) &&
            				array_key_exists( 'MAX_TIME', $tst_data ) &&
            				array_key_exists( 'IS_PUBLIC', $tst_data ) &&
            				array_key_exists( 'IS_MULTY', $tst_data ) &&
            				array_key_exists( 'AT_PERIOD_START_TIME', $tst_data ) &&
            				array_key_exists( 'AT_PERIOD_END_TIME', $tst_data ) &&
            				array_key_exists( 'AT_PERIOD_DAY', $tst_data ) &&
            				array_key_exists( 'NAME', $tst_data ) &&
            				array_key_exists( 'ORG_CODE', $tst_data ) );

            $this->assertTrue( $condition, "\n***** Assert 5 *****\nWrong data was got.\nReceived data:\n".print_r( $tst_data, true )."\n" );


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_createAppType_getAppTypeById is off!!!' ); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	function test_createClient_getClientById(){											//_is_4
        if( ( self::_is_4 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
            $_SESSION[ 'is_skip' ]	= true;

            $ini_data	= array(
            	'FIRSTNAME'	=> 'Test name'
            );

            $id			= utils_ut::create_client( $ini_data );
            $condition	= ( $id > 0 );
            $this->assertTrue( $condition, "\n***** Assert 1 *****\nNew record was not created.\n" );

            $tst_data	= utils_ut::get_client_ById( $id );

            $condition 	= (	array_key_exists( 'ID', $tst_data ) &&
            				array_key_exists( 'FIRSTNAME', $tst_data ) &&
            				array_key_exists( 'ORG_CODE', $tst_data ) );

            $this->assertTrue( $condition, "\n***** Assert 2 *****\nWrong data was got.\nReceived data:\n".print_r( $tst_data, true )."\n" );


            foreach( $ini_data as $field => $value ){
            	$this->assertEquals( $value, $tst_data[ $field ], "***** Assert 3 *****\nWrong data was got for field $field.\nReceived data:\n".print_r( $tst_data, true )."\n" );
            }


            $null_data	= array();

            $id			= utils_ut::create_client( $null_data );
            $condition	= ( $id > 0 );
            $this->assertTrue( $condition, "\n***** Assert 4 *****\nNew record was not created.\n" );

            $tst_data	= utils_ut::get_client_ById( $id );

            $condition 	= (	array_key_exists( 'ID', $tst_data ) &&
            				array_key_exists( 'FIRSTNAME', $tst_data ) &&
            				array_key_exists( 'ORG_CODE', $tst_data ) );

            $this->assertTrue( $condition, "\n***** Assert 5 *****\nWrong data was got.\nReceived data:\n".print_r( $tst_data, true )."\n" );


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_createClient_getClientById is off!!!' ); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	function test_createApp_getAppById(){												//_is_5
		if( ( self::_is_5 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]	= true;
			global $CA_PATH; include( $CA_PATH.'std.php' );

			utils_ut::addItem( 'agenda' );
			utils_ut::addItem( 'agenda' );
			utils_ut::addItem( 'agenda' );

			utils_ut::addItem( 'client' );
			utils_ut::addItem( 'client' );
			utils_ut::addItem( 'client' );
			utils_ut::addItem( 'client' );

			$new_app_type	= array(
				'TIME'		=> 30,
				'IS_MULTY'	=> 1
			);
			utils_ut::addItem( 'app_type', $new_app_type );

			$new_app	= array(
				'APPTYPE_ID'		=> $app_types[ 0 ][ 'ID' ],
				'START_DATE'		=> $today,
				'START_TIME'		=> '12:00',
				'MAX_NUMBER_CLIENT'	=> 10,
				'COMMENT'			=> _CTD_COMMENT,
				'agendas'			=> $agendas[ 0 ][ 'AGENDA_ID' ].','.$agendas[ 1 ][ 'AGENDA_ID' ],
				'clients'			=> $clients[ 0 ][ 'ID' ].','.$clients[ 1 ][ 'ID' ]
			);
			$id		= utils_ut::create_app( $new_app );
            $condition	= ( $id > 0 );
            $this->assertTrue( $condition, "\n***** Assert 1 *****\nNew record was not created.\n" );

            $tst_data	= utils_ut::get_app_ById( $id );

            $condition 	= (	array_key_exists( 'APP_ID', $tst_data ) &&
            				array_key_exists( 'APPTYPE_ID', $tst_data ) &&
            				array_key_exists( 'START_DATE', $tst_data ) &&
            				array_key_exists( 'END_DATE', $tst_data ) &&
            				array_key_exists( 'START_TIME', $tst_data ) &&
            				array_key_exists( 'END_TIME', $tst_data ) &&
            				array_key_exists( 'MAX_NUMBER_CLIENT', $tst_data ) &&
            				array_key_exists( 'COMMENT', $tst_data ) &&
            				array_key_exists( 'PATT_ID', $tst_data ) &&
            				array_key_exists( 'ORG_CODE', $tst_data ) );

            $this->assertTrue( $condition, "\n***** Assert 2 *****\nWrong keys were got.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_app[ 'APPTYPE_ID' ], $tst_data[ 'APPTYPE_ID' ],
            	"***** Assert 3 *****\nWrong data was got for field `APPTYPE_ID`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_app[ 'START_DATE' ], $tst_data[ 'START_DATE' ],
            	"***** Assert 4 *****\nWrong data was got for field `START_DATE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_app[ 'START_DATE' ], $tst_data[ 'END_DATE' ],
            	"***** Assert 5 *****\nWrong data was got for field `END_DATE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_app[ 'START_TIME' ], $tst_data[ 'START_TIME' ],
            	"***** Assert 6 *****\nWrong data was got for field `START_TIME`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( date( 'H:i', strtotime( '+'.$app_types[ 0 ][ 'TIME' ].' minute', strtotime( $new_app[ 'START_TIME' ] ) ) ), $tst_data[ 'END_TIME' ],
            	"***** Assert 7 *****\nWrong data was got for field `END_TIME`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_app[ 'MAX_NUMBER_CLIENT' ], $tst_data[ 'MAX_NUMBER_CLIENT' ],
            	"***** Assert 8 *****\nWrong data was got for field `MAX_NUMBER_CLIENT`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_app[ 'COMMENT' ], $tst_data[ 'COMMENT' ],
            	"***** Assert 9 *****\nWrong data was got for field `COMMENT`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( _UT_ORG_CODE, $tst_data[ 'ORG_CODE' ],
            	"***** Assert 10 *****\nWrong data was got for field `ORG_CODE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );


            foreach( $tst_data[ 'agendas' ] as $id ){
            	$condition	= ( $id == $agendas[ 0 ][ 'AGENDA_ID' ] || $id == $agendas[ 1 ][ 'AGENDA_ID' ] );
            	$this->assertTrue( $condition, "\n***** Assert 11 *****\nWrong data was got for agendas list.\nReceived data:\n".print_r( $tst_data, true )."\n" );
            }

            foreach( $tst_data[ 'clients' ] as $id ){
            	$condition	= ( $id == $clients[ 0 ][ 'ID' ] || $id == $clients[ 1 ][ 'ID' ] );
            	$this->assertTrue( $condition, "\n***** Assert 12 *****\nWrong data was got for clients list.\nReceived data:\n".print_r( $tst_data, true )."\n" );
            }


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_createApp_getAppById is off!!!' ); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE


	function test_create_free_time__get_free_time_ById_NoPattern(){					//_is_6
		if( ( self::_is_6 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]	= true;
			global $CA_PATH; include( $CA_PATH.'std.php' );

			utils_ut::addItem( 'agenda' );

			$new_fr_time	= array(
				'AGENDA_ID'		=> $agendas[ 0 ][ 'AGENDA_ID' ],
				'START_DATE'	=> $today,
				'END_DATE'		=> date( 'd-m-Y', strtotime( '+1 month', strtotime( $today ) ) ),
				'START_TIME'	=> '13:00',
				'END_TIME'		=> '14:00'
			);
			$id		= utils_ut::create_free_time( $new_fr_time );
            $condition	= ( $id > 0 );
            $this->assertTrue( $condition, "\n***** Assert 1 *****\nNew record was not created.\n" );

            $tst_data	= utils_ut::get_free_time_ById( $id );

            $this->assertEquals( $new_fr_time[ 'AGENDA_ID' ], $tst_data[ 'AGENDA_ID' ],
            	"***** Assert 2 *****\nWrong data was got for field `APPTYPE_ID`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'START_DATE' ], $tst_data[ 'START_DATE' ],
            	"***** Assert 3 *****\nWrong data was got for field `START_DATE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'END_DATE' ], $tst_data[ 'END_DATE' ],
            	"***** Assert 4 *****\nWrong data was got for field `END_DATE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'START_TIME' ], $tst_data[ 'START_TIME' ],
            	"***** Assert 5 *****\nWrong data was got for field `START_TIME`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'END_TIME' ], $tst_data[ 'END_TIME' ],
            	"***** Assert 6 *****\nWrong data was got for field `END_TIME`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( '', $tst_data[ 'CYCLE' ],
            	"***** Assert 7 *****\nWrong data was got for field `MAX_NUMBER_CLIENT`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( '', $tst_data[ 'PERIOD' ],
            	"***** Assert 8 *****\nWrong data was got for field `COMMENT`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( '', $tst_data[ 'WEEK_DAYS' ],
            	"***** Assert 9 *****\nWrong data was got for field `COMMENT`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( _UT_ORG_CODE, $tst_data[ 'ORG_CODE' ],
            	"***** Assert 10 *****\nWrong data was got for field `ORG_CODE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_create_free_time__get_free_time_ById_NoPattern is off!!!' ); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	function test_create_free_time__get_free_time_ById_Only_PATT_ID(){					//_is_7
		if( ( self::_is_7 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]	= true;
			global $CA_PATH; include( $CA_PATH.'std.php' );

			utils_ut::addItem( 'agenda' );

			$new_fr_time	= array(
				'AGENDA_ID'		=> $agendas[ 0 ][ 'AGENDA_ID' ],
				'START_DATE'	=> $today,
				'END_DATE'		=> date( 'd-m-Y', strtotime( '+1 month', strtotime( $today ) ) ),
				'START_TIME'	=> '13:00',
				'END_TIME'		=> '14:00',
				'PATT_ID'		=> 1
			);
			$id		= utils_ut::create_free_time( $new_fr_time );
            $condition	= ( $id > 0 );
            $this->assertTrue( $condition, "\n***** Assert 1 *****\nNew record was not created.\n" );

            $tst_data	= utils_ut::get_free_time_ById( $id );

            $this->assertEquals( $new_fr_time[ 'AGENDA_ID' ], $tst_data[ 'AGENDA_ID' ],
            	"***** Assert 2 *****\nWrong data was got for field `APPTYPE_ID`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'START_DATE' ], $tst_data[ 'START_DATE' ],
            	"***** Assert 3 *****\nWrong data was got for field `START_DATE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'END_DATE' ], $tst_data[ 'END_DATE' ],
            	"***** Assert 4 *****\nWrong data was got for field `END_DATE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'START_TIME' ], $tst_data[ 'START_TIME' ],
            	"***** Assert 5 *****\nWrong data was got for field `START_TIME`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'END_TIME' ], $tst_data[ 'END_TIME' ],
            	"***** Assert 6 *****\nWrong data was got for field `END_TIME`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( '', $tst_data[ 'CYCLE' ],
            	"***** Assert 7 *****\nWrong data was got for field `MAX_NUMBER_CLIENT`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( '', $tst_data[ 'PERIOD' ],
            	"***** Assert 8 *****\nWrong data was got for field `COMMENT`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( '', $tst_data[ 'WEEK_DAYS' ],
            	"***** Assert 9 *****\nWrong data was got for field `COMMENT`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( _UT_ORG_CODE, $tst_data[ 'ORG_CODE' ],
            	"***** Assert 10 *****\nWrong data was got for field `ORG_CODE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_create_free_time__get_free_time_ById_Only_PATT_ID is off!!!' ); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	function test_create_free_time__get_free_time_ById_EveryDay_PATT_ID(){				//_is_8
		if( ( self::_is_8 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]	= true;
			global $CA_PATH; include( $CA_PATH.'std.php' );

			utils_ut::addItem( 'agenda' );

			$new_fr_time	= array(
				'AGENDA_ID'		=> $agendas[ 0 ][ 'AGENDA_ID' ],
				'START_DATE'	=> $today,
				'END_DATE'		=> date( 'd-m-Y', strtotime( '+1 month', strtotime( $today ) ) ),
				'START_TIME'	=> '13:00',
				'END_TIME'		=> '14:00',
				'PATT_ID'		=> 1,
				'CYCLE'			=> utils_ut::_cycle_day,
				'PERIOD'		=> 1
			);
			$id		= utils_ut::create_free_time( $new_fr_time );
            $condition	= ( $id > 0 );
            $this->assertTrue( $condition, "\n***** Assert 1 *****\nNew record was not created.\n" );

            $tst_data	= utils_ut::get_free_time_ById( $id );

            $this->assertEquals( $new_fr_time[ 'AGENDA_ID' ], $tst_data[ 'AGENDA_ID' ],
            	"***** Assert 2 *****\nWrong data was got for field `APPTYPE_ID`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'START_DATE' ], $tst_data[ 'START_DATE' ],
            	"***** Assert 3 *****\nWrong data was got for field `START_DATE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'END_DATE' ], $tst_data[ 'END_DATE' ],
            	"***** Assert 4 *****\nWrong data was got for field `END_DATE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'START_TIME' ], $tst_data[ 'START_TIME' ],
            	"***** Assert 5 *****\nWrong data was got for field `START_TIME`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'END_TIME' ], $tst_data[ 'END_TIME' ],
            	"***** Assert 6 *****\nWrong data was got for field `END_TIME`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( '', $tst_data[ 'CYCLE' ],
            	"***** Assert 7 *****\nWrong data was got for field `MAX_NUMBER_CLIENT`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( '', $tst_data[ 'PERIOD' ],
            	"***** Assert 8 *****\nWrong data was got for field `COMMENT`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( '', $tst_data[ 'WEEK_DAYS' ],
            	"***** Assert 9 *****\nWrong data was got for field `COMMENT`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( _UT_ORG_CODE, $tst_data[ 'ORG_CODE' ],
            	"***** Assert 10 *****\nWrong data was got for field `ORG_CODE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_create_free_time__get_free_time_ById_EveryDay_PATT_ID is off!!!' ); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	function test_create_free_time__get_free_time_ById_Day_PATT_ID(){					//_is_9
		if( ( self::_is_9 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]	= true;
			global $CA_PATH; include( $CA_PATH.'std.php' );

			utils_ut::addItem( 'agenda' );

			$new_fr_time	= array(
				'AGENDA_ID'		=> $agendas[ 0 ][ 'AGENDA_ID' ],
				'START_DATE'	=> $today,
				'END_DATE'		=> date( 'd-m-Y', strtotime( '+1 month', strtotime( $today ) ) ),
				'START_TIME'	=> '13:00',
				'END_TIME'		=> '14:00',
				'PATT_ID'		=> 1,
				'CYCLE'			=> utils_ut::_cycle_day,
				'PERIOD'		=> 3
			);
			$id		= utils_ut::create_free_time( $new_fr_time );
            $condition	= ( $id > 0 );
            $this->assertTrue( $condition, "\n***** Assert 1 *****\nNew record was not created.\n" );

            $tst_data	= utils_ut::get_free_time_ById( $id );

            $this->assertEquals( $new_fr_time[ 'AGENDA_ID' ], $tst_data[ 'AGENDA_ID' ],
            	"***** Assert 2 *****\nWrong data was got for field `AGENDA_ID`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'START_DATE' ], $tst_data[ 'START_DATE' ],
            	"***** Assert 3 *****\nWrong data was got for field `START_DATE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'END_DATE' ], $tst_data[ 'END_DATE' ],
            	"***** Assert 4 *****\nWrong data was got for field `END_DATE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'START_TIME' ], $tst_data[ 'START_TIME' ],
            	"***** Assert 5 *****\nWrong data was got for field `START_TIME`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'END_TIME' ], $tst_data[ 'END_TIME' ],
            	"***** Assert 6 *****\nWrong data was got for field `END_TIME`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( utils_ut::_cycle_day, $tst_data[ 'CYCLE' ],
            	"***** Assert 7 *****\nWrong data was got for field `CYCLE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( 3, $tst_data[ 'PERIOD' ],
            	"***** Assert 8 *****\nWrong data was got for field `PERIOD`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( 0, $tst_data[ 'WEEK_DAYS' ],
            	"***** Assert 9 *****\nWrong data was got for field `WEEK_DAYS`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( _UT_ORG_CODE, $tst_data[ 'ORG_CODE' ],
            	"***** Assert 10 *****\nWrong data was got for field `ORG_CODE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_create_free_time__get_free_time_ById_Day_PATT_ID is off!!!' ); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	function test_create_free_time__get_free_time_ById_Week_PATT_ID_NoWeekValue(){		//_is_10
		if( ( self::_is_10 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]	= true;
			global $CA_PATH; include( $CA_PATH.'std.php' );

			utils_ut::addItem( 'agenda' );

			$new_fr_time	= array(
				'AGENDA_ID'		=> $agendas[ 0 ][ 'AGENDA_ID' ],
				'START_DATE'	=> $today,
				'END_DATE'		=> date( 'd-m-Y', strtotime( '+1 month', strtotime( $today ) ) ),
				'START_TIME'	=> '13:00',
				'END_TIME'		=> '14:00',
				'PATT_ID'		=> 1,
				'CYCLE'			=> utils_ut::_cycle_week,
				'PERIOD'		=> 3
			);
			$id		= utils_ut::create_free_time( $new_fr_time );
            $condition	= ( $id > 0 );
            $this->assertTrue( $condition, "\n***** Assert 1 *****\nNew record was not created.\n" );

            $tst_data	= utils_ut::get_free_time_ById( $id );

            $this->assertEquals( $new_fr_time[ 'AGENDA_ID' ], $tst_data[ 'AGENDA_ID' ],
            	"***** Assert 2 *****\nWrong data was got for field `AGENDA_ID`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'START_DATE' ], $tst_data[ 'START_DATE' ],
            	"***** Assert 3 *****\nWrong data was got for field `START_DATE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'END_DATE' ], $tst_data[ 'END_DATE' ],
            	"***** Assert 4 *****\nWrong data was got for field `END_DATE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'START_TIME' ], $tst_data[ 'START_TIME' ],
            	"***** Assert 5 *****\nWrong data was got for field `START_TIME`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'END_TIME' ], $tst_data[ 'END_TIME' ],
            	"***** Assert 6 *****\nWrong data was got for field `END_TIME`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( utils_ut::_cycle_week, $tst_data[ 'CYCLE' ],
            	"***** Assert 7 *****\nWrong data was got for field `CYCLE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( 3, $tst_data[ 'PERIOD' ],
            	"***** Assert 8 *****\nWrong data was got for field `PERIOD`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( 0, $tst_data[ 'WEEK_DAYS' ],
            	"***** Assert 9 *****\nWrong data was got for field `WEEK_DAYS`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( _UT_ORG_CODE, $tst_data[ 'ORG_CODE' ],
            	"***** Assert 10 *****\nWrong data was got for field `ORG_CODE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_create_free_time__get_free_time_ById_Week_PATT_ID_NoWeekValue is off!!!' ); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	function test_create_free_time__get_free_time_ById_Week_PATT_ID(){					//_is_11
		if( ( self::_is_11 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]	= true;
			global $CA_PATH; include( $CA_PATH.'std.php' );

			utils_ut::addItem( 'agenda' );

			$new_fr_time	= array(
				'AGENDA_ID'		=> $agendas[ 0 ][ 'AGENDA_ID' ],
				'START_DATE'	=> $today,
				'END_DATE'		=> date( 'd-m-Y', strtotime( '+1 month', strtotime( $today ) ) ),
				'START_TIME'	=> '13:00',
				'END_TIME'		=> '14:00',
				'PATT_ID'		=> 1,
				'CYCLE'			=> utils_ut::_cycle_week,
				'PERIOD'		=> 3,
				'WEEK_DAYS'		=> 95
			);
			$id		= utils_ut::create_free_time( $new_fr_time );
            $condition	= ( $id > 0 );
            $this->assertTrue( $condition, "\n***** Assert 1 *****\nNew record was not created.\n" );

            $tst_data	= utils_ut::get_free_time_ById( $id );

            $this->assertEquals( $new_fr_time[ 'AGENDA_ID' ], $tst_data[ 'AGENDA_ID' ],
            	"***** Assert 2 *****\nWrong data was got for field `AGENDA_ID`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'START_DATE' ], $tst_data[ 'START_DATE' ],
            	"***** Assert 3 *****\nWrong data was got for field `START_DATE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'END_DATE' ], $tst_data[ 'END_DATE' ],
            	"***** Assert 4 *****\nWrong data was got for field `END_DATE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'START_TIME' ], $tst_data[ 'START_TIME' ],
            	"***** Assert 5 *****\nWrong data was got for field `START_TIME`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'END_TIME' ], $tst_data[ 'END_TIME' ],
            	"***** Assert 6 *****\nWrong data was got for field `END_TIME`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'CYCLE' ], $tst_data[ 'CYCLE' ],
            	"***** Assert 7 *****\nWrong data was got for field `CYCLE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'PERIOD' ], $tst_data[ 'PERIOD' ],
            	"***** Assert 8 *****\nWrong data was got for field `PERIOD`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'WEEK_DAYS' ], $tst_data[ 'WEEK_DAYS' ],
            	"***** Assert 9 *****\nWrong data was got for field `WEEK_DAYS`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( _UT_ORG_CODE, $tst_data[ 'ORG_CODE' ],
            	"***** Assert 10 *****\nWrong data was got for field `ORG_CODE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_create_free_time__get_free_time_ById_Week_PATT_ID is off!!!' ); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE



	function test_create_off_day__get_off_day_ById_NoPattern(){							//_is_12
		if( ( self::_is_12 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]	= true;
			global $CA_PATH; include( $CA_PATH.'std.php' );

			utils_ut::addItem( 'agenda' );

			$new_fr_time	= array(
				'AGENDA_ID'		=> $agendas[ 0 ][ 'AGENDA_ID' ],
				'START_DATE'	=> $today,
				'END_DATE'		=> date( 'd-m-Y', strtotime( '+1 month', strtotime( $today ) ) )
			);
			$id		= utils_ut::create_off_day( $new_fr_time );
            $condition	= ( $id > 0 );
            $this->assertTrue( $condition, "\n***** Assert 1 *****\nNew record was not created.\n" );

            $tst_data	= utils_ut::get_off_day_ById( $id );

            $this->assertEquals( $new_fr_time[ 'AGENDA_ID' ], $tst_data[ 'AGENDA_ID' ],
            	"***** Assert 2 *****\nWrong data was got for field `APPTYPE_ID`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'START_DATE' ], $tst_data[ 'START_DATE' ],
            	"***** Assert 3 *****\nWrong data was got for field `START_DATE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'END_DATE' ], $tst_data[ 'END_DATE' ],
            	"***** Assert 4 *****\nWrong data was got for field `END_DATE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( '', $tst_data[ 'CYCLE' ],
            	"***** Assert 5 *****\nWrong data was got for field `MAX_NUMBER_CLIENT`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( '', $tst_data[ 'PERIOD' ],
            	"***** Assert 6 *****\nWrong data was got for field `COMMENT`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( '', $tst_data[ 'WEEK_DAYS' ],
            	"***** Assert 7 *****\nWrong data was got for field `COMMENT`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( _UT_ORG_CODE, $tst_data[ 'ORG_CODE' ],
            	"***** Assert 8 *****\nWrong data was got for field `ORG_CODE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_create_off_day__get_off_day_ById_NoPattern is off!!!' ); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	function test_create_off_day__get_off_day_ById_Only_PATT_ID(){						//_is_13
		if( ( self::_is_13 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]	= true;
			global $CA_PATH; include( $CA_PATH.'std.php' );

			utils_ut::addItem( 'agenda' );

			$new_fr_time	= array(
				'AGENDA_ID'		=> $agendas[ 0 ][ 'AGENDA_ID' ],
				'START_DATE'	=> $today,
				'END_DATE'		=> date( 'd-m-Y', strtotime( '+1 month', strtotime( $today ) ) ),
				'PATT_ID'		=> 1
			);
			$id		= utils_ut::create_off_day( $new_fr_time );
            $condition	= ( $id > 0 );
            $this->assertTrue( $condition, "\n***** Assert 1 *****\nNew record was not created.\n" );

            $tst_data	= utils_ut::get_off_day_ById( $id );

            $this->assertEquals( $new_fr_time[ 'AGENDA_ID' ], $tst_data[ 'AGENDA_ID' ],
            	"***** Assert 2 *****\nWrong data was got for field `APPTYPE_ID`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'START_DATE' ], $tst_data[ 'START_DATE' ],
            	"***** Assert 3 *****\nWrong data was got for field `START_DATE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'END_DATE' ], $tst_data[ 'END_DATE' ],
            	"***** Assert 4 *****\nWrong data was got for field `END_DATE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( '', $tst_data[ 'CYCLE' ],
            	"***** Assert 5 *****\nWrong data was got for field `MAX_NUMBER_CLIENT`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( '', $tst_data[ 'PERIOD' ],
            	"***** Assert 6 *****\nWrong data was got for field `COMMENT`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( '', $tst_data[ 'WEEK_DAYS' ],
            	"***** Assert 7 *****\nWrong data was got for field `COMMENT`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( _UT_ORG_CODE, $tst_data[ 'ORG_CODE' ],
            	"***** Assert 8 *****\nWrong data was got for field `ORG_CODE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_create_off_day__get_off_day_ById_Only_PATT_ID is off!!!' ); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	function test_create_off_day__get_off_day_ById_EveryDay_PATT_ID(){					//_is_14
		if( ( self::_is_14 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]	= true;
			global $CA_PATH; include( $CA_PATH.'std.php' );

			utils_ut::addItem( 'agenda' );

			$new_fr_time	= array(
				'AGENDA_ID'		=> $agendas[ 0 ][ 'AGENDA_ID' ],
				'START_DATE'	=> $today,
				'END_DATE'		=> date( 'd-m-Y', strtotime( '+1 month', strtotime( $today ) ) ),
				'PATT_ID'		=> 1,
				'CYCLE'			=> utils_ut::_cycle_day,
				'PERIOD'		=> 1
			);
			$id		= utils_ut::create_off_day( $new_fr_time );
            $condition	= ( $id > 0 );
            $this->assertTrue( $condition, "\n***** Assert 1 *****\nNew record was not created.\n" );

            $tst_data	= utils_ut::get_off_day_ById( $id );

            $this->assertEquals( $new_fr_time[ 'AGENDA_ID' ], $tst_data[ 'AGENDA_ID' ],
            	"***** Assert 2 *****\nWrong data was got for field `APPTYPE_ID`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'START_DATE' ], $tst_data[ 'START_DATE' ],
            	"***** Assert 3 *****\nWrong data was got for field `START_DATE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'END_DATE' ], $tst_data[ 'END_DATE' ],
            	"***** Assert 4 *****\nWrong data was got for field `END_DATE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( '', $tst_data[ 'CYCLE' ],
            	"***** Assert 5 *****\nWrong data was got for field `MAX_NUMBER_CLIENT`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( '', $tst_data[ 'PERIOD' ],
            	"***** Assert 6 *****\nWrong data was got for field `COMMENT`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( '', $tst_data[ 'WEEK_DAYS' ],
            	"***** Assert 7 *****\nWrong data was got for field `COMMENT`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( _UT_ORG_CODE, $tst_data[ 'ORG_CODE' ],
            	"***** Assert 8 *****\nWrong data was got for field `ORG_CODE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_create_off_day__get_off_day_ById_EveryDay_PATT_ID is off!!!' ); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	function test_create_off_day__get_off_day_ById_Day_PATT_ID(){						//_is_15
		if( ( self::_is_15 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]	= true;
			global $CA_PATH; include( $CA_PATH.'std.php' );

			utils_ut::addItem( 'agenda' );

			$new_fr_time	= array(
				'AGENDA_ID'		=> $agendas[ 0 ][ 'AGENDA_ID' ],
				'START_DATE'	=> $today,
				'END_DATE'		=> date( 'd-m-Y', strtotime( '+1 month', strtotime( $today ) ) ),
				'PATT_ID'		=> 1,
				'CYCLE'			=> utils_ut::_cycle_day,
				'PERIOD'		=> 3
			);
			$id		= utils_ut::create_off_day( $new_fr_time );
            $condition	= ( $id > 0 );
            $this->assertTrue( $condition, "\n***** Assert 1 *****\nNew record was not created.\n" );

            $tst_data	= utils_ut::get_off_day_ById( $id );

            $this->assertEquals( $new_fr_time[ 'AGENDA_ID' ], $tst_data[ 'AGENDA_ID' ],
            	"***** Assert 2 *****\nWrong data was got for field `AGENDA_ID`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'START_DATE' ], $tst_data[ 'START_DATE' ],
            	"***** Assert 3 *****\nWrong data was got for field `START_DATE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'END_DATE' ], $tst_data[ 'END_DATE' ],
            	"***** Assert 4 *****\nWrong data was got for field `END_DATE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( utils_ut::_cycle_day, $tst_data[ 'CYCLE' ],
            	"***** Assert 5 *****\nWrong data was got for field `CYCLE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( 3, $tst_data[ 'PERIOD' ],
            	"***** Assert 6 *****\nWrong data was got for field `PERIOD`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( 0, $tst_data[ 'WEEK_DAYS' ],
            	"***** Assert 7 *****\nWrong data was got for field `WEEK_DAYS`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( _UT_ORG_CODE, $tst_data[ 'ORG_CODE' ],
            	"***** Assert 8 *****\nWrong data was got for field `ORG_CODE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_create_off_day__get_off_day_ById_Day_PATT_ID is off!!!' ); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	function test_create_off_day__get_off_day_ById_Week_PATT_ID_NoWeekValue(){			//_is_16
		if( ( self::_is_16 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]	= true;
			global $CA_PATH; include( $CA_PATH.'std.php' );

			utils_ut::addItem( 'agenda' );

			$new_fr_time	= array(
				'AGENDA_ID'		=> $agendas[ 0 ][ 'AGENDA_ID' ],
				'START_DATE'	=> $today,
				'END_DATE'		=> date( 'd-m-Y', strtotime( '+1 month', strtotime( $today ) ) ),
				'PATT_ID'		=> 1,
				'CYCLE'			=> utils_ut::_cycle_week,
				'PERIOD'		=> 3
			);
			$id		= utils_ut::create_off_day( $new_fr_time );
            $condition	= ( $id > 0 );
            $this->assertTrue( $condition, "\n***** Assert 1 *****\nNew record was not created.\n" );

            $tst_data	= utils_ut::get_off_day_ById( $id );

            $this->assertEquals( $new_fr_time[ 'AGENDA_ID' ], $tst_data[ 'AGENDA_ID' ],
            	"***** Assert 2 *****\nWrong data was got for field `AGENDA_ID`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'START_DATE' ], $tst_data[ 'START_DATE' ],
            	"***** Assert 3 *****\nWrong data was got for field `START_DATE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'END_DATE' ], $tst_data[ 'END_DATE' ],
            	"***** Assert 4 *****\nWrong data was got for field `END_DATE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( utils_ut::_cycle_week, $tst_data[ 'CYCLE' ],
            	"***** Assert 5 *****\nWrong data was got for field `CYCLE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( 3, $tst_data[ 'PERIOD' ],
            	"***** Assert 6 *****\nWrong data was got for field `PERIOD`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( 0, $tst_data[ 'WEEK_DAYS' ],
            	"***** Assert 7 *****\nWrong data was got for field `WEEK_DAYS`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( _UT_ORG_CODE, $tst_data[ 'ORG_CODE' ],
            	"***** Assert 8 *****\nWrong data was got for field `ORG_CODE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_create_off_day__get_off_day_ById_Week_PATT_ID_NoWeekValue is off!!!' ); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	function test_create_off_day__get_off_day_ById_Week_PATT_ID(){						//_is_17
		if( ( self::_is_17 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]	= true;
			global $CA_PATH; include( $CA_PATH.'std.php' );

			utils_ut::addItem( 'agenda' );

			$new_fr_time	= array(
				'AGENDA_ID'		=> $agendas[ 0 ][ 'AGENDA_ID' ],
				'START_DATE'	=> $today,
				'END_DATE'		=> date( 'd-m-Y', strtotime( '+1 month', strtotime( $today ) ) ),
				'PATT_ID'		=> 1,
				'CYCLE'			=> utils_ut::_cycle_week,
				'PERIOD'		=> 3,
				'WEEK_DAYS'		=> 95
			);
			$id		= utils_ut::create_off_day( $new_fr_time );
            $condition	= ( $id > 0 );
            $this->assertTrue( $condition, "\n***** Assert 1 *****\nNew record was not created.\n" );

            $tst_data	= utils_ut::get_off_day_ById( $id );

            $this->assertEquals( $new_fr_time[ 'AGENDA_ID' ], $tst_data[ 'AGENDA_ID' ],
            	"***** Assert 2 *****\nWrong data was got for field `AGENDA_ID`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'START_DATE' ], $tst_data[ 'START_DATE' ],
            	"***** Assert 3 *****\nWrong data was got for field `START_DATE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'END_DATE' ], $tst_data[ 'END_DATE' ],
            	"***** Assert 4 *****\nWrong data was got for field `END_DATE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'CYCLE' ], $tst_data[ 'CYCLE' ],
            	"***** Assert 5 *****\nWrong data was got for field `CYCLE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'PERIOD' ], $tst_data[ 'PERIOD' ],
            	"***** Assert 6 *****\nWrong data was got for field `PERIOD`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( $new_fr_time[ 'WEEK_DAYS' ], $tst_data[ 'WEEK_DAYS' ],
            	"***** Assert 7 *****\nWrong data was got for field `WEEK_DAYS`.\nReceived data:\n".print_r( $tst_data, true )."\n" );

            $this->assertEquals( _UT_ORG_CODE, $tst_data[ 'ORG_CODE' ],
            	"***** Assert 8 *****\nWrong data was got for field `ORG_CODE`.\nReceived data:\n".print_r( $tst_data, true )."\n" );


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_create_off_day__get_off_day_ById_Week_PATT_ID is off!!!' ); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE


	function test_create_cat__get_cat_ById(){											//_is_18
		if( ( self::_is_18 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]	= true;
			global $CA_PATH; include( $CA_PATH.'std.php' );


            $cat	= array(
            	'AGE_CAT_NAME'	=> 'CateGory_NaMe'
            );
            $cat_id	= utils_ut::create_cat( $cat );
            $condition	= ( $cat_id > 0 );
            $this->assertTrue( $condition, "\n***** Assert 1 *****\nCategory was not created.\n" );

            $tst_cat	= utils_ut::get_cat_ById( $cat_id );

            $condition 	= (	array_key_exists( 'AGE_CAT_ID', $tst_cat ) &&
            				array_key_exists( 'AGE_CAT_NAME', $tst_cat ) &&
            				array_key_exists( 'ORG_CODE', $tst_cat ) );

            $this->assertTrue( $condition, "\n***** Assert 2 *****\nBad data was got.\nReceived data:\n".print_r( $tst_cat, true )."\n" );
            $this->assertEquals( $cat[ 'AGE_CAT_NAME' ], $tst_cat[ 'AGE_CAT_NAME' ], "***** Assert 3 *****\nBad category name was got.\nReceived data:\n".print_r( $tst_cat, true )."\n" );


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_create_off_day__get_off_day_ById_Week_PATT_ID is off!!!' ); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	function test_assignAgendasToCat__getCategoryAssignedInfo(){							//_is_19
		if( ( self::_is_19 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
			$_SESSION[ 'is_skip' ]	= true;
			global $CA_PATH; include( $CA_PATH.'std.php' );

			utils_ut::addItem( 'agenda' );
			utils_ut::addItem( 'agenda' );
			utils_ut::addItem( 'agenda' );
			utils_ut::addItem( 'agenda' );

			utils_ut::addItem( 'cat' );
			utils_ut::addItem( 'cat' );


			utils_ut::addItem( 'app_type', array( 'AGE_CAT_ID' => $cats[ 0 ][ 'AGE_CAT_ID' ] ) );	//0
			utils_ut::addItem( 'app_type', array( 'AGE_CAT_ID' => $cats[ 1 ][ 'AGE_CAT_ID' ] ) );	//1
			utils_ut::addItem( 'app_type' );														//2

			utils_ut::addItem( 'app_type', array( 'AGE_CAT_ID' => $cats[ 0 ][ 'AGE_CAT_ID' ] ) );	//3
			utils_ut::addItem( 'app_type', array( 'AGE_CAT_ID' => $cats[ 1 ][ 'AGE_CAT_ID' ] ) );	//4
			utils_ut::addItem( 'app_type' );														//5


			$ags	= array( $agendas[ 1 ][ 'AGENDA_ID' ], $agendas[ 3 ][ 'AGENDA_ID' ] );
			utils_ut::assignAgendasToCat( $ags, $cats[ 0 ][ 'AGE_CAT_ID' ] );

			$info	= utils_ut::getCategoryAssignedInfo( $cats[ 0 ][ 'AGE_CAT_ID' ] );
			$this->assertEquals( $cats[ 0 ][ 'AGE_CAT_ID' ], $info[ 'AGE_CAT_ID' ], "***** Assert 1 *****\nWrong category id was got.\nReceived data:\n".print_r( $info, true )."\n" );
			$this->assertEquals( $cats[ 0 ][ 'AGE_CAT_NAME' ], $info[ 'AGE_CAT_NAME' ], "***** Assert 2 *****\nWrong category name was got.\nReceived data:\n".print_r( $info, true )."\n" );
			$this->assertEquals( 2, count( $info[ 'ags' ] ), "***** Assert 3 *****\nWrong quantity of agendas was got.\nReceived data:\n".print_r( $info, true )."\n" );
			$this->assertEquals( 2, count( $info[ 'typs' ] ), "***** Assert 4 *****\nWrong quantity of app types was got.\nReceived data:\n".print_r( $info, true )."\n" );

			foreach( $info[ 'ags' ] as $ag_id ){
				$condition	= ( $ag_id == $agendas[ 1 ][ 'AGENDA_ID' ] || $ag_id == $agendas[ 3 ][ 'AGENDA_ID' ] );
				$this->assertTrue( $condition, "\n***** Assert 5 *****\nWrong agenda id was got.\nReceived data:\n".print_r( $info, true )."\n" );
			}

			foreach( $info[ 'typs' ] as $app_type_id ){
				$condition	= ( $app_type_id == $app_types[ 0 ][ 'ID' ] || $app_type_id == $app_types[ 3 ][ 'ID' ] );
				$this->assertTrue( $condition, "\n***** Assert 6 *****\nWrong app type id was got.\nReceived data:\n".print_r( $info, true )."\n" );
			}


            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_create_off_day__get_off_day_ById_Week_PATT_ID is off!!!' ); }
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

}//Class end





/*
				SECONDARY PART

$this->assertEquals( $expected, $real, "***** Assert 1 *****\nInfo.\n" );
$this->assertTrue( $condition, "\n***** Assert 1 *****\nInfo.\n" );
$this->assertFalse( $condition, "\n***** Assert 1 *****\nInfo.\n" );


 */
