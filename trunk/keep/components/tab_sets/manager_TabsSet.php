<?php
class manager_TabsSet extends PTabsSet {

	public function __construct( $Owner ){
		$this->mTabCodes	= array(
			_TAB_NEWS_CODE,
			_TAB_CLIENTS_CODE,
			_TAB_LISTS_CODE,
			_TAB_GOODS_CODE,
			_TAB_LOGOUT_CODE
		);

		$_SESSION['tab_code'] = ( $_SESSION['tab_code'] == NULL ) ? _TAB_NEWS_CODE : $_SESSION['tab_code'];

		parent::__construct( $this );
		$this->initHtmlView();
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

	public function goods_TabsHandler( &$objResponse, $NULL ){
		$auth_obj = new Authentication();

		if ( $auth_obj->isGrantAccess( array( 'manager' ))){
			$_SESSION['tab_code']	= _TAB_GOODS_CODE;
			$this->execTabHandler( $objResponse );
		}else{
			$objResponse = $this->doAccessDenied();
		}
	}
//______________________________________________________________________________

	public function clients_TabsHandler( &$objResponse, $NULL ){
		$auth_obj = new Authentication();

		if ( $auth_obj->isGrantAccess( array( 'manager' ))){
			$_SESSION['tab_code']	= _TAB_CLIENTS_CODE;
			$this->execTabHandler( $objResponse );
		}else{
			$objResponse = $this->doAccessDenied();
		}
	}
//______________________________________________________________________________

	public function news_TabsHandler( &$objResponse, $NULL ){
		$admin_tab_obj	= new admin_TabsSet( NULL );
		$admin_tab_obj->news_TabsHandler( $objResponse, NULL );
	}
//______________________________________________________________________________

	public function lists_TabsHandler( &$objResponse, $NULL ){
		$auth_obj = new Authentication();

		if ( $auth_obj->isGrantAccess( array( 'manager', 'admin' ))){
			$_SESSION['tab_code']	= _TAB_LISTS_CODE;
			$this->execTabHandler( $objResponse );
		}else{
			$objResponse = $this->doAccessDenied();
		}
	}
//______________________________________________________________________________

}//	Class end
