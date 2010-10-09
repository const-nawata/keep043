<?php
define( '_CTD_COMMENT', 'This record was created by Unit Test' );

require_once 'config.php';
global $CA_PATH;																									//  Domestic

require_once( $CA_PATH.'PHPUnit/Framework.php' );
require_once( $CA_PATH.'PHPUnit/TextUI/TestRunner.php' );

//class CA_Framework_TestCase extends PHPUnit_Framework_TestCase{
//	public static $mIndex = 0;
//}


require_once( $CA_PATH.'test/ut_main_const.php' );
require_once( $CA_PATH.'test/session_tuning.php' );
require_once( $CA_PATH.'test/utils_ut.php' );


require_once( $CA_PATH.'test/DB/dbTestsSuite.php' );
require_once( $CA_PATH.'test/utils_ut/utilsTestsSuite.php' );
require_once( $CA_PATH.'test/utils_ut/secondaryUtilsTestsSuite.php' );
require_once( $CA_PATH.'test/widget/anyAgendaTestsSuite.php' );


if( !defined( 'PHPUnit_MAIN_METHOD' ) ){
	define( 'PHPUnit_MAIN_METHOD', 'do_tst::main' );
}



ini_set( "allow_call_time_pass_reference", "true" );

class do_tst{

	public static function main(){
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	public static function suite(){
		$suite = new PHPUnit_Framework_TestSuite( 'PHPUnit Framework' );
		$suite->addTestSuite( 'dbTestsSuite' );

		if( 0 ){	//	All tests
			$suite->addTestSuite( 'utilsTestsSuite' );
			$suite->addTestSuite( 'secondaryUtilsTestsSuite' );
			$suite->addTestSuite( 'anyAgendaTestsSuite' );
		}else{		//	Debug zone
//			$suite->addTestSuite( 'utilsTestsSuite' );
			$suite->addTestSuite( 'anyAgendaTestsSuite' );
		}
		return $suite;
	}
//------------------------------------------------------------------------------------- _UT_ORG_CODE

}//Class end

echo "<pre>";
if( PHPUnit_MAIN_METHOD == 'do_tst::main' ){
	$_SESSION[ 'm_ind' ]	= 0;
	$_SESSION[ 'org_code' ]	= _UT_ORG_CODE;
	$_SESSION[ 'is_skip' ]		= false;

	do_tst::main();


	unset( $_SESSION[ 'org_code' ] );
	unset( $_SESSION[ 'is_skip' ] );
	unset( $_SESSION[ 'm_ind' ] );

	unset( $_SESSION[ 'agendas' ] );
	unset( $_SESSION[ 'cats' ] );
	unset( $_SESSION[ 'clients' ] );
	unset( $_SESSION[ 'app_types' ] );
	unset( $_SESSION[ 'apps' ] );
	unset( $_SESSION[ 'free_times' ] );
	unset( $_SESSION[ 'off_days' ] );
}
