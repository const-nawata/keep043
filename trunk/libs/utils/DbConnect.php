<?php
/**
 * This class is used for DB connection.
 * @author C.Nawata
 *
 */
class DbConnect {
	public $mIsSuccess;
	private $mHost;
	private $mDbName;
	private $mUser;
	private $mPass;

	/** sets configuration parameters for DB connection.
	 * @param string $Host
	 * @param string $DbName
	 * @param string $User
	 * @param string $Pass
	 * @return void
	 */
	public function __construct( $Host, $DbName, $User, $Pass ){
		$this->mHost	= $Host;
		$this->mDbName	= $DbName;
		$this->mUser	= $User;
		$this->mPass	= $Pass;
	}
//--------------------------------------------------------------------------------------------------

/**
 * performs connetion process to DB
 * @access	public
 * @return	void
 */
	public function doConnect(){
		global $gl_MysqliObj;

		$gl_MysqliObj = new mysqli( $this->mHost, $this->mUser, $this->mPass, $this->mDbName );
		if( $gl_MysqliObj->connect_errno ){
			$this->mIsSuccess	= false;
			throw new Exception( _EX.'DB Connection Error: '.$gl_MysqliObj->connect_errno.". ".$gl_MysqliObj->connect_error );
		}else{
			$this->mIsSuccess	= true;
		}

		$sql = "SET NAMES utf8";
		$result = $gl_MysqliObj->query( $sql );
	}
//--------------------------------------------------------------------------------------------------

}//	Class end
?>