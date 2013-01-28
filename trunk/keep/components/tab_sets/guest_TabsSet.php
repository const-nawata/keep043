<?php
class guest_TabsSet extends PTabsSet{

	public function __construct( $Owner ){
		$this->mTabCodes	= array(
		_PPSK_TAB_HOME_CODE,
		_TAB_CATALOGUE_CODE,
		_TAB_CONTACT_CODE,
		'login'
		);
		( $_SESSION[ 'tab_code' ] == NULL ) ? $_SESSION[ 'tab_code' ] = _PPSK_TAB_HOME_CODE :'';
		parent::__construct( $this );
		$this->initHtmlView();
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

	public function home_TabsHandler( &$objResponse, $NULL ){//	_PPSK_TAB_HOME_CODE
		$_SESSION[ 'tab_code' ]	= _PPSK_TAB_HOME_CODE;
		$this->execTabHandler( $objResponse );
	}
//______________________________________________________________________________

	public function contact_TabsHandler( &$objResponse, $NULL ){//	_TAB_CONTACT_CODE
		$_SESSION[ 'tab_code' ]	= _TAB_CONTACT_CODE;
		$this->execTabHandler( $objResponse );
	}
//______________________________________________________________________________

	public function catalogue_TabsHandler( &$objResponse, $NULL ){//	_TAB_CATALOGUE_CODE
		$_SESSION[ 'tab_code' ]	= _TAB_CATALOGUE_CODE;
		$this->execTabHandler( $objResponse );
	}
//______________________________________________________________________________

	public function login_TabsHandler( &$objResponse, $NULL ){
		$_SESSION['tab_code']	= 'login';
		$this->execTabHandler( $objResponse );
		$objResponse->script( "setFocus( 'login' );" );
	}
//______________________________________________________________________________

}//	Class end
