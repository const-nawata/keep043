<?php
class admin_TabsSet extends PTabsSet {

	public function __construct( $Owner ){
		$this->mTabCodes	= array(
		_TAB_SETTINGS_CODE,
		_TAB_MANAGERS_CODE,
		_TAB_NEWS_CODE,
		_TAB_LOGOUT_CODE
		);
		( $_SESSION[ 'tab_code' ] == NULL ) ? $_SESSION[ 'tab_code' ] = _TAB_SETTINGS_CODE :'';
		parent::__construct( $this );
		$this->initHtmlView();
	}
	//--------------------------------------------------------------------------------------------------

	public function __destruct(){
		parent::__destruct();
	}
	//--------------------------------------------------------------------------------------------------

	public function managers_TabsHandler( &$objResponse, $NULL ){
		$auth_obj = new Authentication();
		if( $auth_obj->isGrantAccess( array( _LEVEL_ADMIN ) ) ){
			$_SESSION[ 'tab_code' ]	= _TAB_MANAGERS_CODE;
			$this->execTabHandler( $objResponse );
		}else{
			$objResponse = $this->doAccessDenied();
		}
	}
	//--------------------------------------------------------------------------------------------------

	public function settings_TabsHandler( &$objResponse, $NULL ){
		$auth_obj = new Authentication();

		if( $auth_obj->isGrantAccess( array( _LEVEL_ADMIN ) ) ){
			$_SESSION[ 'tab_code' ]	= _TAB_SETTINGS_CODE;
			$this->execTabHandler( $objResponse );
		}else{
			$objResponse = $this->doAccessDenied();
		}
	}
	//---------------------------------------------------------------------------------------------------------------------------------------------------

	public function news_TabsHandler( &$objResponse, $NULL ){
		$auth_obj = new Authentication();
		if ( $auth_obj->isGrantAccess( array( _LEVEL_ADMIN, _LEVEL_MANAGER ) ) ){
			$_SESSION[ 'tab_code' ] = _TAB_NEWS_CODE;
			$this->execTabHandler( $objResponse );
		}else{
			$objResponse = $this->doAccessDenied();
		}
	}
	//---------------------------------------------------------------------------------------------------------------------------------------------------

}//	Class end
?>