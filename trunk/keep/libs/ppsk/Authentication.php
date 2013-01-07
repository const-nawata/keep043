<?php
@define( '_PPSK_IS_CIPHER',		false );
@define('_PPSK_LEVEL_GUEST',	'guest');
@define('_PPSK_TAB_HOME_CODE',	'home');


class Authentication{

	public function __construct( $isNewSess = false ){
		$sess_id	= session_id();
		if( $sess_id == _EMPTY ){
			@session_start();
		}

		if( $isNewSess ){
			session_unset();
			session_destroy();
			session_start();
		}

		if( !count( $_SESSION ) ){
			$_SESSION[ 'level' ]		= _PPSK_LEVEL_GUEST;
			$_SESSION[ 'user_id' ]		= 0;
			$_SESSION[ 'tab_code' ]		= _PPSK_TAB_HOME_CODE;
			if( _PPSK_IS_CIPHER ){
				$_SESSION[ 'cipher_key' ] 	= sipherManager::getSipherKey( session_id() );
				$_SESSION[ 'cipher_base' ]	= sipherManager::getSipherBase();
			}
		}

	}
//______________________________________________________________________________

	/**
	 * difines if level(s) which contains in $level parameter was permitted
	 * @access public
	 * @param array $levels - array of levels for which operation is permitted
	 * @return boolean
	 * */
	public function isGrantAccess( $levels = array() ) {
		if( !count( $levels ) ){ return true; }
		foreach( $levels as &$level ){
			if ( $_SESSION[ 'level' ] == $level ){ return true; }
		}
		return false;
	}
//______________________________________________________________________________

	public function __destruct(){}
//______________________________________________________________________________
}//	Class end																				#######
