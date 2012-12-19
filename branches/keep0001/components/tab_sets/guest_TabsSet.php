<?php
class guest_TabsSet extends PTabsSet{

	public function __construct( $Owner ){
		$this->mTabCodes	= array(
		_PPSK_TAB_HOME_CODE,
		_TAB_CATALOGUE_CODE,
		_TAB_CONTACT_CODE,
		_TAB_LOGIN_CODE
		);
		( $_SESSION[ 'tab_code' ] == NULL ) ? $_SESSION[ 'tab_code' ] = _PPSK_TAB_HOME_CODE :'';
		parent::__construct( $this );
		$this->initHtmlView();
	}
	//--------------------------------------------------------------------------------------------------

	public function __destruct(){
		parent::__destruct();
	}
	//--------------------------------------------------------------------------------------------------

	public function home_TabsHandler( &$objResponse, $NULL ){//	_PPSK_TAB_HOME_CODE
		$_SESSION[ 'tab_code' ]	= _PPSK_TAB_HOME_CODE;
		$this->execTabHandler( $objResponse );
	}
	//---------------------------------------------------------------------------------------------------------------------------------------------------

	public function contact_TabsHandler( &$objResponse, $NULL ){//	_TAB_CONTACT_CODE
		$_SESSION[ 'tab_code' ]	= _TAB_CONTACT_CODE;
		$this->execTabHandler( $objResponse );
	}
	//---------------------------------------------------------------------------------------------------------------------------------------------------

	public function catalogue_TabsHandler( &$objResponse, $NULL ){//	_TAB_CATALOGUE_CODE
		$_SESSION[ 'tab_code' ]	= _TAB_CATALOGUE_CODE;
		$this->execTabHandler( $objResponse );
	}
	//---------------------------------------------------------------------------------------------------------------------------------------------------

	public function login_TabsHandler( &$objResponse, $NULL ){//	_TAB_LOGIN_CODE
		$_SESSION[ 'tab_code' ]	= _TAB_LOGIN_CODE;
		$this->execTabHandler( $objResponse );
		$objResponse->addScript( "setFocus( 'login' );" );
	}
	//---------------------------------------------------------------------------------------------------------------------------------------------------


}
?>