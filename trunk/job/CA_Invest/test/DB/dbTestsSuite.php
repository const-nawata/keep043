<?php
class dbTestsSuite extends PHPUnit_Framework_TestCase{
	const _is_all = true;   //  false  true

	const _is_t1 = false;

    protected function setUp(){
    	session_tuning::initSessionData();
    }
//------------------------------------------------------------------------------------- _UT_ORG_CODE

    protected function tearDown(){
    	 utils_ut::deleteOrgData();
    }
//------------------------------------------------------------------------------------- _UT_ORG_CODE

	function test_Db_Mysqli_Connection(){
        if( ( self::_is_t1 || self::_is_all ) && !$_SESSION[ 'is_skip' ] ){
            $_SESSION[ 'is_skip' ]	= true;
            global $CA_PATH, $gl_MysqliObj;
            global $Host, $DBName, $User, $Pass;

            @$gl_MysqliObj = new mysqli( $Host, $User, $Pass, $DBName );

            $this->assertEquals( 0, $gl_MysqliObj->connect_errno, "\n***** Assert 1 *****\nDB Connection Error: ".$gl_MysqliObj->connect_errno.". ".$gl_MysqliObj->connect_error );
			$sql	= "SET NAMES utf8";
			$result	= $gl_MysqliObj->query( $sql );

            $_SESSION[ 'is_skip' ]	= false;
        }else{ $this->markTestSkipped( 'test_Db_Mysqli_Connection is off!!!' ); }
	}
}


/*
				SECONDARY PART

$this->assertEquals( $expected, $real, "***** Assert 1 *****\nInfo.\n" );
$this->assertTrue( $condition, "\n***** Assert 1 *****\nInfo.\n" );
$this->assertFalse( $condition, "\n***** Assert 1 *****\nInfo.\n" );


 */
?>